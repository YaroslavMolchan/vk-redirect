<?php

namespace App\Contracts;

interface SenderInterface {

    /**
     * @param object $sender Sender provider instance
     * @param int $receiver_id
     */
    public function __construct($sender, int $receiver_id);

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