<?php

namespace App\Providers;

use App\Domain\Products\Interfaces\ProductRepositoryInterface;
use App\Domain\Products\Interfaces\StockRepositoryInterface;
use App\Domain\Products\Repositories\ProductRepository;
use App\Domain\Wallets\Repositories\WalletLogRepository;
use App\Domain\Wallets\Repositories\WalletLogRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use SergiX44\Nutgram\Nutgram;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        ProductRepositoryInterface::class => ProductRepository::class,
        StockRepositoryInterface::class => ProductRepository::class,
        WalletLogRepositoryInterface::class => WalletLogRepository::class
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(Nutgram::class, fn() => new Nutgram(config('telegram.bot_token')));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
