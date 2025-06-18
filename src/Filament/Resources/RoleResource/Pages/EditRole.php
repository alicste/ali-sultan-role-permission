<?php

namespace AliSultan\RolePermission\Filament\Resources\RoleResource\Pages;

use Filament\Resources\Pages\EditRecord;
use AliSultan\RolePermission\Filament\Resources\RoleResource;
use Illuminate\Support\Arr;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    /**
     * Before saving, remove dynamic permission groups from main data.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return Arr::except(
            $data,
            collect($data)->keys()->filter(fn($k) => str_starts_with($k, 'permissions_group_'))->all()
        );
    }

    /**
     * After saving, sync role permissions based on grouped selections.
     */
    protected function afterSave(): void
    {
        $role = $this->record;
        $permissions = $this->getGroupedPermissions();
        $role->syncPermissions($permissions);
    }

    /**
     * Get all selected permissions from grouped checkbox inputs.
     */
    protected function getGroupedPermissions(): array
    {
        $data = $this->form->getState();

        return collect($data)
            ->filter(fn($_, $key) => str_starts_with($key, 'permissions_group_'))
            ->flatten()
            ->unique()
            ->values()
            ->all();
    }
}
