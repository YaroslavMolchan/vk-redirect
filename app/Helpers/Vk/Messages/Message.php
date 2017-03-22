<?php

namespace App\Helpers\Vk\Messages;

use App\Contracts\AttachmentInterface;
use App\Contracts\MessageInterface;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class Message implements MessageInterface {

    private $data;
    private $attachments = [];
    private $user;
    private $message;

    /**
     * @param array $data Message data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function addAttachment(AttachmentInterface $attachment)
    {
        array_push($this->attachments, $attachment);
    }

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
        if (is_null($this->message)) {
            $this->message = '<strong>' . $this->user['first_name'] . ' ' . $this->user['last_name'] . '</strong> отправил' . ($this->user['sex'] == 1 ? 'a' : '') . ' сообщение.' . PHP_EOL;
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
        }

        return $this->message;
    }

    /**
     * @param array $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function getUser() {
        return $this->user;
    }

    public function getId()
    {
        return $this->data['id'];
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

    public function replyButtons()
    {
        $user_id = $this->data['user_id'];
        return new InlineKeyboardMarkup(
            [
                [
                    ['switch_inline_query_current_chat' => '/answer ' . $user_id . ' ', 'text' => 'Ответить'],
                    ['switch_inline_query_current_chat' => '/quote ' . $this->data['id'] . ' ', 'text' => 'Цитировать'],
                    ['url' => 'https://vk.com/im?sel=' . $user_id, 'text' => 'Диалог']
                ]
            ]
        );
    }

    /**
     * Sender ID
     * @return int|string
     */
    public function getUserId()
    {
        return $this->user['id'];
    }
}