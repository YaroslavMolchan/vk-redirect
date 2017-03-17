<?php

namespace App\Helpers;

use App\Contracts\ReceiverInterface;
use App\Contracts\UserInterface;

class User implements UserInterface {

    private $receiver;
    private $user_id;
    private $name;
    private $sex;

    /**
     * @author MY
     * @param int $user_id
     * @param ReceiverInterface $receiver
     */
    public function __construct(int $user_id, ReceiverInterface $receiver)
    {
        $this->user_id = $user_id;
        $this->receiver = $receiver;
        $this->setName($user_id);
        $this->setSex($user_id);
    }

    public function setName($user_id)
    {
        $this->name = $this->receiver->getName($user_id);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @author MY
     * @return mixed
     */
    public function getSex()
    {
        return $this->sex;
    }

    public function setSex($user_id)
    {
        $this->sex = $this->receiver->getSex($user_id);
    }

    /**
     * @author MY
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @author MY
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
}