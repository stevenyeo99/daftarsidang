<?php

namespace App\Models;

use App\Models\BaseModel;

class Role extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that are errors from model validation.
     *
     * @var errors
     */
    protected $errors;

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    /**
     * Get the customized error message.
     */
    public function messages(string $key, string $keyTwo = null)
    {
        switch ($key) {
            case 'validation':
                return [
                    'name.required' => 'Nama peran tidak boleh kosong.',
                    'name.unique' => 'Peran dengan nama yang sama sudah ada , harap masukan nama yang lain.',
                    'name.max' => 'Nama peran tidak boleh lewat dari 45 huruf atau angka.',
                ];
            case 'success':
                switch ($keyTwo) {
                    case 'create':
                        return 'Telah membuat peran dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui peran dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus peran dengan sukses!';
                    case 'import':
                        return 'Telah mengimpor peran dengan sukses!';
                    default:
                        break;
                }
            case 'failDelete':
                    return 'Peran ini tidak bisa dihapus karena telah dipakai ditempat lain.';
            case 'failMappingExcelArray':
                    return 'Mapping dari excel terjadi error, bisa terjadi karena salah penamaan kolom, atau bisa karena nilai yang di input tidak benar.';
            default:
                break;
        }
    }

    /**
     * Get the validation rules.
     */
    public function rules(Role $role)
    {
        if (isset($role->id)) { // when update
            return [
                'name' => 'required|max:45|unique:roles,name,'.$role->id,
            ];
        } else { // when create
            return [
                'name' => 'required|max:45|unique:roles',
            ];
        }
    }
}
