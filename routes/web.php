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

use VK\VK;

$app->get('/', function () use ($app) {
    $results = app('db')->select("SELECT * FROM users");
//    return $results;
    $vk = new VK(env('VK_APP_ID'), env('VK_API_SECRET'), env('VK_ACCESS_TOKEN'));
    return $vk->api('messages.get', [
        'out' => 0,
        'count' => 5,
        'filters' => 0,
        'v' => 5.62
    ]);
});
