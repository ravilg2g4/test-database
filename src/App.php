<?php

declare(strict_types=1);

namespace App;

use App\CrudOperationProvider\CrudOperationProviderFactory;
use App\Repositories\MySqlDatabaseConnectProvider;
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
            $crudOperationProvider = CrudOperationProviderFactory::getCrudOperationProvider($requestSource);
            $crudOperation = $crudOperationProvider->getCrudOperation();
            if ($crudOperation === 'create' || $crudOperation === 'delete') {
                $valueRequestProvider = ValueRequestProviderFactory::getValueRequestProvider($requestSource, $crudOperation);
                $valueRequest = $valueRequestProvider->getArrayValueRequest();
            }
            $repository = UsersRepositoryFactory::getUsersRepository();
            $crudOperationHandler = @new CrudOperationHandler(
                repository: $repository,
                crudOperation: $crudOperation,
                valueRequest: $valueRequest
            );
            $crudOperationHandler->handlerCrudOperation();
        } catch (\Exception $e) {
            print(json_encode($e->__toString(), JSON_UNESCAPED_UNICODE) . PHP_EOL);
            exit();
        }
        print(PHP_EOL . json_encode("Запрос обработан успешно", JSON_UNESCAPED_UNICODE) . PHP_EOL);
    }
}
