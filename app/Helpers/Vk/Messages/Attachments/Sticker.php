<?php

namespace App\Helpers\Vk\Messages\Attachments;

use App\Contracts\AttachmentInterface;

class Sticker implements AttachmentInterface {

    public function __construct(array $item)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Стикер';
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
}