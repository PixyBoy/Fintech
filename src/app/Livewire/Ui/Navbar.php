<?php

namespace App\Livewire\Ui;

use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Navbar extends Component
{
    public bool $authed = false;

    public function mount()
    {
        $this->authed = Auth::check();
    }

    #[On('auth-changed')]
    public function refreshAuth()
    {
        $this->authed = Auth::check();
    }

    public function logout()
    {
        Auth::logout();
        $this->authed = false;
        $this->dispatch('toast', message: 'با موفقیت خارج شدید.', type: 'info');
    }

    public function render()
    {
        return view('livewire.ui.navbar');
    }
}
