<?php

namespace App\Modules\UiKit;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/Presentation/Resources/views', 'ui');

        // ثبت کلاس‌ـمحور با namespace
        Blade::componentNamespace(
            'App\\Modules\\UiKit\\Presentation\\View\\Components',
            'ui'
        );
    }
}
