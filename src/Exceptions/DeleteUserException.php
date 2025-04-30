<?php

namespace App\Exceptions;

use Throwable;

class DeleteUserException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct(message: $message);
    }
    public function __toString(): string
    {
        return __CLASS__ . ": {$this->message}";
    }
}
