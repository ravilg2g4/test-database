<?php

declare(strict_types=1);

namespace App;

class EnvProvider
{
    public static function getEnv(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
        $dotenv->load();
    }
}
