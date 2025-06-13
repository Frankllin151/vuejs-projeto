<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ParcelasController;
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');
// vendas
Route::get('dashboard',[VendaController::class, 'index'] )
->middleware(['auth', 'verified'])->name('dashboard');

Route::post("/dashboard/novavenda", [VendaController::class, "create"])
->middleware(['auth', 'verified'])->name('dashboard.delete');

Route::delete("/dashboard/venda/{id}/delete", [VendaController::class, "delete"])
->middleware(['auth', 'verified'])->name('dashboard.delete');
// vendas end
// produtos
Route::get('dashboard/produto', [ProdutoController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('produto.index');

Route::put("/dashboard/produto/{id}/edit", [ProdutoController::class, "edit"])
->middleware(['auth', 'verified'])
    ->name('produto.edit');

Route::delete('/dashboard/produto/{id}', [ProdutoController::class, "destroy"])
->middleware(['auth', 'verified'])
    ->name('produto.destroy');

Route::post("/dashboard/postproduto", [ProdutoController::class,"create"])
->middleware(['auth', 'verified'])
    ->name('produto.destroy');
// produtos end

// parcelas 
Route::get('dashboard/parcelas',[ParcelasController::class, "index"])
->middleware(['auth', 'verified'])
    ->name('parcela.all');
Route::put("dashboard/parcelas/{id}",[ParcelasController::class, "edit"])
->middleware(['auth', 'verified'])
    ->name('parcela.editar');
Route::post('/dashboard/postnovaparcela', [ParcelasController::class, "create"])
->middleware(['auth', 'verified'])
    ->name('parcela.postnovaparcela');
Route::delete("/dashboard/parceladelete/{id}",[ParcelasController::class, "delete"] )
->middleware(['auth', 'verified'])
    ->name('parcela.postnovaparcela');
// parcelas  end
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
