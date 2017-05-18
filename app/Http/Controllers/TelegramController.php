<?php

namespace App\Http\Controllers;

use App\Helpers\Message;
use App\Helpers\Vk\Helper;
use GuzzleHttp\Client;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use VK\VK;

class TelegramController extends Controller
{
    public function webhook()
    {
        try {
            $vk_api = new VK(env('VK_APP_ID'), env('VK_API_SECRET'), env('VK_ACCESS_TOKEN'));
            $vk = new Helper();
            $vk->setSender($vk_api);

            $telegram = new BotApi(env('TELEGRAM_BOT_API'));
            $bot = new \TelegramBot\Api\Client('TELEGRAM_BOT_API');

            $bot->command('answer', function ($message, ...$params) use ($bot, $telegram, $vk) {
                $receiver_id = array_shift($params);
                $text = implode(' ', $params);

                $m = new Message();
                $m->setMessage($text);
                $vk->setReceiverId($receiver_id);
                if (!$vk->sendMessage($m)) {
                    $telegram->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка');
                }
            });

            $bot->command('quote', function ($message, ...$params) use ($bot, $telegram, $vk) {
                $receiver_id = array_shift($params);
                $text = implode(' ', $params);

                $result = app('db')->select("SELECT `message` FROM `messages` WHERE `id` = ?", [$receiver_id]);
                if (empty($result)) {
                    $telegram->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка. Сообщение не найдено в базе.');
                } else {
                    $result_data = json_decode($result[0]->message);
                    $m = new Message();
                    $m->setMessage($text);
                    $vk->setReceiverId($result_data->user_id);
                    if (!$vk->sendForwardedMessage($m, $receiver_id)) {
                        $telegram->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка');
                    }
                }
            });

            $bot->command('slack', function ($message, ...$params) use ($bot, $telegram, $vk) {
                $receiver = array_shift($params);
                $text = implode(' ', $params);

                $client = new Client();
                $client->request('GET', 'https://slack.com/api/chat.postMessage?token=' . env('SLACK_API_TOKEN') . '&channel=' . $receiver . '&text=' . $text . '&as_user=true');
            });

            $bot->run();

        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}