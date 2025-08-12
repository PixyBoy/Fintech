<?php

namespace App\Modules\SiteShell;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/Presentation/Resources/views', 'site');

        Route::middleware('web')
            ->group(__DIR__.'/Presentation/Http/Routes/web.php');
    }
}
