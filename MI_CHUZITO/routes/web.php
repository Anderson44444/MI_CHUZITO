<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EntregaController;

Route::resource('menu-items', MenuItemController::class);

Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'index']);
    Route::get('/ventas', [ReportController::class, 'ventas']);
    Route::get('/inventario', [ReportController::class, 'inventario']);
    Route::get('/pedidos', [ReportController::class, 'pedidos']);
    Route::get('/tiempos', [ReportController::class, 'tiempos']);
});

Route::prefix('entregas')->name('entregas.')->group(function () {
    Route::get('/', [EntregaController::class, 'index'])->name('index');
    Route::get('/{id}', [EntregaController::class, 'show'])->name('show');
    Route::post('/{id}/en-ruta', [EntregaController::class, 'enRuta'])->name('enRuta');
    Route::post('/{id}/confirmar', [EntregaController::class, 'confirmar'])->name('confirmar');
});