<?php

namespace App\Modules\AdminShell;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/Presentation/Resources/views', 'admin');

        Route::middleware(['web','auth:admin','admin.2fa'])
            ->prefix('admin')
            ->as('admin.')
            ->group(__DIR__.'/Presentation/Http/Routes/admin.php');
    }
}
