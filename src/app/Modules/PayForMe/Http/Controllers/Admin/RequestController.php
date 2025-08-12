<?php

namespace App\Modules\PayForMe\Http\Controllers\Admin;

use App\Modules\PayForMe\Application\UseCases\Admin\UpdateStatus;
use App\Modules\PayForMe\Infrastructure\Persistence\Eloquent\Models\PayForMeRequestModel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RequestController extends Controller
{
    public function __construct(protected UpdateStatus $updateStatus)
    {
    }

    public function index()
    {
        $requests = PayForMeRequestModel::latest()->paginate();
        return view('pay-for-me::admin.index', compact('requests'));
    }

    public function show($id)
    {
        $request = PayForMeRequestModel::findOrFail($id);
        return view('pay-for-me::admin.show', compact('request'));
    }

    public function updateStatus($id, Request $request)
    {
        $attachment = null;
        if ($request->hasFile('receipt')) {
            $attachment = \App\Modules\PayForMe\Infrastructure\Storage\Attachments\AttachmentHelper::store($request->file('receipt'));
        }
        $this->updateStatus->execute($id, $request->input('status'), $request->input('note'), $attachment);
        return redirect()->back();
    }
}
