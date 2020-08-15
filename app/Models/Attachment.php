<?php

namespace App\Models;

use App\Models\BaseModel;

class Attachment extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'file_name'
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
                    'name.required' => 'Nama lampiran tidak boleh kosong.',
                    'name.unique' => 'Lampiran dengan nama yang sama sudah ada , harap masukan nama yang lain.',
                    'name.max' => 'Nama lampiran tidak boleh lewat dari 255 huruf atau angka.',
                    'type.required' => 'Tipe lampiran tidak boleh kosong.',
                    'type.numeric' => 'Tipe lampiran harus berupa angka.',
                    'file_name.required' => 'Nama file tidak boleh kosong.',
                    'file_name.max' => 'Nama file tidak boleh lewat dari 255 huruf atau angka.',
                    'fileUploader.required' => 'File tidak boleh kosong.',
                    'fileUploader.image' => 'File harus berupa sebuah image.',
                    'fileUploader.mimes' => 'Format file harus berupa jpeg, png, jpg, pdf atau svg.',
                    'fileUploader.max' => 'Ukuran file tidak boleh lebih besar dari 1MB.',
                    'ktpUploader.required' => 'File KTP tidak boleh kosong.',
                    'ktpUploader.image' => 'File KTP harus berupa sebuah image.',
                    'ktpUploader.mimes' => 'Format file KTP harus berupa jpeg, png, jpg, pdf atau svg.',
                    'ktpUploader.max' => 'Ukuran file KTP tidak boleh lebih besar dari 1MB.',
                    'kartuKeluargaUploader.required' => 'File Kartu Keluarga tidak boleh kosong.',
                    'kartuKeluargaUploader.image' => 'File Kartu Keluarga harus berupa sebuah image.',
                    'kartuKeluargaUploader.mimes' => 'Format file Kartu Keluarga harus berupa jpeg, png, jpg, pdf atau svg.',
                    'kartuKeluargaUploader.max' => 'Ukuran file Kartu Keluarga tidak boleh lebih besar dari 1MB.',
                    'ijazahSMAUploader.required' => 'File Ijazah SMA tidak boleh kosong.',
                    'ijazahSMAUploader.image' => 'File Ijazah SMA harus berupa sebuah image.',
                    'ijazahSMAUploader.mimes' => 'Format file Ijazah SMA harus berupa jpeg, png, jpg, pdf atau svg.',
                    'ijazahSMAUploader.max' => 'Ukuran file Ijazah SMA tidak boleh lebih besar dari 1MB.',
                    'ijazahS1Uploader.required' => 'File Ijazah S1 tidak boleh kosong.',
                    'ijazahS1Uploader.image' => 'File Ijazah S1 harus berupa sebuah image.',
                    'ijazahS1Uploader.mimes' => 'Format file Ijazah S1 harus berupa jpeg, png, jpg, pdf atau svg.',
                    'ijazahS1Uploader.max' => 'Ukuran file Ijazah S1 tidak boleh lebih besar dari 1MB.',
                ];
            case 'success':
                switch ($keyTwo) {
                    case 'create':
                        return 'Telah membuat lampiran dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui lampiran dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus lampiran dengan sukses!';
                    case 'import':
                        return 'Telah mengimpor lampiran dengan sukses!';
                    default:
                        break;
                }
            case 'failDelete':
                    return 'Lampiran ini tidak bisa dihapus karena telah dipakai ditempat lain.';
            case 'failMappingExcelArray':
                    return 'Mapping dari excel terjadi error, bisa terjadi karena salah penamaan kolom, atau bisa karena nilai yang di input tidak benar.';
            case 'fileNotFoundOnDirectory':
                    return 'File sebelumnya tidak ditemukan, silahkan hubungi administrator.';
            default:
                break;
        }
    }

    /**
     * Get the validation rules.
     */
    public function rules(Attachment $attachment)
    {
        if (isset($attachment->id)) { // when update
            return [
                'name' => 'required|max:255|unique:attachments,name,'.$attachment->id,
                'type' => 'required|numeric',
                'file_name' => 'required|max:255',
            ];
        } else { // when create
            return [
                'name' => 'required|max:255|unique:attachments',
                'type' => 'required|numeric',
                'file_name' => 'required|max:255',
            ];
        }
    }
}
