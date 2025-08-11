<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Application\Http\Controllers\AuthController;

Route::middleware('web')->group(function () {

    Route::view('/login', 'authmod::auth.login')->name('auth.login');

    Route::post('/auth/request-otp', [AuthController::class, 'requestOtp'])
        ->middleware('throttle:otp-request')
        ->name('auth.request_otp');
    Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify_otp');

    Route::middleware('auth')->group(function () {
        Route::get('/kyc/level-1', [AuthController::class, 'kycLevel1Form'])->name('kyc.l1.form');
        Route::post('/kyc/level-1', [AuthController::class, 'kycLevel1Submit'])->name('kyc.l1.submit');
    });
});
