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

    /**
     * @author MY
     * @return bool
     */
    public function process()
    {
        if ($this->vk->getItems()->count() < 1) {
            return false;
        }

        $this->vk->getItems()->each(function ($item) {
            $message = new Vk\Messages\Message($item);
            $message->setUser($this->vk->getUser($item['user_id']));

            $this->parseAttachments($item, $message);

            if ($this->telegram->sendMessage($message)) {
                $message->delivered();
            }
        });

        return true;
    }

    /**
     * @author MY
     * @param $item
     * @param Vk\Messages\Message $message
     */
    public function parseAttachments($item, $message)
    {
        if (!empty($item['geo'])) {
            $attachment = new Telegram\Messages\Attachments\Location($item['geo']);
            $message->addAttachment($attachment);
        }

        if (isset($item['attachments'])) {
            foreach ($item['attachments'] as $item) {
                $this->addAttachment($item, $message);
            }
        }
    }

    /**
     * @author MY
     * @param $item
     * @param Vk\Messages\Message $message
     */
    public function addAttachment($item, $message)
    {
        if ($item['type'] == 'sticker') {
            $attachment = new Telegram\Messages\Attachments\Sticker($item);
//						$bot->sendPhoto($chatID, $item['sticker']['photo_512'], 'sticker');
        }
        elseif ($item['type'] == 'photo') {
            $attachment = new Telegram\Messages\Attachments\Photo($item);
//			    		array_push($result, 'Фото: '. $item['photo']['photo_604']);
        }
        elseif ($item['type'] == 'doc') {
            $attachment = new Telegram\Messages\Attachments\Doc($item);
//			    		array_push($result, 'Документ: '. $item['doc']['title']);
//                        $items['doc'][] = [
//                            'title' => $item['doc']['title'],
//                            'src' => $item['doc']['url']
//                        ];
        }
        elseif ($item['type'] == 'audio') {
            $attachment = new Telegram\Messages\Attachments\Audio($item);
//			    		array_push($result, 'Музыка: '. $item['audio']['artist'] . ' - '. $item['audio']['title']);
            //$item['audio']['url']
//                        $items['audio'][] = [
//                            'title' => $item['audio']['artist'] .' - '. $item['audio']['title'],
//                            'src' => $item['audio']['url']
//                        ];
        }
        elseif ($item['type'] == 'video') {
            $attachment = new Telegram\Messages\Attachments\Video($item);
//                        $this->sender->sendAttachment($attachment);
//                        $items['video'][] = [
//                            'title' => $item['video']['title'],
//                            'src' => $item['video']['photo_800']
//                        ];
//			    		array_push($result, 'Видео: '. $attachment['video']['title']);
            //$attachment['audio']['photo_320']
        }
        elseif ($item['type'] == 'link') {
            $attachment = new Telegram\Messages\Attachments\Link($item);
        }
        if (isset($attachment)) {
            $message->addAttachment($attachment);
        }
    }
}