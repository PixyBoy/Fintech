<?php

namespace App\Modules\Auth\Presentation\Livewire\Public;

use Livewire\Component;
use App\Modules\Auth\Application\Commands\RequestOtp;
use App\Modules\Auth\Application\Commands\VerifyOtpAndLogin;
use App\Modules\Auth\Application\DTO\RequestOtpData;
use App\Modules\Auth\Application\DTO\VerifyOtpData;

class LoginDialog extends Component
{
    public bool $open = false;
    public string $state = 'phone';
    public string $phone = '';
    public string $code = '';

    protected $listeners = [
        'open-login' => 'open',
    ];

    public function open(): void
    {
        $this->resetErrorBag();
        $this->state = 'phone';
        $this->open = true;
    }

    public function requestOtp(): void
    {
        $this->validate(['phone' => ['required','string','min:10']]);
        app(RequestOtp::class)->handle(new RequestOtpData($this->phone));
        $this->state = 'otp';
    }

    public function verify(): void
    {
        $this->validate([
            'phone' => ['required','string'],
            'code'  => ['required','digits:6'],
        ]);
        app(VerifyOtpAndLogin::class)->handle(new VerifyOtpData($this->phone, $this->code));
        $this->dispatch('auth-updated');
        $this->state = 'success';
    }

    public function render()
    {
        return view('auth::public.auth.login-dialog');
    }
}
