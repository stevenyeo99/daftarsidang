<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SessionStatus extends Enum
{
    const Taked = 0;
    const Cancelled = 1;

    /**
     * Get the description for an enum value
     *
     * @param  int  $value
     * @return string
     */
    public static function getString(int $value): string
    {
        switch ($value) {
            case self::Taked:
                return 'Sudah Pernah Sidang';
            case self::Cancelled:
                return 'Batal Sidang';
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
            if ($getKeys[$key] == self::getKey(self::Taked)) {
                $getKeys[$key] = "Sudah Pernah Sidang";
            } elseif ($getKeys[$key] == self::getKey(self::Cancelled)) {
                $getKeys[$key] = "Batal Sidang";
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
            case 'sudah pernah sidang':
                return self::Taked;
            case 'batal sidang':
                return self::Cancelled;
            default:
                return null;
        }
    }
}
