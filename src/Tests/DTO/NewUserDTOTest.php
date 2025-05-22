<?php

declare(strict_types=1);

namespace App\tests\DTO;

use App\DTO\NewUserDTO;
use PHPUnit\Framework\TestCase;

class NewUserDTOTest extends TestCase
{
    public function testNewUserDTOCreate(): void
    {
        $data = [
            'name' => 'Ivan',
            'surname' => 'Ivanov',
            'email' => 'ivan.ivanov@gmail.com'
        ];

        $newUser = new NewUserDTO($data);

        $this->assertEquals($data['name'], $newUser->name);
        $this->assertEquals($data['surname'], $newUser->surname);
        $this->assertEquals($data['email'], $newUser->email);
    }
}
