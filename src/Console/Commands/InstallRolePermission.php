<?php

namespace AliSultan\RolePermission\Console\Commands;

use Illuminate\Console\Command;

class InstallRolePermission extends Command
{
    protected $signature = 'role-permission:install';
    protected $description = 'Publish and seed AliSultan Role Permission package';

    public function handle()
    {
        $this->call('vendor:publish', [
            '--provider' => "AliSultan\\RolePermission\\RolePermissionServiceProvider",
            '--tag' => 'config',
            '--force' => true,
        ]);

        $this->call('vendor:publish', [
            '--provider' => "AliSultan\\RolePermission\\RolePermissionServiceProvider",
            '--tag' => 'migrations',
            '--force' => true,
        ]);

        $this->call('vendor:publish', [
            '--provider' => "AliSultan\\RolePermission\\RolePermissionServiceProvider",
            '--tag' => 'seeders',
            '--force' => true,
        ]);

        $this->call('migrate', ['--force' => true]);
        $this->call('db:seed', ['--class' => 'AliSultan\\RolePermission\\Database\\Seeders\\RolePermissionSeeder']);

        $this->info('AliSultan RolePermission package installed successfully.');
    }
}
