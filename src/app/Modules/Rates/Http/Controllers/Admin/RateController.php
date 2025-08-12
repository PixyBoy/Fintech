<?php

namespace App\Modules\Rates\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class RateController extends Controller
{
    public function index()
    {
        return view('rates::admin.rates.index');
    }
}
