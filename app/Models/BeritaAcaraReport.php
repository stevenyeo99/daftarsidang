<?php

namespace App\Models;

use App\Models\BaseModel;

class BeritaAcaraReport extends BaseModel
{
    protected $table = 'berita_acara_report';

    public $timestamps = false;

    protected $errors;

    /**
     * belongs to request table
     */
    public function request() {
        return $this->belongsTo('App\Models\Request', 'request_id');
    }

    /**
     * belongs to penjadwalan_sidang
     */
    public function penjadwalan_sidang() {
        return $this->belongsTo('App\Models\PenjadwalanSidang', 'penjadwalan_sidang_id');
    }

    public function ketua_penguji() {
        return $this->belongsTo('App\Models\ProdiUser', 'ketua_penguji_user_id');
    }

    public function penguji() {
        return $this->belongsTo('App\Models\ProdiUser', 'penguji_user_id');
    }

    /**
     * message for penilaian sidang
     */
    public function messages(string $key, string $keyTwo = null) {
        switch($key) {
            case 'success':
                switch($keyTwo) {
                    case 'save':
                        return 'Data berita acara telah tersimpan!';
                        break;
                    case 'permission':
                        return 'Form berita acara telah diberikan akses!';
                    default:
                        break;
                }
                break;
            case 'fail':
                switch($keyTwo) {
                    case 'formNotRightTiming':
                        return 'Mohon maaf waktu sidang masih belum dimulai!';
                        break;
                }
                break;
            default:
                return 'Silahkan melakukan perbaikan pada sistem daftar sidang!';
                break;
        }
    }
}
