<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CreationType extends Enum
{
    const KP = 0;
    const Skripsi = 1;
    const Tesis = 2;

    /**
     * Get the description for an enum value
     *
     * @param  int  $value
     * @return string
     */
    public static function getString(int $value): string
    {
        switch ($value) {
            case self::KP:
                return 'KP';
            case self::Skripsi:
                return 'Skripsi';
            case self::Tesis:
                return 'Tesis';
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
            if ($getKeys[$key] == self::getKey(self::KP)) {
                $getKeys[$key] = "KP";
            } elseif ($getKeys[$key] == self::getKey(self::Skripsi)) {
                $getKeys[$key] = "Skripsi";
            } elseif ($getKeys[$key] == self::getKey(self::Tesis)) {
                $getKeys[$key] = "Tesis";
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
    public static function getStringsExcept(int $exceptValue): array
    {
        $getKeys = self::getStrings();

        unset($getKeys[$exceptValue]);

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
            case 'kp':
                return self::KP;
            case 'skripsi':
                return self::Skripsi;
            case 'tesis':
                return self::Tesis;
            default:
                return null;
        }
    }
}
