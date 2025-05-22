<?php

declare(strict_types=1);

namespace App\CrudOperationProvider;

use App\Exceptions\EntityNotFoundException;

class CrudOperationProviderFactory
{
    public static function getCrudOperationProvider(string $requestSource): object
    {
        if ($requestSource === 'terminal') {
            return new CrudOperationFromTerminalProvider();
        } elseif ($requestSource === 'http') {
            return new CrudOperationFromHttpProvider();
        } else {
            throw new EntityNotFoundException(
                entityName: 'CrudOperationProvider',
                failed: 'requestSource',
                fieldValue: $requestSource
            );
        }
    }
}
