<?php

declare(strict_types=1);

namespace App\ValueRequestProvider\Delete;

use App\Exceptions\ChoiceActionException;
use App\Exceptions\DataForCRUDNotFoundException;

class ValueDeleteRequestFromHttpProvider implements ValueDeleteRequestProviderInterface
{
    public function getArrayValueRequest(): array
    {
        $paramDelete = $this->getParamDelete();
        $valueDelete = $this->getValueDelete($paramDelete);
        return ['paramDelete' => $paramDelete, 'valueDelete' => $valueDelete];
    }
    public function getParamDelete(): string
    {
        $arrayRequestUrl = $this->getArrayRequestUrl();
        $paramDelete = $arrayRequestUrl[count($arrayRequestUrl) - 2];
        if (($paramDelete !== 'id' && $paramDelete !== 'email') || is_null($paramDelete)) {
            throw new ChoiceActionException(action: 'параметр для удаления пользователя', actualChoice: $paramDelete, expectedChoice: 'id или email');
        }
        return $paramDelete;
    }
    public function getValueDelete(string $paramDelete): string|int
    {
        $arrayRequestUrl = $this->getArrayRequestUrl();
        $valueDelete = $arrayRequestUrl[count($arrayRequestUrl) - 1];
        if (is_null($valueDelete) || $valueDelete === '') {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'delete', dataName: 'valueDelete');
        }
        if ($paramDelete === 'id') {
            $valueDelete = (int)$valueDelete;
        }
        return $valueDelete;
    }
    private function getArrayRequestUrl(): array
    {
        $requestUrl = $_SERVER['REQUEST_URI'];
        $arrayRequestUrl = explode('/', $requestUrl);
        // добавить проверку
        return $arrayRequestUrl;
    }
}
