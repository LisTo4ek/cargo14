<?php

declare(strict_types=1);

namespace App\Services\Calculator\Entity;

use App\Services\Calculator\Contracts\ContainerInterface;
use App\Services\Calculator\Enum\ContainerType;

class Container implements ContainerInterface
{
    public function __construct(
        private ContainerType $type,
        private int $width,
        private int $height,
        private int $length
    ) {
    }

    public function getType(): ContainerType
    {
        return $this->type;
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
}