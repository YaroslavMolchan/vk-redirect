<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface ReceiverInterface {

    public function __construct($receiver);

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