<?php

namespace App\Helpers\Vk\Messages\Attachments;

class Location extends Attachment {

    public function getMethod()
    {
        return 'sendLocation';
    }

    public function getOptions()
    {
        return $this->coordinates();
    }

    public function coordinates() {
        $coordinates = explode(' ', $this->item['coordinates']);

        return [
            'latitude' => $coordinates[0],
            'longitude' => $coordinates[1]
        ];
    }
}