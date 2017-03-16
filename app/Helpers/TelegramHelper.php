<?php

namespace App\Helpers;

use App\Contracts\AttachmentInterface;
use App\Contracts\MessageInterface;
use App\Contracts\SenderInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class TelegramHelper implements SenderInterface {

    private $bot;
    private $chat_id;

    public function __construct(string $bot_api, int $chat_id)
    {
        $this->chat_id = $chat_id;
        $this->bot = new BotApi($bot_api);
    }

    public function sendMessage(MessageInterface $message)
    {
        echo 'Отправка сообщения через telegram' . PHP_EOL;
//        $this->bot->sendMessage($this->chat_id, $message->getMessage(), 'HTML');
    }

    public function sendAttachment(AttachmentInterface $attachment)
    {
        $this->bot->sendMessage($this->chat_id, 'text', 'HTML', false, null,
            (new InlineKeyboardMarkup())
        );
        dd(1);
        $options = array_prepend($attachment->getOptions(), $this->chat_id);

        call_user_func_array([$this->bot, $attachment->getMethod()], $options);
        dd($options);
    }
}