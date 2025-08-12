<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Rates\Http\Controllers\Admin\RateController;
use App\Modules\Rates\Http\Controllers\Admin\FeeRuleController;

Route::middleware(['web','auth'])
    ->prefix('admin/rates')
    ->group(function(){
        Route::get('/', [RateController::class, 'index'])->name('admin.rates.index');
        Route::get('/fees', [FeeRuleController::class, 'index'])->name('admin.fees.index');
        Route::get('/quote-tester', fn() => view('rates::admin.quote.tester'))->name('admin.quote.tester');
    });
