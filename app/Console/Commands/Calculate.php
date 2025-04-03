<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Calculator\CalculatorService;
use App\Services\Calculator\Contracts\UsedContainerInterface;
use App\Services\Calculator\Entity\Package;
use App\Services\Calculator\Enum\AlgorithmType;
use App\Services\Calculator\Enum\ContainerType;
use Illuminate\Console\Command;

class Calculate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(
        private CalculatorService $calculatorService,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transports = [
//            [
//                'name' => 'Transport 1',
//                'packages' => array_merge(
//                    array_fill(0,27, new Package(7800, 7900, 9300)),
//                ),
//                'containers' => [
//                    ContainerType::DRY_40FT,
//                    ContainerType::DRY_10FT,
//                ],
//            ],
            [
                'name' => 'Transport 2',
                'packages' => array_merge(
//                    array_fill(0,24, new Package(3000, 6000, 9000, true, true, true)),
//                    array_fill(0,33, new Package(7500, 10000, 20000, true, true, true)),
                    array_fill(0,24, new Package(3000, 6000, 9000, false, false, false)),
                    array_fill(0,33, new Package(7500, 10000, 20000, false, false, false)),
//                    array_fill(0,24, new Package(3000, 6000, 9000, true)),
//                    array_fill(0,33, new Package(7500, 10000, 20000, true)),
                ),
                'containers' => [
                    ContainerType::DRY_40FT,
                    ContainerType::DRY_10FT,
                ],
            ],
//            [
//                'name' => 'Transport 3',
//                'packages' => array_merge(
//                    array_fill(0,10, new Package(8000, 10000, 20000)),
//                    array_fill(0,25, new Package(6000, 8000, 15000)),
//                ),
//                'containers' => [
//                    ContainerType::DRY_40FT,
//                    ContainerType::DRY_10FT,
//                ],
//            ],
//            [
//                'name' => 'Transport 5',
//                'packages' => array_merge(
//                    array_fill(0,24, new Package(10, 10, 10, true, true, true)),
//                    array_fill(0,33, new Package(10, 10, 20, true, true, true)),
//                ),
//                'containers' => [
//                    ContainerType::A1,
//                ],
//            ],
        ];

        foreach ($transports as $transport) {
            $this->info($transport['name']);

            $totalPackages = count($transport['packages']);

            $res = $this->calculatorService->calculate(
                $transport['containers'],
                $transport['packages'],
                AlgorithmType::BEST_FIT,
            );
            dump([
                'total_packages' => $totalPackages,
                'containers_space' => collect($res)
                    ->sum(fn(UsedContainerInterface $usedContainer) => $usedContainer->getContainer()->getVolume()),
                'containers_used' => count($res),
                'packages_packed' => collect($res)
                    ->sum(fn(UsedContainerInterface $uc) => count($uc->getPackagePositions())),
                'packages_per_container' => collect($res)
                    ->map(fn(UsedContainerInterface $uc) => [
                        'container_type' => $uc->getContainer()->getType()->value,
                        'packages' => count($uc->getPackagePositions())
                    ])
                    ->toArray()
            ]);
        }
    }
}
