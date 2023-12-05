<?php

namespace App\Domain\Wallets\Listeners;

use App\Domain\Wallets\Actions\AccrualWalletAction;
use App\Domain\Wallets\Events\RegistrationConfirmEvent;
use App\Domain\Wallets\Services\ChangeBalanceService;
use App\Domain\Wallets\Services\UserWalletService;

class CreateUserWalletListener
{
    public function __construct(
        protected UserWalletService $userWalletService,
        protected ChangeBalanceService $balanceService,
        protected AccrualWalletAction $accrualWalletAction
    ) {
    }

    public function handle(RegistrationConfirmEvent $event): void
    {
        $userDto = $event->getUserDto();
        $walletDto = $this->userWalletService->create($userDto->getId());

        $this->accrualWalletAction->execute($walletDto->number, 1000, 'За успешную регистрацию');
    }
}
