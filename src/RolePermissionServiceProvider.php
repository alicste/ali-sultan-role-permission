<?php

// namespace AliSultan\RolePermission;

// use Illuminate\Support\ServiceProvider;

// class RolePermissionServiceProvider extends ServiceProvider
// {
//     public function boot()
//     {
//         // Publish config
//         $this->publishes([
//             __DIR__ . '/../config/role-permission.php' => config_path('role-permission.php'),
//         ], 'config');

//         // Publish migrations
//         $this->publishes([
//             __DIR__ . '/../database/migrations/' => database_path('migrations'),
//         ], 'migrations');

//         // Publish seeders
//         $this->publishes([
//             __DIR__ . '/../database/seeders/' => database_path('seeders'),
//         ], 'seeders');

//         // Load migrations automatically if not published
//         $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

//         if ($this->app->runningInConsole()) {
//             $this->commands([
//                 \AliSultan\RolePermission\Console\Commands\InstallRolePermission::class,
//             ]);
//         }
//     }

//     public function register()
//     {
//         // Merge config to allow default config usage
//         $this->mergeConfigFrom(
//             __DIR__ . '/../config/role-permission.php',
//             'role-permission'
//         );
//     }
// }
namespace AliSultan\RolePermission;
use Filament\Support\Providers\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;
class RolePermissionServiceProvider extends PluginServiceProvider
{
    public static string $name = 'role-permission';

    protected array $resources = [
        \AliSultan\RolePermission\Filament\Resources\PermissionGroupResource::class,
        \AliSultan\RolePermission\Filament\Resources\PermissionResource::class,
        \AliSultan\RolePermission\Filament\Resources\RoleResource::class,
        \AliSultan\RolePermission\Filament\Resources\UserResource::class,
    ];

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name);
    }
    public function boot()
    {
        // ✅ Config publish
        $this->publishes([
            __DIR__ . '/../config/role-permission.php' => config_path('role-permission.php'),
        ], 'config');

        // ✅ Migrations publish (inside src/database/migrations)
        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ], 'migrations');

        // ✅ Seeders publish (inside src/database/seeders)
        $this->publishes([
            __DIR__ . '/database/seeders' => database_path('seeders'),
        ], 'seeders');

        // ✅ Load migrations from inside src/database/migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Register artisan command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \AliSultan\RolePermission\Console\Commands\InstallRolePermission::class,
            ]);
        }
    }

    public function register()
    {
        // ✅ Merge default config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/role-permission.php',
            'role-permission'
        );
    }
}
