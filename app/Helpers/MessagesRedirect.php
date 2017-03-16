<?php

namespace App\Helpers;

use App\Contracts\ReceiverInterface;
use App\Contracts\SenderInterface;
use App\Helpers\Messages\Attachments\Audio;
use App\Helpers\Messages\Attachments\Doc;
use App\Helpers\Messages\Attachments\Photo;
use App\Helpers\Messages\Attachments\Sticker;
use App\Helpers\Messages\Attachments\Video;
use App\Helpers\Messages\Message;

class MessagesRedirect {

    /**
     * @author MY
     * @var SenderInterface
     */
    private $sender;
    /**
     * @author MY
     * @var ReceiverInterface
     */
    private $receiver;

    /**
     * @author MY
     * MessagesRedirect constructor.
     * @param ReceiverInterface $receiver От кого получаем сообщения
     * @param SenderInterface $sender Кому отправляем сообщения
     */
    public function __construct(ReceiverInterface $receiver, SenderInterface $sender)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
    }

    public function process()
    {
        if ($this->receiver->getItems()->count() < 1) {
            throw new \Exception('Новых сообщений нет');
        }
        $this->receiver->getItems()->each(function ($item) {
//            dd($item);
            $user = new User($item['user_id'], $this->receiver);
            $message = new Message($item['body'], $user);
            if (!empty($item['geo'])) {
                $coordinates = explode(' ', $item['geo']['coordinates']);
                $message->addLocation($coordinates[0],  $coordinates[1]);
            }
            if (isset($item['attachments'])) {
                foreach ($item['attachments'] as $item) {
			    	if ($item['type'] == 'sticker') {
                        $attachment = new Sticker($item);
//						$bot->sendPhoto($chatID, $item['sticker']['photo_512'], 'sticker');
			    	}
                    elseif ($item['type'] == 'photo') {
                        $attachment = new Photo($item);
//			    		array_push($result, 'Фото: '. $item['photo']['photo_604']);
                    }
                    elseif ($item['type'] == 'doc') {
                        $attachment = new Doc($item);
//			    		array_push($result, 'Документ: '. $item['doc']['title']);
                        //$item['doc']['url']
//                        $items['doc'][] = [
//                            'title' => $item['doc']['title'],
//                            'src' => $item['doc']['url']
//                        ];
                    }
                    elseif ($item['type'] == 'audio') {
                        $attachment = new Audio($item);
//			    		array_push($result, 'Музыка: '. $item['audio']['artist'] . ' - '. $item['audio']['title']);
                        //$item['audio']['url']
//                        $items['audio'][] = [
//                            'title' => $item['audio']['artist'] .' - '. $item['audio']['title'],
//                            'src' => $item['audio']['url']
//                        ];
                    }
                    elseif ($item['type'] == 'video') {
                        $attachment = new Video($item);
                        $this->sender->sendAttachment($attachment);
//                        $items['video'][] = [
//                            'title' => $item['video']['title'],
//                            'src' => $item['video']['photo_800']
//                        ];
//			    		array_push($result, 'Видео: '. $attachment['video']['title']);
                        //$attachment['audio']['photo_320']
                    }
                    if (isset($attachment)) {
                        $message->addAttachment($attachment);
                    }
                }
            }
            $this->sender->sendMessage($message);
        });
    }
}