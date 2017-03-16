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
$app->get('/', function () use ($app) {
//
//    $text = 123;
//    $url = 123;
//    $buttons[] = compact('text', 'url');
//
//    $replyMarkup['inline_keyboard'] = array_chunk($buttons, 2);
//    dd(json_encode($replyMarkup));
//{"inline_keyboard":[[{"text":123,"url":123}]]}
//{"inline_keyboard":[[{"text":123,"url":"http:\/\/google.com"}]]}
    $receiver = new Helpers\VkHelper(env('VK_APP_ID'), env('VK_API_SECRET'), env('VK_ACCESS_TOKEN'));
    $sender = new Helpers\TelegramHelper(env('TELEGRAM_BOT_API'), env('TELEGRAM_CHAT_ID'));
    $redirect = new Helpers\MessagesRedirect($receiver, $sender);

    echo $redirect->process();
});
