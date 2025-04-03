<?php

declare(strict_types=1);

namespace App\Services\Calculator;

use App\Services\Calculator\Algorithms\SliceAlgorithm;
use App\Services\Calculator\Contracts\AlgorithmInterface;
use App\Services\Calculator\Enum\AlgorithmType;

class AlgorithmFactory
{
    public static function create(AlgorithmType $algorithmType): AlgorithmInterface
    {
        return match ($algorithmType) {
            AlgorithmType::BEST_FIT => new SliceAlgorithm(),
        };
    }
}