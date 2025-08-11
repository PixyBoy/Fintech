<?php

namespace App\Modules\Auth;

use App\Modules\Auth\Domain\Contracts\OtpStoreInterface;
use App\Modules\Auth\Domain\Contracts\SmsProviderInterface;
use App\Modules\Auth\Infrastructure\Otp\RedisOtpStore;
use App\Modules\Auth\Infrastructure\Sms\DummySmsProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Src\App\Modules\Auth\Application\Http\Livewire\Login;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SmsProviderInterface::class, DummySmsProvider::class);
        $this->app->bind(OtpStoreInterface::class, RedisOtpStore::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        RateLimiter::for('otp-request', function (Request $request) {
            $phone = $request->input('phone', '');
            return [
                Limit::perMinutes(5, 3)->by('phone:'.$phone),
                Limit::perMinute(5)->by('ip:'.$request->ip()),
            ];
        });
    }
}
