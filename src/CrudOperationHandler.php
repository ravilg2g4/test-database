<?php

declare(strict_types=1);

namespace App;

use App\DTO\DeleteUserDTO;
use App\DTO\NewUserDTO;
use App\Repositories\RepositoryInterface;

class CrudOperationHandler
{
    public RepositoryInterface $repository;
    public string $crudOperation;
    public ?array $valueRequest;
    public function __construct(RepositoryInterface $repository, string $crudOperation, ?array $valueRequest = null)
    {
        $this->repository = $repository;
        $this->crudOperation = $crudOperation;
        $this->valueRequest = $valueRequest;
    }
    public function handlerCrudOperation()
    {
        switch ($this->crudOperation) {
            case 'read':
                $this->repository->read();
                break;
            case 'create':
                $newUser = new NewUserDTO($this->valueRequest);
                $this->repository->create($newUser);
                break;
            case 'delete':
                $deleteUser = new DeleteUserDTO($this->valueRequest);
                $this->repository->delete($deleteUser);
        }
    }
}
