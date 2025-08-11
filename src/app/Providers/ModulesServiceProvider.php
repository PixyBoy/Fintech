<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * The discovered modules and their base paths.
     *
     * @var array<string, string>
     */
    protected array $modules = [];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $modulesPath = app_path('Modules');
        if (! is_dir($modulesPath)) {
            return;
        }

        foreach (glob($modulesPath.'/*', GLOB_ONLYDIR) as $modulePath) {
            $name = basename($modulePath);

            if (! config("modules.enabled.$name", true)) {
                continue;
            }

            $this->modules[$name] = $modulePath;

            $providerClass = "App\\Modules\\{$name}\\{$name}ServiceProvider";
            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ($this->modules as $name => $path) {
            $this->loadModule($name, $path);
        }
    }

    /**
     * Load resources for the given module.
     */
    protected function loadModule(string $name, string $modulePath): void
    {
        if (is_dir($modulePath.'/Database/Migrations')) {
            $this->loadMigrationsFrom($modulePath.'/Database/Migrations');
        }

        if (file_exists($modulePath.'/routes/web.php')) {
            $this->loadRoutesFrom($modulePath.'/routes/web.php');
        }

        if (is_dir($modulePath.'/Resources/views')) {
            $this->loadViewsFrom($modulePath.'/Resources/views', 'module::'.Str::lower($name));
        }

        if (is_dir($modulePath.'/Config')) {
            foreach (glob($modulePath.'/Config/*.php') as $configFile) {
                $configName = pathinfo($configFile, PATHINFO_FILENAME);
                $this->mergeConfigFrom($configFile, Str::lower($name).'.'.$configName);
            }
        }
    }
}
