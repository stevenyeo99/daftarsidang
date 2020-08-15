<?php

namespace App\Models;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Contracts\Auth\Authenticatable;

class Student extends BaseModel implements Authenticatable
{
    use AuthenticableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'npm',
        'name',
        'sex',
        'NIK',
        'birth_place',
        'religion',
        'email',
        'phone_number',
        'address',
        'work_status',
        'toga_size',
        'consumption_type',
        'existing_degree',
        'certification_degree',
        'profile_filled',
        'is_profile_accurate',
        'must_fill_attachment',
        'toeic_grade'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'birthdate',
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
     * Get the semester that owns by student
     */
    public function semester()
    {
        return $this->belongsTo('App\Models\Semester', 'semester_id');
    }

    /**
     * Get the study program that owns by student
     */
    public function studyProgram()
    {
        return $this->belongsTo('App\Models\StudyProgram', 'study_program_id');
    }

    /**
     * Get the company that owns the current student
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    /**
     * Get the parents that give the user a birth.
     */
    public function parents()
    {
        return $this->hasMany('App\Models\CustomParent');
    }

    /**
     * Get the achievements that owns by student.
     */
    public function achievements()
    {
        return $this->hasMany('App\Models\Achievement');
    }

    /**
     * Get the certificates that owns by student.
     */
    public function certificates()
    {
        return $this->hasMany('App\Models\Certificate');
    }

    /**
     * Get the attachments that owns by student.
     */
    public function attachments()
    {
        return $this->hasMany('App\Models\Attachment');
    }

    /**
     * Get the requests that owns by student.
     */
    public function requests()
    {
        return $this->hasMany('App\Models\Request');
    }

    /**
     * Get the session statuses that owns by student.
     */
    public function session_statuses()
    {
        return $this->hasMany('App\Models\SessionStatus');
    }

    /**
     * Get the student valid status, status valid to do any action
     */
    public function getStudentProfileValidStatus()
    {
        $default_values = [
            $this->npm,
            $this->name,
            $this->sex,
            $this->birth_place,
            $this->birthdate,
            $this->religion,
            $this->address,
            $this->study_program_id,
            $this->phone_number,
        ];

        $student_valid = !in_array(null, $default_values, true); // check if null values in array

        return $student_valid;
    }

