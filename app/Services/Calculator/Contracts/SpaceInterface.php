<?php

declare(strict_types=1);

namespace App\Services\Calculator\Contracts;

interface SpaceInterface
{
    public function getX(): int;
    public function getY(): int;
    public function getZ(): int;
    public function getWidth(): int;
    public function getHeight(): int;
    public function getLength(): int;
}