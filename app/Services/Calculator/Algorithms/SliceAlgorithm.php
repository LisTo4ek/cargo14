<?php

declare(strict_types=1);

namespace App\Services\Calculator\Algorithms;

use App\Services\Calculator\ContainerFactory;
use App\Services\Calculator\Contracts\AlgorithmInterface;
use App\Services\Calculator\Contracts\ContainerInterface;
use App\Services\Calculator\Contracts\PackageInterface;
use App\Services\Calculator\Contracts\SpaceInterface;
use App\Services\Calculator\Contracts\UsedContainerInterface;
use App\Services\Calculator\Entity\Package;
use App\Services\Calculator\Entity\PackagePosition;
use App\Services\Calculator\Entity\Space;
use App\Services\Calculator\Entity\UsedContainer;
use App\Services\Calculator\Enum\ContainerType;

class SliceAlgorithm implements AlgorithmInterface
{
    /**
     * @param array<ContainerType> $containerTypes
     * @param array<Package> $packages
     * @return array<array{container: ContainerInterface, packagePositions: array}>
     */
    public function calculate(array $containerTypes, array $packages): array
    {
        $containersAvailable = collect($containerTypes)
            ->map(fn(ContainerType $type) => ContainerFactory::create($type));

        $maxContainerVolume = $containersAvailable->max(fn(ContainerInterface $container) => $container->getVolume());

        /** @var array<UsedContainerInterface> $usedContainers */
        $usedContainers = [];

        // Sort packages in descending order by volume
        usort($packages, fn($a, $b) => $b->getVolume() - $a->getVolume());

        foreach ($packages as $package) {
            /** @var UsedContainerInterface|null $bestFitContainer */
            $bestFitContainer = null;
            $minRemainingSpace = PHP_INT_MAX;
            $bestFitContainerIndex = -1;

            // Find container with minimum remaining space after placing the package
            foreach ($usedContainers as $index => $usedContainer) {
                $remainingSpace = $usedContainer->getVolume() - $usedContainer->getUsedVolume() - $package->getVolume();
                $space = $this->fitsInContainer($usedContainer, $package)[0] ?? null;

                if ($remainingSpace >= 0 && $remainingSpace < $minRemainingSpace && $space) {
                    $bestFitContainer = $usedContainer;
                    $minRemainingSpace = $remainingSpace;
                    $bestFitContainerIndex = $index;
                }
            }

            // If no suitable container found, create new one from the best fitting container type
            if ($bestFitContainer === null) {
                $minRemainingSpaceForNewContainer = PHP_INT_MAX;
                $selectedContainerType = null;

                foreach ($containerTypes as $type) {
                    $container = new UsedContainer(ContainerFactory::create($type));
                    $remainingSpace = $container->getVolume() - $package->getVolume();

                    if ($remainingSpace >= 0 && $remainingSpace < $minRemainingSpaceForNewContainer) {
                        $selectedContainerType = $type;
                        $minRemainingSpaceForNewContainer = $remainingSpace;
                    }
                }

                if ($selectedContainerType === null) {
                    continue;
                }

                $bestFitContainer = new UsedContainer(ContainerFactory::create($selectedContainerType));
                $usedContainers[] = $bestFitContainer;
                $bestFitContainerIndex = count($usedContainers) - 1;
            }

            // Add package to the best fit container
            if ($this->tryToAddPackage($bestFitContainer, $package)) {
                $usedContainers[$bestFitContainerIndex] = $bestFitContainer;
            }
        }

        return $usedContainers;
    }

    public function tryToAddPackage(UsedContainerInterface &$usedContainer, PackageInterface $package): bool
    {
        [$space, $index] = $this->fitsInContainer($usedContainer, $package);
        if ($space) {
            $this->placePackage($usedContainer, $package, $space, $index);
            return true;
        }

        return false;
    }

    public function fitsInContainer(UsedContainerInterface $usedContainer, PackageInterface $package): array
    {
        $spaces = $usedContainer->getSpaces();
        foreach ($spaces as $index => $space) {
            if ($this->fitsInSpace($package, $space)) {
                return [$space, $index];
            }
        }

        return [null, null];
    }

