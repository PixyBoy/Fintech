<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->prefix('admin/orders')
    ->group(function () {
        Route::view('/', 'orders::admin.orders.index')->name('admin.orders.index');
        Route::view('/{order}', 'orders::admin.orders.show')->name('admin.orders.show');
    });
