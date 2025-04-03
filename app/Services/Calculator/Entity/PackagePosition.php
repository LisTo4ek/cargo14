<?php

declare(strict_types=1);

namespace App\Services\Calculator\Entity;

use App\Services\Calculator\Contracts\PackageInterface;
use App\Services\Calculator\Contracts\PackagePositionInterface;

class PackagePosition implements PackagePositionInterface
{
    public function __construct(
        private PackageInterface $package,
        private int $x,
        private int $y,
        private int $z,
    ) {}

    public function getPackage(): PackageInterface
    {
        return $this->package;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getZ(): int
    {
        return $this->z;
    }
}