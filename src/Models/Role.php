<?php

namespace AliSultan\RolePermission\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = ['name', 'guard_name', 'built_in', 'user_type'];
    protected static function booted()
{
    static::deleting(function ($role) {
        if ($role->built_in) {
            throw new \Exception('Built-in roles cannot be deleted.');
        }
    });
}

}
