<?php

declare(strict_types=1);

namespace App\Repositories;

class UsersRepositoryFactory
{
    public function getUsersRepository(): RepositoryInterface
    {
        $choiceRepository = new ChoiceRepository();
        $choiceRepository = $choiceRepository->choiceRepository;
        if ($choiceRepository === 'json') {
            return new JsonRepository();
        } elseif ($choiceRepository === 'mysql') {
            return new MySqlRepository();
        }
    }
}
