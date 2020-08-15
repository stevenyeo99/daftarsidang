<?php

namespace App\Models;

use App\Models\BaseModel;

class HardcoverKP extends BaseModel
{
    protected $table = 'hardcover_kp';

    /**
     * mass attribute
     */
    protected $fillable = [
        'nama_mahasiswa', 'npm', 'prodi', 'nama_pembimbing', 'tanggal_submit', 'tanggal_validasi'
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    // for preventing from timestamp inserted while doing insert or updating
    public $timestamps = false;

    protected $errors;

    public function user() {
        return $this->belongsTo('App\Models\User', 'create_user_id');
    }

    public function messages(string $key, string $keyTwo = null) {
        switch($key) {
            case 'validation':
                return [
                    'nama_mahasiswa.required' => 'nama_mahasiswa.required',
                    'nama_mahasiswa.max' => 'nama_mahasiswa.max',
                    'npm.required' => 'npm.required',
                    'npm.numeric' => 'npm.numeric',
                    'npm.min' => 'npm.min',
                    'prodi.required' => 'prodi.required',
                    'prodi.max' => 'prodi.max',
                    'nama_pembimbing.required' => 'nama_pembimbing.required',
                    'nama_pembimbing.max' => 'nama_pembimbing.max',
                    'tanggal_submit.required' => 'tanggal_submit.required',
                    'tanggal_submit.date_format' => 'tanggal_submit.date_format',
                    'tanggal_validasi.required' => 'tanggal_validasi.required',
                    'tanggal_validasi.date_format' => 'tanggal_validasi.date_format',
                ];
            case 'success':
                switch($keyTwo) {
                    case 'upload':
                        return 'Telah melakukan upload data hardcover kp dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus data Hardcover KP dengan sukses!';
                    default:
                        break;
                }
            case 'failDelete':
                return 'Data hardcover kp gagal dihapus';
            case 'emptyExcelRequest':
                return 'Tidak dapat ekspor melalui excel karena data masih kosong!';
            case 'failMappingExcelArray':
                return 'Mapping dari excel terjadi error, bisa terjadi karena salah penamaan kolom, atau bisa karena nilai yang di input tidak benar.';
            default:
                break;
        }
    }

    public function rules(HardcoverKP $hcKP) {
        return [
            'nama_mahasiswa' => 'required|max:255',
            'npm' => 'required|numeric|min:7',
            'prodi' => 'required|max:255',
            'nama_pembimbing' => 'required|max:255',
            'tanggal_submit' => 'required|date_format:Y-m-d',
            'tanggal_validasi' => 'required|date_format:Y-m-d',
        ];
    }
}
