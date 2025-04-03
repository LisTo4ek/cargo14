<?php

declare(strict_types=1);

namespace App\Services\Calculator\Entity;

use App\Services\Calculator\Contracts\SpaceInterface;

class Space implements SpaceInterface
{
    public function __construct(
        private int $x,
        private int $y,
        private int $z,
        private int $width,
        private int $height,
        private int $length,
    ) {}

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