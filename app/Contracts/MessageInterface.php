<?php

namespace App\Contracts;

interface MessageInterface {

    /**
     * @param array $data Message data
     * @param UserInterface $user Sender user object
     */
    public function __construct(array $data, UserInterface $user);

    /**
     * Return message, that will send to user
     * @return string
     */
    public function getMessage();

    /**
     * Return user object, that sent message
     * @return UserInterface
     */
    public function getUser();

    /**
     * Add new attachment to attachments array
     * @param AttachmentInterface $attachment
     * @return void
     */
    public function addAttachment(AttachmentInterface $attachment);

    /**
     * Return array of attachments
     * @return AttachmentInterface[]
     */
    public function getAttachments();

    /**
     * Do something when message is delivered, for example: Save message to database
     * @return void
     */
    public function delivered();
}