    /**
     * Get the customized error message.
     */
    public function messages(string $key, string $keyTwo = null)
    {
        switch ($key) {
            case 'validation':
                return [
                    'npm.required' => 'NPM mahasiswa tidak boleh kosong.',
                    'npm.unique' => 'Mahasiswa dengan NPM yang sama sudah ada , harap masukan NPM yang lain.',
                    'npm.max' => 'NPM mahasiswa tidak boleh lewat dari 10 huruf atau angka.',
                    'name.required' => 'Nama mahasiswa tidak boleh kosong.',
                    // 'name.unique' => 'Mahasiswa dengan nama yang sama sudah ada , harap masukan nama yang lain.',
                    'name.max' => 'Nama mahasiswa tidak boleh lewat dari 50 huruf atau angka.',
                    'sex.required' => 'Gender tidak boleh kosong.',
                    'sex.numeric' => 'Gender harus berupa angka.',
                    'birth_place.required' => 'Tempat lahir tidak boleh kosong.',
                    'birth_place.max' => 'Tempat lahir tidak boleh lewat dari 45 huruf atau angka.',
                    'birthdate.required' => 'Tanggal lahir tidak boleh kosong.',
                    'birthdate.date' => 'Tanggal lahir harus berupa tanggal.',
                    'birthdate.before' => 'Tanggal lahir tidak boleh kecil dari 13 tahun.',
                    'religion.required' => 'Agama tidak boleh kosong.',
                    'religion.max' => 'Agama tidak boleh lewat dari 45 huruf atau angka.',
                    'email.required' => 'Email mahasiswa tidak boleh kosong.',
                    'email.unique' => 'Mahasiswa dengan email yang sama sudah ada , harap masukan email yang lain.',
                    'email.max' => 'Email mahasiswa tidak boleh lewat dari 45 huruf atau angka.',
                    'email.email' => 'Email mahasiswa harus berupa email yang valid.',
                    'phone_number.numeric' => 'Nomor telepon mahasiswa harus berupa angka.',
                    'phone_number.digits_between' => 'Panjangnya nomor telepon mahasiswa tidak boleh lebih dari 50 dan pendek dari 10.',
                    'phone_number.unique' => 'Mahasiswa dengan nomor telepon yang sama sudah ada , harap masukan nomor telepon yang lain.',
                    'address.required' => 'Alamat mahasiswa tidak boleh kosong.',
                    'address.max' => 'Alamat mahasiswa tidak boleh lewat dari 255 huruf atau angka.',
                    'work_status.required' => 'Status pekerjaan tidak boleh kosong.',
                    'work_status.numeric' => 'Status pekerjaan harus berupa angka.',
                    'toga_size.required' => 'Ukuran toga tidak boleh kosong.',
                    'toga_size.numeric' => 'Ukuran toga harus berupa angka.',
                    'consumption_type.required' => 'Tipe Konsumsi tidak boleh kosong.',
                    'consumption_type.numeric' => 'Tipe Konsumsi harus berupa angka.',
                    'existing_degree.max' => 'Gelar mahasiswa tidak boleh lewat dari 45 huruf atau angka.',
                    'certification_degree.max' => 'Gelar sertifikasi mahasiswa tidak boleh lewat dari 45 huruf atau angka.',
                    'semester.required' => 'Semester tidak boleh kosong.',
                    'study_program.required' => 'Program Studi tidak boleh kosong.',
                    'NIK.required' => 'NIK mahasiswa tidak boleh kosong.',
                    'NIK.numeric' => 'NIK mahasiswa harus berupa angka.',
                    'NIK.digits_between' => 'Panjangnya NIK mahasiswa tidak boleh lebih dari 20 dan pendek dari 15.',
                    'NIK.unique' => 'Mahasiswa dengan NIK yang sama sudah ada , harap masukan NIK yang lain.',
                    'toeic_grade.required' => 'Nilai TOEIC tidak boleh kosong.',
                    'toeic_grade.numeric' => 'Nilai TOEIC harus berupa angka.',
                    'toeic_grade.digits' => 'Nilai TOEIC tidak boleh lewat dari 3 angka.',
                    'toeic_grade.max' => 'Nilai TOEIC tidak boleh lebih dari 1000.',
                ];
            case 'success':
                switch ($keyTwo) {
                    case 'create':
                        return 'Telah membuat mahasiswa dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui mahasiswa dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus mahasiswa dengan sukses!';
                    case 'import':
                        return 'Telah mengimpor mahasiswa dengan sukses!';
                    case 'updateCompany':
                        return 'Telah memperbarui data pekerjaan dengan sukses!';
                    case 'updateIsProfileAccurateFalse':
                        return 'Silahkan memperbenarkan data anda!';
                    default:
                        break;
                }
            case 'failDelete':
                    return 'Mahasiswa ini tidak bisa dihapus karena telah dipakai ditempat lain.';
            case 'failMappingExcelArray':
                    return 'Mapping dari excel terjadi error, bisa terjadi karena salah penamaan kolom, atau bisa karena nilai yang di input tidak benar.';
            case 'failSetIsProfileAccurateFalse':
                    return 'Sudah memasuki mode perbaiki data portal!';
            case 'updateProfileAfterSetIsProfileAccurateFalse':
                    return 'Dikarenakan anda mengganti data default, maka anda diwajibkan untuk lampirkan Ijazah SMA, KTP dan KK.';
            case 'emptyExcelRequest':
                    return 'Tidak dapat ekspor melalui excel karena data masih kosong!';
            case 'studentProfileInvalidIssue':
                    return 'Data profil masih belum lengkap!';
            case 'validation.get.already.sessioned.list':
                return [
                    'request_type.required' => 'Jenis karya ilmiah tidak boleh kosong.',
                    'request_type.numeric' => 'Jenis karya ilmiah harus berupa angka.',
                    'program_study_code.max' => 'Kode program studi tidak boleh lewat dari 10 huruf atau angka.',
                    'generation.max' => 'Angkatan tidak boleh lewat dari 100.',
                    'generation.numeric' => 'Angkatan harus berupa angka.',
                ];
            default:
                break;
        }
    }

