<?php

namespace AliSultan\RolePermission\Database\Seeders;

use Illuminate\Database\Seeder;
use AliSultan\RolePermission\Models\Role;
use AliSultan\RolePermission\Models\Permission;
use AliSultan\RolePermission\Models\PermissionGroup;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // === Step 1: Define permission groups and permissions ===
        $permissionsMap = [
            'Post' => ['post_create', 'post_edit', 'post_view', 'post_delete'],
            'User Management' => ['user_create', 'user_edit', 'user_view', 'user_delete'],
            'Role Management' => ['role_create', 'role_edit', 'role_view', 'role_delete'],
        ];

        $allPermissionIds = [];

        foreach ($permissionsMap as $groupName => $permissions) {
            $group = PermissionGroup::firstOrCreate(['name' => $groupName]);

            foreach ($permissions as $perm) {
                $permission = Permission::firstOrCreate([
                    'name' => $perm,
                    'guard_name' => 'web',
                    'permission_group_id' => $group->id,
                ]);
                $allPermissionIds[] = $permission->id;
            }
        }

        // === Step 2: Create built-in roles ===
        $superadmin = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
        ], [
            'built_in' => true,
            'user_type' => 'admin',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ], [
            'built_in' => true,
            'user_type' => 'admin',
        ]);

        // === Step 3: Create dynamic role (example: teacher) ===
        $teacher = Role::firstOrCreate([
            'name' => 'teacher',
            'guard_name' => 'web',
        ], [
            'built_in' => false,
            'user_type' => 'admin',
        ]);

        // === Step 4: Assign permissions ===
        $superadmin->syncPermissions(Permission::all());
        $admin->syncPermissions(Permission::whereNotIn('name', ['role_delete'])->get());
        $teacher->syncPermissions(Permission::where('name', 'like', 'post_%')->get());
    }
}
