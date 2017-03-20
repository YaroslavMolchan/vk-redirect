<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Helpers;
use TelegramBot\Api\BotApi;
use VK\VK;

$app->get('/', function () use ($app) {
//    $sender_provider = new BotApi(env('TELEGRAM_BOT_API'));
    $receiver_provider = new VK(env('VK_APP_ID'), env('VK_API_SECRET'), env('VK_ACCESS_TOKEN'));
    $receiver = new Helpers\VkHelper();
    $receiver->setSender($receiver_provider);
    $message = new Helpers\Vk\Messages\Message();
    $message->setMessage('test message');
    $receiver->setReceiverId(env('VK_ID'));
    $receiver->sendMessage($message);
//    $sender = new Helpers\Telegram($sender_provider, env('TELEGRAM_CHAT_ID'));
//    $redirect = new Helpers\MessagesRedirect($receiver, $sender);
//
//    $redirect->process();
});

$app->post('/', function () use ($app) {
    $content = file_get_contents("php://input");
    $data = json_decode($content, true);
    echo $data['challenge'];
});

$app->post('/telegram/webhook', 'TelegramController@webhook');
