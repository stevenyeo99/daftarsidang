<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Validator;

class PenjadwalanSidang extends BaseModel
{
    // status 0 validate by prodi, 1 prodi jadwal, 2 prodi re-jadwal

    protected $table = 'penjadwalan_sidang';

    protected $fillable = ['tanggal_sidang', 'dosen_pembimbing_id', 'dosen_penguji_id', 'penjadwalan_by', 
    'ruangan_sidang', 'penempatan_by', 'sidang_type'];

    public $timestamps = false;

    protected $errors;

    public $needBackup;

    // for identify rules when prodi use or baak pic.
    public $position;

    // belongs to request
    public function request() 
    {
        return $this->belongsTo('App\Models\Request', 'request_id');
    }

    // belongs to prodi user admin
    public function prodiAdmin() {
        return $this->belongsTo('App\Models\ProdiUser', 'penjadwalan_by');
    }

    // belongs to ruangan sidang
    public function ruangan_sidang() {
        return $this->belongsTo('App\Models\RuanganSidang', 'ruangan_sidang_id');
    }
    
    // belong to prodi user dospem
    public $dospem;

    // belong to prodi user dospenguji
    public $dospenguji;

    // belong to prodi user dospem bak
    public $dospemBAK;

    // old attribute for sending notification that they will be replacing
    public $dospemOld;

    public $dospengujiOld;

    public $dospemBAKOld;

    public $history;

    /**
     * messages display to user
     */
    public function messages(string $key, string $keyTwo = null) {
        switch($key) {
            case 'validation':
                return [
                    'dosen_penguji_id' => 'Harap pilih dosen penguji.',
                    'tanggal_sidang' => 'Harap isi tangal-waktu sidang',
                    'dosen_pembimbing_backup_id.required' => 'Harap pilih dosen pembimbing back up jika diperlukan backup.',
                ];
            case 'success':
                switch($keyTwo) {
                    case 'schedule':
                        return 'Telah melakukan penjadwalan sidang dengan sukses!';
                    case 'reschedule':
                        return 'Telah ulang melakukan penjadwalan sidang dengan sukses!';
                    case 'schedule-place':
                        return 'Telah mengatur ruangan sidang dengan sukses, jangan lupa kirim surat undangan!';
                    case 'send-invitation':
                        return 'Telah mengirim surat undangan dengan sukses!';
                    case 'resend-invitation':
                        return 'Telah ulang mengirim surat undangan dengan sukses!';
                    default: 
                        break;
                }
            case 'failScheduled':
                return 'Telah gagal melakukan penjadwalan butuh perbaikan pada sistem :P';
            case 'failChooseDosenPenguji':
                return 'Telah gagal memilih dosen penguji dikarenakan tanggal-waktu pengujian sudah dipakai.';
            case 'failChooseRuangan':
                return 'Telah gagal memilih ruangan sidang dikarenakan tanggal-waktu pengujian sudah digunakan.';
        }
    }

    /**
     * business rules saat posting data
     * 0 prodi, 1 baak
     */
    public function rules(PenjadwalanSidang $jadwal) {
        if($jadwal->needBackup == 'YES' && $jadwal->position == 0) {
            return [
                'dosen_penguji_id' => 'required',
                'tanggal_sidang' => 'required',
                'dosen_pembimbing_backup_id' => 'required',
            ];
        } else if($jadwal->needBackup == 'NO' && $jadwal->position == 0) {
            return [
                'dosen_penguji_id' => 'required',
                'tanggal_sidang' => 'required',
            ];
        } else { // baak do update ruangan sidang
            return [
                'ruangan_sidang' => 'required',
                'penempatan_by' => 'required',
            ];
        }
    }
}
