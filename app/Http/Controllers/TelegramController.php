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
    const REGEXP = '/^(?:@\w+\s)?\/([^\s@]+)\s?([^\s@]+)\s?(.*)$/';

    public function webhook()
    {
        try {
            $content = file_get_contents("php://input");
            $data = json_decode($content, true);

            $vk_api = new VK(env('VK_APP_ID'), env('VK_API_SECRET'), env('VK_ACCESS_TOKEN'));
            $vk = new Helper();
            $vk->setSender($vk_api);

            $telegram = new BotApi(env('TELEGRAM_BOT_API'));

            preg_match(self::REGEXP, $data['message']['text'], $matches);

            if (!isset($matches[1]) || !isset($matches[2]) || !isset($matches[3])) {
                return 'Error';
            }

            if ($matches[1] == 'answer') {
                $m = new Message();
                $m->setMessage($matches[3]);
                $vk->setReceiverId($matches[2]);
                if (!$vk->sendMessage($m)) {
                    $telegram->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка');
                }
            } elseif ($matches[1] == 'quote') {
                $result = app('db')->select("SELECT `message` FROM `messages` WHERE `id` = ?", [$matches[2]]);
                if (empty($result)) {
                    $telegram->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка. Сообщение не найдено в базе.');
                } else {
                    $result_data = json_decode($result[0]->message);
                    $m = new Message();
                    $m->setMessage($matches[3]);
                    $vk->setReceiverId($result_data->user_id);
                    if (!$vk->sendForwardedMessage($m, $matches[2])) {
                        $telegram->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка');
                    }
                }
            } elseif ($matches[1] == 'slack') {
                $client = new Client();
                $client->request('GET', 'https://slack.com/api/chat.postMessage?token=' . env('SLACK_API_TOKEN') . '&channel=' . $matches[2] . '&text=' . $matches[3] . '&as_user=true');
            }

        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
