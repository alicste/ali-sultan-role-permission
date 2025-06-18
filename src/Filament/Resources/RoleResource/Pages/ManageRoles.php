<?php

namespace AliSultan\RolePermission\Filament\Resources\RoleResource\Pages;

use Filament\Resources\Pages\ManageRecords;
use Filament\Pages\Actions\DeleteAction;
use AliSultan\RolePermission\Filament\Resources\RoleResource;

class ManageRoles extends ManageRecords
{
    protected static string $resource = RoleResource::class;

    protected function getTableActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn ($record) => !$record->built_in),
        ];
    }
}
