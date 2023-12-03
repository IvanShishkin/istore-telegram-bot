<?php

namespace App\Filament\Resources;

use App\Domain\Store\Enums\OrderStatusEnum;
use App\Domain\Transactions\Actions\CancelTransactionAction;
use App\Domain\Transactions\Enums\TransactionDirectionEnum;
use App\Domain\Transactions\Enums\TransactionStatusEnum;
use App\Domain\Transactions\Models\Transaction;
use App\Domain\Transactions\Models\TransactionItem;
use App\Domain\User\Models\User;
use App\Domain\Wallets\Enums\WalletTypesEnum;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
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
    protected static ?int $navigationSort = 10;

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
                    ->label('Статус')
                    ->badge()
                    ->color(fn(TransactionStatusEnum $state): string => match ($state->value) {
                        'closed' => 'danger',
                        'completed' => 'success',
                        'new' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('value')
                    ->label('Сумма'),
                Tables\Columns\TextColumn::make('from_direction')
                    ->label('Отправитель')
                    ->default(function (Transaction $record) {
                        return self::getTransactionDirectionPlaceholder($record, TransactionDirectionEnum::FROM);
                    }),
                Tables\Columns\TextColumn::make('to_direction')
                    ->label('Получатель')
                    ->default(function (Transaction $record) {
                        return self::getTransactionDirectionPlaceholder($record, TransactionDirectionEnum::TO);
                    }),
                Tables\Columns\TextColumn::make('error_detail')
                    ->label('Ошибка'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата создания'),

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('action_cancel')
                    ->label('Отменить')
                    ->color('danger')
                    ->button()
                    ->requiresConfirmation()
                    ->visible(fn (Transaction $record) => $record->status === TransactionStatusEnum::NEW)
                    ->action(function (Transaction $record) {
                        try {
                            /** @var CancelTransactionAction $action */
                            $action = \App::make(CancelTransactionAction::class);
                            $action->execute($record->id);
                        } catch (\Exception $exception) {
                            Notification::make()
                                ->title('Ошибка')
                                ->body($exception->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
            ])
            ->bulkActions([

            ]);
    }

    protected static function getTransactionDirectionPlaceholder(
        Transaction $transaction,
        TransactionDirectionEnum $direction
    ): string {
        $return = '';
        /** @var TransactionItem $item */
        $item = $transaction->items()->where('direction', $direction)->first();

        if (!$item) {
            return $return;
        }

        $type = $item->type;

        if ($type === WalletTypesEnum::STORE) {
            $return = 'Store';
        } elseif ($type === WalletTypesEnum::USER) {
            /** @var User $walletHolder */
            $walletHolder = $item->userWallet()->first()?->holder()?->first();

            if ($walletHolder) {
                $return = $walletHolder->name . ' ' . $walletHolder->last_name;
            }
        }

        return $return;
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
