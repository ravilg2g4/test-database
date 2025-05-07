<?php

declare(strict_types=1);

namespace App\CrudOperationProvider;

class CrudOperationProviderFabric
{
    public static function getCrudOperationProvider(string $requestSource): object
    {
        if ($requestSource === 'terminal') {
            return new CrudOperationProviderFromTerminal();
        } elseif ($requestSource === 'http') {
            return new CrudOperationProviderFromHttp();
        }
    }
}
