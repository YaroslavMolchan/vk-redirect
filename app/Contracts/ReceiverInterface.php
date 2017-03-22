<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface ReceiverInterface {

    /**
     * @return Collection
     */
    public function getItems();
}