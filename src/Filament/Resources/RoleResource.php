<?php
// src/Filament/Resources/RoleResource.php

namespace AliSultan\RolePermission\Filament\Resources;

use Filament\Forms\Form as FormsForm;
use Filament\Tables\Table as TablesTable;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use AliSultan\RolePermission\Models\Role;
use AliSultan\RolePermission\Models\PermissionGroup;
use Filament\Forms\Get;
use Filament\Tables\Filters\SelectFilter;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationGroup = 'Role & Permission';
    // protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    public static function form(FormsForm $form): FormsForm
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)
                ->disabled(fn (Get $get, $record) => $record?->built_in ?? false),

            Select::make('user_type')
                ->label('User Type')
                ->options([
                    'admin' => 'Admin',
                    'user' => 'User',
                ])
                ->required()
                ->disabled(fn ($record) => $record?->built_in ?? false),

            Fieldset::make('Permissions')
                ->schema(static::getPermissionGroupFields())
                ->columns(2)
                ->visible(fn (Get $get) => $get('user_type') !== null),
        ]);
    }

    public static function table(TablesTable $table): TablesTable
    {
        return $table->columns([
            TextColumn::make('name')->sortable(),
            TextColumn::make('user_type')
                ->badge()
                ->colors([
                    'admin' => 'success',
                    'user' => 'primary',
                ]),
            BadgeColumn::make('built_in')
                ->enum([true => 'Built-In', false => 'Custom'])
                ->colors([
                    true => 'danger',
                    false => 'secondary',
                ]),
        ])
        ->filters([
            SelectFilter::make('user_type')
                ->options([
                    'admin' => 'Admin',
                    'user' => 'User',
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => RoleResource\Pages\ManageRoles::route('/'),
            'create' => RoleResource\Pages\CreateRole::route('/create'),
            'edit' => RoleResource\Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function getPermissionGroupFields(): array
    {
        $groups = PermissionGroup::with('permissions')->get();
        $fields = [];

        foreach ($groups as $group) {
            $fields[] = CheckboxList::make("permissions_group_{$group->id}")
                ->label($group->name)
                ->options($group->permissions->pluck('name', 'id'))
                ->columns(2)
                ->bulkToggleable()
                ->dehydrated(false);
        }

        return $fields;
    }
}
