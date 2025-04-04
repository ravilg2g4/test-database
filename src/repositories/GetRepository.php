<?php

declare(strict_types=1);

namespace App\repositories;

class GetRepository
{
    public static function getRepository(string $dbSource): ?RepositoryInterface
    {
        if ($dbSource === 'mysql') {
            return new MySqlRepository();
        } elseif ($dbSource === 'json') {
            return new JsonRepository();
        } else {
            return null;
        }
    }
}
