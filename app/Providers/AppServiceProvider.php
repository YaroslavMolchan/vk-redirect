<?php

namespace App\Providers;

use App\Contracts\ReceiverInterface;
use App\Helpers\VkHelper;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\Telegram\Telegram;
use NotificationChannels\Telegram\TelegramChannel;
use GuzzleHttp\Client as HttpClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(TelegramChannel::class)
            ->needs(Telegram::class)
            ->give(function () {
                return new Telegram(
                    env('TELEGRAM_BOT_API'),
                    new HttpClient()
                );
            });
    }
}
