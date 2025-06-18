<?php

namespace AliSultan\RolePermission\Filament\Resources;

use Filament\Forms\Form as FormsForm;
use Filament\Tables\Table as TablesTable;
use Filament\Resources\Resource;
use AliSultan\RolePermission\Models\PermissionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class PermissionGroupResource extends Resource
{
    protected static ?string $model = PermissionGroup::class;

    protected static ?string $navigationGroup = 'Role & Permission';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(FormsForm $form): FormsForm
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true),
        ]);
    }

    public static function table(TablesTable $table): TablesTable
    {
        return $table->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('name'),
            TextColumn::make('created_at')->since(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => PermissionGroupResource\Pages\ManagePermissionGroups::route('/'),
        ];
    }
}
