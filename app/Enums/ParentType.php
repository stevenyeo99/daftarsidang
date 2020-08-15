<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ParentType extends Enum
{
    const Father = 0;
    const Mother = 1;

    /**
     * Get the description for an enum value
     *
     * @param  int  $value
     * @return string
     */
    public static function getString(int $value): string
    {
        switch ($value) {
            case self::Father:
                return 'Ayah';
            case self::Mother:
                return 'Ibu';
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
            if ($getKeys[$key] == self::getKey(self::Father)) {
                $getKeys[$key] = "Ayah";
            } elseif ($getKeys[$key] == self::getKey(self::Mother)) {
                $getKeys[$key] = "Ibu";
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
            case 'ayah':
                return self::Father;
            case 'ibu':
                return self::Mother;
            default:
                return null;
        }
    }
}
