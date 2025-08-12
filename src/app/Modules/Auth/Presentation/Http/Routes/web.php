<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Presentation\Http\Controllers\LogoutController;

Route::post('/logout', [LogoutController::class, 'web'])->name('auth.web.logout');
