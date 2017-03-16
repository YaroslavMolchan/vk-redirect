<?php

namespace App\Contracts;

interface AttachmentInterface {

    public function __construct(array $item);

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return array
     */
    public function getOptions();

}