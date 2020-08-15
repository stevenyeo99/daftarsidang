<?php

namespace App\Models;

use App\Enums\SessionStatus as SessionStatusEnum;
use App\Models\BaseModel;

class SessionStatus extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'type'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date',
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
     * Get the student that owned the session status
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
                    'status.required' => 'Status sidang tidak boleh kosong.',
                    'status.numeric' => 'Status sidang harus berupa angka.',
                    'date.required' => 'Tanggal sidang tidak boleh kosong.',
                    'date.date' => 'Tanggal sidang harus berupa tanggal.',
                    'type.required' => 'Jenis karya ilmiah tidak boleh kosong.',
                    'type.numeric' => 'Jenis karya ilmiah harus berupa angka.',
                    'student.required' => 'Mahasiswa tidak boleh kosong.',
                ];
            case 'success':
                switch ($keyTwo) {
                    case 'create':
                        return 'Telah membuat status sidang dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui status sidang dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus status sidang dengan sukses!';
                    case 'import':
                        return 'Telah mengimpor status sidang dengan sukses!';
                    default:
                        break;
                }
            case 'failDelete':
                    return 'Status sidang ini tidak bisa dihapus karena telah dipakai ditempat lain.';
            case 'failMappingExcelArray':
                    return 'Mapping dari excel terjadi error, bisa terjadi karena salah penamaan kolom, atau bisa karena nilai yang di input tidak benar.';
            default:
                break;
        }
    }

    /**
     * Get the validation rules.
     */
    public function rules(SessionStatus $session_status)
    {
    	if (isset($session_status->status)) {
	        if ($session_status->status == SessionStatusEnum::Taked) { // when the status is taked
	            return [
	                'status' => 'required|numeric',
	                'date' => 'required|date',
                    'type' => 'required|numeric',
	                'student' => 'required',
	            ];
	        } else { // when the status is cancelled
	            return [
	                'status' => 'required|numeric',
	                'date' => 'date',
	                'type' => 'required|numeric',
                    'student' => 'required',
	            ];
	        }
    	}

    	// default rules
        return [
            'status' => 'required|numeric',
            'date' => 'date',
            'type' => 'required|numeric',
            'student' => 'required',
        ];
    }
}
