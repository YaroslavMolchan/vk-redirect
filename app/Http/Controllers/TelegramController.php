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

            $bot->command('answer', function ($message, ...$params) use ($bot, $telegram_api, $vk) {
                $receiver_id = array_shift($params);
                $text = implode(' ', $params);

                $m = new Message();
                $m->setMessage($text);
                $vk->setReceiverId($receiver_id);
                if (!$vk->sendMessage($m)) {
                    $telegram_api->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка');
                }
            });

            $bot->command('quote', function ($message, ...$params) use ($bot, $telegram_api, $vk) {
                $receiver_id = array_shift($params);
                $text = implode(' ', $params);

                $result = app('db')->select("SELECT `message` FROM `messages` WHERE `id` = ?", [$receiver_id]);
                if (empty($result)) {
                    $telegram_api->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка. Сообщение не найдено в базе.');
                } else {
                    $result_data = json_decode($result[0]->message);
                    $m = new Message();
                    $m->setMessage($text);
                    $vk->setReceiverId($result_data->user_id);
                    if (!$vk->sendForwardedMessage($m, $receiver_id)) {
                        $telegram_api->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка');
                    }
                }
            });

            $bot->on(function($update) use ($telegram){
                $callback = $update->getCallbackQuery();
                $telegram->getSender()->answerCallbackQuery($callback->getId());

                $callback_data = json_decode($callback->getData(), true);
                $type = $callback_data['type'];

                $result = app('db')->select("SELECT `message` FROM `messages` WHERE `id` = ?", [$callback_data['id']]);
                if (empty($result)) {
                    $telegram->getSender()->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка. Сообщение не найдено в базе.');
                } else {
                    $result_data = json_decode($result[0]->message, true);
                    $telegram->setReceiverId(env('TELEGRAM_CHAT_ID'));
                    if ($type == 'point') {
                        $attachment = new Location($result_data['geo']);
                        if (!$telegram->sendAttachment($attachment)) {
                            $telegram->getSender()->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка');
                        }
                    }
                    else {
                        $attachments = collect($result_data['attachments'])->where('type', $type)->all();
                        foreach ($attachments as $data) {
                            $class = '\App\Helpers\Vk\Messages\Attachments\\' . ucfirst($type);
                            $attachment = new $class($data);
                            if (!$telegram->sendAttachment($attachment)) {
                                $telegram->getSender()->sendMessage(env('TELEGRAM_CHAT_ID'), 'Произошла ошибка');
                            }
                        }
                    }
                }
            }, function($update){
                return true;
            });

            $bot->run();

        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}