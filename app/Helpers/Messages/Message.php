<?php

namespace App\Helpers\Messages;

use App\Contracts\AttachmentInterface;
use App\Contracts\MessageInterface;
use App\Contracts\UserInterface;

class Message implements MessageInterface {

    private $data;
    private $attachments = [];
    private $user;
    private $message;

    /**
     * @param array $data Message data
     * @param UserInterface $user Sender user object
     */
    public function __construct(array $data, UserInterface $user)
    {
        $this->data = $data;
        $this->user = $user;
    }

    public function addAttachment(AttachmentInterface $attachment)
    {
        array_push($this->attachments, $attachment);
    }

    /**
     * Return message, that will send to user
     * @return string
     */
    public function getMessage()
    {
        $this->message = '<strong>' . $this->user->getName() . '</strong> отправил' . ($this->user->getSex() == 1 ? 'a' : '') . ' сообщение.' . PHP_EOL;
        if (!empty($this->data['body'])) {
            $this->message .= 'Текст сообщения: ' . $this->data['body'] . PHP_EOL;
        }
        if (!empty($this->attachments)) {
            $this->message .= 'К сообщению добавлены вложения: ';
            $attachments = [];
            foreach ($this->attachments as $attachment) {
                if (!isset($attachments[$attachment->getName()])) {
                    $attachments[$attachment->getName()] = 1;
                }
                else {
                    $attachments[$attachment->getName()]++;
                }
            }
            $message_attachments = [];
            foreach ($attachments as $name => $count) {
                if ($count > 1) {
                    $name .= ' ('.$count.' шт.)';
                }
                $message_attachments[] = $name;
            }
            $this->message .= implode(', ', $message_attachments);
        }

        return $this->message;
    }

    /**
     * Return user object, that sent message
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * Return array of attachments
     * @return AttachmentInterface[]
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * Do something when message is delivered, for example: Save message to database
     * @return void
     */
    public function delivered()
    {
        app('db')->insert('insert into messages (id, message, delivered_at) values (?, ?, ?)', [
            $this->data['id'],
            json_encode($this->data),
            (new \DateTime())->format('Y-m-d H:i:s')
        ]);
    }
}