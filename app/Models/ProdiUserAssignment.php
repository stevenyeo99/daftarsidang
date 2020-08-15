<?php

namespace App\Models;

use App\Models\BaseModel;

class ProdiUserAssignment extends BaseModel
{
    protected $table = 'prodi_user_assignment';

    protected $fillable = ['prodi_user_id', 'study_program_id'];

    public $timestamps = false;

    /**
     * messages to user
     */
    public function messages(string $key, string $keytwo = null) {
        switch($key) {
            case 'success':
                switch($keytwo) {
                    case 'assign':
                        return "Telah berhasil mengolongkan user ke prodi ini";
                    case 'reassign':
                        return "Telah berhasil mengubah data user yang tergolong dalam prodi ini";
                    case 'delete':
                        return "Telah berhasil menghapus data user yang tergolong dalam prodi ini";
                }
            case 'failDelete':
                return 'User dosen yang terlibat pada prodi ini tidak dapat dihapus dikarenakan sedang dipakai di tempat lain.';
            default:
                break;
        }
    }
}
