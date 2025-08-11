<?php

namespace App\Modules\SharedKernel;

use Illuminate\Support\ServiceProvider;

class SharedKernelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }
}
