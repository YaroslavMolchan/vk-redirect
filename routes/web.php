<?php

$app->get('/', function () use ($app) {
    return redirect('https://molchan.me');
});

$app->post('/telegram/webhook', 'TelegramController@webhook');

$app->post('/slack/webhook', 'SlackController@webhook');
