<?php
namespace AliSultan\RolePermission\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use AliSultan\RolePermission\Filament\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
