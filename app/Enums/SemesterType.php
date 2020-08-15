<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SemesterType extends Enum
{
    const Even = 0; // genap
    const Odd = 1; // ganjil

    /**
     * Get the description for an enum value
     *
     * @param  int  $value
     * @return string
     */
    public static function getString(int $value): string
    {
        switch ($value) {
            case self::Even:
                return 'Genap';
            case self::Odd:
                return 'Ganjil';
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
            if ($getKeys[$key] == self::getKey(self::Even)) {
                $getKeys[$key] = "Genap";
            } elseif ($getKeys[$key] == self::getKey(self::Odd)) {
                $getKeys[$key] = "Ganjil";
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
            case 'genap':
                return self::Even;
            case 'ganjil':
                return self::Odd;
            default:
                return null;
        }
    }
}
