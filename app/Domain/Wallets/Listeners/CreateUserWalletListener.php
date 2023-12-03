<?php

namespace App\Domain\Wallets\Listeners;

use App\Domain\Wallets\Services\ChangeBalanceService;
use App\Domain\Wallets\Services\UserWalletService;
use App\Domain\Wallets\UserWallet;
use App\Events\RegistrationConfirmEvent;

class CreateUserWalletListener
{
    public function __construct(
        protected UserWalletService $userWalletService,
        protected ChangeBalanceService $balanceService
    ) {
    }

    public function handle(RegistrationConfirmEvent $event): void
    {
        $userDto = $event->getUserDto();
        $walletDto = $this->userWalletService->create($userDto->getId());

        $wallet = new UserWallet($walletDto->number);
        // пополним счет за регистрацию на 1к
        $this->balanceService->increase($wallet, 1000, 'Пополнение при регистрации пользователя');
    }
}