    private function fitsInSpace(PackageInterface $package, SpaceInterface $space): bool
    {
        $fits = ($package->getWidth() <= $space->getWidth() &&
            $package->getHeight() <= $space->getHeight() &&
            $package->getLength() <= $space->getLength());

        if ($package->isRotatableX()) {
            $fits = $fits || ($package->getHeight() <= $space->getWidth() &&
                    $package->getWidth() <= $space->getHeight() &&
                    $package->getLength() <= $space->getLength());
        }

        if ($package->isRotatableY()) {
            $fits = $fits || ($package->getWidth() <= $space->getWidth() &&
                    $package->getLength() <= $space->getHeight() &&
                    $package->getHeight() <= $space->getLength());
        }

        if ($package->isRotatableZ()) {
            $fits = $fits || ($package->getLength() <= $space->getWidth() &&
                    $package->getHeight() <= $space->getHeight() &&
                    $package->getWidth() <= $space->getLength());
        }

        return $fits;
    }

    private function placePackage(UsedContainer $usedContainer, PackageInterface $package, SpaceInterface $space, int $spaceIndex): UsedContainerInterface
    {
        $usedContainer
            ->removeSpace($spaceIndex)
            ->addPackage($package)
            ->addPackagePosition(
                new PackagePosition(
                    $package,
                    $space->getX(),
                    $space->getY(),
                    $space->getZ(),
                )
            );

        return $this->splitSpace($usedContainer, $package, $space);
    }
    private function splitSpace(UsedContainer $usedContainer, PackageInterface $package, SpaceInterface $space): UsedContainerInterface
    {
        // Original orientation
        if ($space->getWidth() > $package->getWidth()) {
            $usedContainer->addSpace(
                new Space(
                    $space->getX() + $package->getWidth(),
                    $space->getY(),
                    $space->getZ(),
                    $space->getWidth() - $package->getWidth(),
                    $space->getHeight(),
                    $space->getLength()
                )
            );
        }

        if ($space->getHeight() > $package->getHeight()) {
            $usedContainer->addSpace(
                new Space(
                    $space->getX(),
                    $space->getY() + $package->getHeight(),
                    $space->getZ(),
                    $package->getWidth(),
                    $space->getHeight() - $package->getHeight(),
                    $space->getLength()
                )
            );
        }

        if ($space->getLength() > $package->getLength()) {
            $usedContainer->addSpace(
                new Space(
                    $space->getX(),
                    $space->getY(),
                    $space->getZ() + $package->getLength(),
                    $package->getWidth(),
                    $space->getHeight(),
                    $space->getLength() - $package->getLength()
                )
            );
        }

        // Rotated orientations
        if ($package->isRotatableX()) {
            if ($space->getWidth() > $package->getHeight()) {
                $usedContainer->addSpace(
                    new Space(
                        $space->getX() + $package->getHeight(),
                        $space->getY(),
                        $space->getZ(),
                        $space->getWidth() - $package->getHeight(),
                        $space->getHeight(),
                        $space->getLength()
                    )
                );
            }

            if ($space->getHeight() > $package->getWidth()) {
                $usedContainer->addSpace(
                    new Space(
                        $space->getX(),
                        $space->getY() + $package->getWidth(),
                        $space->getZ(),
                        $package->getHeight(),
                        $space->getHeight() - $package->getWidth(),
                        $space->getLength()
                    )
                );
            }
        }

        if ($package->isRotatableY()) {
            if ($space->getWidth() > $package->getLength()) {
                $usedContainer->addSpace(
                    new Space(
                        $space->getX() + $package->getLength(),
                        $space->getY(),
                        $space->getZ(),
                        $space->getWidth() - $package->getLength(),
                        $space->getHeight(),
                        $space->getLength()
                    )
                );
            }

            if ($space->getLength() > $package->getWidth()) {
                $usedContainer->addSpace(
                    new Space(
                        $space->getX(),
                        $space->getY(),
                        $space->getZ() + $package->getWidth(),
                        $package->getLength(),
                        $space->getHeight(),
                        $space->getLength() - $package->getWidth()
                    )
                );
            }
        }

        if ($package->isRotatableZ()) {
            if ($space->getHeight() > $package->getLength()) {
                $usedContainer->addSpace(
                    new Space(
                        $space->getX(),
                        $space->getY() + $package->getLength(),
                        $space->getZ(),
                        $package->getWidth(),
                        $space->getHeight() - $package->getLength(),
                        $space->getLength()
                    )
                );
            }

            if ($space->getLength() > $package->getHeight()) {
                $usedContainer->addSpace(
                    new Space(
                        $space->getX(),
                        $space->getY(),
                        $space->getZ() + $package->getHeight(),
                        $package->getWidth(),
                        $package->getLength(),
                        $space->getLength() - $package->getHeight()
                    )
                );
            }
        }

        return $usedContainer;
    }
}