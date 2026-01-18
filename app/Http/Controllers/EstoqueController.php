<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Estoque;


class EstoqueController extends Controller
{
    public function index(Request $request)
    {
        $query = Estoque::with('produto');

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('status')) {
            if ($request->status === 'disponivel') {
                $query->where('quantidade', '>', 5);
            } elseif ($request->status === 'pouco') {
                $query->whereBetween('quantidade', [1, 5]);
            } elseif ($request->status === 'esgotado') {
                $query->where('quantidade', 0);
            }
        }

        if ($request->filled('search')) {
            $query->whereHas('produto', function($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->search . '%');
            });
        }

        // Paginação
        $ProdutoPages = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $totalProdutos = $query->sum('quantidade');
        $valorTotalEstoque = $query->sum(\DB::raw('quantidade * valor_unitario'));
        $totalEsgotados = $query->where('quantidade', 0)->count();
        $totalPouco = $query->whereBetween('quantidade', [1,5])->count();
        $categorias = Estoque::select('categoria')->distinct()->pluck('categoria');


        return view('estoque.index', compact(
            'ProdutoPages',
            'totalProdutos',
            'valorTotalEstoque',
            'totalEsgotados',
            'totalPouco',
            'categorias'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'valor' => 'required|numeric',
            'quantidade' => 'required|integer',
        ]);

        Estoque::create([
            'nome' => $request->nome,
            'valor' => $request->valor,
            'quantidade' => $request->quantidade,
            'data_hora' => now(),
        ]);

        return redirect()->back()->with('sucesso', 'Produto adicionado ao estoque!');
    }

    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        Estoque::findOrFail($id)->delete();

        return redirect()->back()->with('sucesso', 'Entrada removida com sucesso!');

    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:produtos,id',
            'categoria' => 'required|string|max:255',
            'nome' => 'required|string|max:255',
            'quantidade' => 'required|integer|min:0',
            'valor' => 'required|numeric|min:0',
        ]);

        $produto = Estoque::findOrFail($request->id);
        $produto->update([
            'categoria' => $request->categoria,
            'nome' => $request->nome,
            'quantidade' => $request->quantidade,
            'valor_unitario' => $request->valor,
        ]);
        return response()->json($produto);
    }

}





