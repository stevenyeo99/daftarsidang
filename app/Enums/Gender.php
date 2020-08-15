<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Gender extends Enum
{
    const Male = 0;
    const Female = 1;

    /**
     * Get the description for an enum value
     *
     * @param  int  $value
     * @return string
     */
    public static function getString(int $value): string
    {
        switch ($value) {
            case self::Male:
                return 'Pria';
            case self::Female:
                return 'Wanita';
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
            if ($getKeys[$key] == self::getKey(self::Male)) {
                $getKeys[$key] = "Pria";
            } elseif ($getKeys[$key] == self::getKey(self::Female)) {
                $getKeys[$key] = "Wanita";
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
            case 'pria':
                return self::Male;
            case 'wanita':
                return self::Female;
            default:
                return null;
        }
    }
}
