<?php

declare(strict_types=1);

namespace App\Repositories\Http;

use App\AppInterface;
use App\Repositories;
use App\Repositories\UsersRepositoryFactory;

class AppHttp implements AppInterface
{
    public const string METHODREQUEST = 'HTTP';
    public function run(): void
    {
        $dbSource = new Repositories\GetDbSource();
        $dbSource->getDbSource();
        $dbSource = $dbSource->dbSource;

        $repository = UsersRepositoryFactory::getUsersRepository($dbSource);

        $request = $_SERVER['REQUEST_URI'];
        $request = explode('/', $request);
        $request = $request[1];

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                switch ($request) {
                    case 'list-users':
                        $repository->read();
                        break;
                }
                break;
            case 'POST':
                switch ($request) {
                    case 'create-user':
                        $repository->create();
                        $repository->answerCreate();
                        break;
                }
                break;
            case 'DELETE':
                switch ($request) {
                    case 'delete-user':
                        $repository->delete();
                        $repository->answerDelete();
                        break;
                }
                break;
        }
    }
}
