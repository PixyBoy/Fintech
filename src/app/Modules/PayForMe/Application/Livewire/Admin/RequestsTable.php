<?php

namespace App\Modules\PayForMe\Application\Livewire\Admin;

use App\Modules\PayForMe\Infrastructure\Persistence\Eloquent\Models\PayForMeRequestModel;
use Livewire\Component;
use Livewire\WithPagination;

class RequestsTable extends Component
{
    use WithPagination;

    public string $status = '';

    public function render()
    {
        $query = PayForMeRequestModel::query();
        if ($this->status) {
            $query->where('status', $this->status);
        }
        $requests = $query->paginate();
        return view('pay-for-me::admin.requests-table', ['requests' => $requests]);
    }
}
