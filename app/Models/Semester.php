<?php

namespace App\Models;

use App\Models\BaseModel;

class Semester extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'year', 'is_active', 'text'
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
     * Get the user that created the semester
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    /**
     * Get the customized error message.
     */
    public function messages(string $key, string $keyTwo = null)
    {
        switch ($key) {
            case 'validation':
                return [
                    'type.required' => 'Tipe semester tidak boleh kosong.',
                    'year.required' => 'Tahun semester tidak boleh kosong.',
                    'year.numeric' => 'Tahun semester harus berupa angka.',
                    'year.date_format' => 'Tahun semester harus berupa tahun yang valid.',
                    'year.digits' => 'Tahun semester harus berupa tahun yang valid.',
                    'year.max' => 'Tahun semester tidak boleh lebih dari 2100 tahun.',
                    'year.min' => 'Tahun semester tidak boleh kurang dari 2015 tahun.',
                    'is_active.required' => 'Status semester tidak boleh kosong.',
                ];
            case 'success':
                switch ($keyTwo) {
                    case 'create':
                        return 'Telah membuat semester dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui semester dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus semester dengan sukses!';
                    case 'import':
                        return 'Telah mengimpor semester dengan sukses!';
                    default:
                        break;
                }
            case 'failDelete':
                    return 'Semester ini tidak bisa dihapus karena telah dipakai ditempat lain.';
            case 'failMappingExcelArray':
                    return 'Mapping dari excel terjadi error, bisa terjadi karena salah penamaan kolom, atau bisa karena nilai yang di input tidak benar.';
            case 'failStoreDuplicateException':
                    return 'Semester dengan tipe yang sama sudah ada, harap masukan data yang lain.';
            default:
                break;
        }
    }

    /**
     * Get the validation rules.
     */
    public function rules(Semester $semester)
    {
        if (isset($semester->id)) { // when update
            return [
                'type' => 'required',
                'year' => 'numeric|date_format:Y|digits:4|max:2100|min:2015',
                'is_active' => 'required',
            ];
        } else { // when create
            return [
                'type' => 'required',
                'year' => 'numeric|date_format:Y|digits:4|max:2100|min:2015',
                'is_active' => 'required',
            ];
        }
    }
}