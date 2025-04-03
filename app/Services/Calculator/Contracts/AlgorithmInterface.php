<?php

declare(strict_types=1);

namespace App\Services\Calculator\Contracts;

use App\Services\Calculator\Enum\ContainerType;

interface AlgorithmInterface
{
    /**
     * @param array<ContainerType> $containerTypes
     * @param array<PackageInterface> $packages
     * @return array
     */
    public function calculate(array $containerTypes, array $packages): array;
}