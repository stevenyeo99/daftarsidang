<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AttachmentType extends Enum
{
    const KTP = 0;
    const KartuKeluarga = 1;
    const IjazahSMA = 2;
    const IjazahS1 = 3;
    const AktaKelahiran = 4;

    /**
     * Get the description for an enum value
     *
     * @param  int  $value
     * @return string
     */
    public static function getString(int $value): string
    {
        switch ($value) {
            case self::KTP:
                return 'KTP';
            case self::KartuKeluarga:
                return 'Kartu Keluarga';
            case self::IjazahSMA:
                return 'Ijazah SMA';
            case self::IjazahS1:
                return 'Ijazah S1';
            case self::AktaKelahiran:
                return 'Akta Kelahiran';
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
            if ($getKeys[$key] == self::getKey(self::KTP)) {
                $getKeys[$key] = "KTP";
            } elseif ($getKeys[$key] == self::getKey(self::KartuKeluarga)) {
                $getKeys[$key] = "Kartu Keluarga";
            } elseif ($getKeys[$key] == self::getKey(self::IjazahSMA)) {
                $getKeys[$key] = "Ijazah SMA";
            } elseif ($getKeys[$key] == self::getKey(self::IjazahS1)) {
                $getKeys[$key] = "Ijazah S1";
            } elseif ($getKeys[$key] == self::getKey(self::AktaKelahiran)) {
                $getKeys[$key] = "Akta Kelahiran";
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
            case 'ktp':
                return self::KTP;
            case 'kartu keluarga':
                return self::KartuKeluarga;
            case 'ijazah sma':
                return self::IjazahSMA;
            case 'ijazah s1':
                return self::IjazahS1;
            case 'akta kelahiran':
                return self::AktaKelahiran;
            default:
                return null;
        }
    }
}
