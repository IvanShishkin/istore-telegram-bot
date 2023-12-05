<?php

namespace App\Providers;

use App\Domain\Products\Listeners\OrderIncreaseProductStockListener;
use App\Domain\Products\Listeners\OrderReduceProductStockListener;
use App\Domain\Store\Events\OrderCancelledEvent;
use App\Domain\Store\Events\OrderCreatedEvent;
use App\Domain\Store\Events\OrderProcessedEvent;
use App\Domain\User\Listeners\NotifyAccrualWalletListener;
use App\Domain\User\Listeners\NotifyOrderCancelListener;
use App\Domain\User\Listeners\NotifyOrderProcessedListener;
use App\Domain\Wallets\Events\AccrualWalletEvent;
use App\Domain\Wallets\Events\RegistrationConfirmEvent;
use App\Domain\Wallets\Listeners\CreateUserWalletListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCreatedEvent::class => [
            OrderReduceProductStockListener::class,
        ],
        OrderCancelledEvent::class => [
            OrderIncreaseProductStockListener::class,
            NotifyOrderCancelListener::class,
        ],
        RegistrationConfirmEvent::class => [
            CreateUserWalletListener::class,
        ],
        OrderProcessedEvent::class => [
            NotifyOrderProcessedListener::class,
        ],
        AccrualWalletEvent::class => [
            NotifyAccrualWalletListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
