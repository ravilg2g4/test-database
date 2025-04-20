<?php

declare(strict_types=1);

namespace App\Repositories;

use App\AppFactory;
use App\Repositories\Http\JsonHttpRepository;
use App\Repositories\Http\MySqlHttpRepository;
use App\Repositories\Terminal\JsonTerminalRepository;
use App\Repositories\Terminal\MySqlTerminalRepository;

class UsersRepositoryFactory
{
    public static function getUsersRepository(string $dbSource): ?RepositoryInterface
    {
        $app = AppFactory::getApp();

        if ($app::METHODREQUEST === 'HTTP') {
            if ($dbSource === 'mysql') {
                return new MySqlHttpRepository();
            } elseif ($dbSource === 'json') {
                return new JsonHttpRepository();
            } else {
                return null;
            }
        } elseif ($app::METHODREQUEST === 'TERMINAL') {
            if ($dbSource === 'mysql') {
                return new MySqlTerminalRepository();
            } elseif ($dbSource === 'json') {
                return new JsonTerminalRepository();
            } else {
                return null;
            }
        }
    }
}
