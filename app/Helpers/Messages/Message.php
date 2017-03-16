<?php

namespace App\Helpers\Messages;

use App\Contracts\AttachmentInterface;
use App\Contracts\MessageInterface;
use App\Contracts\UserInterface;

class Message implements MessageInterface {

    private $text;
    private $attachments = [];
    private $user;
    private $message;

    /**
     * @author MY
     * @param string $text
     * @param UserInterface $user
     */
    public function __construct(string $text, UserInterface $user)
    {
        $this->text = $text;
        $this->user = $user;
        echo 'Новое сообщение от '.$this->user->getName().' с текстом: '. $text . PHP_EOL;
        $this->message = 'Новое сообщение от '.$this->user->getName() . PHP_EOL;
    }

    public function addLocation($latitude, $longitude)
    {
        echo 'К сообщению прикреплены координаты: '. $latitude . ' - ' . $longitude . PHP_EOL;
        $this->message .= 'К сообщению прикреплены координаты: '. $latitude . ' - ' . $longitude . PHP_EOL;
    }

    public function addAttachment(AttachmentInterface $attachment)
    {
        echo 'К сообщению прикреплено вложение: '. get_class($attachment) . PHP_EOL;
        $this->message .= 'К сообщению прикреплено вложение: '. get_class($attachment) . PHP_EOL;
        array_push($this->attachments, $attachment);
    }

    public function getMessage()
    {
        $this->message = '<strong>' . $this->user->getName() . '</strong> отправил' . ($this->user->getSex() == 1 ? 'a' : '') . ' сообщение.' . PHP_EOL;
        if (!empty($this->text)) {
            $this->message .= 'Текст сообщения: ' . $this->text . PHP_EOL;
        }
        return $this->message;
    }
}