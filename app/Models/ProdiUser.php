<?php

namespace App\Models;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Validator;

class ProdiUser extends BaseModel implements Authenticatable
{
    use AuthenticableTrait;

    protected $table = 'prodi_user';
    
    public $type = 'prodi';
    
    // mass assignment
    protected $fillable = [
        'username', 'email', 'is_admin', 'password', 'study_programs_id', 'initial_name',
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
     * has one study program
     */
    public function studyprogram() {
        return $this->belongsTo('App\Models\StudyProgram', 'study_programs_id');
    }

    /**
     * Get the errors from model validation.
     */
    public function errors() {
        return $this->errors;
    }

    /**
     * get the customise messages
     */
    public function messages(string $key, string $keyTwo = null) {
        switch($key) {
            case 'validation':
                return [
                    'username.required' => 'Username tidak boleh kosong.',
                    'username.unique' => 'User dengan username yang sama sudah ada, harap masukan username yang lain.',
                    'username.max' => 'Username tidak boleh lewat dari 50 huruf atau angka.',
                    'email.required' => 'Email user dosen tidak boleh kosong.',
                    'email.unique' => 'User dengan email yang sama sudah ada, harap masukan email yang lain.',
                    'email.max' => 'Email user tidak boleh lewat dari 50 huruf atau angka.',
                    'email.email' => 'Email user harus berupa email yang valid.',
                    'password.required' => 'Password user tidak boleh kosong.',
                    'password.confirmed' => 'Konfirmasi password tidak cocok',
                ];
            case 'success':
                switch($keyTwo) {
                    case 'create':
                        return 'Telah membuat user dosen dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui user dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus user dengan sukses!';
                    default:
                        break;
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
    public function rules(ProdiUser $prodiUser) {
        if(isset($prodiUser->id)) { // when update
            return [
                'username' => 'required|max:50|unique:prodi_user,username,'.$prodiUser->id,
                'initial_name' => 'required|max:50',
                'email' => 'required|email|max:50|unique:prodi_user,email,'.$prodiUser->id,
                'password' => 'required|confirmed',
            ];            
        } else {
            return [
                'username' => 'required|max:50|unique:prodi_user,username',
                'initial_name' => 'required|max:50',
                'email' => 'required|email|max:50|unique:prodi_user,email',
                'password' => 'required|confirmed',
            ];
        }
    }
}
