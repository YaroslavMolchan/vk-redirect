<?php

namespace App\Http\Controllers;

use App\Helpers\Vk\Helper;
use App\Helpers\Vk\Messages\Message;
use App\Helpers\VkHelper;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\Exception;
use VK\VK;

class SlackController extends Controller
{
    const TOKEN = 'hzBuk2H9OMtMd5nhRzfUXhuT';

    public function webhook()
    {
        try {
            $content = file_get_contents("php://input");
            $data = json_decode($content, true);

            if ($data['token'] == self::TOKEN) {
                $user_id = $data['event']['user'];
                $message = $data['event']['text'];
                $telegram = new BotApi(env('TELEGRAM_BOT_API'));
                $telegram->sendMessage(env('TELEGRAM_CHAT_ID'), 'Пользователь '. $user_id .' отправил сообщение: '. $message);
            }

        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
