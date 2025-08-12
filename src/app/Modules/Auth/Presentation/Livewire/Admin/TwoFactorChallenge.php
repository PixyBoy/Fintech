<?php

namespace App\Modules\Auth\Presentation\Livewire\Admin;

use Livewire\Component;
use App\Modules\Auth\Application\Commands\VerifyTwoFactor;
use App\Modules\Auth\Application\DTO\TwoFactorVerifyData;

class TwoFactorChallenge extends Component
{
    public string $code = '';

    public function verify()
    {
        $this->validate(['code' => ['required','digits:6']]);
        app(VerifyTwoFactor::class)->handle(new TwoFactorVerifyData($this->code));
        return redirect()->route('admin.dashboard');
    }

    public function render()
    {
        return view('auth::admin.auth.two-factor')->layout('admin::layouts.admin');
    }
}
