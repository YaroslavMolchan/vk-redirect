<?php

namespace App\Contracts;

interface SenderInterface {

    /**
     * @param object $sender
     */
    public function setSender($sender);

    /**
     * Get Message and resend it
     * @param MessageInterface $message
     * @return bool send result
     */
    public function sendMessage(MessageInterface $message);

    /**
     * Get attachment and resend it
     * @param AttachmentInterface $attachment
     * @return bool send result
     */
    public function sendAttachment(AttachmentInterface $attachment);
}