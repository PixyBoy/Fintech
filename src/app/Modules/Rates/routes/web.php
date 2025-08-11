<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->prefix('admin/rates')
    ->group(function () {
        Route::view('/', 'rates::admin.rates.index')->name('admin.rates.index');
        Route::view('/fees', 'rates::admin.fees.index')->name('admin.fees.index');
        Route::view('/quote-tester', 'rates::admin.quote.tester')->name('admin.quote.tester');
    });
