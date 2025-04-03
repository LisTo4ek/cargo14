<?php

namespace App\Services\Calculator\Enum;

use App\Services\Calculator\Dto\Container;

enum ContainerType: string
{
    case DRY_40FT = '40ft';
    case DRY_10FT = '10ft';
    case A1 = 'a1';
    case A2 = 'a2';

    /**
     * @return array{width: int, height: int, length: int}
     */
    public function getDimensions(): array
    {
        return match($this) {
            self::DRY_40FT => [
                'width' => 23480, // mm
                'height' => 23844, // mm
                'length' => 120310, // mm
            ],
            self::DRY_10FT => [
                'width' => 23480, // mm
                'height' => 23844, // mm
                'length' => 27940, // mm
            ],
            self::A1 => [
                'width' => 20, // mm
                'height' => 20, // mm
                'length' => 20, // mm
            ],
            self::A2 => [
                'width' => 30, // mm
                'height' => 30, // mm
                'length' => 30, // mm
            ],
        };
    }
}