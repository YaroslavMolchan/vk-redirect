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
$app->post('/', function () use ($app) {
//    $content = file_get_contents("php://input");
//    $data = json_decode($content, true);
//    $telegram_api = new BotApi(env('TELEGRAM_BOT_API'));
//    $telegram_api->sendMessage(env('TELEGRAM_CHAT_ID'), json_encode($data));
    echo 'ok';
});

$app->post('/telegram/webhook', 'TelegramController@webhook');

$app->post('/slack/webhook', 'SlackController@webhook');
