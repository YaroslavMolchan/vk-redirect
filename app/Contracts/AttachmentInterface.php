<?php

namespace App\Contracts;

interface AttachmentInterface {

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return array
     */
    public function getOptions();

}