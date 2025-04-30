<?php

namespace App\Repositories;

use App\Exceptions\ChoiceActionException;
use App\Exceptions\EntityNotFoundException;

class ChoiceRepository
{
    public string $choiceRepository;
    public function __construct()
    {
        GetEnv::getEnv();
        $choiceRepository = array_key_exists('DB_SOURCE', $_ENV);
        if ($choiceRepository === false) {
            throw new EntityNotFoundException(entityName: '.env', failed: 'DB_SOURCE', fieldValue: '');
        } elseif ($choiceRepository === 'json' || $choiceRepository === 'mysql') {
            throw new ChoiceActionException(action: 'базу данных в файле .env', actualChoice: $choiceRepository, expectedChoice: 'mysql или json');
        }
        $this->choiceRepository = $_ENV['DB_SOURCE'];
    }
}
