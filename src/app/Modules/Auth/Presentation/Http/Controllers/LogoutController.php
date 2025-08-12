<?php

namespace App\Modules\Auth\Presentation\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function web(): RedirectResponse
    {
        Auth::guard('web')->logout();
        return back();
    }

    public function admin(): RedirectResponse
    {
        Auth::guard('admin')->logout();
        session()->forget(['admin_needs_2fa','admin_2fa_passed']);
        return redirect()->route('auth.admin.login');
    }
}
