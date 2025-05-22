<?php

declare(strict_types=1);

namespace App\ValueRequestProvider\Delete;

interface ValueDeleteRequestProviderInterface
{
    public function getArrayValueRequest(): array;
    public function getParamDelete(): string;
    public function getValueDelete(string $paramDelete): string|int;
}
