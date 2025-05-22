<?php

declare(strict_types=1);

namespace App\ValueRequestProvider\Create;

interface ValueCreateRequestProviderInterface
{
    public function getArrayValueRequest(): array;
    public function getNameNewUser(): string;
    public function getSurnameNewUser(): string;
    public function getEmailNewUser(): string;
}
