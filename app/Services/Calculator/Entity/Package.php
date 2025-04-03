<?php

declare(strict_types=1);

namespace App\Services\Calculator\Entity;

use App\Services\Calculator\Contracts\PackageInterface;

class Package implements PackageInterface
{
    public function __construct(
        private int $width,
        private int $height,
        private int $length,
        private bool $isRotatableX = false,
        private bool $isRotatableY = false,
        private bool $isRotatableZ = false,
    ) {
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getVolume(): int
    {
        return $this->width * $this->height * $this->length;
    }

    public function isRotatableX(): bool
    {
        return $this->isRotatableX;
    }

    public function isRotatableY(): bool
    {
        return $this->isRotatableY;
    }

    public function isRotatableZ(): bool
    {
        return $this->isRotatableZ;
    }
}