<?php

declare(strict_types=1);

namespace App\Exceptions;

class CreateNewUserException extends \Exception
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
