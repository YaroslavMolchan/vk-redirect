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
//    print_r($bot->sendMessage(67852056, 123));
//    return;
	$bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_API'));

	$messages = app('db')->select("SELECT * FROM `messages` ORDER BY id DESC");
    $vk = new VK(env('VK_APP_ID'), env('VK_API_SECRET'), env('VK_ACCESS_TOKEN'));
    $options = [
        'out' => 0,
        'count' => 50,
        'filters' => 0,
        'v' => 5.62
    ];
    if (!empty($messages) && isset($messages[0])) {
    	$options['last_message_id'] = $messages[0]['id'];
    }
    $result = $vk->api('messages.get', $options);
    $unread = collect($result['response']['items'])->where('read_state', 0);
    if ($unread->count() > 0) {
    	//insert last message id to database
    	// app('db')->insert('insert into messages (id) values (?)', [$unread->first()['id']]);
    	$unread->each(function ($item) use ($bot) {
			$chatID = env('TELEGRAM_CHAT_ID');
    		$result = [];
		    if (!empty($item['body'])) {
		    	array_push($result, $item['body']);
		    }
			if (!empty($item['geo'])) {
				$coordinates = explode(' ', $item['geo']['coordinates']);
				$bot->sendVenue($chatID, $coordinates[0], 'Отправлены координаты', $coordinates[1], $item['geo']['coordinates']['place']['title']);
				array_push($result, $item['geo']['coordinates']['place']['title']);
			}
		    if (isset($item['attachments'])) {
		    	foreach ($item['attachments'] as $attachment) {
			    	if ($attachment['type'] == 'sticker') {
			    		array_push($result, 'Стикер: '. $attachment['sticker']['photo_128']);
						$bot->sendPhoto($chatID, $attachment['sticker']['photo_128'], 'sticker');
			    	}
			    	elseif ($attachment['type'] == 'photo') {
			    		array_push($result, 'Фото: '. $attachment['photo']['photo_604']);
			    	}
			    	elseif ($attachment['type'] == 'doc') {
			    		array_push($result, 'Документ: '. $attachment['doc']['title']);
						//$attachment['doc']['url']
			    	}
			    	elseif ($attachment['type'] == 'audio') {
			    		array_push($result, 'Музыка: '. $attachment['audio']['artist'] . ' - '. $attachment['audio']['title']);
						//$attachment['audio']['url']
			    	}
			    	elseif ($attachment['type'] == 'video') {
			    		array_push($result, 'Видео: '. $attachment['video']['title']);
						//$attachment['audio']['photo_320']
			    	}
			    }
		    }
		    echo implode('<br />', $result);
		    echo'-----------------';
		});
		return;
    }
});
