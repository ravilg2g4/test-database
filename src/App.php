<?php

declare(strict_types=1);

namespace App;

use App\CrudOperationProvider\CrudOperationProviderFabric;
use App\ValueRequestProvider\ValueRequestProviderFactory;
use App\Repositories\UsersRepositoryFactory;
use App\DTO\NewUserDTO;
use App\DTO\DeleteUserDTO;

class App
{
    public function run(): void
    {
        try {
            $requestSource = RequestSourceDetector::getRequestSource();
            $crudOperationProvider = CrudOperationProviderFabric::getCrudOperationProvider($requestSource);
            $crudOperation = $crudOperationProvider->getCrudOperation();
            if ($crudOperation === 'create' || $crudOperation === 'delete') {
                $valueRequestProvider = ValueRequestProviderFactory::getValueRequestProvider($requestSource, $crudOperation);
                $valueRequest = $valueRequestProvider->getArrayValueRequest();
            }
            $repository = UsersRepositoryFactory::getUsersRepository();
            switch ($crudOperation) {
                case 'read':
                    $repository->read();
                    break;
                case 'create':
                    $newUser = new NewUserDTO($valueRequest);
                    $repository->create($newUser);
                    break;
                case 'delete':
                    $deleteUser = new DeleteUserDTO($valueRequest);
                    $repository->delete($deleteUser);
            }
        } catch (\Exception $e) {
            print(json_encode($e->__toString(), JSON_UNESCAPED_UNICODE) . PHP_EOL);
            exit();
        }
        print(PHP_EOL . json_encode("Запрос обработан успешно", JSON_UNESCAPED_UNICODE) . PHP_EOL);
    }
}
