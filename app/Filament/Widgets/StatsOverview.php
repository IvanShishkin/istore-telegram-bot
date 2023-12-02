<?php

namespace App\Filament\Widgets;

use App\Domain\Store\Enums\OrderStatusEnum;
use App\Domain\Store\Models\Order;
use App\Domain\Wallets\Dto\WalletDto;
use App\Domain\Wallets\Exceptions\WalletAlreadyExistsException;
use App\Domain\Wallets\Exceptions\WalletNotExistsException;
use App\Domain\Wallets\Models\UserWalletModel;
use App\Domain\Wallets\Services\StoreWalletService;
use Filament\Actions\Action;
use Filament\Infolists\Components\Actions;
use Filament\Notifications\Notification;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Session\Store;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $options = [];

        try {
            $walletData = $this->getStoreWalletData();
            $options = [
                Stat::make('Баланс магазина', $walletData->balance)->description('💎INC'),
                Stat::make('Баланс пользователей', $this->getUserWalletsBalance())->description('💎INC'),
                Stat::make('Новых заказов', $this->getCountNewOrders())
                    ->description('Всего заказов ' . $this->getCountOrders()),
            ];
        } catch (WalletNotExistsException) {

        }

        return $options;
    }

    /**
     * @throws WalletNotExistsException
     */
    public function getStoreWalletData(): WalletDto
    {
        /** @var StoreWalletService $storeWalletService */
        $storeWalletService = \App::make(StoreWalletService::class);
        return $storeWalletService->getWalletData();
    }

    public function getUserWalletsBalance()
    {
        return UserWalletModel::sum('balance');
    }

    public function getCountNewOrders(): int
    {
        return Order::where('status', OrderStatusEnum::NEW)->count();
    }

    public function getCountOrders(): int
    {
        return Order::count();
    }
}
