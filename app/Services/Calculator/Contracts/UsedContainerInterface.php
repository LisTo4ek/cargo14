<?php

declare(strict_types=1);

namespace App\Services\Calculator\Contracts;

interface UsedContainerInterface
{
    public function getContainer(): ContainerInterface;
    public function getWidth(): int;
    public function getHeight(): int;
    public function getLength(): int;
    public function getVolume(): int;
    public function getUsedVolume(): int;
    public function getRemainedVolume(): int;
    public function getSpaces(): array;
    public function setSpaces(array $spaces): self;
    public function addSpace(SpaceInterface $space): self;
    public function getPackages(): array;
    public function setPackages(array $packages): self;
    public function addPackage(PackageInterface $package): self;
    public function getPackagePositions(): array;
    public function setPackagePositions(array $packagePositions): self;
    public function addPackagePosition(PackagePositionInterface $packagePosition): self;
    public function removeSpace(int $index): self;
}