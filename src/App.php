<?php

declare(strict_types=1);

namespace App;

use App\Repositories\UsersRepositoryFactory;
use App\ValueRequest\ValueRequestFactory;

class App
{
    public function run(): void
    {
        try {
            $valueRequestFactory = new ValueRequestFactory();
            $valueRequest = $valueRequestFactory->getValueRequest();

            $repository = new UsersRepositoryFactory();
            $repository = $repository->getUsersRepository();
            $choiceCRUD = new ChoiceCRUD();
            $choiceCRUD = $choiceCRUD->choiceCRUD;

            switch ($choiceCRUD) {
                case 'read':
                    $repository->read();
                    break;
                case 'create':
                    $newUser = $valueRequest->getNewUser();
                    $repository->create($newUser);
                    break;
                case 'delete':
                    $choiceDelete = $valueRequest->getChoiceDelete();
                    $valueDelete = $valueRequest->getValueDelete($choiceDelete);
                    $repository->delete($choiceDelete, $valueDelete);
            }
        } catch (\Exception $e) {
            print(json_encode($e->__toString(), JSON_UNESCAPED_UNICODE) . PHP_EOL);
            exit();
        }
        print(PHP_EOL . json_encode("Запрос обработан успешно", JSON_UNESCAPED_UNICODE) . PHP_EOL);
    }
}
