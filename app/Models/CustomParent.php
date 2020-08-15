<?php

namespace App\Models;

use App\Models\BaseModel;

class CustomParent extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type',
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
     * Get the company that owns the current parent
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    /**
     * Get the student that born by current parent
     */
    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'student_id');
    }

    /**
     * Get the customized error message.
     */
    public function messages(string $key, string $keyTwo = null)
    {
        switch ($key) {
            case 'validation':
                return [
                    'name.required' => 'Nama Orang tua tidak boleh kosong.',
                    'name.max' => 'Nama Orang tua tidak boleh lewat dari 50 huruf atau angka.',
                    'type.required' => 'Tipe Orang tua tidak boleh kosong.',
                    'type.numeric' => 'Tipe Orang tua harus berupa angka.',
                    'student.required' => 'Mahasiswa tidak boleh kosong.',
                ];
            case 'success':
                switch ($keyTwo) {
                    case 'create':
                        return 'Telah membuat data dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui data dengan sukses!';
                    case 'updateFather':
                        return 'Telah memperbarui data ayah dengan sukses!';
                    case 'updateMother':
                        return 'Telah memperbarui data ibu dengan sukses!';
                    case 'updateBothParent':
                        return 'Telah memperbarui data ayah dan ibu dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus data dengan sukses!';
                    case 'import':
                        return 'Telah mengimpor data dengan sukses!';
                    default:
                        break;
                }
            case 'failDelete':
                    return 'Data ini tidak bisa dihapus karena telah dipakai ditempat lain.';
            case 'failMappingExcelArray':
                    return 'Mapping dari excel terjadi error, bisa terjadi karena salah penamaan kolom, atau bisa karena nilai yang di input tidak benar.';
            default:
                break;
        }
    }

    /**
     * Get the validation rules.
     */
    public function rules(Parent $parent)
    {
        if (isset($parent->id)) { // when update
            return [
                'name' => 'required|max:50',
                'type' => 'required|numeric',
                'student' => 'required',
            ];
        } else { // when create
            return [
                'name' => 'required|max:50',
                'type' => 'required|numeric',
                'student' => 'required',
            ];
        }
    }
}