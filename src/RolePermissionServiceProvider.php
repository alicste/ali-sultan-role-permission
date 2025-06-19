<?php

namespace AliSultan\RolePermission;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;

class RolePermissionServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/role-permission.php',
            'role-permission'
        );
    }

    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/role-permission.php' => config_path('role-permission.php'),
        ], 'config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ], 'migrations');

        // Publish seeders
        $this->publishes([
            __DIR__ . '/database/seeders' => database_path('seeders'),
        ], 'seeders');

        // Auto-load migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Register Filament resources manually (optional)
        if (class_exists(Filament::class)) {
            Filament::registerResources([
                \AliSultan\RolePermission\Filament\Resources\PermissionGroupResource::class,
                \AliSultan\RolePermission\Filament\Resources\PermissionResource::class,
                \AliSultan\RolePermission\Filament\Resources\RoleResource::class,
                \AliSultan\RolePermission\Filament\Resources\UserResource::class,
            ]);
        }

        // Register Artisan commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \AliSultan\RolePermission\Console\Commands\InstallRolePermission::class,
            ]);
        }
    }
}
