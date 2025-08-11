<?php

namespace App\Modules\Orders\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders::admin.orders.index');
    }

    public function show()
    {
        return view('orders::admin.orders.show');
    }
}
