<?php

namespace App\Filament\Resources;

use App\Domain\Wallets\Models\UserWalletModel;
use App\Domain\Wallets\Services\ChangeBalanceService;
use App\Domain\Wallets\UserWallet;
use App\Filament\Resources\WalletResource\Pages;
use App\Filament\Resources\WalletResource\RelationManagers;
use App\Models\Wallet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WalletResource extends Resource
{
    protected static ?string $model = UserWalletModel::class;
    protected static ?string $recordTitleAttribute = 'Кошельки';
    protected static ?string $pluralModelLabel = 'Кошельки';

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('holder.full_name')->label('Пользователь'),
                TextColumn::make('balance')->label('Баланс'),
                TextColumn::make('number')->label('Номер')->copyable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('increase_balance')
                    ->button()
                    ->label('Пополнить')
                    ->form([
                        Forms\Components\TextInput::make('balance_value')
                            ->label('Значение пополнения')
                            ->numeric(true)
                            ->required(),
                        Forms\Components\Textarea::make('comment')
                            ->label('Комментарий')

                    ])
                    ->action(function (
                        array $data,
                        UserWalletModel $record,
                        ChangeBalanceService $balanceService
                    ): void {
                        $incValue = $data['balance_value'];
                        $comment = $data['comment'];
                        try {
                            $userWallet = new UserWallet($record->number);
                            $balanceService->increase($userWallet, $incValue, $comment);
                        } catch (\Exception $exception) {
                            Notification::make('error_increase_balance')
                                ->title('Ошибка')
                                ->body($exception->getMessage())
                                ->send();
                        }
                    })
            ])
            ->bulkActions([

            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LogsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWallets::route('/'),
            'create' => Pages\CreateWallet::route('/create'),
            'edit' => Pages\EditWallet::route('/{record}/edit'),
        ];
    }
}
