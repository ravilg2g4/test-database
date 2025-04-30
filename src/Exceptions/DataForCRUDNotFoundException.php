<?php

namespace App\Exceptions;

class DataForCRUDNotFoundException extends \Exception
{
    private string $entityName;
    private string $action;
    private string $dataName;
    public function __construct(string $entityName, string $action, string $dataName)
    {
        $this->entityName = $entityName;
        $this->action = $action;
        $this->dataName = $dataName;
        parent::__construct();
    }
    public function __toString(): string
    {
        return __CLASS__ . ": Нет данных {$this->dataName} для {$this->action} сущности {$this->entityName}";
    }
}
