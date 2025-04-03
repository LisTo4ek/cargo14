<?php

declare(strict_types=1);

namespace App\Services\Calculator\Contracts;

use App\Services\Calculator\Enum\ContainerType;

interface ContainerInterface
{
    public function getType(): ContainerType;
    public function getWidth(): int;
    public function getHeight(): int;
    public function getLength(): int;
    public function getVolume(): int;
}