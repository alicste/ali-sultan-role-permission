<?php
namespace AliSultan\RolePermission\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\EditRecord;
use AliSultan\RolePermission\Filament\Resources\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
}
