<?php

namespace App\Contracts;

interface MessageInterface {

    public function __construct(string $text, UserInterface $user);

    public function getMessage();

}