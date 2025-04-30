<?php

namespace App;

use App\Exceptions\ChoiceActionException;
use App\Exceptions\EntityNotFoundException;
use App\Repositories\GetEnv;

class DatabaseSourceUsersProvider
{
    public string $databaseSourceUsers;
    public function __construct()
    {
        GetEnv::getEnv();
        $databaseSourceUsers = array_key_exists('DB_SOURCE', $_ENV);
        if ($databaseSourceUsers === false) {
            throw new EntityNotFoundException(entityName: '.env', failed: 'DB_SOURCE', fieldValue: '');
        } elseif ($databaseSourceUsers === 'json' || $databaseSourceUsers === 'mysql') {
            throw new ChoiceActionException(action: 'базу данных в файле .env', actualChoice: $choiceRepository, expectedChoice: 'mysql или json');
        }
        $this->databaseSourceUsers = $_ENV['DB_SOURCE'];
    }
}
