<?php

namespace App\Exceptions;

use Throwable;

class ChoiceActionException extends \Exception
{
    private string $action;
    private string $actualChoice;
    private string $expectedChoice;

    public function __construct(string $action, string $actualChoice, string $expectedChoice)
    {
        $this->action = $action;
        $this->actualChoice = $actualChoice;
        $this->expectedChoice = $expectedChoice;
        parent::__construct();
    }
    public function __toString(): string
    {
        return __CLASS__ . ": Выбирая {$this->action}, пользователь ввел {$this->actualChoice}, ожидалось {$this->expectedChoice}";
    }
}
