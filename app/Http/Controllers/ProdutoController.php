<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function index(Request $request) {
        $produtosPag = Produto::latest('id')->paginate(10);
        $produtos = Produto::all();

        if ($request->ajax()) {
            return view('produto.partials.produtos_table', compact('produtosPag'))->render();
        }

        return view('produto.index', compact('produtos', 'produtosPag')); // ✅ passar $produtosPag para a view
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
//        dd($request->all());
        $request->validate([
            'categoria' => 'required|string|max:255',
            'nome' => 'required|string|max:255',
            'quantidade' => 'required|integer|min:0',
            'valor' => 'required|numeric|min:0',
            'gramatura' => 'nullable|string|max:255',
        ]);

        $produto = Produto::create([
            'categoria' => $request->categoria,
            'nome' => $request->nome,
            'quantidade' => $request->quantidade,
            'valor' => $request->valor,
            'gramatura' => $request->gramatura ?? null,
        ]);

        return response()->json($produto);
    }



    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'id' => 'required|exists:produtos,id',
            'categoria' => 'required|string|max:255',
            'nome' => 'required|string|max:255',
            'quantidade' => 'required|integer|min:0',
            'valor' => 'required|numeric|min:0',
            'gramatura' => 'nullable|string|max:255',
        ]);

        $produto = Produto::findOrFail($request->id);
        $produto->update([
            'categoria' => $request->categoria,
            'nome' => $request->nome,
            'quantidade' => $request->quantidade,
            'valor' => $request->valor,
            'gramatura' => $request->gramatura ?? null,
        ]);
        return response()->json($produto);
    }

    public function destroy($id)
    {
        Produto::findOrFail($id)->delete();

        return redirect()->back()->with('sucesso', 'Entrada removida com sucesso!');
    }

}
