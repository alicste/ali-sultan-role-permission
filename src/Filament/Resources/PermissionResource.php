<?php

namespace AliSultan\RolePermission\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Forms\Form as FormsForm;
use Filament\Tables\Table as TablesTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use AliSultan\RolePermission\Models\Permission;
use AliSultan\RolePermission\Models\PermissionGroup;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationGroup = 'Role & Permission';
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    public static function form(FormsForm $form): FormsForm
    {
        return $form->schema([
            TextInput::make('name')->required(),
            Select::make('permission_group_id')
                ->label('Permission Group')
                ->relationship('group', 'name')
                ->searchable()
                ->preload(),
            TextInput::make('guard_name')->default('web')->required(),
        ]);
    }

    public static function table(TablesTable $table): TablesTable
    {
        return $table->columns([
            TextColumn::make('name')->searchable(),
            TextColumn::make('group.name')->label('Group'),
            TextColumn::make('guard_name'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => PermissionResource\Pages\ManagePermissions::route('/'),
        ];
    }
}
