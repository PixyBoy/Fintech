<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Presentation\Livewire\Admin\LoginPage;
use App\Modules\Auth\Presentation\Livewire\Admin\TwoFactorChallenge;
use App\Modules\Auth\Presentation\Http\Controllers\LogoutController;

Route::middleware('web')->group(function () {
    Route::get('/admin/login', LoginPage::class)->name('auth.admin.login');
    Route::get('/admin/two-factor', TwoFactorChallenge::class)->name('auth.admin.2fa');
    Route::post('/admin/logout', [LogoutController::class, 'admin'])->name('auth.admin.logout');
});
