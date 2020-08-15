<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Enums\CreationType;

class Hardcover extends BaseModel
{
    protected $table = 'hardcover_mahasiswa';

    public $timestamps = false;

    /**
     * hardcover kp, skripsi, tesis messages method
     */
    public function messages(string $key) {
        switch($key) {
            case 'emptyExcelRequest':
                return 'Tidak dapat ekspor melalui excel karena data masih kosong!';
            default:
                break;
        }        
    }

    /**
     * return route of hardcover module(kp, skripsi, tesis)
     */
    public function getRoute($key) {
        switch($key) {
            case CreationType::KP:
                return route('admin.hardcover_kp');
                break;
            case CreationType::Skripsi:
                return route('admin.hardcover_skripsi');
                break;
            case CreationType::Tesis:
                return route('admin.hardcover_tesis');
                break;
            default:
                break;
        }
    }
}