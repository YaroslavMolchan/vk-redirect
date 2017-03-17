<?php

namespace App\Helpers;

use App\Contracts\ReceiverInterface;
use Illuminate\Support\Collection;
use VK\VK;

class VkHelper implements ReceiverInterface {

    private $vk;
    private $items;

    private $params = [
        'v' => 5.62
    ];

    public function __construct(int $app_id, string $api_secret, string  $token)
    {
        $this->vk = new VK($app_id, $api_secret, $token);
        $this->setItems();
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
        $result = $this->vk->api('messages.get', $options);
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
        $result = $this->vk->api('users.get', $params);
        $user = $result['response'][0];
        $insert = array_values($user);
        app('db')->insert('insert into users (id, first_name, last_name, sex) values (?, ?, ?, ?)', $insert);
        return $user;
    }
}