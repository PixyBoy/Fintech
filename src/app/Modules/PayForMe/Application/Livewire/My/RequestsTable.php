<?php

namespace App\Modules\PayForMe\Application\Livewire\My;

use App\Modules\PayForMe\Application\UseCases\GetMyRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class RequestsTable extends Component
{
    use WithPagination;

    public function render(GetMyRequests $useCase)
    {
        $requests = $useCase->execute(Auth::id());
        return view('pay-for-me::my.requests-table', ['requests' => $requests]);
    }
}
