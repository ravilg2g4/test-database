<?php

declare(strict_types=1);

namespace App\Tests\ValueRequestProvider\Create;

use App\Exceptions\DataForCRUDNotFoundException;
use App\ValueRequestProvider\Create\ValueCreateRequestFromTerminalProvider;
use PHPUnit\Framework\TestCase;

class ValueCreateRequestFromTerminalProviderTest extends TestCase
{
    public function testGetArrayValueRequest(): void
    {
        $expectedArray = ['name' => 'test', 'surname' => 'test', 'email' => 'test'];
        $input = 'test';
        $this->readlineMock($input);
        $provider = new ValueCreateRequestFromTerminalProvider();

        $result = $provider->getArrayValueRequest();

        $this->assertEquals($expectedArray, $result);
    }
    public function testGetNameNewUser(): void
    {
        $expectedName = 'name';
        $inputName = 'name';
        $this->readlineMock($inputName);
        $provider = new ValueCreateRequestFromTerminalProvider();

        $result = $provider->getNameNewUser();

        $this->assertEquals($expectedName, $result);
    }
    public function testGetNameNewUserForExceptions(): void
    {
        $inputName = '';
        $this->readlineMock($inputName);
        $provider = new ValueCreateRequestFromTerminalProvider();

        $this->expectException(DataForCRUDNotFoundException::class);

        $provider->getNameNewUser();
    }
    public function testGetSurnameNewUser(): void
    {
        $expectedSurname = 'surname';
        $inputSurname = 'surname';
        $this->readlineMock($inputSurname);
        $provider = new ValueCreateRequestFromTerminalProvider();

        $result = $provider->getSurnameNewUser();

        $this->assertEquals($expectedSurname, $result);
    }
    public function testGetSurnameNewUserForExceptions(): void
    {
        $inputSurname = '';
        $this->readlineMock($inputSurname);
        $provider = new ValueCreateRequestFromTerminalProvider();

        $this->expectException(DataForCRUDNotFoundException::class);

        $provider->getNameNewUser();
    }
    public function testGetEmailNewUser(): void
    {
        $expectedEmail = 'email';
        $inputEmail = 'email';
        $this->readlineMock($inputEmail);
        $provider = new ValueCreateRequestFromTerminalProvider();

        $result = $provider->getEmailNewUser();

        $this->assertEquals($expectedEmail, $result);
    }
    public function testGetEmailNewUserForExceptions(): void
    {
        $inputEmail = '';
        $this->readlineMock($inputEmail);
        $provider = new ValueCreateRequestFromTerminalProvider();

        $this->expectException(DataForCRUDNotFoundException::class);

        $provider->getNameNewUser();
    }
    private function readlineMock(string $input): void
    {
        global $inputForReadline;
        $inputForReadline = $input;

        if (!function_exists('App\ValueRequestProvider\Create\readline')) {
            eval('namespace App\ValueRequestProvider\Create;
                function readline() {
                global $inputForReadline;
                return $inputForReadline;
            }');
        }
        unset($inputForReadline);
    }
}
