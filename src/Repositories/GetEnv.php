<?php

declare(strict_types=1);

namespace App\Repositories;

class GetEnv
{
    public static function getEnv(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();
    }
}
