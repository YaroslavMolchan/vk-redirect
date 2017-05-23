<?php

namespace App\Helpers\Vk\Messages\Attachments;

use App\Contracts\AttachmentInterface;

class Attachment implements AttachmentInterface {

    protected $item;
    protected $type;

    protected $names = [
        'audio' => 'Музыка',
        'doc' => 'Документ',
        'location' => 'Локация',
        'photo' => 'Фото',
        'sticker' => 'Стикер',
        'video' => 'Видео',
    ];

    protected $icons = [
        'audio' => '🎵',
        'doc' => '📎',
        'location' => '🌍',
        'photo' => '🎑',
        'sticker' => '🗽',
        'video' => '🎬',
    ];

    public function __construct(array $item)
    {
        $this->type = $item['type'];
        $this->item = $item[$item['type']];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->names[$this->type];
    }

    /**
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
            'text' => $this->getName()
        ];
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icons[$this->type];
    }
}