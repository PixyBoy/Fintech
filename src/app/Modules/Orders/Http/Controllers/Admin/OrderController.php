<?php

namespace App\Modules\Orders\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders::admin.orders.index');
    }

    public function show(int $order)
    {
        return view('orders::admin.orders.show');
    }
}
