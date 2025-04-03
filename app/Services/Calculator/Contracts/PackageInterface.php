<?php

declare(strict_types=1);

namespace App\Services\Calculator\Contracts;

interface PackageInterface
{
    public function getWidth(): int;
    public function getHeight(): int;
    public function getLength(): int;
    public function getVolume(): int;
    public function isRotatableX(): bool;
    public function isRotatableY(): bool;
    public function isRotatableZ(): bool;
}