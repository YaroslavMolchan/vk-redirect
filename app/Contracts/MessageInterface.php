<?php

namespace App\Contracts;

use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

interface MessageInterface {

    /**
     * Return message text, that would send to user
     * @return string|null
     */
    public function getMessage();

    /**
     * Sender ID
     * @return int|string
     */
    public function getUserId();

    /**
     * Inline reply to message
     * @return InlineKeyboardMarkup|null
     */
    public function replyButtons();
}