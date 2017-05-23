<?php

namespace App\Helpers\Telegram;

use App\Contracts\AttachmentInterface;
use App\Contracts\MessageInterface;
use App\Contracts\SenderInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class Helper implements SenderInterface
{
    /**
     * @var BotApi
     */
    private $sender;
    private $receiver_id;

    /**
     * @param BotApi $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @param int $receiver_id
     */
    public function setReceiverId($receiver_id)
    {
        $this->receiver_id = $receiver_id;
    }

    public function sendMessage(MessageInterface $message)
    {
        return $this->sender->sendMessage($this->receiver_id, $message->getMessage(), 'HTML', false, null, $message->replyButtons());
    }

    public function sendAttachment(AttachmentInterface $attachment)
    {
        $options = array_prepend($attachment->getOptions(), $this->receiver_id);

        return call_user_func_array([$this->sender, $attachment->getMethod()], $options);
    }
}