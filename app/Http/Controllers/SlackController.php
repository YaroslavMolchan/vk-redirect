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
    const API_TOKEN = 'xoxp-12399505830-12402070032-156797583316-cc53fa594a53c05772616ec33514a5db';

    public function webhook()
    {
        try {
            $content = file_get_contents("php://input");
            $data = json_decode($content, true);

            if ($data['token'] == self::TOKEN) {
                $user_id = $data['event']['user'];
                $message = $data['event']['text'];
                $client = new \GuzzleHttp\Client();
                $response = $client->request('GET', 'https://slack.com/api/users.info?token='.self::API_TOKEN.'&user='.$user_id);
                $slack_data = json_decode((string)$response->getBody(), true);
                if (isset($slack_data['ok'])) {
                    $user_id = $slack_data['user']['name'];
                }
                $telegram = new BotApi(env('TELEGRAM_BOT_API'));
                $telegram->sendMessage(env('TELEGRAM_CHAT_ID'), 'Пользователь '. $user_id .' отправил сообщение: '. $message);
            }

        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
