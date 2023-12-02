<?php

namespace App\Filament\Resources;

use App\Domain\User\Models\User;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;

use App\Telegram\Components\ActionLinkBuilder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $recordTitleAttribute = 'Пользователи';
    protected static ?string $pluralModelLabel = 'Пользователи';

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Имя'),
                Forms\Components\TextInput::make('last_name')
                    ->label('Фамилия'),
                Forms\Components\TextInput::make('email'),
                Forms\Components\TextInput::make('confirm_token')
                    ->label('Токен подтверждения')
                    ->disabled(),
                Forms\Components\Toggle::make('active')
                    ->label('Активность'),
                Forms\Components\Toggle::make('is_admin')
                    ->label('Админ'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Имя'),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Фамилия'),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('confirm_token_link')
                    ->label('Ссылка для подтверждения регистрации')
                    ->copyable()
                    ->default(function (User $record) {
                        if (!empty($record->confirm_token) && !$record->active) {
                            return ActionLinkBuilder::makeRegistrationConfirm($record->confirm_token);
                        }
                    })

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
