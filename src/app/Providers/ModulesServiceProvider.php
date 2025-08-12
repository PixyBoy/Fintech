<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $modulesPath = app_path('Modules');
        if (! is_dir($modulesPath)) return;

        foreach (glob($modulesPath.'/*', GLOB_ONLYDIR) as $modulePath) {
            $name = basename($modulePath);

            if (! config("modules.enabled.$name", true)) {
                continue;
            }
            $provider = "App\\Modules\\{$name}\\ModuleServiceProvider";
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }
}
