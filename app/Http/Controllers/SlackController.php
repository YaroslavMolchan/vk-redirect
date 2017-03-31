<?php

namespace App\Http\Controllers;

use App\Helpers\Slack\Message;
use App\Helpers\Telegram\Helper;
use Exception;
use GuzzleHttp\Client;
use TelegramBot\Api\BotApi;

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
                if ($user_id == 'U0CBU220Y') {
                    return true;
                }
                $text = $data['event']['text'];
                $client = new Client();
                $response = $client->request('GET', 'https://slack.com/api/users.info?token='.env('SLACK_API_TOKEN').'&user='.$user_id);
                $slack_data = json_decode((string)$response->getBody(), true);
                if (isset($slack_data['ok'])) {
                    $user_id = $slack_data['user']['name'];
                }

                $message = new Message();
                $message->setUserId($data['event']['user']);
                $message->setMessage('[Slack] <strong>'.$user_id. '</strong>: '. $text);

                $telegram_api = new BotApi(env('TELEGRAM_BOT_API'));
                $sender = new Helper();
                $sender->setSender($telegram_api);
                $sender->setReceiverId(env('TELEGRAM_CHAT_ID'));

                $sender->sendMessage($message);
            }

        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
