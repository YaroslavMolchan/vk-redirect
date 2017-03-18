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

            $bot->command('answer', function ($message, $message_id, $text) use ($bot) {
                $bot->sendMessage($message->getChat()->getId(), 'Answer to ID:' . $message_id.' with text:' . $text);
            });

            $bot->run();

        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
