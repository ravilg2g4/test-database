<?php

namespace App\Exceptions;

class ChoiceRepositoryException extends \Exception
{
    private string $actualChoice;
    public function __construct(string $actualChoice)
    {
        $this->actualChoice = $actualChoice;
        parent::__construct();
    }
    public function __toString(): string
    {
        return __CLASS__ . ": Выбирая базу данных, пользователь указал в .env {$this->actualChoice}, ожидалось json или mysql";
    }
}
