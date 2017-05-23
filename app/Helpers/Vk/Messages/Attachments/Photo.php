<?php

namespace App\Helpers\Vk\Messages\Attachments;

class Photo extends Attachment {

    public function getMethod()
    {
        return 'sendPhoto';
    }

    public function getOptions()
    {
        return [
            'photo' => $this->item['photo_604']
        ];
    }
}