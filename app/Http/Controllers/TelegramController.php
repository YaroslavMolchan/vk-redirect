<?php

namespace App\Http\Controllers;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\Exception;
use TelegramBot\Api\Types\Message;

class TelegramController extends Controller
{

    public function webhook()
    {
        try {
            $bot = new Client(env('TELEGRAM_BOT_API'));

//            $sender = new BotApi(env('TELEGRAM_BOT_API'));
//            $message = new Message();

            $bot->command('ping', function ($message) use ($bot) {
                $bot->sendMessage($message->getChat()->getId(), 'pong!');
            });

            $bot->run();

        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
