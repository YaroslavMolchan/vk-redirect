<?php

namespace App\Helpers\Vk\Messages\Attachments;

use App\Contracts\AttachmentInterface;

class Video implements AttachmentInterface {

    /**
     * @var array
     */
    private $item;

    public function __construct(array $item)
    {
        $this->item = $item[$item['type']];
    }

    /**
     * Ссылку на видео взять нельзя, так что отправляем просто ссылку на видео VK
     * @return string
     */
    public function getMethod()
    {
        return 'sendMessage';
    }

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

    /**
     * @return string
     */
    public function getName()
    {
        return 'Видео';
    }
}