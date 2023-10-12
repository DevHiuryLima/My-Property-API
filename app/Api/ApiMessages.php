<?php

namespace App\Api;

class ApiMessages
{
    private $message = [];

    public function __contruct(string $message, array $data = [])
    {
        $this->message['message'] = $this->message;
        $this->message['errors'] = $data;
    }

    public function getMessage()
    {
        $this->message;
    }

}
