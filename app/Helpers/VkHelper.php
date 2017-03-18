<?php

namespace App\Helpers;

use App\Contracts\AttachmentInterface;
use App\Contracts\MessageInterface;
use App\Contracts\ReceiverInterface;
use App\Contracts\SenderInterface;
use Illuminate\Support\Collection;
use VK\VK;

class VkHelper implements ReceiverInterface, SenderInterface {

    /**
     * @var VK
     */
    private $receiver;
    private $sender;
    private $receiver_id;
    private $items;

    private $params = [
        'v' => 5.62
    ];

    /**
     * @author MY
     */
    public function __construct()
    {
        $this->setItems();
    }

    /**
     * @param VK $receiver
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * @param object $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @param int $receiver_id
     */
    public function setReceiverId($receiver_id)
    {
        $this->receiver_id = $receiver_id;
    }

    /**
     * @return Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    private function setItems()
    {
        $messages = app('db')->select("SELECT * FROM `messages` ORDER BY id DESC");

        $options = [
            'count' => 20,
            'v' => 5.62
        ];
        if (!empty($messages) && isset($messages[0])) {
            $options['last_message_id'] = $messages[0]->id;
        }
        $result = $this->receiver->api('messages.get', $options);
        $this->items = collect($result['response']['items'])->where('read_state', 0);
    }

    /**
     * @param int $user_id
     * @return string
     */
    public function getName(int $user_id)
    {
        $data = $this->getData($user_id);
        return $data['last_name'] . ' ' . $data['first_name'];
    }

    public function getSex(int $user_id)
    {
        $data = $this->getData($user_id);
        return $data['sex'];
    }

    /**
     * @author MY
     * @param int $user_id
     * @return array
     */
    private function getData(int $user_id) {
        $users = app('db')->select("SELECT * FROM `users` WHERE `id` = ?", [$user_id]);
        if (!empty($users)) {
            return (array)$users[0];
        }
        $params = array_merge($this->params, [
            'user_ids' => $user_id,
            'fields' => 'sex'
        ]);
        $result = $this->receiver->api('users.get', $params);
        $user = $result['response'][0];
        $insert = array_values($user);
        app('db')->insert('insert into users (id, first_name, last_name, sex) values (?, ?, ?, ?)', $insert);
        return $user;
    }

    /**
     * Get Message and resend it
     * @param MessageInterface $message
     * @return bool send result
     */
    public function sendMessage(MessageInterface $message)
    {
        $params = array_merge($this->params, [
            'user_id' => $this->receiver_id,
            'random_id' => rand(1, 99999),
            'message' => 'sex'
        ]);
        $this->receiver->api('messages.send', $params);
    }

    /**
     * Get attachment and resend it
     * @param AttachmentInterface $attachment
     * @return bool send result
     */
    public function sendAttachment(AttachmentInterface $attachment)
    {
        // TODO: Implement sendAttachment() method.
    }
}