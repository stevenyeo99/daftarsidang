<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class RequestStatus extends Enum
{
    const Draft = 0;
    const Verification = 1;
    const Accept = 2;
    const Reject = 3;
    const RejectBySistem = 4;
    // prodi
    const AcceptProdi = 5;
    const RejectProdi = 6;
    // finance
    const AcceptFinance = 7;
    const RejectFinance = 8;

    /**
     * Get the description for an enum value
     *
     * @param  int  $value
     * @return string
     */
    public static function getString(int $value): string
    {
        switch ($value) {
            case self::Draft:
                return 'Draft';
            case self::Verification:
                return 'Sedang Verifikasi';
            case self::AcceptFinance:
                return 'Diterima oleh finance';
            case self::RejectFinance:
                return 'Ditolak oleh finance';
            case self::Accept:
                return 'Diterima oleh baak';
            case self::Reject:
                return 'Ditolak oleh baak';
            case self::RejectBySistem:
                return 'Ditolak oleh sistem';
            case self::AcceptProdi:
                return 'Diterima oleh prodi';
            case self::RejectProdi:
                return 'Ditolak oleh prodi';
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
            if ($getKeys[$key] == self::getKey(self::Draft)) {
                $getKeys[$key] = "Draft";
            } elseif ($getKeys[$key] == self::getKey(self::Verification)) {
                $getKeys[$key] = "Sedang Verifikasi";
            } else if($getKeys[$key] == self::getKey(self::AcceptFinance)) {
                $getKeys[$key] = "Diterima oleh finance";
            } else if($getKeys[$key] == self::getKey(self::RejectFinance)) {
                $getKeys[$key] = "Ditolak oleh finance";
            } elseif ($getKeys[$key] == self::getKey(self::Accept)) {
                $getKeys[$key] = "Diterima oleh baak";
            } elseif ($getKeys[$key] == self::getKey(self::Reject)) {
                $getKeys[$key] = "Ditolak oleh baak";
            } elseif ($getKeys[$key] == self::getKey(self::RejectBySistem)) {
                $getKeys[$key] = "Ditolak oleh sistem";
            } else if($getKeys[$key] == self::getKey(self::AcceptProdi)) {
                $getKeys[$key] = "Diterima oleh prodi";
            } else if($getKeys[$key] == self::getKey(self::RejectProdi)) {
                $getKeys[$key] = "Ditolak oleh prodi";
            } else {
                $value = "Not Found";
            }
        }

        return $getKeys;
    }

    public static function getFinanceStrings(): array
    {
        $getKeys = self::getKeys();

        foreach ($getKeys as $key => $value) {
            if ($getKeys[$key] == self::getKey(self::Verification)) {
                $getKeys[$key] = "Sedang Verifikasi";
            } else if($getKeys[$key] == self::getKey(self::AcceptFinance)) {
                $getKeys[$key] = "Diterima oleh finance";
            } else if($getKeys[$key] == self::getKey(self::RejectFinance)) {
                $getKeys[$key] = "Ditolak oleh finance";
            } else {
                unset($getKeys[$key]);
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
            case 'draft':
                return self::Draft;
            case 'sedang verifikasi':
                return self::Verification;
            case 'diterima oleh finance':
                return self::AcceptFinance;
            case 'ditolak oleh finance':
                return self::RejectFinance;
            case 'diterima oleh baak':
                return self::Accept;
            case 'ditolak oleh baak':
                return self::Reject;
            case 'ditolak oleh sistem':
                return self::RejectBySistem;
            case 'diterima oleh prodi':
                return self::AcceptProdi;
            case 'ditolak oleh prodi':
                return self::RejectProdi;
            default:
                return null;
        }
    }
}
