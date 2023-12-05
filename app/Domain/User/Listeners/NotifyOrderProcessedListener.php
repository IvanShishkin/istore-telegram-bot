<?php

namespace App\Domain\User\Listeners;

use App\Domain\Store\Events\OrderProcessedEvent;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Services\UserService;
use App\Telegram\Notifications\UserNotification;

class NotifyOrderProcessedListener
{
    public function __construct(
        protected UserNotification $notification,
        protected UserService $userService
    )
    {
    }

    public function handle(OrderProcessedEvent $event): void
    {
        $orderDto = $event->getOrderDto();

        try {
            $user = $this->userService->byId($orderDto->getUserId());

            $this->notification->sendMessage(
                __('bot_notification.order_processed', [
                    'order_id' => $orderDto->getId(),
                    'price' => $orderDto->getPrice()
                ]),
                $user->getExternalId()
            );
        } catch (UserNotFoundException $e) {
        }
    }
}
