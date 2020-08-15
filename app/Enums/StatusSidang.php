<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class StatusSidang extends Enum
{
    const Waiting = 0;
    const Done = 1;
    const Cancel =  2;

    /**
     * get description for enum value
     */
    public static function getString(int $value): string
    {
        switch($value) {
            case self::Waiting:
                return 'Belum Sidang';
            case self::Done:
                return 'Sudah Sidang';
            case self::Cancel:
                return 'Batal Sidang';
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
            if($getKeys[$key] == self::getKey(self::Waiting)) {
                $getKeys[$key] = "Belum Sidang";
            } else if($getKeys[$key] == self::getKey(self::Done)) {
                $getKeys[$key] = "Sudah Sidang";
            } else if($getKeys[$key] == self::getKey(self::Cancel)) {
                $getKeys[$key] = "Batal Sidang";
            } else {
                $getKeys[$key] = "Batal Sidang";
            }
        }

        return $getKeys;
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
        switch (strtolower($string)) {
            case 'waiting':
                return self::Waiting;
            case 'done':
                return self::Done;
            case 'cancel':
                return self::Cancel;
            default:
                return null;
        }
    }
}