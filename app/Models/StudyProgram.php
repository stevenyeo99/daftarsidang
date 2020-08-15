<?php

namespace App\Models;

use App\Models\BaseModel;

class StudyProgram extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name',
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
     * Get the user that created the study program
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    /**
     * Get the faculty that owns the study program
     */
    public function faculty()
    {
        return $this->belongsTo('App\Models\Faculty', 'faculty_id');
    }

    /**
     * Get the study program that owns the study program user roles
     */
    public function studyProgramUser() {
        return $this->belongsToMany('App\Models\StudyProgramUserRoles', 'study_program_user_roles', 'study_program_id', 'study_program_user_id');
    }

    /**
     * Get the customized error message.
     */
    public function messages(string $key, string $keyTwo = null)
    {
        switch ($key) {
            case 'validation':
                return [
                    'code.required' => 'Kode program studi tidak boleh kosong.',
                    'code.unique' => 'Program studi dengan kode yang sama sudah ada , harap masukan kode yang lain.',
                    'code.max' => 'Kode program studi tidak boleh lewat dari 10 huruf atau angka.',
                    'name.required' => 'Nama program studi tidak boleh kosong.',
                    'name.unique' => 'Program studi dengan nama yang sama sudah ada , harap masukan nama yang lain.',
                    'name.max' => 'Nama program studi tidak boleh lewat dari 50 huruf atau angka.',
                    'faculty.required' => 'Fakultas tidak boleh kosong.',
                ];
            case 'success':
                switch ($keyTwo) {
                    case 'create':
                        return 'Telah membuat program studi dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui program studi dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus program studi dengan sukses!';
                    case 'import':
                        return 'Telah mengimpor program studi dengan sukses!';
                    default:
                        break;
                }
            case 'failDelete':
                    return 'Program studi ini tidak bisa dihapus karena telah dipakai ditempat lain.';
            case 'failMappingExcelArray':
                    return 'Mapping dari excel terjadi error, bisa terjadi karena salah penamaan kolom, atau bisa karena nilai yang di input tidak benar.';
            default:
                break;
        }
    }

    /**
     * Get the validation rules.
     */
    public function rules(StudyProgram $studyProgram)
    {
        if (isset($studyProgram->id)) { // when update
            return [
                'code' => 'required|max:10|unique:study_programs,code,'.$studyProgram->id,
                'name' => 'required|max:50|unique:study_programs,name,'.$studyProgram->id,
                'faculty' => 'required',
            ];
        } else { // when create
            return [
                'code' => 'required|max:10|unique:study_programs',
                'name' => 'required|max:50|unique:study_programs',
                'faculty' => 'required',
            ];
        }
    }
}
