<?php

declare(strict_types=1);

namespace App\ValueRequest;

use App\Repositories\NewUser;

interface ValueRequestInterface
{
    public function getNewUser(): NewUser;
    public function getIdNewUser(): int;
    public function getNameNewUser(): string;
    public function getSurnameNewUser(): string;
    public function getEmailNewUser(): string;
    public function getChoiceDelete(): string;
    public function getValueDelete(string $choiceDelete): string|int;
}
