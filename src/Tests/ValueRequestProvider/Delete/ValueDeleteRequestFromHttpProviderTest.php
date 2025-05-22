<?php

declare(strict_types=1);

namespace App\Tests\ValueRequestProvider\Delete;

use App\Exceptions\ChoiceActionException;
use App\Exceptions\DataForCRUDNotFoundException;
use App\ValueRequestProvider\Delete\ValueDeleteRequestFromHttpProvider;
use PHPUnit\Framework\TestCase;

class ValueDeleteRequestFromHttpProviderTest extends TestCase
{
    public function testGetArrayValueRequest(): void
    {
        $expectedArray = ['paramDelete' => 'id',
            'valueDelete' => '2'];
        $_SERVER['REQUEST_URI'] = '/delete-user/id/2';
        $provider = new ValueDeleteRequestFromHttpProvider();

        $result = $provider->getArrayValueRequest();

        $this->assertEquals($expectedArray, $result);
    }
    public function testGetParamDeleteForId(): void
    {
        $expectedParamDelete = 'id';
        $_SERVER['REQUEST_URI'] = '/delete-user/id/2';
        $provider = new ValueDeleteRequestFromHttpProvider();

        $result = $provider->getParamDelete();

        $this->assertEquals($expectedParamDelete, $result);
    }
    public function testGetParamDeleteForEmail(): void
    {
        $expectedParamDelete = 'email';
        $_SERVER['REQUEST_URI'] = '/delete-user/email/test@gmail.com';
        $provider = new ValueDeleteRequestFromHttpProvider();

        $result = $provider->getParamDelete();

        $this->assertEquals($expectedParamDelete, $result);
    }
    public function testGetParamDeleteForException(): void
    {
        $_SERVER['REQUEST_URI'] = '/delete-user/invalid/2';
        $provider = new ValueDeleteRequestFromHttpProvider();

        $this->expectException(ChoiceActionException::class);

        $provider->getParamDelete();
    }
    public function testGetValueDeleteForId(): void
    {
        $paramDelete = 'id';
        $expectedValueDelete = 2;
        $_SERVER['REQUEST_URI'] = '/delete-user/id/2';
        $provider = new ValueDeleteRequestFromHttpProvider();

        $result = $provider->getValueDelete($paramDelete);

        $this->assertEquals($expectedValueDelete, $result);
    }
    public function testGetValueDeleteForEmail(): void
    {
        $paramDelete = 'email';
        $expectedValueDelete = 'test@gmail.com';
        $_SERVER['REQUEST_URI'] = '/delete-user/email/test@gmail.com';
        $provider = new ValueDeleteRequestFromHttpProvider();

        $result = $provider->getValueDelete($paramDelete);

        $this->assertEquals($expectedValueDelete, $result);
    }
    public function testGetValueDeleteForException(): void
    {
        $paramDelete = 'email';
        $_SERVER['REQUEST_URI'] = '/delete-user/email/';
        $provider = new ValueDeleteRequestFromHttpProvider();

        $this->expectException(DataForCRUDNotFoundException::class);

        $provider->getValueDelete($paramDelete);
    }
}
