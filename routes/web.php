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
//    $receiver_provider = new VK(env('VK_APP_ID'), env('VK_API_SECRET'), env('VK_ACCESS_TOKEN'));
//    $receiver = new Helpers\VkHelper($receiver_provider);
//    $sender = new Helpers\Telegram($sender_provider, env('TELEGRAM_CHAT_ID'));
//    $redirect = new Helpers\MessagesRedirect($receiver, $sender);
//
//    $redirect->process();
});

$app->post('/telegram/webhook', 'TelegramController@webhook');
