<?php

namespace App\Http\Controllers;

use App\Enums\AttachmentType;
use App\Enums\ConsumptionType;
use App\Enums\CreationType;
use App\Enums\Gender;
use App\Enums\ParentType;
use App\Enums\RequestStatus;
use App\Enums\SemesterType;
use App\Enums\SessionStatus as SessionStatusEnum;
use App\Enums\TogaSize;
use App\Enums\WorkState;
use App\Http\Controllers\MasterController;
use App\Models\Achievement;
use App\Models\Attachment;
use App\Models\Certificate;
use App\Models\Semester;
use App\Models\SessionStatus;
use App\Models\Student;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;

class AdminStudentController extends MasterController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the student list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = $this->getBreadCrumbs('studentList');
        $title = $this->getTitle('studentList');
        $sub_title = $this->getSubTitle('studentList');
        $semesters = Semester::all()->pluck('text', 'id');
        $master_css = "active";
        $student_css = "active";

        return view('admin.student.student')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('semesters', $semesters)
                ->with('master_css', $master_css)
                ->with('student_css', $student_css);
    }

    /**
     * Show the skripsi submitted student list [to meteor user].
     *
     * @return \Illuminate\Http\Response
     */
    public function meteorSkripsiIndex()
    {
        $breadcrumbs = $this->getBreadCrumbs('meteorSkripsiStudentList');
        $title = $this->getTitle('meteorSkripsiStudentList');
        $sub_title = $this->getSubTitle('meteorSkripsiStudentList');
        $semesters = Semester::all()->pluck('text', 'id');
        $master_css = "active";
        $skripsi_student_css = "active";
        $is_skripsi = 'true';

        return view('admin.student.meteor_student')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('semesters', $semesters)
                ->with('master_css', $master_css)
                ->with('skripsi_student_css', $skripsi_student_css)
                ->with('is_skripsi', $is_skripsi);
    }

    /**
     * Show the tesis submitted student list [to meteor user].
     *
     * @return \Illuminate\Http\Response
     */
    public function meteorTesisIndex()
    {
        $breadcrumbs = $this->getBreadCrumbs('meteorTesisStudentList');
        $title = $this->getTitle('meteorTesisStudentList');
        $sub_title = $this->getSubTitle('meteorTesisStudentList');
        $semesters = Semester::all()->pluck('text', 'id');
        $master_css = "active";
        $tesis_student_css = "active";
        $is_skripsi = 'false';

        return view('admin.student.meteor_student')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('semesters', $semesters)
                ->with('master_css', $master_css)
                ->with('tesis_student_css', $tesis_student_css)
                ->with('is_skripsi', $is_skripsi);
    }

    /**
     * Get student list From Ajax.
     *
     * @param Request ajax request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentList(Request $request)
    {
        $students = Student::join('study_programs AS stdprg', 'students.study_program_id', '=', 'stdprg.id')
                         ->join('semesters AS smtr', 'students.semester_id', '=', 'smtr.id')
                         ->select([
                            'students.id',
                            'students.npm AS npm',
                            'students.name AS name',
                            'students.email AS email',
                            'students.phone_number AS phone_number',
                            DB::raw('SUBSTR(students.npm, 1, 2) as generation'),
                            'smtr.id AS semester_id',
                            'smtr.text AS semester',
                            'stdprg.name AS study_program_name',
                         ]);

        return Datatables::of($students)
                        ->setRowClass(function () {
                            return "custom-tr-text-ellipsis";
                        })
                        ->filterColumn('study_program_name', function ($query, $keyword) {
                            $query->whereRaw("CONCAT(stdprg.name,'-',stdprg.name) like ?", ["%{$keyword}%"]);
                        })
                        ->filterColumn('semester', function ($query, $keyword) {
                            $query->whereRaw("CONCAT(smtr.id,'-',smtr.id) like ?", ["%{$keyword}%"]);
                        })
                        ->filterColumn('generation', function ($query, $keyword) {
                            $query->whereRaw("CONCAT(SUBSTR(students.npm, 1, 2),'-',SUBSTR(students.npm, 1, 2)) like ?", ["%{$keyword}%"]);
                        })
                        ->addColumn('actions', function (Student $student)  {
                            return $this->getActionsButtons($student);
                        })
                        ->rawColumns(['actions', 'semester'])
                        ->make(true);
    }

    /**
     * Get skripsi submitted student list From Ajax.
     *
     * @param Request ajax request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSkripsiSubmittedStudentList(Request $request)
    {
        $students = Student::join('study_programs AS stdprg', 'students.study_program_id', '=', 'stdprg.id')
                         ->join('semesters AS smtr', 'students.semester_id', '=', 'smtr.id')
                         ->join('requests as rqsts', 'rqsts.student_id', '=', 'students.id')
                         ->select([
                            'students.id',
                            'students.npm AS npm',
                            'students.name AS name',
                            'students.email AS email',
                            'students.phone_number AS phone_number',
                            DB::raw('SUBSTR(students.npm, 1, 2) as generation'),
                            'smtr.id AS semester_id',
                            'smtr.text AS semester',
                            'stdprg.name AS study_program_name',
                         ])
                         ->where('rqsts.type', CreationType::Skripsi)
                         ->where('rqsts.status', '!=', RequestStatus::Draft)
                         ->groupBy('students.id', 'npm', 'name', 'email', 'phone_number', 'smtr.id', 'smtr.text', 'stdprg.name');

        return Datatables::of($students)
                        ->setRowClass(function () {
                            return "custom-tr-text-ellipsis";
                        })
                        ->filterColumn('study_program_name', function ($query, $keyword) {
                            $query->whereRaw("CONCAT(stdprg.name,'-',stdprg.name) like ?", ["%{$keyword}%"]);
                        })
                        ->filterColumn('semester', function ($query, $keyword) {
                            $query->whereRaw("CONCAT(smtr.id,'-',smtr.id) like ?", ["%{$keyword}%"]);
                        })
                        ->filterColumn('generation', function ($query, $keyword) {
                            $query->whereRaw("CONCAT(SUBSTR(students.npm, 1, 2),'-',SUBSTR(students.npm, 1, 2)) like ?", ["%{$keyword}%"]);
                        })
                        ->rawColumns(['semester'])
                        ->make(true);
    }

    /**
     * Get tesis submitted student list From Ajax.
     *
     * @param Request ajax request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTesisSubmittedStudentList(Request $request)
    {
        $students = Student::join('study_programs AS stdprg', 'students.study_program_id', '=', 'stdprg.id')
                         ->join('semesters AS smtr', 'students.semester_id', '=', 'smtr.id')
                         ->join('requests as rqsts', 'rqsts.student_id', '=', 'students.id')
                         ->select([
                            'students.id',
                            'students.npm AS npm',
                            'students.name AS name',
                            'students.email AS email',
                            'students.phone_number AS phone_number',
                            DB::raw('SUBSTR(students.npm, 1, 2) as generation'),
                            'smtr.id AS semester_id',
                            'smtr.text AS semester',
                            'stdprg.name AS study_program_name',
                         ])
                         ->where('rqsts.type', CreationType::Tesis)
                         ->where('rqsts.status', '!=', RequestStatus::Draft)
                         ->groupBy('students.id', 'npm', 'name', 'email', 'phone_number', 'smtr.id', 'smtr.text', 'stdprg.name');

        return Datatables::of($students)
                        ->setRowClass(function () {
                            return "custom-tr-text-ellipsis";
                        })
                        ->filterColumn('study_program_name', function ($query, $keyword) {
                            $query->whereRaw("CONCAT(stdprg.name,'-',stdprg.name) like ?", ["%{$keyword}%"]);
                        })
                        ->filterColumn('semester', function ($query, $keyword) {
                            $query->whereRaw("CONCAT(smtr.id,'-',smtr.id) like ?", ["%{$keyword}%"]);
                        })
                        ->filterColumn('generation', function ($query, $keyword) {
                            $query->whereRaw("CONCAT(SUBSTR(students.npm, 1, 2),'-',SUBSTR(students.npm, 1, 2)) like ?", ["%{$keyword}%"]);
                        })
                        ->rawColumns(['semester'])
                        ->make(true);
    }

    /**
     * Get Student's Certificate List From Ajax.
     *
     * @param Route model binding student, Request ajax request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentCertificateList(Student $student, Request $request)
    {
        $certificates = $student->certificates()->get();

        return Datatables::of($certificates)
                        ->setRowClass(function () {
                            return "custom-tr-text-ellipsis";
                        })
                        ->editColumn('place', function (Certificate $certificate) {
                            return strlen($certificate->place) > 0 ? $certificate->place : '-';
                        })
                        ->make(true);
    }

    /**
     * Get Student's Achievement List From Ajax.
     *
     * @param Request ajax request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentAchievementList (Student $student, Request $request)
    {
        $achievements = $student->achievements()->get();

        return Datatables::of($achievements)
                        ->setRowClass(function () {
                            return "custom-tr-text-ellipsis";
                        })
                        ->editColumn('place', function (Achievement $achievement) {
                            return strlen($achievement->place) > 0 ? $achievement->place : '-';
                        })
                        ->make(true);
    }

    /**
     * Get Student's Session Status List From Ajax.
     *
     * @param Request ajax request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentSessionStatusList (Student $student, Request $request)
    {
        $session_statuses = $student->session_statuses()->get();

        return Datatables::of($session_statuses)
                        ->setRowClass(function () {
                            return "custom-tr-text-ellipsis";
                        })
                        ->editColumn('date', function (SessionStatus $session_status) {
                            return strlen($session_status->date) > 0 ? date('d-m-Y', strtotime($session_status->date)) : '-';
                        })
                        ->editColumn('status', function (SessionStatus $session_status) {
                            return $this->getSessionStatusStatusLabel($session_status);
                        })
                        ->editColumn('type', function (SessionStatus $session_status) {
                            return $this->getSessionStatusTypeLabel($session_status);
                        })
                        ->rawColumns(['type', 'status'])
                        ->make(true);
    }

    /**
     * Show the student detail.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewDetail(Student $student)
    {
        $breadcrumbs = $this->getBreadCrumbs('adminStudentProfile');
        $title = $this->getTitle('adminStudentProfile');
        $sub_title = $this->getSubTitle('adminStudentProfile');
        $genders = Gender::getStrings();
        $semesters = Semester::all()->pluck('text', 'id');
        $study_programs = StudyProgram::all()->pluck('name', 'id');
        $work_statuses = WorkState::getStrings();
        $toga_sizes = TogaSize::getStrings();
        $consumption_types = ConsumptionType::getStrings();
        $master_css = "active";
        $student_css = "active";

        // parents' data
        $father = $student->parents()->where('type', ParentType::Father)->first();
        $mother = $student->parents()->where('type', ParentType::Mother)->first();
        $parentCurrentCompany = $this->getParentCompany($student);

        // student's collection data
        $ktp = $student->attachments()->where('type', AttachmentType::KTP)->first();
        $kartuKeluarga = $student->attachments()->where('type', AttachmentType::KartuKeluarga)->first();
        $aktaKelahiran = $student->attachments()->where('type', AttachmentType::AktaKelahiran)->first();
        $ijazahSMA = $student->attachments()->where('type', AttachmentType::IjazahSMA)->first();
        $ijazahS1 = $student->attachments()->where('type', AttachmentType::IjazahS1)->first();

        return view('admin.student.student_profile')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('genders', $genders)
                ->with('semesters', $semesters)
                ->with('study_programs', $study_programs)
                ->with('work_statuses', $work_statuses)
                ->with('toga_sizes', $toga_sizes)
                ->with('consumption_types', $consumption_types)
                ->with('student', $student)
                ->with('father', $father)
                ->with('mother', $mother)
                ->with('parentCurrentCompany', $parentCurrentCompany)
                ->with('master_css', $master_css)
                ->with('student_css', $student_css)
                ->with('kartuKeluarga', $kartuKeluarga)
                ->with('aktaKelahiran', $aktaKelahiran)
                ->with('ijazahSMA', $ijazahSMA)
                ->with('ijazahS1', $ijazahS1)
                ->with('ktp', $ktp);
    }

    public function getActionsButtons($model, array $extraClassToAdd = [])
    {
        $modelId = $model->id;
        $detail =  $this->getRoute('detail', $modelId);
        $detailClass = '';

        if (count($extraClassToAdd) > 0) {
            foreach ($extraClassToAdd as $key => $value) {
                if ($extraClassToAdd[$key] = 'detail') {
                    $detailClass = $value;
                }
            }
        }

        return "<a href='{$detail}' title='LIHAT RINCIAN' class='btn btn-primary {$detailClass}'><span class='fa fa-eye'></span> Lihat Rincian </a>";
    }

    /**
     * Get student's parent's company.
     *
     * @param string $route, array $errors
     *
     * @return string
     */
    public function getParentCompany(Student $student)
    {
        $father = $student->parents()->where('type', ParentType::Father)->first();
        $mother = $student->parents()->where('type', ParentType::Mother)->first();

        $currentCompany = null;

        if ($mother != null) {
            if ($mother->company()->first() != null) {
                $currentCompany = $mother->company()->first();
            }
        }
        if ($father != null) {
            if ($father->company()->first() != null) {
                $currentCompany = $father->company()->first();
            }
        }

        return $currentCompany;
    }

    /**
     * Download KTP.
     *
     * @param model binding student
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadKTP(Student $student)
    {
        $ktp = $student->attachments()->where('type', AttachmentType::KTP)->first();
        
        if ($ktp == null) {
            abort(404);
        }

        try {
            $filePath = $this->getStudentFileFolderPath($student).$ktp->file_name;

            return Response::download($filePath, $ktp->file_name);
        } catch (\Exception $e) {
            $attachment = new Attachment;
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('detail', $student->id), new \Exception($attachment->messages('fileNotFoundOnDirectory')));
        }
    }

    /**
     * Download KK.
     *
     * @param model binding student
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadKK(Student $student)
    {
        $kk = $student->attachments()->where('type', AttachmentType::KartuKeluarga)->first();
        
        if ($kk == null) {
            abort(404);
        }

        try {
            $filePath = $this->getStudentFileFolderPath($student).$kk->file_name;


            return Response::download($filePath, $kk->file_name);
        } catch (\Exception $e) {
            $attachment = new Attachment;
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('detail', $student->id), new \Exception($attachment->messages('fileNotFoundOnDirectory')));
            
        }
    }

    /**
     * Download AK.
     *
     * @param model binding student
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadAK(Student $student)
    {
        $ak = $student->attachments()->where('type', AttachmentType::AktaKelahiran)->first();
        
        if ($ak == null) {
            abort(404);
        }

        try {
            $filePath = $this->getStudentFileFolderPath($student).$ak->file_name;


            return Response::download($filePath, $ak->file_name);
        } catch (\Exception $e) {
            $attachment = new Attachment;
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('detail', $student->id), new \Exception($attachment->messages('fileNotFoundOnDirectory')));
            
        }
    }

    /**
     * Download Ijazah SMA.
     *
     * @param model binding student
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadIjazahSMA(Student $student)
    {
        $ijazahSMA = $student->attachments()->where('type', AttachmentType::IjazahSMA)->first();
        
        if ($ijazahSMA == null) {
            abort(404);
        }

        try {
            $filePath = $this->getStudentFileFolderPath($student).$ijazahSMA->file_name;

            return Response::download($filePath, $ijazahSMA->file_name);
        } catch (\Exception $e) {
            $attachment = new Attachment;
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('detail', $student->id), new \Exception($attachment->messages('fileNotFoundOnDirectory')));
            
        }
    }

    /**
     * Download Ijazah S1.
     *
     * @param model binding student
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadIjazahS1(Student $student)
    {
        $ijazahS1 = $student->attachments()->where('type', AttachmentType::IjazahS1)->first();
        
        if ($ijazahS1 == null) {
            abort(404);
        }

        try {
            $filePath = $this->getStudentFileFolderPath($student).$ijazahS1->file_name;

            return Response::download($filePath, $ijazahS1->file_name);
        } catch (\Exception $e) {
            $attachment = new Attachment;
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('detail', $student->id), new \Exception($attachment->messages('fileNotFoundOnDirectory')));
            
        }
    }

    /**
     * Download all students as excel.
     *
     * @param Request $request
     *
     * @return string
     */
    public function downloadStudentExcel(Request $request)
    {
        $data = Input::all();
        // dd($data);

        $student_result = Student::join('study_programs AS stdprg', 'students.study_program_id', '=', 'stdprg.id')
                         ->join('semesters AS smtr', 'students.semester_id', '=', 'smtr.id')
                         ->select([
                            'students.*',
                            // 'students.name',
                            // 'students.email',
                            // 'students.phone_number',
                            'smtr.type AS semester_type',
                            'stdprg.name AS study_program_name',
                         ]);

        // dd($student_result->get());

        // Handle if user filter the datas
        if (isset($data['npm'])) {
            $student_result = $student_result->whereRaw("CONCAT(students.npm,'-',students.npm) like ?", ["%{$data['npm']}%"]);
        }

        if (isset($data['name'])) {
            $student_result = $student_result->whereRaw("CONCAT(students.name,'-',students.name) like ?", ["%{$data['name']}%"]);
        }

        if (isset($data['email'])) {
            $student_result = $student_result->whereRaw("CONCAT(students.email,'-',students.email) like ?", ["%{$data['email']}%"]);
        }

        if (isset($data['phone_number'])) {
            $student_result = $student_result->whereRaw("CONCAT(students.phone_number,'-',students.phone_number) like ?", ["%{$data['phone_number']}%"]);
        }

        if (isset($data['semester_type'])) {
            $student_result = $student_result->whereRaw("CONCAT(smtr.type,'-',smtr.type) like ?", ["%{$data['semester_type']}%"]);
        }

        if (isset($data['program_study'])) {
            $student_result = $student_result->whereRaw("CONCAT(stdprg.name,'-',stdprg.name) like ?", ["%{$data['program_study']}%"]);
        }

        $student_result = $student_result->get();
        $result_array = array();

        // set locale date name
        setlocale(LC_TIME, 'id-ID');
        
        if (count($student_result) > 0) {
            foreach ($student_result as $key => $res) {
                // student's parents' data
                $father = $res->parents()->where('type', ParentType::Father)->first();
                $mother = $res->parents()->where('type', ParentType::Mother)->first();
                $company = $res->company()->first();
                $certificates = $res->certificates()->get();
                $achievements = $res->achievements()->get();

                // student's session statuses
                $kp_session_status = $res->session_statuses->where('type', CreationType::KP)->first();
                $skripsi_session_status = $res->session_statuses->where('type', CreationType::Skripsi)->first();
                $tesis_session_status = $res->session_statuses->where('type', CreationType::Tesis)->first();

                $result_array[$key]['NPM'] = $res->npm;
                $result_array[$key]['Nama'] = $res->name;
                $result_array[$key]['Email'] = $res->email;
                $result_array[$key]['Nomor Telefon'] = $res->phone_number;
                $result_array[$key]['NIK'] = $res->NIK;
                $result_array[$key]['Nilai TOEIC'] = $res->toeic_grade;
                $result_array[$key]['Jenis Kelamin'] = Gender::getString($res->sex);
                $result_array[$key]['Semester Pendaftaran Sidang'] = $res->semester()->first()->text;
                $result_array[$key]['Program Studi'] = $res->study_program_name;
                $result_array[$key]['Gelar S1'] = strlen($res->existing_degree) > 0 ? $res->existing_degree : ' - ';
                $result_array[$key]['Gelar Sertifikasi'] = strlen($res->certification_degree) > 0 ? $res->certification_degree : ' - ';
                $result_array[$key]['Tempat Lahir'] = $res->birth_place;
                $result_array[$key]['Tanggal Lahir'] = strftime('%e %B %Y', strtotime($res->birthdate));
                $result_array[$key]['Agama'] = $res->religion;
                $result_array[$key]['Alamat Domisili'] = $res->address;
                $result_array[$key]['Status Pekerjaan'] = WorkState::getString($res->work_status);
                $result_array[$key]['Ukuran Toga'] = TogaSize::getString($res->toga_size);
                $result_array[$key]['Konsumsi saat Wisuda'] = ConsumptionType::getString($res->consumption_type);
                $result_array[$key]['Nama Ayah Kandung'] = $father != null ? $father->name : ' -  ';
                $result_array[$key]['Nama Ibu Kandung'] = $mother != null ? $mother->name : ' - ';
                $result_array[$key]['Nama Tempat Usaha'] = $company != null ? $company->name : ' - ';
                $result_array[$key]['Bidang Usaha'] = $company != null ? $company->field : ' - ';
                $result_array[$key]['Nomor Telefon Tempat Usaha'] = $company != null ? $company->phone_number : ' - ';
                $result_array[$key]['Alamat Tempat Usaha'] = $company != null ? $company->address : ' - ';
                $result_array[$key]['Sudah Pernah Sidang KP'] = $kp_session_status !== null ? SessionStatusEnum::getString($kp_session_status->status) : 'Belum Sidang';
                $result_array[$key]['Sudah Pernah Sidang Skripsi'] = $skripsi_session_status !== null ? SessionStatusEnum::getString($skripsi_session_status->status) : 'Belum Sidang';
                $result_array[$key]['Sudah Pernah Sidang Tesis'] = $tesis_session_status !== null ? SessionStatusEnum::getString($tesis_session_status->status) : 'Belum Sidang';
                $result_array[$key]['Tanggal Sidang KP'] = $kp_session_status !== null ? $kp_session_status->date != null ? strftime('%e %B %Y', strtotime($kp_session_status->date)) : ' - ' : ' - ';
                $result_array[$key]['Tanggal Sidang Skripsi'] = $skripsi_session_status !== null ? $skripsi_session_status->date != null ? strftime('%e %B %Y', strtotime($skripsi_session_status->date)) : ' - ' : ' - ';
                $result_array[$key]['Tanggal Sidang Tesis'] = $tesis_session_status !== null ? $tesis_session_status->date != null ? strftime('%e %B %Y', strtotime($tesis_session_status->date)) : ' - ' : ' - ';

                // initiate for certificates and achievements
                for ($i = 1; $i <= 5; $i++) { 
                    $result_array[$key]['Sertifikasi '.$i] = ' - ';
                }
                for ($i = 1; $i <= 10; $i++) { 
                    $result_array[$key]['Prestasi '.$i] = ' - ';
                }

                if (count($certificates) > 0) {
                    foreach ($certificates as $certKey => $cert) {
                        $result_array[$key]['Sertifikasi '.($certKey+1)] = $cert->year . '/' . $cert->name . '/' .$cert->place; 
                    }
                }

                if (count($achievements) > 0) {
                    foreach ($achievements as $achiKey => $achievement) {
                        $result_array[$key]['Prestasi '.($achiKey+1)] = $achievement->year . '/' . $achievement->name . '/' .$achievement->place; 
                    }
                }
                // dd($result_array);
            }

            Excel::create('Laporan Mahasiswa', function($excel) use ($result_array) {

                $excel->sheet('Sheet 1', function($sheet) use ($result_array) {

                    // Fill the XLS with Data
                    $sheet->fromArray($result_array, null, 'A1', true);

                    // Set Row Height
                    $sheet->setHeight(1, 25);

                    // $sheet->cell('D1', function($cell) {
                    //     $cell->setValue('Tanggal Daftar');
                    // });

                    // Set Sheet Border
                    // $sheet->setAllBorders('thin');

                    // Manipulate Row
                    $sheet->row(1, function ($row) {
                        $row->setFontWeight('bold');
                        $row->setAlignment('center');
                        $row->setValignment('center');
                    });

                    // Freeze first row
                    $sheet->freezeFirstRow();

                });
            })->export('xls');
        }

        // redirect
        $student = new Student;
        $this->setFlashMessage('danger', $student->messages('emptyExcelRequest'));
        return redirect($this->getRoute('list'));
    }

    /**
     * Get all routes.
     *
     * @param int $studentId
     *
     * @return string
     */
    public function getRoute($key, $studentId = null)
    {
        switch ($key) {
            case 'list':
                return route('students');
            case 'detail':
                return route('student.view.detail', $studentId);

            default:
                # code...
                break;
        }
    }

    /**
     * Get a refactored type labels for student datatable.
     *
     * @param model binding student
     *
     * @return string
     */
    private function getRecordsTypeLabel(Student $student)
    {
        $type = SemesterType::getString($student->semester);

        // if ($record->type == RequestStatus::getValue('KP')) {
        //     $extra_class = 'info';
        // } else {
        //     $extra_class = 'warning';
        // }
        $extra_class = 'success';

        return "<span class='label label-{$extra_class}'>{$type}</span>";
    }

    /**
     * Get a refactored session status status labels for request datatable.
     *
     * @param SessionStatus $session_status
     *
     * @return string
     */
    private function getSessionStatusStatusLabel(SessionStatus $session_status)
    {
        $status = SessionStatusEnum::getString($session_status->status);

        if ($session_status->status == SessionStatusEnum::Taked) {
            $extra_class = 'success';
        } elseif ($session_status->status == SessionStatusEnum::Cancelled) {
            $extra_class = 'danger';
        }

        return "<span class='label label-{$extra_class}'>{$status}</span>";
    }

    /**
     * Get a refactored session status type labels for request datatable.
     *
     * @param SessionStatus $session_status
     *
     * @return string
     */
    private function getSessionStatusTypeLabel(SessionStatus $session_status)
    {
        $type = CreationType::getString($session_status->type);

        $extra_class = 'primary';

        return "<span class='label label-{$extra_class}'>{$type}</span>";
    }
}
