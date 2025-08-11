<?php

namespace App\Modules\Rates\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

class FeeRuleController extends Controller
{
    public function index()
    {
        return view('rates::admin.fees.index');
    }
}
