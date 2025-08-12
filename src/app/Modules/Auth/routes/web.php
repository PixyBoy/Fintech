<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Application\Http\Controllers\AuthController;

Route::middleware('web')->group(function () {

    Route::view('/login', 'auth::auth.login')
        ->name('login')
        ->middleware('guest');

    Route::post('/auth/request-otp', [AuthController::class, 'requestOtp'])
        ->middleware('throttle:6,1')
        ->name('auth.request_otp');

    Route::post('/auth/verify-otp',  [AuthController::class, 'verifyOtp'])
        ->middleware('throttle:10,1')
        ->name('auth.verify_otp');

    Route::middleware('auth')->group(function () {
        Route::get('/kyc/level-1', [AuthController::class, 'kycLevel1Form'])->name('kyc.l1.form');
        Route::post('/kyc/level-1', [AuthController::class, 'kycLevel1Submit'])->name('kyc.l1.submit');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
