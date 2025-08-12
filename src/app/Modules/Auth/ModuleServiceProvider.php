<?php

namespace App\Modules\Auth;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind Repository & Gateways
        $this->app->bind(
            \App\Modules\Auth\Domain\Repositories\UserRepository::class,
            \App\Modules\Auth\Infrastructure\Persistence\Eloquent\Repositories\EloquentUserRepository::class
        );

        $this->app->bind(
            \App\Modules\Auth\Infrastructure\Integrations\SmsGateway::class,
            \App\Modules\Auth\Infrastructure\Integrations\LogSmsGateway::class
        );
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/Presentation/Resources/views', 'auth');
        $this->loadMigrationsFrom(__DIR__.'/Infrastructure/Persistence/Migrations');

        // Routes
        Route::middleware('web')->group(__DIR__.'/Presentation/Http/Routes/web.php');
        Route::middleware('web')->group(__DIR__.'/Presentation/Http/Routes/admin.php');

        // Livewire auto-register
        $this->registerLivewire('App\\Modules\\Auth\\Presentation\\Livewire', 'auth');

        // Middleware alias for admin 2FA
        app('router')->aliasMiddleware('admin.2fa', \App\Modules\Auth\Infrastructure\Http\Middleware\EnsureAdminTwoFactorVerified::class);
    }

    protected function registerLivewire(string $baseNamespace, string $aliasPrefix): void
    {
        $map = [
            'Public' => __DIR__.'/Presentation/Livewire/Public',
            'Admin'  => __DIR__.'/Presentation/Livewire/Admin',
        ];

        foreach ($map as $area => $path) {
            if (! is_dir($path)) continue;

            $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($rii as $file) {
                if ($file->isDir() || $file->getExtension() !== 'php') continue;

                $relative = str_replace([$path.DIRECTORY_SEPARATOR, '.php'], '', $file->getPathname());
                $relative = str_replace(DIRECTORY_SEPARATOR, '\\', $relative);
                $class = "{$baseNamespace}\\{$area}\\".$relative;

                if (class_exists($class)) {
                    $slug = strtolower($aliasPrefix.'.'.str_replace('\\', '.', "{$area}.".$relative));
                    Livewire::component($slug, $class);
                }
            }
        }
    }
}
