<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class HardcoverStatus extends Enum
{
    const Validated = 'VALIDATED';
    const Ongoing = 'ONGOING';

    /**
     * get description for enum value
     */
    public static function getString(int $value): string
    {
        switch($value) {
            case self::Validated:
                return 'Tervalidasi';
            case self::Ongoing:
                return 'Dalam Proses';
            default:
                return self::getKey($value);
        }
    }

    /**
     * Get all description
     */
    public static function getStrings(): array
    {
        $getKeys = self::getKeys();
        foreach($getKeys as $key => $value) {
            if($getKeys[$key] == self::getKey(self::Validated)) {
                $getKeys[self::Validated] = "Tervalidasi";
            } else if($getKeys[$key] == self::getKey(self::Ongoing)) {
                $getKeys[self::Ongoing] = "Dalam Proses";
            } else {
                $getKeys[self::Ongoing] = "Dalam Proses";
            }
        }

        return $getKeys;
    }

    /**
     * get hard coded dropdown status
     */
    public static function getDropdownStatus(): array
    {
        return [
            Self::Validated => Self::Validated,
            Self::Ongoing => Self::Ongoing
        ];
    }

    /**
     * Get all description
     */
    public static function getStringsExcept(int $exceptValue): array
    {
        $getKeys = self::getStrings();

        unset($getKeys[$exceptValue]);

        return $getKeys;
    }

    /**
     * Get all description
     */
    public static function getValueByString(string $string)
    {
        switch(strtolower($string)) {
            case 'validated':
                return self::Validated;
            case 'ongoing':
                return self::Ongoing;
            default:
                return null;
        }
    }
}