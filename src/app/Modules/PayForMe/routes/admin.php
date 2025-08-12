<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PayForMe\Http\Controllers\Admin\RequestController as AdminRequestController;

Route::middleware(['web','auth'])
    ->prefix(config('payforme.routes_prefix').'/admin')
    ->group(function () {
        Route::get('/requests', [AdminRequestController::class, 'index'])->name('payforme.admin.index');
        Route::get('/requests/{id}', [AdminRequestController::class, 'show'])->name('payforme.admin.show');
        Route::post('/requests/{id}/status', [AdminRequestController::class, 'updateStatus'])->name('payforme.admin.status');
    });
