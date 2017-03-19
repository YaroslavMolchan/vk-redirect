<?php

namespace App\Helpers\Vk\Messages;

use App\Contracts\MessageInterface;

class Message implements MessageInterface {

    private $message;
    private $user_id;

    /**
     * @param string $message
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
     * @param int $user_id
     */
    public function setUserId(int $user_id)
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
}