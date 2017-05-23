<?php

namespace App\Helpers\Vk\Messages\Attachments;

class Audio extends Attachment {

    public function getMethod()
    {
        return 'sendAudio';
    }

    public function getOptions()
    {
        return [
            'audio' => $this->item['url'],
            'caption' => $this->item['artist'] .' - ' . $this->item['title']
        ];
    }
}