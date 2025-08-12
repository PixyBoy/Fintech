<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Orders\Http\Controllers\Admin\OrderController;

Route::middleware(['web','auth'])
    ->prefix('admin/orders')
    ->group(function(){
        Route::get('/', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    });
