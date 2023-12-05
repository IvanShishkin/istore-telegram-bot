<?php

namespace App\Domain\User\Listeners;

use App\Domain\Store\Events\OrderCancelledEvent;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Services\UserService;
use App\Telegram\Notifications\UserNotification;
use Spatie\LaravelData\Exceptions\InvalidDataClass;

class NotifyOrderCancelListener
{
    public function __construct(
        protected UserNotification $notification,
        protected UserService $userService
    )
    {
    }

    public function handle(OrderCancelledEvent $event): void
    {
        $orderDto = $event->getOrderDto();

        try {
            $user = $this->userService->byId($orderDto->getUserId());

            $this->notification->sendMessage(
                __('bot_notification.order_cancel', [
                    'order_id' => $orderDto->getId(),
                    'price' => $orderDto->getPrice()
                ]),
                $user->getExternalId()
            );
        } catch (UserNotFoundException $e) {
        }
    }
}
