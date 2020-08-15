<?php

namespace App\Models;

use App\Models\BaseModel;

class Company extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'field', 'address', 'phone_number'
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
     * Get the students owned by current company.
     */
    public function students()
    {
        return $this->hasMany('App\Models\Student');
    }

    /**
     * Get the student parents owned by current company.
     */
    public function studentParents()
    {
        return $this->hasMany('App\Models\Parent');
    }

    /**
     * Get the customized error message.
     */
    public function messages(string $key, string $keyTwo = null)
    {
        switch ($key) {
            case 'validation':
                return [
                    'name.required' => 'Nama perusahaan tidak boleh kosong.',
                    'name.unique' => 'Perusahaan dengan nama yang sama sudah ada , harap masukan nama yang lain.',
                    'name.max' => 'Nama perusahaan tidak boleh lewat dari 50 huruf atau angka.',
                    'field.required' => 'Bidang usaha perusahaan tidak boleh kosong.',
                    'field.max' => 'Bidang usaha perusahaan tidak boleh lewat dari 50 huruf atau angka.',
                    'address.required' => 'Alamat perusahaan tidak boleh kosong.',
                    'address.max' => 'Alamat perusahaan tidak boleh lewat dari 255 huruf atau angka.',
                    'phone_number.numeric' => 'Nomor telepon perusahaan harus berupa angka.',
                    'phone_number.digits_between' => 'Panjangnya nomor telepon perusahaan tidak boleh lebih dari 20 dan pendek dari 10.',
                    'phone_number.unique' => 'Perusahaan dengan nomor telepon yang sama sudah ada , harap masukan nomor telepon yang lain.',
                ];
            case 'success':
                switch ($keyTwo) {
                    case 'create':
                        return 'Telah membuat perusahaan dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui perusahaan dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus perusahaan dengan sukses!';
                    case 'import':
                        return 'Telah mengimpor perusahaan dengan sukses!';
                    default:
                        break;
                }
            case 'failDelete':
                    return 'Perusahaan ini tidak bisa dihapus karena telah dipakai ditempat lain.';
            case 'failMappingExcelArray':
                    return 'Mapping dari excel terjadi error, bisa terjadi karena salah penamaan kolom, atau bisa karena nilai yang di input tidak benar.';
            case 'companyExistingIssue':
                    return 'Nama dari perusahaan tersebut sudah ada di database, akan menggunakan data yang sudah ada di sistem.';
            default:
                break;
        }
    }

    /**
     * Get the validation rules.
     */
    public function rules(Company $company)
    {
        if (isset($company->id)) { // when update
            return [
                'name' => 'required|max:50|unique:companies,name,'.$company->id,
                'field' => 'required|max:50',
                'address' => 'required|max:255',
                'phone_number' => 'nullable|numeric|digits_between:10,20|unique:companies,phone_number,'.$company->id,
            ];
        } else { // when create
            return [
                'name' => 'required|max:50|unique:companies',
                'field' => 'required|max:50',
                'address' => 'required|max:255',
                'phone_number' => 'nullable|numeric|digits_between:10,20|unique:companies',
            ];
        }
    }
}