<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class WorkState extends Enum
{
    const OwnBusiness = 0;
    const Work = 1;
    const Both = 2;

    /**
     * Get the description for an enum value
     *
     * @param  int  $value
     * @return string
     */
    public static function getString(int $value): string
    {
        switch ($value) {
            case self::OwnBusiness:
                return 'Usaha Sendiri';
            case self::Work:
                return 'Bekerja';
            case self::Both:
                return 'Usaha Sendiri dan Bekerja';
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
            if ($getKeys[$key] == self::getKey(self::OwnBusiness)) {
                $getKeys[$key] = "Usaha Sendiri";
            } elseif ($getKeys[$key] == self::getKey(self::Work)) {
                $getKeys[$key] = "Bekerja";
            } elseif ($getKeys[$key] == self::getKey(self::Both)) {
                $getKeys[$key] = "Usaha Sendiri dan Bekerja";
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
            case 'usaha sendiri':
                return self::OwnBusiness;
            case 'bekerja':
                return self::Work;
            case 'usaha sendiri dan bekerja':
                return self::Both;
            default:
                return null;
        }
    }
}
