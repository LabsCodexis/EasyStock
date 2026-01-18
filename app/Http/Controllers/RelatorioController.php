<?php
namespace App\Http\Controllers;

use App\Exports\RelatorioGerencialExport;
use App\Models\Entrada;
use App\Models\EstoqueMovimentacao;
use App\Models\Venda;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Produto;
use Maatwebsite\Excel\Facades\Excel;


class RelatorioController extends Controller
{
    public function index(Request $request)
    {
        // Inputs para o HTML (formato ISO para <input type="date">)
        $dataInicioInput = $request->data_inicio ?? now()->startOfMonth()->format('Y-m-d');
        $dataFimInput = $request->data_fim ?? now()->endOfMonth()->format('Y-m-d');

        // Datas para query do banco
        $inicio = $request->data_inicio
            ? Carbon::createFromFormat('Y-m-d', $request->data_inicio)->startOfDay()
            : now()->startOfMonth();
        $fim = $request->data_fim
            ? Carbon::createFromFormat('Y-m-d', $request->data_fim)->endOfDay()
            : now()->endOfMonth();

        $movimentacoesEstoque = EstoqueMovimentacao::whereBetween('created_at', [$inicio, $fim])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $produtos = DB::table('venda_itens')
            ->join('produtos', 'produtos.id', 'venda_itens.produto_id')
            ->select('produtos.nome', DB::raw('SUM(venda_itens.quantidade) as quantidade'))
            ->groupBy('produtos.nome')
            ->orderByDesc('quantidade')
            ->paginate(10);

        $faturamento = Venda::whereBetween('created_at', [$inicio, $fim])->sum('total');
        $qtdVendas = Venda::whereBetween('created_at', [$inicio, $fim])->count();
        $itensVendidos = DB::table('venda_itens')
            ->join('vendas', 'vendas.id', '=', 'venda_itens.venda_id')
            ->whereBetween('vendas.created_at', [$inicio, $fim])
            ->sum('venda_itens.quantidade');

        $entradas = Entrada::whereBetween('created_at', [$inicio, $fim])->sum('valor_total');
        $saidas = EstoqueMovimentacao::where('tipo', 'saida')
            ->whereBetween('created_at', [$inicio, $fim])
            ->sum('total');

        $ticketMedio = $qtdVendas ? $faturamento / $qtdVendas : 0;

        $faturamentoDiario = Venda::select(
            DB::raw('DATE(created_at) as data'),
            DB::raw('SUM(total) as total')
        )
            ->whereBetween('created_at', [$inicio, $fim])
            ->groupBy('data')
            ->orderBy('data')
            ->get();

        $formasPagamento = Venda::select('forma_pagamento', DB::raw('SUM(total) as total'))
            ->whereBetween('created_at', [$inicio, $fim])
            ->groupBy('forma_pagamento')
            ->get();

        return view('relatorios.index', compact(
            'inicio',
            'fim',
            'faturamento',
            'qtdVendas',
            'itensVendidos',
            'entradas',
            'saidas',
            'ticketMedio',
            'faturamentoDiario',
            'formasPagamento',
            'produtos',
            'movimentacoesEstoque',
            'dataInicioInput',
            'dataFimInput'
        ));
    }


    public function pdf(Request $request)
    {
        $inicio = $request->data_inicio ?? now()->startOfMonth();
        $fim    = $request->data_fim ?? now()->endOfMonth();

        $faturamento = Venda::whereBetween('created_at', [$inicio, $fim])->sum('total');
        $qtdVendas = Venda::whereBetween('created_at', [$inicio, $fim])->count();
        $itensVendidos = DB::table('venda_itens')->sum('quantidade');
        $entradas = Entrada::whereBetween('created_at', [$inicio, $fim])->sum('valor_total');
        $saidas = EstoqueMovimentacao::where('tipo', 'saida')
            ->whereBetween('created_at', [$inicio, $fim])
            ->sum('total');

        $ticketMedio = $qtdVendas ? $faturamento / $qtdVendas : 0;

        $faturamentoDiario = Venda::select(
            DB::raw('DATE(created_at) as data'),
            DB::raw('SUM(total) as total')
        )
            ->whereBetween('created_at', [$inicio, $fim])
            ->groupBy('data')
            ->orderBy('data')
            ->get();

        $formasPagamento = Venda::select(
            'forma_pagamento',
            DB::raw('SUM(total) as total')
        )
            ->groupBy('forma_pagamento')
            ->get();

        $produtos = DB::table('venda_itens')
            ->join('produtos', 'produtos.id', 'venda_itens.produto_id')
            ->select(
                'produtos.nome',
                DB::raw('SUM(venda_itens.quantidade) as quantidade')
            )
            ->groupBy('produtos.nome')
            ->orderByDesc('quantidade')
            ->limit(10)
            ->get();

        $movimentacoesEstoque = EstoqueMovimentacao::whereBetween('created_at', [$inicio, $fim])->get();

        $pdf = Pdf::loadView('relatorios.pdf', compact(
            'inicio',
            'fim',
            'faturamento',
            'qtdVendas',
            'itensVendidos',
            'entradas',
            'saidas',
            'ticketMedio',
            'faturamentoDiario',
            'formasPagamento',
            'produtos',
            'movimentacoesEstoque'
        ));

        return $pdf->download('relatorio-gerencial.pdf');
    }

    public function excel(Request $request)
    {
        $inicio = $request->data_inicio
            ? Carbon::parse($request->data_inicio)->startOfDay()
            : now()->startOfMonth();

        $fim = $request->data_fim
            ? Carbon::parse($request->data_fim)->endOfDay()
            : now()->endOfMonth();

        return Excel::download(
            new RelatorioGerencialExport($inicio, $fim),
            'relatorio-gerencial.xlsx'
        );
    }



    public function movimentacoesApi(Request $request)
    {
        try {
            $mov = EstoqueMovimentacao::select(
                'categoria',
                'nome_produto',
                'valor_unitario',
                'quantidade',
                'total',
                'tipo',
                'data'
            )
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            $mov->getCollection()->transform(function($item) {
                $item->data = \Carbon\Carbon::parse($item->data)->format('d/m/Y');
                return $item;
            });

            return response()->json($mov);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Falha ao carregar movimentações',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function produtosApi(Request $request): \Illuminate\Http\JsonResponse
    {
        $prod = DB::table('venda_itens')
            ->join('produtos', 'produtos.id', '=', 'venda_itens.produto_id')
            ->select('produtos.nome', 'produtos.categoria', DB::raw('SUM(venda_itens.quantidade) as quantidade'))
            ->groupBy('produtos.nome', 'produtos.categoria')
            ->orderByDesc('quantidade')
            ->paginate(10);

        return response()->json($prod);
    }

}
