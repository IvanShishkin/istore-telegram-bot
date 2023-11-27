<?php

namespace App\Filament\Resources;

use App\Domain\Store\Actions\CancelOrderAction;
use App\Domain\Store\Actions\InProcessingOrderAction;
use App\Domain\Store\Actions\ProcessedOrderAction;
use App\Domain\Store\Enums\OrderStatusEnum;
use App\Domain\Store\Exceptions\ErrorOrderActionException;
use App\Domain\Store\Models\Order;
use App\Filament\Resources\OrderResource\Pages;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class OrderResource extends Resource
{
    protected static ?string $model = \App\Domain\Store\Models\Order::class;
    protected static ?string $recordTitleAttribute = 'Заказы';
    protected static ?string $pluralModelLabel = 'Заказы';
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

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
                TextColumn::make('id')->label('Номер'),
                TextColumn::make('customer.full_name')->label('Покупатель'),
                TextColumn::make('status')
                    ->label('Статус')
                    ->sortable()
                    ->badge()
                    ->color(fn(OrderStatusEnum $state): string => match ($state->value) {
                        'new' => 'gray',
                        'cancel' => 'danger',
                        'processed' => 'success',
                        'in_processing' => 'warning',
                    }),
                TextColumn::make('product.name')->label('Товар'),
                TextColumn::make('price')->label('Стоимость')->description('IntCoin'),
                TextColumn::make('created_at')->label('Дата оформления')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('in_processing')
                    ->label('В обработке')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        try {
                            /** @var InProcessingOrderAction $cancelOrderAction */
                            $cancelOrderAction = \App::make(InProcessingOrderAction::class);
                            $cancelOrderAction->execute($record->id);

                            self::successNotification('Операция успешно выполнена');
                        } catch (ErrorOrderActionException $exception) {
                            self::errorNotification('Ошибка смены статуса', $exception->getMessage());
                        }

                    }),
                Tables\Actions\Action::make('processed')
                    ->label('Выполнен')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        try {
                            /** @var ProcessedOrderAction $cancelOrderAction */
                            $cancelOrderAction = \App::make(ProcessedOrderAction::class);
                            $cancelOrderAction->execute($record->id);

                            self::successNotification('Операция успешно выполнена');
                        } catch (ErrorOrderActionException $exception) {
                            self::errorNotification('Ошибка смены статуса', $exception->getMessage());
                        }

                    }),

                Tables\Actions\Action::make('cancel')
                    ->label('Отменить')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        try {
                            /** @var CancelOrderAction $cancelOrderAction */
                            $cancelOrderAction = \App::make(CancelOrderAction::class);
                            $cancelOrderAction->execute($record->id);

                            self::successNotification('Операция успешно выполнена');
                        } catch (ErrorOrderActionException $exception) {
                            self::errorNotification('Ошибка смены статуса', $exception->getMessage());
                        }

                    })
            ])
            ->bulkActions([

            ]);
    }

    protected static function successNotification(string $title): void
    {
        Notification::make()
            ->title($title)
            ->success()
            ->send();
    }

    protected static function errorNotification(string $title, string $desc = ''): void
    {
        $notification = Notification::make()
            ->title($title)
            ->danger();

        if (!empty($desc)) {
            $notification->body($desc);
        }

        $notification->send();
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
