<?php

namespace App\Helpers\Slack;

use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class Message extends \App\Helpers\Message {

    public function replyButtons()
    {
        return new InlineKeyboardMarkup(
            [
                [
                    ['switch_inline_query_current_chat' => '/slack ' . $this->getUserId() . ' ', 'text' => 'Ответить']
                ]
            ]
        );
    }

}