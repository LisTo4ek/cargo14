<?php

declare(strict_types=1);

namespace App\Services\Calculator;

use App\Services\Calculator\Contracts\CalculatorInterface;
use App\Services\Calculator\Enum\AlgorithmType;

class CalculatorService implements CalculatorInterface
{
    public function calculate(array $containerTypes, array $packages, AlgorithmType $algorithmType): array
    {
        $algorithm = AlgorithmFactory::create($algorithmType);
        return $algorithm->calculate($containerTypes, $packages);
    }
}