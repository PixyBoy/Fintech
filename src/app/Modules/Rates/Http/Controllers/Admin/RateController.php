<?php

namespace App\Modules\Rates\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

class RateController extends Controller
{
    public function index()
    {
        return view('rates::admin.rates.index');
    }
}
