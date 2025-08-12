<?php

namespace App\Modules\PayForMe\Application\Livewire\Admin;

use App\Modules\PayForMe\Application\UseCases\Admin\UpdateStatus;
use App\Modules\PayForMe\Infrastructure\Persistence\Eloquent\Models\PayForMeRequestModel;
use Livewire\Component;
use Livewire\WithFileUploads;

class RequestShow extends Component
{
    use WithFileUploads;

    public PayForMeRequestModel $request;
    public string $status;
    public ?string $note = null;
    public $receipt;

    public function mount($id)
    {
        $this->request = PayForMeRequestModel::findOrFail($id);
        $this->status = $this->request->status;
    }

    public function updateStatus(UpdateStatus $updateStatus)
    {
        $attachment = null;
        if ($this->receipt) {
            $attachment = \App\Modules\PayForMe\Infrastructure\Storage\Attachments\AttachmentHelper::store($this->receipt);
        }
        $updateStatus->execute($this->request->id, $this->status, $this->note, $attachment);
        $this->request->refresh();
    }

    public function render()
    {
        return view('pay-for-me::admin.request-show');
    }
}
