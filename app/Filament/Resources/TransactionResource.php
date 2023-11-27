<?php

namespace App\Filament\Resources;

use App\Domain\Transactions\Models\Transaction;
use App\Domain\Wallets\Enums\WalletTypesEnum;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $recordTitleAttribute = 'Транзакции';
    protected static ?string $pluralModelLabel = 'Транзакции';

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id-s')
                    ->default(fn(Transaction $record) => $record->id)
                    ->label('Id'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Статус'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Значение'),
                Tables\Columns\TextColumn::make('error_detail')
                    ->label('Ошибка'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата создания'),
                Tables\Columns\TextColumn::make('to_user')
                    ->label('Дата создания')
                    ->default(function (Transaction $record) {
                        $toWallet = $record->to()->where('direction', 'to')->first();
                        if (!$toWallet) {
                            return null;
                        }

                        $type = $toWallet->type;

                        if ($type === WalletTypesEnum::STORE) {
                            return 'Store';
                        }


                    }),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
