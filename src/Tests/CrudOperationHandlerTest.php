<?php

declare(strict_types=1);

namespace App\Tests;

use App\CrudOperationHandler;
use App\Repositories\RepositoryInterface;
use PHPUnit\Framework\TestCase;

class CrudOperationHandlerTest extends TestCase
{
    private $mockRepository;
    protected function setUp(): void
    {
        $this->mockRepository = $this->createMock(RepositoryInterface::class);
    }
    public function testReadOperation(): void
    {
        $crudOperation = 'read';
        $this->mockRepository->expects($this->once())
            ->method('read');

        $handler = new CrudOperationHandler(
            repository: $this->mockRepository,
            crudOperation: $crudOperation
        );
        $handler->handlerCrudOperation();
    }
    public function testCreateOperation(): void
    {
        $crudOperation = 'create';
        $valueRequest = [
            'name' => 'name',
            'surname' => 'surname',
            'email' => 'email@gmail.com'
        ];
        $this->mockRepository->expects($this->once())
            ->method('create');

        $handler = new CrudOperationHandler(
            repository: $this->mockRepository,
            crudOperation: $crudOperation,
            valueRequest: $valueRequest
        );
        $handler->handlerCrudOperation();
    }
    public function testDeleteOperation(): void
    {
        $crudOperation = 'delete';
        $valueRequest = [
            'paramDelete' => 'paramDelete',
            'valueDelete' => 'valueDelete'
        ];
        $this->mockRepository->expects($this->once())
            ->method('delete');

        $handler = new CrudOperationHandler(
            repository: $this->mockRepository,
            crudOperation: $crudOperation,
            valueRequest: $valueRequest
        );
        $handler->handlerCrudOperation();
    }
}
