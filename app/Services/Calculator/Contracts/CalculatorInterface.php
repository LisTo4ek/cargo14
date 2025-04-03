<?php

declare(strict_types=1);

namespace App\Services\Calculator\Contracts;

use App\Services\Calculator\Enum\AlgorithmType;

interface CalculatorInterface
{
    public function calculate(array $containerTypes, array $packages, AlgorithmType $algorithmType): array;
}