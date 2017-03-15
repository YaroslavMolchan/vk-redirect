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
	$messages = app('db')->select("SELECT * FROM `messages` ORDER BY id DESC");
    $vk = new VK(env('VK_APP_ID'), env('VK_API_SECRET'), env('VK_ACCESS_TOKEN'));
    $options = [
        'out' => 0,
        'count' => 1,
        'filters' => 0,
        'v' => 5.62
    ];
    if (!empty($messages) && isset($messages[0])) {
    	$options['last_message_id'] = $messages[0]->id;
    }
    $result = $vk->api('messages.get', $options);
    $unread = collect($result['response']['items'])->where('read_state', 0);
    if ($unread->count() > 0) {
    	//insert last message id to database
    	// app('db')->insert('insert into messages (id) values (?)', [$unread->first()['id']]);
    	$unread->each(function ($item) use ($bot, $vk) {
//            app('db')->insert('insert into messages (id, message) values (?, ?)', [$item['id'], serialize($item)]);
//    	    dd($item);
//            dd($vk->api('users.get', ['user_ids' => 321000568]));
			$chatID = env('TELEGRAM_CHAT_ID');
    		$result[] = 'Имя Фамилия отправил сообщение.';
		    if (!empty($item['body'])) {
		    	array_push($result, 'Текст сообщения: '.$item['body']);
		    }
			if (!empty($item['geo'])) {
				$coordinates = explode(' ', $item['geo']['coordinates']);
                array_push($result, 'К сообщению прикреплены координаты: '.$item['geo']['place']['title']);
//				$bot->sendLocation($chatID, $coordinates[0],  $coordinates[1], $item['geo']['place']['title']);
			}
		    if (isset($item['attachments'])) {
                $attachments = [];
		    	foreach ($item['attachments'] as $attachment) {
//			    	if ($attachment['type'] == 'sticker') {
//			    		array_push($result, 'Стикер: '. $attachment['sticker']['photo_128']);
////						$bot->sendPhoto($chatID, $attachment['sticker']['photo_512'], 'sticker');
//			    	}
			    	if ($attachment['type'] == 'photo') {
                        $attachments['photo'][] = [
                            'src' => $attachment['photo']['photo_604']
                        ];
//			    		array_push($result, 'Фото: '. $attachment['photo']['photo_604']);
			    	}
			    	elseif ($attachment['type'] == 'doc') {
//			    		array_push($result, 'Документ: '. $attachment['doc']['title']);
						//$attachment['doc']['url']
                        $attachments['doc'][] = [
                            'title' => $attachment['doc']['title'],
                            'src' => $attachment['doc']['url']
                        ];
			    	}
			    	elseif ($attachment['type'] == 'audio') {
//			    		array_push($result, 'Музыка: '. $attachment['audio']['artist'] . ' - '. $attachment['audio']['title']);
						//$attachment['audio']['url']
                        $attachments['audio'][] = [
                            'title' => $attachment['audio']['artist'] .' - '. $attachment['audio']['title'],
                            'src' => $attachment['audio']['url']
                        ];
			    	}
			    	elseif ($attachment['type'] == 'video') {
                        $attachments['video'][] = [
                            'title' => $attachment['video']['title'],
                            'src' => $attachment['video']['photo_800']
                        ];
//			    		array_push($result, 'Видео: '. $attachment['video']['title']);
						//$attachment['audio']['photo_320']
			    	}
			    }
                array_push($result, 'К сообщению добавлено:');
                foreach ($attachments as $type => $items) {
                    if ($type == 'video') {
                        array_push($result, 'Видео ('.count($items).' шт.):');
                        foreach ($items as $item) {
                            array_push($result, $item['title'] .' ('.$item['src'].')');
                        }
                    }
                    elseif ($type == 'photo') {
                        array_push($result, 'Фото ('.count($items).' шт.):');
                        foreach ($items as $item) {
                            array_push($result, $item['src']);
                        }
                    }
                    elseif ($type == 'doc') {
                        array_push($result, 'Документы ('.count($items).' шт.):');
                        foreach ($items as $item) {
                            array_push($result, $item['title'] .' ('.$item['src'].')');
                        }
                    }
                    elseif ($type == 'audio') {
                        array_push($result, 'Музыка ('.count($items).' шт.):');
                        foreach ($items as $item) {
                            array_push($result, $item['title'] .' ('.$item['src'].')');
                        }
                    }
			    }
		    }
		    echo implode('<br />', $result);
		    echo'<br />-----------------<br />';
		});
		return;
    }
});
