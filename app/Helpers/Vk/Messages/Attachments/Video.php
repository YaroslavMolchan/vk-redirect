<?php

namespace App\Helpers\Vk\Messages\Attachments;

class Video extends Attachment {

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'text' => $this->getLink()
        ];
    }

    /**
     * Просто собираем ссылку, Telegram сам покажет превью и название
     * @return string
     */
    private function getLink() {
        return 'https://vk.com/video' . $this->item['owner_id'] . '_' . $this->item['id'];
    }
}