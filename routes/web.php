<?php

use Illuminate\Support\Facades\Route;
use Praktika\Orders\Http\Controllers\OrderController;


// reiktu pridėti autorizavima


Route::middleware(['web'])->prefix('admin/orders')->name('orders.')->group(function () {
    
    // Uzsakymu sarasas
    Route::get('/', [OrderController::class, 'index'])->name('index');
    
    // Naujo užsakymo kurimas
    Route::get('/create', [OrderController::class, 'create'])->name('create');
    Route::post('/', [OrderController::class, 'store'])->name('store');
    
    // Vieno uzsakymo perziura
    Route::get('/{id}', [OrderController::class, 'show'])->name('show');
    
    // Uzsakymo redagavimas
    Route::get('/{id}/edit', [OrderController::class, 'edit'])->name('edit');
    Route::put('/{id}', [OrderController::class, 'update'])->name('update');
    
    // Uzsakymo istrynimas
    Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');
    
    // Greitas busenos keitimas
    Route::patch('/{id}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
    
    // Prekiu valdymas uzsakyme 
    Route::post('/{id}/items', [OrderController::class, 'addItem'])->name('addItem');
    Route::delete('/{orderId}/items/{itemId}', [OrderController::class, 'removeItem'])->name('removeItem');
    
});