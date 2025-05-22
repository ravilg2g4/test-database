<?php

declare(strict_types=1);

namespace App\Tests\CrudOperationProvider;

use App\CrudOperationProvider\CrudOperationFromHttpProvider;
use App\Exceptions\ChoiceActionException;
use PHPUnit\Framework\TestCase;

class CrudOperationFromHttpProviderTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($_SERVER['REQUEST_METHOD']);
    }
    public function testGetCrudOperationForGetRequest(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $provider = new CrudOperationFromHttpProvider();

        $result = $provider->getCrudOperation();

        $this->assertEquals('read', $result);
    }
    public function testGetCrudOperationForPostRequest(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $provider = new CrudOperationFromHttpProvider();

        $result = $provider->getCrudOperation();

        $this->assertEquals('create', $result);
    }
    public function testGetCrudOperationForDeleteRequest(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $provider = new CrudOperationFromHttpProvider();

        $result = $provider->getCrudOperation();

        $this->assertEquals('delete', $result);
    }
    public function testGetCrudOperationForInvalidRequest(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'INVALID';
        $provider = new CrudOperationFromHttpProvider();

        $this->expectException(ChoiceActionException::class);

        $provider->getCrudOperation();
    }
}
