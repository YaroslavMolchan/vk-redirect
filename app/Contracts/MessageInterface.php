<?php

namespace App\Contracts;

interface MessageInterface {

    public function __construct(array $data, UserInterface $user);

    public function getMessage();

    public function getUser();

    public function getAttachments();

    public function delivered();
}