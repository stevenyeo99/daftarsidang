<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ConsumptionType extends Enum
{
    const Vegetarian = 0;
    const NonVegetarian = 1;

    /**
     * Get the description for an enum value
     *
     * @param  int  $value
     * @return string
     */
    public static function getString(int $value): string
    {
        switch ($value) {
            case self::Vegetarian:
                return 'Vegetarian';
            case self::NonVegetarian:
                return 'Non Vegetarian';
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
            if ($getKeys[$key] == self::getKey(self::Vegetarian)) {
                $getKeys[$key] = "Vegetarian";
            } elseif ($getKeys[$key] == self::getKey(self::NonVegetarian)) {
                $getKeys[$key] = "Non Vegetarian";
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
            case 'vegetarian':
                return self::Vegetarian;
            case 'non vegetarian':
                return self::NonVegetarian;
            default:
                return null;
        }
    }
}
