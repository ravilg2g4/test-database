<?php

declare(strict_types=1);

namespace App\tests\DTO;

use App\DTO\DeleteUserDTO;
use PHPUnit\Framework\TestCase;

class DeleteUserDTOTest extends TestCase
{
    public function testDeleteUserDTOId(): void
    {
        $data = [
            'paramDelete' => 'id',
            'valueDelete' => '1',
        ];

        $dto = new DeleteUserDTO($data);

        $this->assertEquals($data['paramDelete'], $dto->paramDelete);
        $this->assertEquals($data['valueDelete'], $dto->valueDelete);
    }
    public function testDeleteUserDTOEmail(): void
    {
        $data = [
            'paramDelete' => 'email',
            'valueDelete' => 'test@gmail.com',
        ];

        $dto = new DeleteUserDTO($data);

        $this->assertEquals($data['paramDelete'], $dto->paramDelete);
        $this->assertEquals($data['valueDelete'], $dto->valueDelete);
    }
}
