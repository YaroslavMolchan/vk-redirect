<?php

namespace App\Http\Controllers;

use App\Helpers\Message;
use App\Helpers\Vk\Helper;
use App\Helpers\Vk\Messages\Attachments\Doc;
use App\Helpers\Vk\Messages\Attachments\Location;
use App\Helpers\Vk\Messages\Attachments\Video;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\Types\Update;
use VK\VK;

class TelegramController extends Controller
{
    public function webhook()
    {
        try {
            $vk_api = new VK(env('VK_APP_ID'), env('VK_API_SECRET'), env('VK_ACCESS_TOKEN'));
            $vk = new Helper();
            $vk->setSender($vk_api);

            $telegram_api = new BotApi(env('TELEGRAM_BOT_API'));
            $telegram = new \App\Helpers\Telegram\Helper();
            $telegram->setSender($telegram_api);

            $bot = new \TelegramBot\Api\Client('TELEGRAM_BOT_API');

//            $bot->command('answer', function ($message, ...$params) use ($bot, $telegram_api, $vk) {
//                $receiver_id = array_shift($params);
//                $text = implode(' ', $params);
//
//                $m = new Message();
//                $m->setMessage($text);
//                $vk->setReceiverId($receiver_id);
//                if (!$vk->sendMessage($m)) {
//                    $telegram_api->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка');
//                }
//            });
//
//            $bot->command('quote', function ($message, ...$params) use ($bot, $telegram_api, $vk) {
//                $receiver_id = array_shift($params);
//                $text = implode(' ', $params);
//
//                $result = app('db')->select("SELECT `message` FROM `messages` WHERE `id` = ?", [$receiver_id]);
//                if (empty($result)) {
//                    $telegram_api->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка. Сообщение не найдено в базе.');
//                } else {
//                    $result_data = json_decode($result[0]->message);
//                    $m = new Message();
//                    $m->setMessage($text);
//                    $vk->setReceiverId($result_data->user_id);
//                    if (!$vk->sendForwardedMessage($m, $receiver_id)) {
//                        $telegram_api->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка');
//                    }
//                }
//            });

//            $this->p();

            $bot->on(function($update) use ($telegram_api){
                $callback = $update->getCallbackQuery();
                $data = $callback->getData();
                $telegram_api->answerCallbackQuery($callback->getId());
            }, function($update){
                return true;
            });

//            $attachments = [
//                'audio',
//                'doc',
//                'point',
//                'photo',
//                'sticker',
//                'video',
//            ];
//
//            foreach ($attachments as $type) {
//                $bot->command($type, function ($message, ...$params) use ($bot, $telegram_api, $vk, $telegram, $type) {
//                    $message_id = array_shift($params);
//
//                    $result = app('db')->select("SELECT `message` FROM `messages` WHERE `id` = ?", [$message_id]);
//                    if (empty($result)) {
//                        $telegram_api->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка. Сообщение не найдено в базе.');
//                    } else {
//                        $result_data = json_decode($result[0]->message, true);
//                        $telegram->setReceiverId(env('TELEGRAM_CHAT_ID'));
//                        if ($type == 'point') {
//                            $attachment = new Location($result_data['geo']);
//                            if (!$telegram->sendAttachment($attachment)) {
//                                $telegram_api->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка');
//                            }
//                        }
//                        else {
//                            $attachments = collect($result_data['attachments'])->where('type', $type);
//                            foreach ($attachments as $data) {
//                                $class = '\App\Helpers\Vk\Messages\Attachments\\' . ucfirst($type);
//                                $attachment = new $class($data);
//                                if (!$telegram->sendAttachment($attachment)) {
//                                    $telegram_api->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка');
//                                }
//                            }
//                        }
//                    }
//                });
//            }
//
//            $bot->command('slack', function ($message, ...$params) use ($bot, $telegram_api, $vk) {
//                $receiver = array_shift($params);
//                $text = implode(' ', $params);
//
//                $client = new Client();
//                $client->request('GET', 'https://slack.com/api/chat.postMessage?token=' . env('SLACK_API_TOKEN') . '&channel=' . $receiver . '&text=' . $text . '&as_user=true');
//            });
//
            $bot->run();

        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    public function p($input = null, $title = null)
    {
        if (is_null($title)) {
            $title=$_SERVER['REQUEST_METHOD'];
        }
        if (is_null($input)) {
            $text = var_export($_REQUEST, true);
            $input = @file_get_contents("php://input");
        }
//        $event = json_decode($input);
        $url = "https://api.pushbullet.com/v2/pushes";
        $data = [
            'type' => 'note',
            'title' => $title,
            'body' => $input,
//            'body' => $text,
        ];
        $data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer o.n9yeGc7W6mdLVXucmwCUW3VJ8OKdAdPn",
            "Content-Type: application/json",
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
    }
}