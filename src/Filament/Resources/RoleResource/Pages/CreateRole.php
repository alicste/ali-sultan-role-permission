<?php

namespace AliSultan\RolePermission\Filament\Resources\RoleResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use AliSultan\RolePermission\Filament\Resources\RoleResource;
use Illuminate\Support\Arr;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // You may filter here if needed
        return Arr::except($data, collect($data)->keys()->filter(fn($k) => str_starts_with($k, 'permissions_group_'))->all());
    }

    protected function afterCreate(): void
    {
        $role = $this->record;
        $permissions = $this->getGroupedPermissions();
        $role->syncPermissions($permissions);
    }

    protected function getGroupedPermissions(): array
    {
        $data = $this->form->getState();
        return collect($data)
            ->filter(fn($_, $key) => str_starts_with($key, 'permissions_group_'))
            ->flatten()
            ->all();
    }
}
