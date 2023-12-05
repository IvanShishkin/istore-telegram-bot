<?php

namespace App\Console\Commands;

use App\Domain\Wallets\Services\ChangeBalanceService;
use App\Domain\Wallets\Services\StoreWalletService;
use Illuminate\Console\Command;

class StoreIncreaseBalanceCommand extends Command
{
    protected $signature = 'store:increase-balance {value}';

    protected $description = 'Command description';

    public function handle(StoreWalletService $service, ChangeBalanceService $balanceService): void
    {
        $wallet = $service->initWallet();

        $balanceService->increase($wallet, $this->argument('value'));

    }
}
