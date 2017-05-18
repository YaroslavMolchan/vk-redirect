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
//    $content = file_get_contents("php://input");
//    $data = json_decode($content, true);
//    $telegram_api = new BotApi(env('TELEGRAM_BOT_API'));
//    $telegram_api->sendMessage(env('TELEGRAM_CHAT_ID'), json_encode($data));
//    echo 'ok';

    $data = [
        'update_id' => 43374693,
        'message' => [
            'message_id' => 797,
            'from' => [
                'id' => 67852056,
                'first_name' => 'Yaroslav',
                'last_name' => 'Molchan',
                'username' => 'YaroslavMolchan',
                'language_code' => 'ru'
            ],
            'chat' => [
                'id' => 67852056,
                'first_name' => 'Yaroslav',
                'last_name' => 'Molchan',
                'username' => 'YaroslavMolchan',
                'type' => 'private'
            ],
            'date' => 1495134105,
            'text' => '@MRedirectBot /quote 77062 12',
            'entities' => [
                [
                    'type' => 'mention',
                    'offset' => 0,
                    'length' => 13
                ],
                [
                    'type' => 'bot_command',
                    'offset' => 0,
                    'length' => 13
                ]
            ]
        ]
    ];
    return redirect('https://molchan.me');
});

$app->post('/telegram/webhook', 'TelegramController@webhook');

$app->post('/slack/webhook', 'SlackController@webhook');
