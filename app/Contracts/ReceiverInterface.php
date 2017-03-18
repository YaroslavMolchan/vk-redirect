<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface ReceiverInterface {

    /**
     * @param object $receiver
     */
    public function setReceiver($receiver);

    /**
     * @return Collection
     */
    public function getItems();

    /**
     * @param int $user_id
     * @return array
     */
    public function getName(int $user_id);

    /**
     * @param int $user_id
     * @return int
     */
    public function getSex(int $user_id);
}