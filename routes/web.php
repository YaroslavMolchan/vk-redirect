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

$app->get('/', function () use ($app) {

    //init vk, telegram
    $vk = new \App\Helpers\VkHelper();
    $bot = new \App\Helpers\TelegramHelper();
    $redirect = new \App\Helpers\MessagesRedirect($vk, $bot);

    return $redirect->process();
//        $users = app('db')->select("SELECT * FROM `messages` ORDER BY id DESC");

//	$bot->sendAudio(env('TELEGRAM_CHAT_ID'),
//        'https://cs7-3v4.vk-cdn.net/p15/59dbc55b5ae05a.mp3?extra=Dmu7kWVL1m1gXrVhHIAbaorbFVgRBeH6olTirl0G7BIvdYVGEwWhrA8kt7DUjdhtgIsqkCUfpxuLXrPy2dTBSDhM32L50WkGwmvBMrJkqhRc1pWetGmBEco619EZ0btA92KvszvJbGE',
//        '530',
//    '♡ Armin van Buuren',
//        'Mirage (2010)'
//    );
//    $bot->sendDocument(env('TELEGRAM_CHAT_ID'),
//
//        );
//    dd(1);


    if ($unread->count() > 0) {

		return;
    }
});
