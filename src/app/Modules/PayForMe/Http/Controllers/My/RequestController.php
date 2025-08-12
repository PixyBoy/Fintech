<?php

namespace App\Modules\PayForMe\Http\Controllers\My;

use App\Modules\PayForMe\Application\UseCases\GetMyRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function __construct(protected GetMyRequests $getMyRequests)
    {
    }

    public function index()
    {
        $requests = $this->getMyRequests->execute(Auth::id());
        return view('pay-for-me::my.index', compact('requests'));
    }
}
