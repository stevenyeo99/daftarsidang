<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TurnitinFile extends BaseModel
{
    protected $table = 'turnitin_files';

    public $file_upload;

    // use for validation only
    public $file;

    protected $fillable = [
        'type', 'npm', 'file_name', 'file_display_name', 'file_path', 'uploaded_by', 'uploaded_on'
    ];

    // for preventing from timestamp inserted while doing insert or updating
    public $timestamps = false;

    protected $errors;

    public function user() {
        return $this->belongsTo('App\Models\LibraryUser', 'uploaded_by');
    }

    public function messages(string $key, string $keyTwo = null) {
        switch($key) {
            case 'validation':
                return [
                    'npm.required' => 'NPM Mahasiswa tidak boleh kosong.',
                    'npm.numeric' => 'NPM harus berbentuk angka.',
                    'npm.unique' => 'NPM tersebut sudah upload file turnitinnya berdasarkan kategorinya.',
                    'file.required' => 'File turnitin wajib dilampirkan.',
                    'file.mimes' => 'Format file harus berupa jpeg, png, jpg, pdf atau svg.',
                    'file.max' => 'Ukuran file tidak boleh lebih besar dari 1MB.',
                ];
            case 'validationNoFile':
                return [
                    'npm.required' => 'NPM Mahasiswa tidak boleh kosong.',
                    'npm.numeric' => 'NPM harus berbentuk angka.',
                    'npm.unique' => 'NPM tidak boleh sama.',
                ];
            case 'success':
                switch($keyTwo) {
                    case 'create':
                        return 'Telah mengupload file turnitin dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui file turnitin dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus file turnitin dengan sukses!';
                    default:
                        break;
                }
            case 'failDelete':
                return 'File turnitin ini tidak bisa dihapus karena telah dipakai ditempat lain.';
            case 'fileNotFoundOnDirectory':
                return 'File sebelumnya tidak ditemukan, silahkan hubungi administrator.';
            default:
                break;
        }
    }

    /**
     * validation rules when upload file turnitin
     */
    public function rules(TurnitinFile $turnitinFile)
    {     
        if(isset($turnitinFile->id) && $turnitinFile->file_upload == 'YES') {
            return [
                'npm' => 'required|numeric|unique:turnitin_files,npm,'.$turnitinFile->id,
                'file' => 'required|mimes:jpeg,png,jpg,pdf|max:1024', // for max 1mb
            ];
        } else if(isset($turnitinFile->id) && $turnitinFile->file_upload == 'NO') {
            return [
                'npm' => 'required|numeric|unique:turnitin_files,npm,'.$turnitinFile->id,
            ];
        } else {
            return [
                'npm' => ['required', 'numeric', Rule::unique('turnitin_files')->where(function($query) use($turnitinFile) {
                    $query->where('npm', $turnitinFile->npm)
                        ->where('type', $turnitinFile->type);
                })],
                'file' => 'required|mimes:jpeg,png,jpg,pdf|max:1024', // for max 1mb
            ];
        }
    }
}
