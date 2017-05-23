<?php

namespace App\Helpers\Vk\Messages\Attachments;

class Photo extends Attachment {

    public function getMethod()
    {
        return 'sendPhoto';
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'photo' => $this->item['photo']['photo_604'],
            'disable_notification' => true
        ];
    }

    public function getIcon()
    {
        return config('attachments.icons.' . $this->type);
    }
}