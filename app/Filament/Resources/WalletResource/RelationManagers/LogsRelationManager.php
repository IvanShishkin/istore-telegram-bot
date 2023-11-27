<?php

namespace App\Filament\Resources\WalletResource\RelationManagers;

use App\Domain\Wallets\Enums\WalletLogOperationEnum;
use App\Domain\Wallets\Models\WalletLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LogsRelationManager extends RelationManager
{
    protected static string $relationship = 'logs';

    protected static ?string $model = WalletLog::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('logs')
                    ->required()
                    ->maxLength(255),
            ]);
    }


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('logs')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Дата операции'),
                Tables\Columns\TextColumn::make('value')->label('Значение'),
                Tables\Columns\IconColumn::make('operation')
                    ->icon(fn (WalletLogOperationEnum $state): string => match ($state) {
                        WalletLogOperationEnum::INCREASE => 'heroicon-s-arrow-small-up',
                        WalletLogOperationEnum::REDUCE => 'heroicon-s-arrow-small-down',
                    })
                    ->color(fn (WalletLogOperationEnum $state): string => match ($state) {
                        WalletLogOperationEnum::INCREASE => 'success',
                        WalletLogOperationEnum::REDUCE => 'danger',
                    })->label('Операция'),
                Tables\Columns\TextColumn::make('comment')->label('Комментарий')
            ])
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->actions([

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
