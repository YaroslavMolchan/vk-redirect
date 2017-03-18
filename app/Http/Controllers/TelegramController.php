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

            $bot->command('ping', function ($message, $params) use ($bot) {
                $bot->sendMessage($message->getChat()->getId(), json_encode($params));
            });

            $bot->run();

        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
