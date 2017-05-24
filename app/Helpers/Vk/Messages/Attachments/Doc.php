<?php

namespace App\Helpers\Vk\Messages\Attachments;

class Doc extends Attachment {

//    public function getMethod()
//    {
//        return 'sendDocument';
//    }

    public function getOptions()
    {
        return [
            'text' => 'Документ с названием: "'.$this->item['title'].'". Ссылка: ' . $this->item['url']
        ];
    }

//    public function getOptions()
//    {
//        return [
//            'document' => $this->item['url'],
//            'caption' => $this->item['title']
//        ];
//    }
}