<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\EstoqueMovimentacao;
use App\Models\Entrada;
use App\Models\Produto;
use App\Models\Estoque;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EntradaController extends Controller
{
    public function index()
    {
        $entradas = Entrada::orderBy('created_at', 'desc')->get();
        $produtos = Produto::orderBy('nome')->get();

        $caixaStatus = Caixa::latest()->first();

        $caixaAberto  = $caixaStatus && $caixaStatus->status == 'aberto';
        $caixaFechado = !$caixaStatus || $caixaStatus->status == 'fechado';

        return view('entradas.index', compact(
            'entradas',
            'produtos',
            'caixaAberto',
            'caixaFechado',
            'caixaStatus'
        ));
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'itens' => 'required|array|min:1',
            'itens.*.id' => 'required|exists:produtos,id',
            'itens.*.nome' => 'required|string',
            'itens.*.qtd' => 'required|integer|min:1',
            'itens.*.valor' => 'required|numeric|min:0.01',
        ]);

        $itens = $request->itens;
        $valorTotalEntrada = 0;
        $itensParaSalvar = [];

        foreach ($itens as $item) {
            $produtoId = $item['id'];
            $nomeProduto = $item['nome'];
            $quantidade = (int) $item['qtd'];
            $valorUnitario = (float) $item['valor'];

            $total = $quantidade * $valorUnitario;
            $valorTotalEntrada += $total;

            $itensParaSalvar[] = [
                'produto_id'     => $produtoId,
                'nome'           => $nomeProduto,
                'valor_unitario' => $valorUnitario,
                'quantidade'     => $quantidade,
                'valor_total'    => $total,
            ];

            $produto = Produto::find($produtoId);
            $categoria = $produto->categoria ?? null;

            $estoque = Estoque::firstOrCreate(
                ['produto_id' => $produtoId],
                [
                    'categoria'      => $categoria,
                    'nome'           => $nomeProduto,
                    'quantidade'     => 0,
                    'valor_unitario' => 0,
                    'valor_total'    => 0,
                ]
            );

            $estoque->quantidade += $quantidade;
            $estoque->valor_unitario = $valorUnitario;
            $estoque->valor_total += $total;
            $estoque->save();

            EstoqueMovimentacao::create([
                'produto_id'     => $produtoId,
                'categoria'      => $categoria,
                'nome_produto'   => $nomeProduto,
                'valor_unitario' => $valorUnitario,
                'quantidade'     => $quantidade,
                'total'          => $total,
                'tipo'           => 'entrada',
                'data'           => now(),
            ]);
        }

        Entrada::create([
            'observacao' => $request->observacao ?? null,
            'itens'      => $itensParaSalvar,
            'valor_total'=> $valorTotalEntrada,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Entrada salva com sucesso!',
        ]);
    }


    public function destroy($id)
    {
        Entrada::findOrFail($id)->delete();

        return redirect()->back()->with('sucesso', 'Entrada removida com sucesso!');
    }

}
