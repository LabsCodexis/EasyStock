<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Estoque;
use App\Models\EstoqueMovimentacao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produto;
use App\Models\Caixa;
use App\Models\Venda;
use App\Models\Venda_itens;

class VendaController extends Controller
{
    public function index(Request $request)
    {
        $caixaStatus = Caixa::latest()->first();

        $caixaAberto  = $caixaStatus && $caixaStatus->status == 'aberto';
        $caixaFechado = !$caixaStatus || $caixaStatus->status == 'fechado';

        $query = Estoque::with('produto');

        if ($request->filled('search')) {
            $query->whereHas('produto', function($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        $produtosEstoque = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $nomesProdutos = [];

        foreach ($produtosEstoque as $estoque) {
            $nomesProdutos[$estoque->id] = $estoque->produto->nome ?? 'Produto Não Definido';
        }

        $valorUnitario = [];
        foreach ($produtosEstoque as $estoque) {
            $valorUnitario[$estoque->id] = $estoque->valor_unitario ?? '0,00';
        }

        $categorias = Estoque::select('categoria')->distinct()->pluck('categoria');

        $caixaStatus = Caixa::latest()->first();


        return view('caixa.index', compact(
            'produtosEstoque',
            'nomesProdutos',
            'valorUnitario',
            'categorias',
            'caixaAberto',
            'caixaFechado',
            'caixaStatus'
        ));
    }


    public function abrir(Request $request)
    {
       $caixaAberto = Caixa::where('status', 'aberto')->first();

        if ($caixaAberto) {
            return back()->with('erro', 'Já existe um caixa aberto.');
        }

        Caixa::create([
            'data_abertura'     => Carbon::now(),
            'saldo_inicial'     => $request->saldo_inicial,
            'saldo'             => $request->saldo_inicial,
            'status'            => 'aberto'
        ]);

        return back()->with('sucesso', 'Caixa aberto com sucesso!');
    }


    public function fechar()
    {
        $caixa = Caixa::where('status', 'aberto')->first();

        if (!$caixa) {
            return back()->with('erro', 'Nenhum caixa aberto.');
        }

        $caixa->update([
            'data_fechamento' => Carbon::now(),
            'saldo_final'     => $caixa->saldo,
            'status'          => 'fechado'
        ]);

        return back()->with('sucesso', 'Caixa fechado com sucesso!');
    }

    /**
     * REGISTRAR VENDA (PDV)
     * @throws \Throwable
     */

    public function store(Request $request)
    {
        try {
            $caixaAberto = Caixa::where('status', 'aberto')->latest()->first();

            if (!$caixaAberto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Caixa fechado. Abra o caixa antes de vender.'
                ], 400);
            }

            $data = $request->validate([
                'customer_name'     => 'nullable|string|max:255',
                'payment_method'    => 'required|in:money,debit,credit,pix',

                'subtotal'          => 'required|numeric|min:0',
                'discount'          => 'nullable|numeric|min:0',
                'total'             => 'required|numeric|min:0',

                'items'             => 'required|array|min:1',
                'items.*.id'        => 'required|exists:produtos,id',
                'items.*.quantity'  => 'required|integer|min:1',
            ]);


            DB::transaction(function () use ($data, $caixaAberto) {

                $numeroNota = (Venda::where('caixa_id', $caixaAberto->id)->max('numero_nota') ?? 0) + 1;

                $mapPagamento = [
                    'money'  => 'dinheiro',
                    'debit'  => 'debito',
                    'credit' => 'credito',
                    'pix'    => 'pix',
                ];

                $venda = Venda::create([
                    'caixa_id'        => $caixaAberto->id,
                    'numero_nota'     => $numeroNota,
                    'cliente_nome'    => $data['customer_name'] ?? null,
                    'forma_pagamento' => $mapPagamento[$data['payment_method']],
                    'subtotal'        => $data['subtotal'],
                    'desconto'        => $data['discount'] ?? 0,
                    'total'           => $data['total'],
                ]);

                foreach ($data['items'] as $item) {

                    $produto = Produto::find($item['id']);
                    if (!$produto) {
                        abort(400, 'Produto inválido');
                    }

                    $estoque = Estoque::where('produto_id', $produto->id)->first();
                    if (!$estoque) {
                        abort(400, "Produto {$produto->nome} não possui estoque cadastrado");
                    }

                    if ($estoque->quantidade < $item['quantity']) {
                        abort(400, "Estoque insuficiente para {$produto->nome}");
                    }

                    $valorUnitario = $estoque->valor_unitario;
                    $valorTotalItem = $valorUnitario * $item['quantity'];

                    Venda_itens::create([
                        'venda_id'       => $venda->id,
                        'produto_id'     => $produto->id,
                        'categoria'      => $produto->categoria,
                        'nome_produto'   => $produto->nome,
                        'quantidade'     => $item['quantity'],
                        'valor_unitario' => $valorUnitario,
                        'valor_total'    => $valorTotalItem,
                    ]);

                    $estoque->decrement('quantidade', $item['quantity']);

                    EstoqueMovimentacao::create([
                        'produto_id'     => $produto->id,
                        'categoria'      => $produto->categoria,
                        'nome_produto'   => $produto->nome,
                        'valor_unitario' => $valorUnitario,
                        'quantidade'     => $item['quantity'],
                        'total'          => $valorTotalItem,
                        'tipo'           => 'saida',
                        'data' => Carbon::now(),
                    ]);
                }

                $caixaAberto->increment('saldo', $data['total']);
                $caixaAberto->increment('total_vendas');
            });

            return response()->json([
                'success' => true,
                'message' => 'Venda realizada com sucesso'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    public function getVenda(): \Illuminate\Http\JsonResponse
    {
        $sales = Venda::all(['numero_nota'])->map(function($venda) {
            $venda->sale_id = 'SALE' . str_pad($venda->numero_nota, 4, '0', STR_PAD_LEFT);
            return $venda;
        });

        return response()->json([
            'success' => true,
            'data' => $sales
        ]);
    }

}
