<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SergiX44\Nutgram\Nutgram;

class AppServiceProvider extends ServiceProvider
{
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
