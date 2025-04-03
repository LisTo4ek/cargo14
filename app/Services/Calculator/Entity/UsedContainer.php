<?php

declare(strict_types=1);

namespace App\Services\Calculator\Entity;

use App\Services\Calculator\Contracts\ContainerInterface;
use App\Services\Calculator\Contracts\PackageInterface;
use App\Services\Calculator\Contracts\PackagePositionInterface;
use App\Services\Calculator\Contracts\SpaceInterface;
use App\Services\Calculator\Contracts\UsedContainerInterface;

class UsedContainer implements UsedContainerInterface
{
    /** @var array<SpaceInterface> */
    private array $spaces = [];

    /** @var array<PackageInterface> */
    private array $packages = [];

    /** @var array<PackagePosition> */
    private array $packagePositions = [];

    public function __construct(
        private ContainerInterface $container,
    ) {
        $this->spaces[] = new Space(0, 0, 0, $this->getWidth(), $this->getHeight(), $this->getLength());
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getWidth(): int
    {
        return $this->container->getWidth();
    }

    public function getHeight(): int
    {
        return $this->container->getHeight();
    }

    public function getLength(): int
    {
        return $this->container->getLength();
    }

    public function getVolume(): int
    {
        return $this->container->getVolume();
    }

    public function getUsedVolume(): int
    {
        $usedVolume = 0;
        foreach ($this->packages as $package) {
            $usedVolume += $package->getVolume();
        }
        return $usedVolume;
    }

    public function getRemainedVolume(): int
    {
        return $this->getVolume() - $this->getUsedVolume();
    }

    public function getSpaces(): array
    {
        return $this->spaces;
    }

    public function setSpaces(array $spaces): self
    {
        $this->spaces = $spaces;
        return $this;
    }

    public function addSpace(SpaceInterface $space): self
    {
        $this->spaces[] = $space;
        return $this;
    }

    public function getPackages(): array
    {
        return $this->packages;
    }

    public function setPackages(array $packages): self
    {
        $this->packages = $packages;
        return $this;
    }

    public function addPackage(PackageInterface $package): self
    {
        $this->packages[] = $package;
        return $this;
    }

    public function getPackagePositions(): array
    {
        return $this->packagePositions;
    }

    public function setPackagePositions(array $packagePositions): self
    {
        $this->packagePositions = $packagePositions;
        return $this;
    }

    public function addPackagePosition(PackagePositionInterface $packagePosition): self
    {
        $this->packagePositions[] = $packagePosition;
        return $this;
    }

    public function removeSpace(int $index): self
    {
        unset($this->spaces[$index]);
        return $this;
    }
}