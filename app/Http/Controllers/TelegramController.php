<?php

namespace App\Http\Controllers;

use App\Helpers\Vk\Helper;
use App\Helpers\Vk\Messages\Message;
use App\Helpers\VkHelper;
use TelegramBot\Api\Client;
use TelegramBot\Api\Exception;
use VK\VK;

class TelegramController extends Controller
{

    public function webhook()
    {
        try {
            $bot = new Client(env('TELEGRAM_BOT_API'));

            $api = new VK(env('VK_APP_ID'), env('VK_API_SECRET'), env('VK_ACCESS_TOKEN'));
            $vk = new Helper();
            $vk->setSender($api);

            $bot->command('answer', function ($message, $user_id, $text) use ($bot, $vk) {
                $m = new Message();
                $m->setMessage($text);
                $vk->setReceiverId($user_id);
                if (!$vk->sendMessage($m)) {
                    $bot->sendMessage($message->getChat()->getId(), 'Произошла ошибка');
                }
            });

            $bot->command('quote', function ($message, $message_id, $text) use ($bot, $vk) {
                $result = app('db')->select("SELECT `message` FROM `messages` WHERE `id` = ?", [$message_id]);
                if (empty($result) && !isset($result[0])) {
                    $bot->sendMessage($message->getChat()->getId(), 'Произошла ошибка. Сообщение не найдено в базе.');
                }
                $result_data = json_decode($result[0]->message);
                $m = new Message();
                $m->setMessage($text);
                $vk->setReceiverId($result_data['user_id']);
                if (!$vk->sendForwardedMessage($m, $message_id)) {
                    $bot->sendMessage($message->getChat()->getId(), 'Произошла ошибка');
                }
            });

            $bot->run();

        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
