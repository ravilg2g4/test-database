<?php

namespace App\Tests\CrudOperationProvider;

use App\CrudOperationProvider\CrudOperationFromTerminalProvider;
use App\Exceptions\ChoiceActionException;
use PHPUnit\Framework\TestCase;

class CrudOperationFromTerminalProviderTest extends TestCase
{
    private string $ExpectedTextForChoiceAction = <<<EOT
Что Вы хотите сделать с базой данных о пользователях?
Выберите номер действия:
1. Показать список пользователей;
2. Добавить пользователя;
3. Удалить пользователя.

EOT;
    public function testGetCrudOperationForRead(): void
    {
        $expectedCrudOperation = 'read';
        $inputCrudOperation = '1';
        $this->readlineMock($inputCrudOperation);
        $this->expectOutputString($this->ExpectedTextForChoiceAction);
        $provider = new CrudOperationFromTerminalProvider();

        $result = $provider->getCrudOperation();

        $this->assertEquals($expectedCrudOperation, $result);
    }
    public function testGetCrudOperationForCreate(): void
    {
        $expectedCrudOperation = 'create';
        $inputCrudOperation = '2';
        $this->readlineMock($inputCrudOperation);
        $this->expectOutputString($this->ExpectedTextForChoiceAction);
        $provider = new CrudOperationFromTerminalProvider();

        $result = $provider->getCrudOperation();

        $this->assertEquals($expectedCrudOperation, $result);
    }
    public function testGetCrudOperationForDelete(): void
    {
        $expectedCrudOperation = 'delete';
        $inputCrudOperation = '3';
        $this->readlineMock($inputCrudOperation);
        $this->expectOutputString($this->ExpectedTextForChoiceAction);
        $provider = new CrudOperationFromTerminalProvider();

        $result = $provider->getCrudOperation();

        $this->assertEquals($expectedCrudOperation, $result);
    }
    public function testGetCrudOperationForInvalid(): void
    {
        $inputCrudOperation = 'invalid';
        $this->readlineMock($inputCrudOperation);
        $this->expectOutputString($this->ExpectedTextForChoiceAction);
        $provider = new CrudOperationFromTerminalProvider();

        $this->expectException(ChoiceActionException::class);

        $provider->getCrudOperation();
    }
    private function readlineMock(string $input): void
    {
        global $inputForReadline;
        $inputForReadline = $input;

        if (!function_exists('App\CrudOperationProvider\readline')) {
            eval('namespace App\CrudOperationProvider;
                function readline() {
                global $inputForReadline;
                return $inputForReadline;
            }');
        }
        unset($inputForReadline);
    }
}
