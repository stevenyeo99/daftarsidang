<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class RequestAttachmentType extends Enum
{
    // kp use kartu bimbingan, lembar persetujuan, turnitin
    // skripsi use semua HAHA

    const KARTU_BIMBINGAN = 0;
    const LEMBAR_PERSETUJUAN = 1;
    const TOEIC_OFFICIAL = 2;
    const ANTI_PLAGIAT = 3;
    const ABSTRACT_UCLC = 4;
    const FOTO_METEOR = 5;
    const LEMBAR_TURNITIN = 6;

    /**
     * Get the description for an enum value
     *
     * @param  int  $value
     * @return string
     */
    public static function getString(int $value): string
    {
        switch($value) {
            case self::KARTU_BIMBINGAN:
                return 'Kartu Bimbingan';
            case self::LEMBAR_PERSETUJUAN:
                return 'Lembar Persetujuan';
            case self::TOEIC_OFFICIAL:
                return 'Toeic Official';
            case self::ANTI_PLAGIAT:
                return 'Anti Plagiat';
            case self::ABSTRACT_UCLC:
                return 'Abstract UCLC';
            case self::FOTO_METEOR:
                return 'Foto Meteor';
            case self::LEMBAR_TURNITIN:
                return 'Lembar Turnitin';
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

        foreach($getKeys as $key => $value) {
            if($getKeys[$key] == self::getKey(self::KARTU_BIMBINGAN)) {
                $getKeys[$key] = 'Kartu Bimbingan';
            } else if($getKeys[$key] == self::getKey(self::LEMBAR_PERSETUJUAN)) {
                $getKeys[$key] = 'Lembar Persetujuan';
            } else if($getKeys[$key] == self::getKey(self::TOEIC_OFFICIAL)) {
                $getKeys[$key] = 'Toeic Official';
            } else if($getKeys[$key] == self::getKey(self::ANTI_PLAGIAT)) {
                $getKeys[$key] = 'Anti Plagiat';
            } else if($getKeys[$key] == self::getKey(self::ABSTRACT_UCLC)) {
                $getKeys[$key] = 'Abstract UCLC';
            } else if($getKeys[$key] == self::getKey(self::FOTO_METEOR)) {
                $getKeys[$key] = 'Foto Meteor';
            } else if($getKeys[$key] == self::getKey(self::LEMBAR_TURNITIN)) {
                $getKeys[$key] = 'Lembar Turnitin';
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
    public static function getValueByString(string $string) {
        switch(strtolower($string)) {
            case 'kartu bimbingan':
                return self::KARTU_BIMBINGAN;
            case 'lembar persetujuan':
                return self::LEMBAR_PERSETUJUAN;
            case 'toeic official':
                return self::TOEIC_OFFICIAL;
            case 'anti plagiat':
                return self::ANTI_PLAGIAT;
            case 'abstract uclc':
                return self::ABSTRACT_UCLC;
            case 'foto meteor':
                return self::FOTO_METEOR;
            case 'lembar turnitin':
                return self::LEMBAR_TURNITIN;
            default:
                return null;
        }
    }
}