<?php

declare(strict_types=1);

namespace App\ValueRequestProvider\Delete;

use App\Exceptions\ChoiceActionException;
use App\Exceptions\DataForCRUDNotFoundException;
use App\Exceptions\DeleteUserException;

class ValueDeleteRequestFromTerminalProvider implements ValueDeleteRequestProviderInterface
{
    public function getArrayValueRequest(): array
    {
        $paramDelete = $this->getParamDelete();
        $valueDelete = $this->getValueDelete($paramDelete);
        return ['paramDelete' => $paramDelete, 'valueDelete' => $valueDelete];
    }
    public function getParamDelete(): string
    {
        $paramDelete = readline("Вы знаете id или по почту пользователя? (id/email): ");
        $paramDelete = trim($paramDelete);
        if ($paramDelete !== 'id' && $paramDelete !== 'email') {
            throw new ChoiceActionException(action: 'параметр для удаления пользователя', actualChoice: $paramDelete, expectedChoice: 'id или email');
        }
        return $paramDelete;
    }
    public function getValueDelete(string $paramDelete): string|int
    {
        $valueDelete = readline("Значение {$paramDelete} для удаления пользователя: ");
        $valueDelete = trim($valueDelete);
        if ($valueDelete === '') {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'delete', dataName: 'valueDelete');
        }
        if ($paramDelete === 'id') {
            $stringValueDelete = $valueDelete;
            $valueDelete = (int)$valueDelete;
            if ($valueDelete === 0) {
                throw new DeleteUserException(message: "Введен некорректный id пользователя - {$stringValueDelete}");
            }
        }
        return $valueDelete;
    }
}
