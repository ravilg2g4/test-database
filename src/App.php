<?php

declare(strict_types=1);

namespace App;

use App\Repositories\UsersRepositoryFactory;

class App
{
    public function run(): void
    {
        $dbSource = new Repositories\GetDbSource();
        $dbSource->getDbSource();
        $dbSource = $dbSource->dbSource;

        $repository = UsersRepositoryFactory::getUsersRepository($dbSource);

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $repository->read();
                break;
            case 'POST':
                $repository->create();
                $repository->answerCreate();
                break;
            case 'DELETE':
                $repository->delete();
                $repository->answerDelete();
                break;
        }
    }
}
