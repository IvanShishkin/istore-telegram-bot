<?php

namespace App\Providers;

use App\Domain\Products\Listeners\OrderIncreaseProductStockListener;
use App\Domain\Products\Listeners\OrderReduceProductStockListener;
use App\Domain\Store\Events\OrderCancelledEvent;
use App\Domain\Store\Events\OrderCreatedEvent;
use App\Domain\Wallets\Listeners\CreateUserWalletListener;
use App\Events\RegistrationConfirmEvent;
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
        ],
        RegistrationConfirmEvent::class => [
            CreateUserWalletListener::class,
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
