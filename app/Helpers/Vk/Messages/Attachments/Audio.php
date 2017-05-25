<?php

namespace App\Helpers\Vk\Messages\Attachments;

class Audio extends Attachment {

    public function getMethod()
    {
        if (empty($this->item['url'])) {
            return 'sendMessage';
        }

        return 'sendAudio';
    }

    public function getOptions()
    {
        if (empty($this->item['url'])) {
            return [
                'text' => 'Музыка: ' . $this->item['artist'] .' - ' . $this->item['title'],
            ];
        }

        return [
            'audio' => $this->item['url'],
            'caption' => $this->item['artist'] .' - ' . $this->item['title']
        ];
    }
}