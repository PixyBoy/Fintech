<?php

namespace App\Modules\PayForMe\Application\Livewire\Public;

use App\Modules\PayForMe\Application\DTOs\CreateRequestInput;
use App\Modules\PayForMe\Application\UseCases\CreateRequest;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class RequestForm extends Component
{
    use WithFileUploads;

    public string $target_url = '';
    public $amount_usd = 0;
    public ?string $notes = null;
    public array $attachments = [];

    public ?string $request_code = null;

    public function submit(CreateRequest $createRequest)
    {
        $input = new CreateRequestInput(Auth::id(), $this->target_url, (float)$this->amount_usd, $this->notes, $this->attachments);
        $view = $createRequest->execute($input);
        $this->request_code = $view->request_code;
        $this->reset(['target_url','amount_usd','notes','attachments']);
    }

    public function render()
    {
        return view('pay-for-me::public.form-livewire');
    }
}
