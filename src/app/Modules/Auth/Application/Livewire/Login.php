<?php

namespace App\Modules\Auth\Application\Livewire;

use App\Models\User;
use App\Modules\Auth\Application\Services\OtpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use App\Modules\Auth\Application\Events\UserLoggedIn;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Login extends Component
{
    #[Rule(['required','regex:/^(?:\+98|0)?9\d{9}$/'], as: 'شماره موبایل')]
    public string $phone = '';

    #[Rule(['required','digits_between:4,6'], as: 'کد تایید')]
    public string $code  = '';

    public int $step = 1;
    public bool $busy = false;

    public function mount()
    {
        $this->phone = session('auth_phone', '');
        if ($this->phone) {
            $this->step = 2;
        }
    }

    public function requestOtp(OtpService $otp)
    {
        $this->validateOnly('phone');
        $this->busy = true;

        try {
            $otp->requestCode($this->phone, request()->ip());
            session()->put('auth_phone', $this->phone);
            $this->step = 2;
            $this->dispatch('toast', message: 'کد تأیید ارسال شد.', type: 'success');
        } catch (\Throwable $e) {
            $this->addError('phone', 'ارسال کد ممکن نشد. کمی بعد دوباره تلاش کنید.');
        } finally {
            $this->busy = false;
        }
    }

    public function verifyOtp(OtpService $otp)
    {
        $this->validate();
        $this->busy = true;

        try {
            $otp->verifyCode($this->phone, $this->code, request()->ip());

            $user = User::firstOrCreate(
                ['phone' => $this->phone],
                [
                    'verification_level' => 0,
                    'name' => $this->phone,
                    'email' => $this->phone.'@example.com',
                    'password' => bcrypt('secret'),
                ]
            );

            Auth::login($user, true);
            Event::dispatch(new UserLoggedIn($user));
            session()->forget('auth_phone');

            // Toast + بستن دیالوگ + رفرش Navbar
            $this->dispatch('toast', message: 'با موفقیت وارد شدید.', type: 'success');
            $this->dispatch('close-dialog', name: 'login-dialog');
            $this->dispatch('auth-changed');

            // اگر خواستی بعداً به مسیر خاصی بروی:
            // return $this->redirectIntended('/', navigate: false);

        } catch (\Throwable $e) {
            $this->addError('code', 'کد نادرست است یا منقضی شده.');
        } finally {
            $this->busy = false;
        }
    }

    public function resend(OtpService $otp)
    {
        if ($this->step !== 2 || empty($this->phone)) return;
        try {
            $otp->requestCode($this->phone, request()->ip());
            $this->dispatch('toast', message: 'کد مجدداً ارسال شد.', type: 'info');
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'ارسال مجدد ممکن نشد.', type: 'error');
        }
    }

    public function changePhone()
    {
        $this->resetValidation();
        $this->code = '';
        $this->step = 1;
        session()->forget('auth_phone');
    }

    public function render()
    {
        return view('auth::components.login-dialog');
    }
}
