<?php

namespace App\Helpers;

use App\Contracts\ReceiverInterface;
use App\Contracts\SenderInterface;

class VkToTelegramRedirect {

    /**
     * @var SenderInterface
     */
    private $telegram;

    /**
     * @var ReceiverInterface
     */
    private $vk;

    /**
     * @author MY
     * MessagesRedirect constructor.
     * @param ReceiverInterface $vk От кого получаем сообщения
     * @param SenderInterface $telegram Кому отправляем сообщения
     */
    public function __construct(ReceiverInterface $vk, SenderInterface $telegram)
    {
        $this->telegram = $telegram;
        $this->vk = $vk;
    }

    public function process()
    {
        if ($this->vk->getItems()->count() < 1) {
            throw new \Exception('Новых сообщений нет');
        }

        $this->vk->getItems()->each(function ($item) {
            $user = new Vk\User($item['user_id'], $this->vk);
            $message = new Telegram\Messages\Message($item, $user);
//            if (!empty($item['geo'])) {
//                $attachment = new Location($item['geo']);
//                $message->addAttachment($attachment);
//            }
//            if (isset($item['attachments'])) {
//                foreach ($item['attachments'] as $item) {
//			    	if ($item['type'] == 'sticker') {
//                        $attachment = new Sticker($item);
////						$bot->sendPhoto($chatID, $item['sticker']['photo_512'], 'sticker');
//			    	}
//                    elseif ($item['type'] == 'photo') {
//                        $attachment = new Photo($item);
////			    		array_push($result, 'Фото: '. $item['photo']['photo_604']);
//                    }
//                    elseif ($item['type'] == 'doc') {
//                        $attachment = new Doc($item);
////			    		array_push($result, 'Документ: '. $item['doc']['title']);
//                        //$item['doc']['url']
////                        $items['doc'][] = [
////                            'title' => $item['doc']['title'],
////                            'src' => $item['doc']['url']
////                        ];
//                    }
//                    elseif ($item['type'] == 'audio') {
//                        $attachment = new Audio($item);
////			    		array_push($result, 'Музыка: '. $item['audio']['artist'] . ' - '. $item['audio']['title']);
//                        //$item['audio']['url']
////                        $items['audio'][] = [
////                            'title' => $item['audio']['artist'] .' - '. $item['audio']['title'],
////                            'src' => $item['audio']['url']
////                        ];
//                    }
//                    elseif ($item['type'] == 'video') {
//                        $attachment = new Video($item);
////                        $this->sender->sendAttachment($attachment);
////                        $items['video'][] = [
////                            'title' => $item['video']['title'],
////                            'src' => $item['video']['photo_800']
////                        ];
////			    		array_push($result, 'Видео: '. $attachment['video']['title']);
//                        //$attachment['audio']['photo_320']
//                    }
//                    if (isset($attachment)) {
//                        $message->addAttachment($attachment);
//                    }
//                }
//            }
            if ($this->telegram->sendMessage($message)) {
                $message->delivered();
            }
        });
    }
}