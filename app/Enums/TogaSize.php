<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TogaSize extends Enum
{
    const S = 0;
    const M = 1;
    const L = 2;
    const XL = 3;
    const XXL = 4;

    /**
     * Get the description for an enum value
     *
     * @param  int  $value
     * @return string
     */
    public static function getString(int $value): string
    {
        switch ($value) {
            case self::S:
                return 'S';
            case self::M:
                return 'M';
            case self::L:
                return 'L';
            case self::XL:
                return 'XL';
            case self::XXL:
                return 'XXL';
            default:
                return self::getKey($value);
        }
    }

    /**
     * Get all descriptions
     *
     * @param  null
     * @return array
     */
    public static function getStrings(): array
    {
        $getKeys = self::getKeys();
        
        foreach ($getKeys as $key => $value) {
            if ($getKeys[$key] == self::getKey(self::S)) {
                $getKeys[$key] = "S";
            } elseif ($getKeys[$key] == self::getKey(self::M)) {
                $getKeys[$key] = "M";
            } elseif ($getKeys[$key] == self::getKey(self::L)) {
                $getKeys[$key] = "L";
            } elseif ($getKeys[$key] == self::getKey(self::XL)) {
                $getKeys[$key] = "XL";
            } elseif ($getKeys[$key] == self::getKey(self::XXL)) {
                $getKeys[$key] = "XXL";
            } else {
                $value = "Not Found";
            }
        }

        return $getKeys;
    }

    /**
     * Get all descriptions
     *
     * @param  null
     * @return array
     */
    public static function getValueByString(string $string)
    {
        switch (strtolower($string)) {
            case 's':
                return self::S;
            case 'm':
                return self::M;
            case 'l':
                return self::L;
            case 'xl':
                return self::XL;
            case 'xxl':
                return self::XXL;
            default:
                return null;
        }
    }
}
