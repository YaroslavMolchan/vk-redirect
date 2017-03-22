<?php

namespace App\Helpers\Vk;

use App\Contracts\AttachmentInterface;
use App\Contracts\MessageInterface;
use App\Contracts\ReceiverInterface;
use App\Contracts\SenderInterface;
use Illuminate\Support\Collection;
use VK\VK;

class Helper implements ReceiverInterface, SenderInterface {

    /**
     * @var VK
     */
    private $receiver;

    /**
     * @var VK
     */
    private $sender;
    private $receiver_id;
    private $items;

    private $params = [
        'v' => 5.62
    ];

    /**
     * @param VK $receiver
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * @param VK $sender
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

    public function getItems()
    {
        if (is_null($this->items)) {
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
        return $this->items;
    }

    /**
     * @param int $user_id
     * @return string
     */
    public function getName(int $user_id)
    {
        $data = $this->getData($user_id);
        return $data['first_name'] . ' ' . $data['last_name'];
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
     * @param MessageInterface $message
     * @return bool
     */
    public function sendMessage(MessageInterface $message)
    {
        $params = array_merge($this->params, [
            'user_id' => $this->receiver_id,
            'random_id' => rand(1, 99999),
            'message' => $message->getMessage()
        ]);
        $response = $this->sender->api('messages.send', $params);
        return isset($response['response']) && is_int($response['response']);
    }

    public function sendForwardedMessage(MessageInterface $message, $forward_message_id)
    {
        $this->params['forward_messages'] = $forward_message_id;
        return $this->sendMessage($message);
    }

    /**
     * Get attachment and resend it
     * @param AttachmentInterface $attachment
     * @return bool send result
     */
    public function sendAttachment(AttachmentInterface $attachment)
    {
        return true;
    }
}