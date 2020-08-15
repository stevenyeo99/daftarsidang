<?php

namespace App\Models;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Validator;

class StudyProgramUsers extends BaseModel implements Authenticatable {

    use AuthenticableTrait;

    protected $table = 'study_program_users';

    public $type = 'prodi';

    protected $fillable = [
        'nip', 'username', 'first_name', 'middle_name', 'last_name', 'email', 'is_admin', 'gender',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'created_at', 'updated_at',
    ];

    // for prevent from timestamp got stored on db(need do manually)
    public $timestamps = false;

    /**
     * Get the errors from model validation.
     */
    public function errors() {
        return $this->errors;
    }

    /**
     * The roles that belong to the prodi user
     */
    public function studyProgramUserRoles() {
        return $this->belongsToMany('App\Models\StudyProgramUserRoles', 'study_program_user_roles', 'study_program_user_id', 'study_program_id');
    }

    public function messages(string $key, string $keyTwo = null) {
        switch($key) {
            case 'validation':
                return [
                    'nip.required' => 'NIP dosen tidak boleh kosong.',
                    'nip.unique' => 'NIP dosen yang sama sudah ada, harap masukkan NIP yang lain.',
                    'nip.max' => 'NIP dosen tidak boleh lewat dari 10 huruf atau angka.',
                    'username.required' => 'Username dosen tidak boleh kosong.',
                    'username.unique' => 'Username yang sama sudah ada, harap masukkan username yang lain.',
                    'username.max' => 'Username tidak boleh lewat dari 50 huruf atau angka.',
                    'first_name.required' => 'Nama dosen tidak boleh kosong.',
                    'first_name.max' => 'Nama dosen tidak boleh lewat dari 50 huruf atau angka.',
                    'email.required' => 'Email dosen tidak boleh kosong.',
                    'email.unique' => 'Email dosen yang sama sudah ada, harap masukkan email yang lain.',
                    'email.max' => 'Email dosen tidak boleh lewat dari 50 huruf atau angka.',
                    'email.dosen' => 'Email dosen harus berupa email yang valid.',
                    'gender.required' => 'Jenis Kelamin dosen tidak boleh kosong.',
                    'password.required' => 'Password dosen tidak boleh kosong.',
                    'password.confirmed' => 'Konfirmasi password tidak cocok.',
                ];
            case 'success':
                switch($keyTwo) {
                    case 'create':
                        return 'Telah membuat user dosen dengan sukses!';
                    case 'assign':
                        return 'Telah menambahkan role program studi kepada user dosen dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui user dosen dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus user dosen dengan sukses!';
                }
            case 'failDelete':
                return 'User dosen ini tidak bisa dihapus karena telah dipakai ditempat lain.';
            case 'failDeleteSelf':
                return 'Tidak boleh hapus diri sendiri.';
            default:
                break;
        }
    }

    /**
     * get the validation rules
     */
    public function rules(StudyProgramUsers $studyProgramUsers) {
        if(isset($studyProgramUsers->id)) { // when update
            return [
                'nip' => 'required|max:10|unique:study_program_users,nip,'.$studyProgramUsers->id,
                'username' => 'required|max:50|unique:study_program_users,username,'.$studyProgramUsers->username,
                'first_name' => 'required|max:50',
                'email' => 'required|email|max:50|unique:study_program_users,email,'.$studyProgramUsers->id,
                'gender' => 'required',
                'password' => 'required|confirmed',
            ];
        } else {
            return [
                'nip' => 'required|max:10|unique:study_program_users,nip',
                'username' => 'required|max:50|unique:study_program_users,username',
                'first_name' => 'required|max:50',
                'email' => 'required|email|max:50|unique:study_program_users,email',
                'gender' => 'required',
                'password' => 'required|confirmed',
            ];
        }
    }
}