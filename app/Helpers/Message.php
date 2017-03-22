<?php

namespace App\Helpers;

use App\Contracts\MessageInterface;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class Message implements MessageInterface {

    protected $message;
    protected $user_id;

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Return message, that will send to user
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Inline reply to message
     * @return InlineKeyboardMarkup|null
     */
    public function replyButtons()
    {
        return null;
    }
}