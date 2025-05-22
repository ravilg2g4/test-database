<?php

declare(strict_types=1);

namespace App\ValueRequestProvider;

use App\Exceptions\EntityNotFoundException;
use App\ValueRequestProvider\Create\ValueCreateRequestFromHttpProvider;
use App\ValueRequestProvider\Create\ValueCreateRequestFromTerminalProvider;
use App\ValueRequestProvider\Delete\ValueDeleteRequestFromHttpProvider;
use App\ValueRequestProvider\Delete\ValueDeleteRequestFromTerminalProvider;

class ValueRequestProviderFactory
{
    public static function getValueRequestProvider(string $requestSource, string $crudOperation): object
    {
        if ($crudOperation === 'create') {
            if ($requestSource === 'http') {
                $valueRequestProvider = new ValueCreateRequestFromHttpProvider();
            } elseif ($requestSource === 'terminal') {
                $valueRequestProvider = new ValueCreateRequestFromTerminalProvider();
            }
        } elseif ($crudOperation === 'delete') {
            if ($requestSource === 'http') {
                $valueRequestProvider = new ValueDeleteRequestFromHttpProvider();
            } elseif ($requestSource === 'terminal') {
                $valueRequestProvider = new ValueDeleteRequestFromTerminalProvider();
            }
        } else {
            throw new EntityNotFoundException(entityName: 'ValueRequestProvider для requestSource - {$requestSource}, crudOperation - {$crudOperation}');
        }
        return $valueRequestProvider;
    }
}
