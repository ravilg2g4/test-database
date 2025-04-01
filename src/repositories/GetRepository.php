<?php

declare(strict_types=1);

namespace repositories;

class GetRepository
{
    public static function getRepository($dbSource): ?RepositoryInterface
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
