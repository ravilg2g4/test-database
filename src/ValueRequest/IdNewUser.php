<?php

namespace App\ValueRequest;

use App\Exceptions\ChoiceActionException;
use App\Exceptions\DataForCRUDNotFoundException;
use App\Repositories\choiceRepository;
use App\Repositories\JsonRepository;
use App\Repositories\MySqlRepository;

class IdNewUser
{
    public int $idNewUser;
    public function __construct()
    {
        $choiceRepository = new choiceRepository();
        $choiceRepository = $choiceRepository->choiceRepository;
        if ($choiceRepository === 'mysql') {
            $idNewUser = $this->getIdNewUserFromMySql();
        } elseif ($choiceRepository === 'json') {
            $idNewUser = $this->getIdNewUserFromJson();
        }
        $this->idNewUser = $idNewUser;
    }
    private function getIdNewUserFromMySql(): int
    {
        $dbh = new MySqlRepository();
        $dbh = $dbh->connectMySql();
        $sql = 'SELECT * FROM users WHERE id = (SELECT MAX(id) FROM users);';
        $result = $dbh->query($sql);
        if (is_null($result)) {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'create', dataName: 'id');
        }
        $result = $result->fetch(\PDO::FETCH_ASSOC);
        return $result['id'] + 1;
    }
    private function getIdNewUserFromJson(): int
    {
        $database = new JsonRepository();
        $databaseArray = $database->getDatabaseArray();
        $lastUser = $databaseArray[array_key_last($databaseArray)];
        if (is_null($lastUser)) {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'create', dataName: 'id');
        }
        return $lastUser['id'] + 1;
    }
}
