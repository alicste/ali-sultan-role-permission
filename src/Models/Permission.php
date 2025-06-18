<?php

namespace AliSultan\RolePermission\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use AliSultan\RolePermission\Models\PermissionGroup;

class Permission extends SpatiePermission
{
    protected $fillable = ['name', 'guard_name', 'permission_group_id'];

    public function group()
    {
        return $this->belongsTo(PermissionGroup::class, 'permission_group_id');
    }
}
