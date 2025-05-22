<?php

declare(strict_types=1);

namespace App\Tests\ValueRequestProvider;

use App\Exceptions\EntityNotFoundException;
use App\ValueRequestProvider\Create\ValueCreateRequestFromHttpProvider;
use App\ValueRequestProvider\Create\ValueCreateRequestFromTerminalProvider;
use App\ValueRequestProvider\Delete\ValueDeleteRequestFromHttpProvider;
use App\ValueRequestProvider\Delete\ValueDeleteRequestFromTerminalProvider;
use App\ValueRequestProvider\ValueRequestProviderFactory;
use PHPUnit\Framework\TestCase;

class ValueRequestProviderFactoryTest extends TestCase
{
    public function testGetValueRequestProviderForCreateHttp(): void
    {
        $requestSource = 'http';
        $crudOperation = 'create';
        $requestProviderFactory = new ValueRequestProviderFactory();
        $expectedProvider = new ValueCreateRequestFromHttpProvider();

        $result = $requestProviderFactory->getValueRequestProvider($requestSource, $crudOperation);

        $this->assertEquals($expectedProvider, $result);
    }
    public function testGetValueRequestProviderForCreateTerminal(): void
    {
        $requestSource = 'terminal';
        $crudOperation = 'create';
        $requestProviderFactory = new ValueRequestProviderFactory();
        $expectedProvider = new ValueCreateRequestFromTerminalProvider();

        $result = $requestProviderFactory->getValueRequestProvider($requestSource, $crudOperation);

        $this->assertEquals($expectedProvider, $result);
    }
    public function testGetValueRequestProviderForDeleteHttp(): void
    {
        $requestSource = 'http';
        $crudOperation = 'delete';
        $requestProviderFactory = new ValueRequestProviderFactory();
        $expectedProvider = new ValueDeleteRequestFromHttpProvider();

        $result = $requestProviderFactory->getValueRequestProvider($requestSource, $crudOperation);

        $this->assertEquals($expectedProvider, $result);
    }
    public function testGetValueRequestProviderForDeleteTerminal(): void
    {
        $requestSource = 'terminal';
        $crudOperation = 'delete';
        $requestProviderFactory = new ValueRequestProviderFactory();
        $expectedProvider = new ValueDeleteRequestFromTerminalProvider();

        $result = $requestProviderFactory->getValueRequestProvider($requestSource, $crudOperation);

        $this->assertEquals($expectedProvider, $result);
    }
    public function testGetValueRequestProviderForException(): void
    {
        $requestSource = 'invalid';
        $crudOperation = 'invalid';
        $requestProviderFactory = new ValueRequestProviderFactory();

        $this->expectException(EntityNotFoundException::class);

        $requestProviderFactory->getValueRequestProvider($requestSource, $crudOperation);
    }
}
