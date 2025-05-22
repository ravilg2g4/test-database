<?php

declare(strict_types=1);

namespace App\Tests\ValueRequestProvider\Delete;

use App\Exceptions\ChoiceActionException;
use App\Exceptions\DataForCRUDNotFoundException;
use App\Exceptions\DeleteUserException;
use App\ValueRequestProvider\Delete\ValueDeleteRequestFromTerminalProvider;
use PHPUnit\Framework\TestCase;

class ValueDeleteRequestFromTerminalProviderTest extends TestCase
{
    public function testGetParamDeleteForId(): void
    {
        $expectedParamDelete = 'id';
        $inputParamDelete = 'id';
        $this->readlineMock($inputParamDelete);
        $provider = new ValueDeleteRequestFromTerminalProvider();

        $result = $provider->getParamDelete();

        $this->assertEquals($expectedParamDelete, $result);
    }
    public function testGetParamDeleteForEmail(): void
    {
        $expectedParamDelete = 'email';
        $inputParamDelete = 'email';
        $this->readlineMock($inputParamDelete);
        $provider = new ValueDeleteRequestFromTerminalProvider();

        $result = $provider->getParamDelete();

        $this->assertEquals($expectedParamDelete, $result);
    }
    public function testGetParamDeleteForExceptions(): void
    {
        $inputParamDelete = 'invalid';
        $this->readlineMock($inputParamDelete);
        $provider = new ValueDeleteRequestFromTerminalProvider();

        $this->expectException(ChoiceActionException::class);

        $provider->getParamDelete();
    }
    public function testGetValueDeleteForId(): void
    {
        $expectedValueDelete = 2;
        $paramDelete = 'id';
        $inputValueDelete = '2';
        $this->readlineMock($inputValueDelete);
        $provider = new ValueDeleteRequestFromTerminalProvider();

        $result = $provider->getValueDelete($paramDelete);

        $this->assertEquals($expectedValueDelete, $result);
    }
    public function testGetValueDeleteForEmail(): void
    {
        $expectedValueDelete = 'test@gmail.com';
        $paramDelete = 'email';
        $inputValueDelete = 'test@gmail.com';
        $this->readlineMock($inputValueDelete);
        $provider = new ValueDeleteRequestFromTerminalProvider();

        $result = $provider->getValueDelete($paramDelete);

        $this->assertEquals($expectedValueDelete, $result);
    }
    public function testGetValueDeleteForNotFoundValue(): void
    {
        $paramDelete = 'email';
        $inputValueDelete = '';
        $this->readlineMock($inputValueDelete);
        $provider = new ValueDeleteRequestFromTerminalProvider();

        $this->expectException(DataForCRUDNotFoundException::class);

        $provider->getValueDelete($paramDelete);
    }
    public function testGetValueDeleteForInvalidId(): void
    {
        $paramDelete = 'id';
        $inputValueDelete = 'invalid';
        $this->readlineMock($inputValueDelete);
        $provider = new ValueDeleteRequestFromTerminalProvider();

        $this->expectException(DeleteUserException::class);

        $provider->getValueDelete($paramDelete);
    }

    private function readlineMock(string $input): void
    {
        global $inputForReadline;
        $inputForReadline = $input;

        if (!function_exists('App\ValueRequestProvider\Delete\readline')) {
            eval('namespace App\ValueRequestProvider\Delete;
                function readline() {
                global $inputForReadline;
                return $inputForReadline;
            }');
        }
        unset($inputForReadline);
    }
}
