<?php

declare(strict_types=1);

namespace App\Services\Calculator;

use App\Services\Calculator\Contracts\ContainerInterface;
use App\Services\Calculator\Entity\Container;
use App\Services\Calculator\Enum\ContainerType;

class ContainerFactory
{
    public static function create(ContainerType $containerType): ContainerInterface
    {
        return new Container($containerType, ...$containerType->getDimensions());
    }
}