<?php

namespace App\Helpers;

use App\Contracts\AttachmentInterface;
use App\Contracts\MessageInterface;
use App\Contracts\SenderInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class TelegramHelper implements SenderInterface
{
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
        $user_id = $message->getUser()->getUserId();
        $reply = new InlineKeyboardMarkup(
            [
                [
                    ['switch_inline_query_current_chat' => '/answer' . $user_id . ' ', 'text' => 'Ответить'], ['switch_inline_query_current_chat' => '/quote' . $user_id . ' ', 'text' => 'Цитировать'], ['url' => 'https://vk.com/im?sel=' . $user_id, 'text' => 'Диалог']
                ]
            ]
        );
        $this->bot->sendMessage($this->chat_id, $message->getMessage(), 'HTML', false, null, $reply);

    }

    public function sendAttachment(AttachmentInterface $attachment)
    {
        $this->bot->sendMessage($this->chat_id, 'text', 'HTML');
        $options = array_prepend($attachment->getOptions(), $this->chat_id);

        call_user_func_array([$this->bot, $attachment->getMethod()], $options);
    }
}