<?php

namespace App\Helpers\Telegram\Messages\Attachments;

use App\Contracts\AttachmentInterface;

class Audio implements AttachmentInterface {

    public function __construct(array $item)
    {
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        // (MY)TODO: Implement getMethod() method.
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        // (MY)TODO: Implement getOptions() method.
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Музыка';
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return '🎵';
    }
}