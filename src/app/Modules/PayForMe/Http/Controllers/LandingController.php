<?php

namespace App\Modules\PayForMe\Http\Controllers;

use Illuminate\Routing\Controller;

class LandingController extends Controller
{
    public function index()
    {
        return view('pay-for-me::public.landing');
    }
}
