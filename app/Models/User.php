<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Validator;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'user_roles', 'user_id', 'role_id')->withTimestamps();
    }

    /**
     * Get the faculties created by current user.
     */
    public function faculties()
    {
        return $this->hasMany('App\Models\Faculty');
    }

    /**
     * Get the study programs created by current user.
     */
    public function studyPrograms()
    {
        return $this->hasMany('App\Models\StudyProgram');
    }

    /**
     * Get the semesters created by current user.
     */
    public function semesters()
    {
        return $this->hasMany('App\Models\Semester');
    }

    /**
     * Get the hardcover kp created by current user.
     */
    public function hardcoverKP() {
        return $this->hasMany('App\Models\HardcoverKP');
    }

    /**
     * Validate data with optional custom messages.
     */
    public function validate($model, array $data, array $customMessage = [])
    {
        $validator = Validator::make($data, $this->rules($model), $customMessage);

        if ($validator->fails())
        {
            $this->errors = $validator->errors();
            return false;
        }
        return true;
    }

    /**
     * Get the errors from model validation.
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Get the customized error message.
     */
    public function messages(string $key, string $keyTwo = null)
    {
        switch ($key) {
            case 'validation':
                return [
                    'role.required' => 'Peran tidak boleh kosong.',
                    'username.required' => 'Username tidak boleh kosong.',
                    'username.unique' => 'User dengan username yang sama sudah ada , harap masukan username yang lain.',
                    'username.max' => 'Username tidak boleh lewat dari 45 huruf atau angka.',
                    'email.required' => 'Email user tidak boleh kosong.',
                    'email.unique' => 'User dengan email yang sama sudah ada , harap masukan email yang lain.',
                    'email.max' => 'Email user tidak boleh lewat dari 45 huruf atau angka.',
                    'email.email' => 'Email user harus berupa email yang valid.',
                    'password.required' => 'Password user tidak boleh kosong.',
                    // 'password.min' => 'Password tidak boleh kurang dari 6 huruf atau angka.',
                    // 'password.max' => 'Password tidak boleh lewat dari 20 huruf atau angka.',
                    'password.confirmed' => 'Konfirmasi password tidak cocok.',
                ];
            case 'success':
                switch ($keyTwo) {
                    case 'create':
                        return 'Telah membuat user dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui user dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus user dengan sukses!';
                    case 'import':
                        return 'Telah mengimpor user dengan sukses!';
                    default:
                        break;
                }
            case 'failDelete':
                    return 'User ini tidak bisa dihapus karena telah dipakai ditempat lain.';
            case 'failDeleteSelf':
                    return 'Tidak boleh hapus diri sendiri.';
            case 'failMappingExcelArray':
                    return 'Mapping dari excel terjadi error, bisa terjadi karena salah penamaan kolom, atau bisa karena nilai yang di input tidak benar.';
            default:
                break;
        }
    }

    /**
     * Get the validation rules.
     */
    public function rules(User $user)
    {
        if (isset($user->id)) { // when update
            return [
                'role' => 'required',
                'username' => 'required|max:45|unique:users,username,'.$user->id,
                'email' => 'required|email|max:45|unique:users,email,'.$user->id,
                'password' => 'required|confirmed',
            ];
        } else { // when create
            return [
                'role' => 'required',
                'username' => 'required|max:45|unique:users',
                'email' => 'required|email|max:45|unique:users',
                'password' => 'required|confirmed',
            ];
        }
    }
}
