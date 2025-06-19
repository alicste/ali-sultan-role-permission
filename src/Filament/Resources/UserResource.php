<?php

namespace AliSultan\RolePermission\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Forms\Form as FormsForm;
use Filament\Tables\Table as TablesTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\PasswordInput;
use Filament\Forms\Components\MultiSelect;
use Filament\Tables\Columns\BadgeColumn;
use App\Models\User; // or your User model namespace
use AliSultan\RolePermission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'User Management';
    // protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(FormsForm $form): FormsForm
    {
        return $form->schema([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            PasswordInput::make('password')
                ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                ->minLength(8)
                ->maxLength(255)
                ->dehydrateStateUsing(fn ($state) => $state ? bcrypt($state) : null),
            Select::make('user_type')
                ->label('User Type')
                ->options([
                    'admin' => 'Admin',
                    'user' => 'User',
                ])
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set) => $set('role_id', null)),
            Select::make('role_id')
                ->label('Role')
                ->options(function (callable $get) {
                    $userType = $get('user_type');
                    if (!$userType) {
                        return [];
                    }
                    return Role::where('user_type', $userType)->pluck('name', 'id');
                })
                ->required()
                ->searchable(),
        ]);
    }

    public static function table(TablesTable $table): TablesTable
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('email')->searchable(),
            TextColumn::make('role.name')->label('Role')->sortable(),
            TextColumn::make('user_type')->label('User Type')->sortable(),
            BadgeColumn::make('active')
                ->label('Active')
                ->enum([true => 'Yes', false => 'No'])
                ->colors([
                    true => 'success',
                    false => 'danger',
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => UserResource\Pages\ManageUsers::route('/'),
            'create' => UserResource\Pages\CreateUser::route('/create'),
            'edit' => UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
