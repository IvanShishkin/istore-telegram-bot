<?php

namespace App\Filament\Widgets;

use App\Domain\Wallets\Dto\WalletDto;
use App\Domain\Wallets\Exceptions\WalletAlreadyExistsException;
use App\Domain\Wallets\Exceptions\WalletNotExistsException;
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
                Stat::make('Баланс магазина', $walletData->balance),
                Stat::make('Новых заказов', '12')
                    ->description('Всего заказов 102')
                    ->descriptionIcon('heroicon-m-arrow-trending-down')
                    ->color('success'),
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
}
