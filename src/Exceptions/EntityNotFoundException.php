<?php

namespace App\Exceptions;

class EntityNotFoundException extends \Exception
{
    private string $entityName;
    private string $failed;
    private mixed $fieldValue;

    public function __construct(string $entityName, string $failed, mixed $fieldValue)
    {
        $this->entityName = $entityName;
        $this->failed = $failed;
        $this->fieldValue = $fieldValue;
        parent::__construct();
    }
    public function __toString(): string
    {
        return __CLASS__ . ": Не найден {$this->entityName} c {$this->failed} - {$this->fieldValue}";
    }
}
