<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PayForMe\Http\Controllers\LandingController;
use App\Modules\PayForMe\Http\Controllers\My\RequestController as MyRequestController;
use App\Modules\PayForMe\Http\Controllers\RequestController;

Route::middleware(['web','auth'])
    ->prefix(config('payforme.routes_prefix'))
    ->group(function () {
        Route::get('/', [LandingController::class, 'index'])->name('payforme.landing');
        Route::get('/request', [RequestController::class, 'create'])->name('payforme.request.create');
        Route::post('/request', [RequestController::class, 'store'])->name('payforme.request.store');
        Route::get('/my', [MyRequestController::class, 'index'])->name('payforme.my.index');
    });
