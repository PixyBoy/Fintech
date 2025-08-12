<?php

namespace App\Modules\PayForMe\Http\Controllers;

use App\Modules\PayForMe\Application\DTOs\CreateRequestInput;
use App\Modules\PayForMe\Application\UseCases\CreateRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function __construct(protected CreateRequest $createRequest)
    {
    }

    public function create()
    {
        return view('payforme::public.form');
    }

    public function store(Request $request)
    {
        $input = new CreateRequestInput(
            Auth::id(),
            $request->input('target_url'),
            (float) $request->input('amount_usd'),
            $request->input('notes'),
            $request->file('attachments', [])
        );
        $view = $this->createRequest->execute($input);
        return redirect()->route('payforme.landing')->with('request_code', $view->request_code);
    }
}
