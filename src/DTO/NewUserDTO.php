<?php

declare(strict_types=1);

namespace App\DTO;

readonly class NewUserDTO
{
    public string $name;
    public string $surname;
    public string $email;
    public function __construct(array $valueRequest)
    {
        $this->name = $valueRequest['name'];
        $this->surname = $valueRequest['surname'];
        $this->email = $valueRequest['email'];
    }
}
