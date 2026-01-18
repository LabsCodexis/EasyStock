<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\RelatorioController;

/*
|--------------------------------------------------------------------------
| Rotas de Produtos
|--------------------------------------------------------------------------
*/
Route::prefix('produtos')->name('produtos.')->group(function () {
    Route::get('/', [ProdutoController::class, 'index'])->name('index'); // lista// form novo
    Route::post('/', [ProdutoController::class, 'store'])->name('store'); // salvar novo// form editar
    Route::put('/{id}', [ProdutoController::class, 'update'])->name('update'); // atualizar
    Route::delete('/{id}', [ProdutoController::class, 'destroy'])->name('destroy'); // deletar
});


/*
|--------------------------------------------------------------------------
| Rotas de Entradas
|--------------------------------------------------------------------------
*/
Route::prefix('entradas')->name('entradas.')->group(function () {
    Route::get('/', [EntradaController::class, 'index'])->name('index');
      Route::post('/', [EntradaController::class, 'store'])->name('store');
    Route::delete('/{id}', [EntradaController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Rotas de Estoque
|--------------------------------------------------------------------------
*/
Route::prefix('estoque')->name('estoque.')->group(function () {
    Route::get('/', [EstoqueController::class, 'index'])->name('index');
    Route::post('/', [EstoqueController::class, 'store'])->name('store');
    Route::put('/{id}', [EstoqueController::class, 'update'])->name('update');
    Route::delete('/{id}', [EstoqueController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Rotas de Caixa/Vendas
|--------------------------------------------------------------------------
*/
Route::prefix('caixa')->name('caixa.')->group(function () {
    Route::get('/', [VendaController::class, 'index'])->name('index');
    Route::post('/abrir', [VendaController::class, 'abrir'])->name('abrir');
    Route::post('/fechar', [VendaController::class, 'fechar'])->name('fechar');
    Route::post('/store', [VendaController::class, 'store'])->name('store');
});

Route::get('/venda', [VendaController::class, 'getVenda'])->name('venda.get');

/*
|--------------------------------------------------------------------------
| Rotas de Relatórios
|--------------------------------------------------------------------------
*/
Route::prefix('relatorios')->name('relatorios.')->group(function () {
    Route::get('/', [RelatorioController::class, 'index'])->name('index');
    Route::get('/pdf', [RelatorioController::class, 'pdf'])->name('pdf');
    Route::get('/excel', [RelatorioController::class, 'excel'])->name('excel');
});

Route::get('/movimentacoes', [RelatorioController::class, 'movimentacoesApi'])->name('movimentacoes.api');
Route::get('/produtos/api', [RelatorioController::class, 'produtosApi'])->name('produtos.api');
