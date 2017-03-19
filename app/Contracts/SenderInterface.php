<?php

namespace App\Contracts;

interface SenderInterface {

    /**
     * @param object $sender
     */
    public function setSender($sender);

    /**
     * @param MessageInterface $message
     * @return bool result
     */
    public function sendMessage(MessageInterface $message);

    /**
     * @param AttachmentInterface $attachment
     * @return bool result
     */
    public function sendAttachment(AttachmentInterface $attachment);
}