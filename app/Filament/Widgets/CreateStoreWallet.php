<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Wallets\Exceptions\WalletAlreadyExistsException;
use App\Domain\Wallets\Services\StoreWalletService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CreateStoreWallet extends BaseWidget
{
    protected static string $view = 'filament.widgets.widget-create-store-wallet';

    /**
     * @throws WalletAlreadyExistsException
     */
    public function createStoreWallet(): void
    {
        /** @var StoreWalletService $storeWalletService */
        $storeWalletService = \App::make(StoreWalletService::class);
        $storeWalletService->create();
    }

    public function walletExists(): bool
    {
        /** @var StoreWalletService $storeWalletService */
        $storeWalletService = \App::make(StoreWalletService::class);
        return $storeWalletService->exists();
    }
}
