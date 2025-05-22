<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\ChoiceActionException;
use App\Exceptions\ChoiceRepositoryException;
use App\Exceptions\EntityNotFoundException;

class DatabaseSourceUsers
{
    public string $databaseSourceUsers;
    public function __construct()
    {
        EnvProvider::getEnv();
        $this->checkDatabaseSource();
        $this->databaseSourceUsers = $_ENV['DB_SOURCE'];
    }
    private function checkDatabaseSource(): void
    {
        $databaseSourceUsers = array_key_exists('DB_SOURCE', $_ENV);
        if ($databaseSourceUsers === false) {
            throw new EntityNotFoundException(entityName: '.env', failed: 'DB_SOURCE', fieldValue: '');
        } elseif ($_ENV['DB_SOURCE'] !== 'json' && $_ENV['DB_SOURCE'] !== 'mysql') {
            throw new ChoiceRepositoryException(actualChoice: $_ENV['DB_SOURCE']);
        }
    }
}
