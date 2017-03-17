<?php

namespace App\Helpers;

use App\Contracts\AttachmentInterface;
use App\Contracts\MessageInterface;
use App\Contracts\SenderInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class Telegram implements SenderInterface
{
    private $sender;
    private $receiver_id;

    public function __construct($sender, int $receiver_id)
    {
        $this->receiver_id = $receiver_id;
        $this->sender = $sender;
    }

    public function sendMessage(MessageInterface $message)
    {
        $user_id = $message->getUser()->getUserId();
//        ['switch_inline_query_current_chat' => '/answer' . $user_id . ' ', 'text' => 'Ответить'], ['switch_inline_query_current_chat' => '/quote' . $user_id . ' ', 'text' => 'Цитировать'], ['url' => 'https://vk.com/im?sel=' . $user_id, 'text' => 'Диалог']
        $reply = new InlineKeyboardMarkup(
            [
                [
                    ['url' => 'https://vk.com/im?sel=' . $user_id, 'text' => 'Диалог']
                ]
            ]
        );
        $this->sender->sendMessage($this->receiver_id, $message->getMessage(), 'HTML', false, null, $reply);
    }

    public function sendAttachment(AttachmentInterface $attachment)
    {
        $options = array_prepend($attachment->getOptions(), $this->receiver_id);

        call_user_func_array([$this->sender, $attachment->getMethod()], $options);
    }
}