<?php

namespace App\Contracts;

interface MessageInterface {

    /**
     * Return message, that will send to user
     * @return string
     */
    public function getMessage();

    /**
     * @return int
     */
    public function getUserId();
}