<?php

declare(strict_types=1);

namespace App\Services\Calculator\Contracts;

interface PackagePositionInterface
{
    public function getPackage(): PackageInterface;
    public function getX(): int;
    public function getY(): int;
    public function getZ(): int;
}