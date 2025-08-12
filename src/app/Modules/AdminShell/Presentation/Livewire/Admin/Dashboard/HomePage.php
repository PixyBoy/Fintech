<?php

namespace App\Modules\AdminShell\Presentation\Livewire\Admin\Dashboard;

use Livewire\Component;

class HomePage extends Component
{
    public function render()
    {
        return view('admin::dashboard.home')->layout('admin::layouts.admin');
    }
}
