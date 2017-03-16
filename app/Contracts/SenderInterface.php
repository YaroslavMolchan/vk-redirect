<?php

namespace App\Contracts;

interface SenderInterface {
    public function __construct(string $bot_api, int $chat_id);

    public function sendMessage(MessageInterface $message);

    public function sendAttachment(AttachmentInterface $attachment);
}