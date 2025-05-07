<?php

declare(strict_types=1);

namespace App\ValueRequestProvider\Create;

use App\Exceptions\DataForCRUDNotFoundException;

class ValueCreateRequestFromHttpProvider implements ValueCreateRequestProviderInterface
{
    public function getArrayValueRequest(): array
    {
        $name = $this->getNameNewUser();
        $surname = $this->getSurnameNewUser();
        $email = $this->getEmailNewUser();
        return ['name' => $name, 'surname' => $surname, 'email' => $email];
    }
    private function getArrayFromJsonPost(): array
    {
        $json = file_get_contents('php://input');
        if ($json === false) {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'create', dataName: 'json из POST-запроса');
        }
        return json_decode($json, true);
    }
    public function getNameNewUser(): string
    {
        $arrayNewUser = $this->getArrayFromJsonPost();
        $name = $arrayNewUser['name'];
        if ($name === '' or $name === null) {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'create', dataName: 'name');
        }
        return $name;
    }
    public function getSurnameNewUser(): string
    {
        $arrayNewUser = $this->getArrayFromJsonPost();
        $surname = $arrayNewUser['surname'];
        if ($surname === '' or $surname === null) {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'create', dataName: 'surname');
        }
        return $surname;
    }
    public function getEmailNewUser(): string
    {
        $arrayNewUser = $this->getArrayFromJsonPost();
        $email = $arrayNewUser['email'];
        if ($email === '' or $email === null) {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'create', dataName: 'email');
        }
        return $email;
    }
}
