<?php

namespace App\Models;

use App\Models\BaseModel;

class Faculty extends BaseModel
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
     * Get the user that created the faculty
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    /**
     * Get the study programs owned by current faculty.
     */
    public function studyPrograms()
    {
        return $this->hasMany('App\Models\StudyProgram');
    }

    /**
     * Get the customized error message.
     */
    public function messages(string $key, string $keyTwo = null)
    {
        switch ($key) {
            case 'validation':
                return [
                    'name.required' => 'Nama fakultas tidak boleh kosong.',
                    'name.unique' => 'Fakultas dengan nama yang sama sudah ada , harap masukan nama yang lain.',
                    'name.max' => 'Nama fakultas tidak boleh lewat dari 50 huruf atau angka.',
                ];
            case 'success':
                switch ($keyTwo) {
                    case 'create':
                        return 'Telah membuat fakultas dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui fakultas dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus fakultas dengan sukses!';
                    case 'import':
                        return 'Telah mengimpor fakultas dengan sukses!';
                    default:
                        break;
                }
            case 'failDelete':
                    return 'Fakultas ini tidak bisa dihapus karena telah dipakai ditempat lain.';
            case 'failMappingExcelArray':
                    return 'Mapping dari excel terjadi error, bisa terjadi karena salah penamaan kolom, atau bisa karena nilai yang di input tidak benar.';
            default:
                break;
        }
    }

    /**
     * Get the validation rules.
     */
    public function rules(Faculty $faculty)
    {
        if (isset($faculty->id)) { // when update
            return [
                'name' => 'required|max:50|unique:faculties,name,'.$faculty->id,
            ];
        } else { // when create
            return [
                'name' => 'required|max:50|unique:faculties',
            ];
        }
    }
}
