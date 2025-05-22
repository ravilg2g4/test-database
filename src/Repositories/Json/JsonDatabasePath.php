<?php

declare(strict_types=1);

namespace App\Repositories\Json;

use App\EnvProvider;
use App\Exceptions\EntityNotFoundException;

class JsonDatabasePath
{
    public string $jsonDatabasePath;
    public function __construct()
    {
        EnvProvider::getEnv();
        $this->checkJsonDatabasePath();
        $this->jsonDatabasePath = $_ENV['JSON_DATABASE_PATH'];
    }

    private function checkJsonDatabasePath(): void
    {
        if (array_key_exists('JSON_DATABASE_PATH', $_ENV) === false || $_ENV['JSON_DATABASE_PATH'] === '') {
            throw new EntityNotFoundException(entityName: 'JSON_DATABASE_PATH');
        }
    }
}
