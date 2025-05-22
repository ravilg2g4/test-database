<?php

declare(strict_types=1);

namespace App\Tests\CrudOperationProvider;

use App\CrudOperationProvider\CrudOperationProviderFactory;
use App\CrudOperationProvider\CrudOperationFromHttpProvider;
use App\CrudOperationProvider\CrudOperationFromTerminalProvider;
use App\Exceptions\EntityNotFoundException;
use PHPUnit\Framework\TestCase;

class CrudOperationProviderFactoryTest extends TestCase
{
    public function testGetCrudOperationProviderForTerminal(): void
    {
        $requestSource = 'terminal';
        $fabric = new CrudOperationProviderFactory();
        $expectedProvider = new CrudOperationFromTerminalProvider();

        $result = $fabric->getCrudOperationProvider($requestSource);

        $this->assertEquals($expectedProvider, $result);
    }
    public function testGetCrudOperationProviderForHttp(): void
    {
        $requestSource = 'http';
        $fabric = new CrudOperationProviderFactory();
        $expectedProvider = new CrudOperationFromHttpProvider();

        $result = $fabric->getCrudOperationProvider($requestSource);

        $this->assertEquals($expectedProvider, $result);
    }
    public function testGetCrudOperationProviderForInvalidRequestSource(): void
    {
        $requestSource = 'invalid';
        $fabric = new CrudOperationProviderFactory();

        $this->expectException(EntityNotFoundException::class);

        $fabric->getCrudOperationProvider($requestSource);
    }
}
