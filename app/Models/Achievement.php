<?php

namespace App\Models;

use App\Models\BaseModel;

class Achievement extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'place', 'year'
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
     * Get the student that owned the attachment
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
                    'student.required' => 'Mahasiswa tidak boleh kosong.',
                    'name.required' => 'Nama prestasi tidak boleh kosong.',
                    'name.unique' => 'Prestasi dengan nama yang sama sudah ada , harap masukan nama yang lain.',
                    'name.max' => 'Nama prestasi tidak boleh lewat dari 50 huruf atau angka.',
                    'place.max' => 'Tempat prestasi tidak boleh lewat dari 255 huruf atau angka.',
                    'year.numeric' => 'Tahun prestasi harus berupa angka.',
                    'year.date_format' => 'Tahun prestasi harus berupa tahun yang valid.',
                    'year.digits' => 'Tahun prestasi harus berupa tahun yang valid.',
                    'year.max' => 'Tahun prestasi tidak boleh lebih dari 2100 tahun.',
                ];
            case 'success':
                switch ($keyTwo) {
                    case 'create':
                        return 'Telah membuat prestasi dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui prestasi dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus prestasi dengan sukses!';
                    case 'import':
                        return 'Telah mengimpor prestasi dengan sukses!';
                    default:
                        break;
                }
            case 'failDelete':
                    return 'Prestasi ini tidak bisa dihapus karena telah dipakai ditempat lain.';
            case 'failMappingExcelArray':
                    return 'Mapping dari excel terjadi error, bisa terjadi karena salah penamaan kolom, atau bisa karena nilai yang di input tidak benar.';
            case 'failStoreLimitException':
                    return 'Seorang mahasiswa hanya diijinkan untuk menambahkan 10 prestasi saja.';
            default:
                break;
        }
    }

    /**
     * Get the validation rules.
     */
    public function rules(Achievement $achievement)
    {
        if (isset($achievement->id)) { // when update
            return [
                'name' => 'required|max:50|unique:achievements,name,'.$achievement->id,
                'place' => 'max:255',
                'year' => 'numeric|date_format:Y|digits:4|max:2100',
                'student' => 'required',
            ];
        } else { // when create
            return [
                'name' => 'required|max:50|unique:achievements',
                'place' => 'max:255',
                'year' => 'numeric|date_format:Y|digits:4|max:2100',
                'student' => 'required',
            ];
        }
    }
}