    /**
     * Get the validation rules.
     */
    public function rules(Student $student)
    {
        $dt = new Carbon();
        $before = $dt->subYears(13)->format('Y-m-d'); // bikin default limit 13 tahun

        if (isset($student->id)) { // when update
            if ($student->is_profile_accurate) {
                return [
                    'npm' => 'max:10|unique:students,npm,'.$student->id, // not required because the profile from portal already accurate
                    // 'name' => 'max:50|unique:students,name,'.$student->id, // not required because the profile from portal already accurate
                    'name' => 'max:50', // not required because the profile from portal already accurate
                    'sex' => 'numeric', // not required because the profile from portal already accurate
                    'NIK' => 'required|numeric|digits_between:15,20|unique:students,NIK,'.$student->id,
                    'toeic_grade' => 'required|numeric|digits:3|max:1000',
                    'birth_place' => 'max:45', // not required because the data is important but not from portal
                    'birthdate' => 'date|before:'.$before, // not required because the profile from portal already accurate
                    'religion' => 'max:45', // not required because the profile from portal already accurate
                    'email' => 'required|string|email|max:45|unique:students,email,'.$student->id,
                    'phone_number' => 'numeric|digits_between:10,50|unique:students,phone_number,'.$student->id, // not required because the profile from portal already accurate
                    'address' => 'max:255', // not required because the profile from portal already accurate
                    'work_status' => 'required|numeric',
                    'toga_size' => 'required|numeric',
                    'consumption_type' => 'required|numeric',
                    'existing_degree' => 'max:45',
                    'certification_degree' => 'max:45',
                    'semester' => 'required',
                    // 'study_program' => 'required', // not required because the profile from portal already accurate
                ];
            }

            return [
                'npm' => 'required|max:10|unique:students,npm,'.$student->id,
                // 'name' => 'required|max:50|unique:students,name,'.$student->id,
                'name' => 'required|max:50',
                'sex' => 'required|numeric',
                'NIK' => 'required|numeric|digits_between:15,20|unique:students,NIK,'.$student->id,
                'toeic_grade' => 'required|numeric|digits:3|max:1000',
                'birth_place' => 'required|max:45',
                'birthdate' => 'required|date|before:'.$before,
                'religion' => 'required|max:45',
                'email' => 'required|string|email|max:45|unique:students,email,'.$student->id,
                'phone_number' => 'numeric|digits_between:10,50|unique:students,phone_number,'.$student->id,
                'address' => 'required|max:255',
                'work_status' => 'required|numeric',
                'toga_size' => 'required|numeric',
                'consumption_type' => 'required|numeric',
                'existing_degree' => 'max:45',
                'certification_degree' => 'max:45',
                'semester' => 'required',
                'study_program' => 'required',
            ];
        } else { // when create [' Currently not used at all']
            return [
                'npm' => 'required|max:10|unique:students',
                // 'name' => 'required|max:50|unique:students',
                'name' => 'required|max:50',
                'sex' => 'required|numeric',
                'NIK' => 'numeric|digits_between:15,20|unique:students',
                'toeic_grade' => 'required|numeric|digits:3|max:1000',
                'birth_place' => 'required|max:45',
                'birthdate' => 'required|date|before:'.$before,
                'religion' => 'required|max:45',
                'email' => 'required|string|email|max:45|unique:students',
                'phone_number' => 'numeric|digits_between:10,50|unique:students',
                'address' => 'required|max:255',
                'work_status' => 'required|numeric',
                'toga_size' => 'required|numeric',
                'consumption_type' => 'required|numeric',
                'existing_degree' => 'max:45',
                'certification_degree' => 'max:45',
                'semester' => 'required',
                'study_program' => 'required',
            ];
        }
    }

    /**
     * Get the validation rules when getting student that already sessioned "sudah pernah sidang".
     */
    public function getStudentAlreadySessionedListRules(Student $student)
    {
        return [
            'request_type' => 'required|numeric',
            'program_study_code' => 'max:10',
            'generation' => 'max:100|numeric',
        ];
    }
}