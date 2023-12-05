<?php

namespace App\Domain\User\Listeners;

use App\Domain\User\Services\UserService;
use App\Domain\Wallets\Events\AccrualWalletEvent;
use App\Telegram\Notifications\UserNotification;

class NotifyAccrualWalletListener
{
    public function __construct(
        protected UserService $userService,
        protected UserNotification $userNotification
    )
    {
    }

    public function handle(AccrualWalletEvent $event): void
    {
        $walletDto = $event->getWalletDto();
        $comment = $event->getComment();
        $accrualValue = $event->getAccrualValue();

        $userData = $this->userService->byId($walletDto->holder_id);

        $this->userNotification->sendMessage(__('bot_notification.accrual_wallet', [
            'name' => $userData->getName(),
            'balance' => $walletDto->balance,
            'inc_value' => $accrualValue,
            'comment' => $comment
        ]), $userData->getExternalId());
    }
}
