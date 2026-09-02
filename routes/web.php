<?php

use App\Http\Controllers\BoletoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotaFiscalController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/boletos', [BoletoController::class, 'index'])->name('boletos.index');
    Route::get('/boletos/{boleto}/pdf', [BoletoController::class, 'pdf'])->name('boletos.pdf');

    Route::get('/notas-fiscais', [NotaFiscalController::class, 'index'])->name('notas-fiscais.index');
    Route::get('/notas-fiscais/{notaFiscal}', [NotaFiscalController::class, 'show'])->name('notas-fiscais.show');
    Route::get('/notas-fiscais/{notaFiscal}/pdf', [NotaFiscalController::class, 'pdf'])->name('notas-fiscais.pdf');

    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{pedidoId}', [PedidoController::class, 'show'])->name('pedidos.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
