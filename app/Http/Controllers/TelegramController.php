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

            $bot->command('ping', function ($message, $message_id) use ($bot) {
                $bot->sendMessage($message->getChat()->getId(), $message->getChat()->getId());
            });

            $bot->run();

        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
