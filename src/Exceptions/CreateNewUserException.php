<?php

declare(strict_types=1);

namespace App\Exceptions;

class CreateNewUserException extends \Exception
{
    public function __construct()
    {
        parent::__construct(message: "Ошибка при создании нового пользователя");
    }
    public function __toString(): string
    {
        return __CLASS__ . ": {$this->message}";
    }

}
