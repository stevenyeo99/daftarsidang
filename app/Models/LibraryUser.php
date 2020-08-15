<?php

namespace App\Models;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Validator;

class LibraryUser extends BaseModel implements Authenticatable
{
    use AuthenticableTrait;

    protected $table = 'library_user';

    public $type = 'library';

    // mass assignment
    protected $fillable = [
        'username', 'email', 'is_admin', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'created_at', 'updated_at',
    ];

    public $timestamps = false;

    /**
     * Validate data with optional custom message.
     */
    public function validate($model, array $data, array $customMessage = []) {
        $validator = Validator::make($data, $this->rules($model), $customMessage);

        if($validator->fails()) {
            $this->errors = $validator->errors();
            return false;
        }
        return true;
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
                        return 'Telah membuat user perpus dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui user dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus user dengan sukses!';
                    default:
                        break;
                }
            case 'failDelete':
                return 'User perpus ini tidak bisa dihapus karena telah dipakai ditempat lain.';
            case 'failDeleteSelf':
                return 'Tidak boleh hapus diri sendiri.';
            default:
                break;
        }
    }

     /**
     * get the validation rules
     */
    public function rules(LibraryUser $libUser) {
        if(isset($libUser->id)) { // when update
            return [
                'username' => 'required|max:50|unique:library_user,username,'.$libUser->id,
                'email' => 'required|email|max:50|unique:library_user,email,'.$libUser->id,
                'password' => 'required|confirmed',
            ];            
        } else {
            return [
                'username' => 'required|max:50|unique:library_user',
                'email' => 'required|email|max:50|unique:library_user',
                'password' => 'required|confirmed',
            ];
        }
    }
}
