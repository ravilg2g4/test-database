<?php

declare(strict_types=1);

namespace App\CrudOperationProvider;

use App\Exceptions\ChoiceActionException;

class CrudOperationProviderFromHttp
{
    public function getCrudOperation(): string
    {
        $crudOperation = match ($_SERVER['REQUEST_METHOD']) {
            'GET' => 'read',
            'POST' => 'create',
            'DELETE' => 'delete',
            default => throw new ChoiceActionException(action: 'действие с базой данных', actualChoice: $_SERVER['REQUEST_METHOD'], expectedChoice: 'GET, POST, DELETE')
        };
        return $crudOperation;
    }
}
