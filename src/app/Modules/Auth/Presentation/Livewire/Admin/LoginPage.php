<?php

namespace App\Modules\Auth\Presentation\Livewire\Admin;

use Livewire\Component;
use App\Modules\Auth\Application\Commands\AdminLogin;
use App\Modules\Auth\Application\DTO\AdminLoginData;

class LoginPage extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = true;

    public function submit()
    {
        $this->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);

        $needs2fa = app(AdminLogin::class)->handle(
            new AdminLoginData($this->email, $this->password, $this->remember)
        );

        if ($needs2fa) {
            return redirect()->route('auth.admin.2fa');
        }
        return redirect()->route('admin.dashboard');
    }

    public function render()
    {
        return view('auth::admin.auth.login')->layout('admin::layouts.admin');
    }
}
