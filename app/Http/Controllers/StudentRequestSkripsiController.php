<?php

namespace App\Http\Controllers;

use App\Enums\AttachmentType;
use App\Enums\CreationType;
use App\Enums\ParentType;
use App\Enums\RequestStatus;
use App\Enums\StatusSidang;
use App\Enums\RequestAttachmentType as RequestAttachment;
use App\Http\Controllers\MasterController;
use App\Mail\RequestSubmitted;
use App\Models\Request as CustomRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;
use Yajra\Datatables\Datatables;
use App\Models\ProdiUser;
use App\Models\RequestAttachment as RequestAttachmentTypeModel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class StudentRequestSkripsiController extends MasterController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Show the skripsi requests.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = $this->getBreadCrumbs('skripsiRequest');
        $title = $this->getTitle('skripsiRequest');
        $sub_title = $this->getSubTitle('skripsiRequest');
        $request_css = "active";
        $skripsi_request_css = "active";
        $statuses = RequestStatus::getStrings();
        $types = CreationType::getStringsExcept(CreationType::KP);

        return view('student.request.skripsi')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('request_css', $request_css)
                ->with('statuses', $statuses)
                ->with('types', $types)
                ->with('skripsi_request_css', $skripsi_request_css);
    }

    /**
     * Get skripsi Request List From Ajax.
     *
     * @param CustomRequest ajax request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSkripsiRequestList(Request $request)
    {
        $student = \Auth::guard('student')->user();
        $requests = $student->requests()
                            ->where(function ($query) {
                                $query->where('type', CreationType::Skripsi)
                                      ->orWhere('type', CreationType::Tesis);
                            });

        return Datatables::of($requests)
                        ->setRowClass(function () {
                            return "custom-tr-text-ellipsis";
                        })
                        ->filterColumn('start_date', function ($query, $keyword) {
                            $query->whereRaw("DATE_FORMAT(start_date,'%d %M %Y') like ?", ["%$keyword%"]);
                        })
                        ->filterColumn('end_date', function ($query, $keyword) {
                            $query->whereRaw("DATE_FORMAT(end_date,'%d %M %Y') like ?", ["%$keyword%"]);
                        })
                        ->editColumn('status', function(CustomRequest $request) {
                            return $this->getRecordsStatusLabel($request);
                        })
                        ->editColumn('type', function(CustomRequest $request) {
                            return $this->getRecordsTypeLabel($request);
                        })
                        ->editColumn('start_date', function(CustomRequest $request) {
                            if ($request->start_date) {
                                return date('d M Y', strtotime($request->start_date));
                            } else {
                                return '-';
                            }
                        })
                        ->editColumn('end_date', function(CustomRequest $request) {
                            if ($request->end_date) {
                                return date('d M Y', strtotime($request->end_date));
                            } else {
                                return '-';
                            }
                        })
                        ->addColumn('actions', function (CustomRequest $customRequest)  {
                            return $this->getRecordsActionsButtons($customRequest);
                        })
                        ->rawColumns(['actions', 'type', 'status'])
                        ->make(true);
    }

    /**
     * Get create new request view.
     *
     * @param empty
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $student = \Auth::guard('student')->user();

        $breadcrumbs = $this->getBreadCrumbs('createSkripsiRequest');
        $title = $this->getTitle('createSkripsiRequest');
        $sub_title = $this->getSubTitle('createSkripsiRequest');
        $request_css = "active";
        $skripsi_request_css = "active";
        $btn_label = "Buat";
        $statuses = RequestStatus::getStrings();
        $types = CreationType::getStringsExcept(CreationType::KP);
        $sessions = ['1' => '1','2' => '2','3' => '3'];

        // add list of attribute for dosen
        $prodiUser = new ProdiUser;
        $prodiUserModel = $prodiUser::leftJoin('prodi_user_assignment AS pua', 'pua.prodi_user_id', '=', 'prodi_user.id')
                                ->where('is_admin', 0)->where('pua.study_program_id', $student->study_program_id)
                                ->selectRaw('prodi_user.*, pua.id AS assignment_id')->get();

        // call total SA point API
        $client = new Client();

        $response = $client->request('POST', $this->saPoinAPIEndPoint, [
            'json' => [
                'npm' => $student->npm,
                'id' => $this->API_ID,
                'password' => $this->API_PASSWORD,            ]
        ]);

        $responseBody = json_decode($response->getBody());

        $saPoint = $responseBody->total_point;

        $prodiUserArray = [];
        foreach($prodiUserModel as $value) {
            // enhancement not store by prodi user id will be store assignment id 
            $prodiUserArray[$value->assignment_id] = $value->username;
        }
        
        return view('student.request.create-or-edit-skripsi-request')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('request_css', $request_css)
                ->with('skripsi_request_css', $skripsi_request_css)
                ->with('btn_label', $btn_label)
                ->with('statuses', $statuses)
                ->with('sessions', $sessions)
                ->with('types', $types)
                ->with('prodi_user', $prodiUserArray)
                ->with('saPoint', $saPoint);
    }

    /**
     * Save new request.
     *
     * @param empty
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function store()
    {
        $customRequest = new CustomRequest;
        $student = \Auth::guard('student')->user();

        $data = Input::all();
        
        $data['status'] = RequestStatus::Draft;
        $data['student'] = $student->id;

        $customRequest->type = $data['type']; // provide the type to model first to support validation

        // for this section check student has request kp on process or not dont allow student to multiple requesting
        if($customRequest->type != null && $customRequest->type != 0) {
            $checkCustomRequest = CustomRequest::where('student_id', $student->id)
                        ->where('type', $customRequest->type)
                        ->where(function($query) {
                            $query->where('status', '!=', RequestStatus::Reject)
                                ->where('status', '!=', RequestStatus::RejectProdi)
                                ->where('status', '!=', RequestStatus::RejectFinance);
                        })->get();
            
            // for this section wheres student already complete their KP
            $checkCustomRequest1 = CustomRequest::where('student_id', $student->id)
                            ->where('type', $customRequest->type)
                            ->where(function($query) {
                                $query->where('status_lulus', 8);
                            })->get();
        }

        if ($customRequest->validate($customRequest, $data, $customRequest->messages('validation'))) {
            DB::beginTransaction();
            try {
                if($checkCustomRequest1->count() != 0) {
                    $errors = new MessageBag([$customRequest->messages('failRequestSkripsiAlreadyDone')]);
                    if($data['type'] == CreationType::Tesis) {
                        $errors = new MessageBag([$customRequest->messages('failRequestTesisAlreadyDone')]);
                    }
                    return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
                }
                if($checkCustomRequest->count() != 0) {
                    $errors = new MessageBag([$customRequest->messages('failRequestMultipleSkripsi')]);
                    if($data['type'] == CreationType::Tesis) {
                        $errors = new MessageBag([$customRequest->messages('failRequestMultipleTesis')]);
                    }
                    return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
                }

                // store
                $customRequest->session = $data['session'];
                $customRequest->type = $data['type'];
                $customRequest->title = $data['title'];
                $customRequest->status = $data['status'];
                if ($customRequest->session != '1') 
                {
                    $customRequest->repeat_reason = $data['repeat_reason'];
                }
                $customRequest->start_date = Carbon::parse($data['start_date']);
                $customRequest->end_date = Carbon::parse($data['end_date']);
                $customRequest->mentor_name = $data['mentor_name'];
                $customRequest->mentor_id = $data['mentor_id'];
                $customRequest->student_id = $data['student'];
                // new for status sidang
                $customRequest->status_sidang = StatusSidang::Waiting;
                $customRequest->sa_point = $data['sa_point'];
                $customRequest->save();

                $requestId = $customRequest->id;

                $typeFolder = 'SKRIPSI';
                if($customRequest->type == CreationType::Tesis) {
                    $typeFolder = 'TESIS';
                }

                // store row of persetujuan data to table
                $attachment = new RequestAttachmentTypeModel;
                $file = $data['lembar_persetujuan'];
                // $file
                $fileName = $student->npm.'-'.$typeFolder.'-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                // set lembar persetujuan object
                $attachment->request_id = $requestId;
                $attachment->attachment_type = RequestAttachment::LEMBAR_PERSETUJUAN;
                $attachment->file_name = $fileName;
                $attachment->file_display_name = $file->getClientOriginalName();
                $attachment->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::LEMBAR_PERSETUJUAN)) . $fileName;
                $attachment->uploaded_on = now();
                $attachment->created_at = now();
                // move to folder 
                $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::LEMBAR_PERSETUJUAN)), $fileName);
                // store data into db
                $attachment->save();

                // store row of kartu bimbingan data to table
                $attachment = new RequestAttachmentTypeModel;
                $file = $data['kartu_bimbingan'];
                // $file
                $fileName = $student->npm.'-'.$typeFolder.'-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                // set lembar persetujuan object
                $attachment->request_id = $requestId;
                $attachment->attachment_type = RequestAttachment::KARTU_BIMBINGAN;
                $attachment->file_name = $fileName;
                $attachment->file_display_name = $file->getClientOriginalName();
                $attachment->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::KARTU_BIMBINGAN)) . $fileName;
                $attachment->uploaded_on = now();
                $attachment->created_at = now();
                // move to folder 
                $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::KARTU_BIMBINGAN)), $fileName);
                // store data into db
                $attachment->save();

                // store row of lembar turnitin data to table
                $attachment = new RequestAttachmentTypeModel;
                $file = $data['lembar_turnitin'];
                // $file
                $fileName = $student->npm.'-'.$typeFolder.'-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                // set lembar persetujuan object
                $attachment->request_id = $requestId;
                $attachment->attachment_type = RequestAttachment::LEMBAR_TURNITIN;
                $attachment->file_name = $fileName;
                $attachment->file_display_name = $file->getClientOriginalName();
                $attachment->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::LEMBAR_TURNITIN)) . $fileName;
                $attachment->uploaded_on = now();
                $attachment->created_at = now();
                // move to folder 
                $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::LEMBAR_TURNITIN)), $fileName);
                // store data into db
                $attachment->save();

                // store row of lembar plagiat data to table
                $attachment = new RequestAttachmentTypeModel;
                $file = $data['lembar_plagiat'];
                // $file
                $fileName = $student->npm.'-'.$typeFolder.'-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                // set lembar persetujuan object
                $attachment->request_id = $requestId;
                $attachment->attachment_type = RequestAttachment::ANTI_PLAGIAT;
                $attachment->file_name = $fileName;
                $attachment->file_display_name = $file->getClientOriginalName();
                $attachment->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::ANTI_PLAGIAT)) . $fileName;
                $attachment->uploaded_on = now();
                $attachment->created_at = now();
                // move to folder 
                $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::ANTI_PLAGIAT)), $fileName);
                // store data into db
                $attachment->save();

                // store row of foto meteor data to table
                $attachment = new RequestAttachmentTypeModel;
                $file = $data['foto_meteor'];
                // $file
                $fileName = $student->npm.'-'.$typeFolder.'-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                // set lembar persetujuan object
                $attachment->request_id = $requestId;
                $attachment->attachment_type = RequestAttachment::FOTO_METEOR;
                $attachment->file_name = $fileName;
                $attachment->file_display_name = $file->getClientOriginalName();
                $attachment->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::FOTO_METEOR)) . $fileName;
                $attachment->uploaded_on = now();
                $attachment->created_at = now();
                // move to folder 
                $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::FOTO_METEOR)), $fileName);
                // store data into db
                $attachment->save();

                // store row of official toeic data to table
                $attachment = new RequestAttachmentTypeModel;
                $file = $data['official_toeic'];
                // $file
                $fileName = $student->npm.'-'.$typeFolder.'-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                // set lembar persetujuan object
                $attachment->request_id = $requestId;
                $attachment->attachment_type = RequestAttachment::TOEIC_OFFICIAL;
                $attachment->file_name = $fileName;
                $attachment->file_display_name = $file->getClientOriginalName();
                $attachment->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::TOEIC_OFFICIAL)) . $fileName;
                $attachment->uploaded_on = now();
                $attachment->created_at = now();
                // move to folder 
                $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::TOEIC_OFFICIAL)), $fileName);
                // store data into db
                $attachment->save();

                // store row of official toeic data to table
                $attachment = new RequestAttachmentTypeModel;
                $file = $data['abstract_uclc'];
                // $file
                $fileName = $student->npm.'-'.$typeFolder.'-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                // set lembar persetujuan object
                $attachment->request_id = $requestId;
                $attachment->attachment_type = RequestAttachment::ABSTRACT_UCLC;
                $attachment->file_name = $fileName;
                $attachment->file_display_name = $file->getClientOriginalName();
                $attachment->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::ABSTRACT_UCLC)) . $fileName;
                $attachment->uploaded_on = now();
                $attachment->created_at = now();
                // move to folder 
            $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::ABSTRACT_UCLC)), $fileName);
                // store data into db
                $attachment->save();

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $customRequest->messages('success', 'create'));
                return redirect($this->getRoute('list'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('create'), $e);
            }
        } else {
            $errors = $customRequest->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('create'), $errors);
        }
    }

    /**
     * Edit request.
     *
     * @param model binding request
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function edit(CustomRequest $customRequest)
    {
        $breadcrumbs = $this->getBreadCrumbs('editSkripsi');
        $title = $this->getTitle('editSkripsi');
        $sub_title = $this->getSubTitle('editSkripsi');
        $request_css = "active";
        $skripsi_request_css = "active";
        $btn_label = "Ubah";
        $statuses = RequestStatus::getStrings();
        $types = CreationType::getStringsExcept(CreationType::KP);
        $sessions = ['1' => '1','2' => '2','3' => '3'];

        // set dosen dropdown data
        $prodiUser = new ProdiUser;
        $prodiUserModel = $prodiUser::leftJoin('prodi_user_assignment AS pua', 'pua.prodi_user_id', '=', 'prodi_user.id')
                        ->where('is_admin', 0)->where('pua.study_program_id', $customRequest->student()->first()->study_program_id)
                        ->selectRaw('prodi_user.*, pua.id AS assignment_id')->get();
        $dospemArray = []; 
        foreach($prodiUserModel as $value) {
            $dospemArray[$value->assignment_id] = $value->username;
        }

        // set object of persetujuan attachment and kartu bimbingan object and lembar turnitin object
        $lembar_persetujuan = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::LEMBAR_PERSETUJUAN)
                                ->first();
        $kartu_bimbingan = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::KARTU_BIMBINGAN)                    
                                ->first();
        $lembar_turnitin = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::LEMBAR_TURNITIN)
                                ->first();
        $lembar_plagiat = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::ANTI_PLAGIAT)
                                ->first();
        $foto_meteor = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::FOTO_METEOR)
                                ->first();
        $official_toeic = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::TOEIC_OFFICIAL)
                                ->first();
        $abstract_uclc = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::ABSTRACT_UCLC)
                                ->first();

        return view('student.request.create-or-edit-skripsi-request')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('request_css', $request_css)
                ->with('skripsi_request_css', $skripsi_request_css)
                ->with('customRequest', $customRequest)
                ->with('btn_label', $btn_label)
                ->with('statuses', $statuses)
                ->with('sessions', $sessions)
                ->with('types', $types)
                ->with('prodi_user', $dospemArray)
                ->with('lembar_persetujuan', $lembar_persetujuan)
                ->with('kartu_bimbingan', $kartu_bimbingan)
                ->with('lembar_turnitin', $lembar_turnitin)
                ->with('lembar_plagiat', $lembar_plagiat)
                ->with('foto_meteor', $foto_meteor)
                ->with('official_toeic', $official_toeic)
                ->with('abstract_uclc', $abstract_uclc);
    }

    /**
     * Update request.
     *
     * @param CustomRequest $request, Request request
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function update(CustomRequest $customRequest, Request $request)
    {
        $student = \Auth::guard('student')->user();

        $data = Input::all();
        $data['type'] = $customRequest->type;
        $data['status'] = RequestStatus::Draft;
        $data['student'] = $student->id;

        // identify to upload or not
        $customRequest->file_upload = "NO";
        $customRequest->file_upload2 = "NO";
        $customRequest->file_upload3 = "NO";
        $customRequest->file_upload4 = "NO";
        $customRequest->file_upload5 = "NO";
        $customRequest->file_upload6 = "NO";
        $customRequest->file_upload7 = "NO";
        
        // fileeditable from form
        $fileEditable = $data['fileEditable'];
        $fileEditable2 = $data['fileEditable2'];
        $fileEditable3 = $data['fileEditable3'];
        $fileEditable4 = $data['fileEditable4'];
        $fileEditable5 = $data['fileEditable5'];
        $fileEditable6 = $data['fileEditable6'];
        $fileEditable7 = $data['fileEditable7'];

        if($fileEditable == '1') {
            $customRequest->file_upload = 'YES';
        }

        if($fileEditable2 == '1') {
            $customRequest->file_upload2 = 'YES';
        }
        
        if($fileEditable3 == '1') {
            $customRequest->file_upload3 = 'YES';
        }

        if($fileEditable4 == '1') {
            $customRequest->file_upload4 = 'YES';
        }

        if($fileEditable5 == '1') {
            $customRequest->file_upload5 = 'YES';
        }

        if($fileEditable6 == '1') {
            $customRequest->file_upload6 = 'YES';
        }

        if($fileEditable7 == '1') {
            $customRequest->file_upload7 = 'YES';
        }

        if ($customRequest->validate($customRequest, $data, $customRequest->messages('validation'))) {
            DB::beginTransaction();
            try {
                // store
                $customRequest->session = $data['session'];
                $customRequest->type = $data['type'];
                $customRequest->title = $data['title'];
                $customRequest->status = $data['status'];
                if ($customRequest->session != '1') 
                {
                    $customRequest->repeat_reason = $data['repeat_reason'];
                }
                $customRequest->start_date = Carbon::parse($data['start_date']);
                $customRequest->end_date = Carbon::parse($data['end_date']);
                $customRequest->mentor_name = $data['mentor_name'];
                $customRequest->student_id = $data['student'];
                $customRequest->mentor_id = $data['mentor_id'];
                $customRequest->save();

                // request id
                $request_id = $customRequest->id;

                // type karya ilmiah
                $ilmiahType = 'SKRIPSI';
                if($customRequest->type == CreationType::Tesis) {
                    $ilmiahType = 'TESIS';
                }

                // lembar persetujuan
                if($customRequest->file_upload == 'YES') {
                    $persetujuan = RequestAttachmentTypeModel::where('request_id', $request_id)
                                                            ->where('attachment_type', RequestAttachment::LEMBAR_PERSETUJUAN)
                                                            ->first();
                    $persetujuanOldFile = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::LEMBAR_PERSETUJUAN)) . $persetujuan->file_name;
                    if(file_exists($persetujuanOldFile)) {
                        unlink($persetujuanOldFile);
                    }
                    
                    $file = $data['lembar_persetujuan'];
                    $fileName = $student->npm.'-'.$ilmiahType.'-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                    $persetujuan->file_name = $fileName;
                    $persetujuan->file_display_name = $file->getClientOriginalName();
                    $persetujuan->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::LEMBAR_PERSETUJUAN)) . $persetujuan->file_name;
                    $persetujuan->uploaded_on = now();
                    $persetujuan->updated_at = now();
                    // move to folder
                    $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::LEMBAR_PERSETUJUAN)), $fileName);
                    $persetujuan->save();
                }

                // kartu bimbingan
                if($customRequest->file_upload2 == 'YES') {
                    $kartu_bimbingan = RequestAttachmentTypeModel::where('request_id', $request_id)
                                                            ->where('attachment_type', RequestAttachment::KARTU_BIMBINGAN)
                                                            ->first();
                    $kartuBimbinganOldFile = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::KARTU_BIMBINGAN)) . $kartu_bimbingan->file_name;
                    if(file_exists($kartuBimbinganOldFile)) {
                        unlink($kartuBimbinganOldFile);
                    }
                    
                    $file = $data['kartu_bimbingan'];
                    $fileName = $student->npm.'-'.$ilmiahType.'-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                    $kartu_bimbingan->file_name = $fileName;
                    $kartu_bimbingan->file_display_name = $file->getClientOriginalName();
                    $kartu_bimbingan->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::KARTU_BIMBINGAN)) . $kartu_bimbingan->file_name;
                    $kartu_bimbingan->uploaded_on = now();
                    $kartu_bimbingan->updated_at = now();
                    // move to folder
                    $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::KARTU_BIMBINGAN)), $fileName);
                    $kartu_bimbingan->save();
                }

                // lembar turnitin
                if($customRequest->file_upload3 == 'YES') {
                    $lembar_turnitin = RequestAttachmentTypeModel::where('request_id', $request_id)
                                                            ->where('attachment_type', RequestAttachment::LEMBAR_TURNITIN)
                                                            ->first();
                    $lembarTurnitinOldFile = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::LEMBAR_TURNITIN)) . $lembar_turnitin->file_name;
                    if(file_exists($lembarTurnitinOldFile)) {
                        unlink($lembarTurnitinOldFile);
                    }
                    
                    $file = $data['lembar_turnitin'];
                    $fileName = $student->npm.'-'.$ilmiahType.'-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                    $lembar_turnitin->file_name = $fileName;
                    $lembar_turnitin->file_display_name = $file->getClientOriginalName();
                    $lembar_turnitin->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::LEMBAR_TURNITIN)) . $lembar_turnitin->file_name;
                    $lembar_turnitin->uploaded_on = now();
                    $lembar_turnitin->updated_at = now();
                    // move to folder
                    $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::LEMBAR_TURNITIN)), $fileName);
                    $lembar_turnitin->save();
                }

                // lembar plagiat
                if($customRequest->file_upload4 == 'YES') {
                    $lembar_plagiat = RequestAttachmentTypeModel::where('request_id', $request_id)
                                                            ->where('attachment_type', RequestAttachment::ANTI_PLAGIAT)
                                                            ->first();
                    $lembarPlagiatOldFile = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::ANTI_PLAGIAT)) . $lembar_plagiat->file_name;
                    if(file_exists($lembarPlagiatOldFile)) {
                        unlink($lembarPlagiatOldFile);
                    }
                    
                    $file = $data['lembar_plagiat'];
                    $fileName = $student->npm.'-'.$ilmiahType.'-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                    $lembar_plagiat->file_name = $fileName;
                    $lembar_plagiat->file_display_name = $file->getClientOriginalName();
                    $lembar_plagiat->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::ANTI_PLAGIAT)) . $lembar_plagiat->file_name;
                    $lembar_plagiat->uploaded_on = now();
                    $lembar_plagiat->updated_at = now();
                    // move to folder
                    $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::ANTI_PLAGIAT)), $fileName);
                    $lembar_plagiat->save();
                }

                // foto meteor
                if($customRequest->file_upload5 == 'YES') {
                    $foto_meteor = RequestAttachmentTypeModel::where('request_id', $request_id)
                                                            ->where('attachment_type', RequestAttachment::FOTO_METEOR)
                                                            ->first();
                    $fotoMeteorOldFile = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::FOTO_METEOR)) . $foto_meteor->file_name;
                    if(file_exists($fotoMeteorOldFile)) {
                        unlink($fotoMeteorOldFile);
                    }
                    
                    $file = $data['foto_meteor'];
                    $fileName = $student->npm.'-'.$ilmiahType.'-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                    $foto_meteor->file_name = $fileName;
                    $foto_meteor->file_display_name = $file->getClientOriginalName();
                    $foto_meteor->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::FOTO_METEOR)) . $foto_meteor->file_name;
                    $foto_meteor->uploaded_on = now();
                    $foto_meteor->updated_at = now();
                    // move to folder
                    $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::FOTO_METEOR)), $fileName);
                    $foto_meteor->save();
                }

                // official toeic
                if($customRequest->file_upload6 == 'YES') {
                    $official_toeic = RequestAttachmentTypeModel::where('request_id', $request_id)
                                                            ->where('attachment_type', RequestAttachment::TOEIC_OFFICIAL)
                                                            ->first();
                    $officialToeicOldFile = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::TOEIC_OFFICIAL)) . $official_toeic->file_name;
                    if(file_exists($officialToeicOldFile)) {
                        unlink($officialToeicOldFile);
                    }
                                        
                    $file = $data['official_toeic'];
                    $fileName = $student->npm.'-'.$ilmiahType.'-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                    $official_toeic->file_name = $fileName;
                    $official_toeic->file_display_name = $file->getClientOriginalName();
                    $official_toeic->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::TOEIC_OFFICIAL)) . $official_toeic->file_name;
                    $official_toeic->uploaded_on = now();
                    $official_toeic->updated_at = now();
                    // move to folder
                    $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::TOEIC_OFFICIAL)), $fileName);
                    $official_toeic->save();
                }

                // abstract uclc
                if($customRequest->file_upload7 == 'YES') {
                    $abstract_uclc = RequestAttachmentTypeModel::where('request_id', $request_id)
                                                            ->where('attachment_type', RequestAttachment::ABSTRACT_UCLC)
                                                            ->first();
                    $abstractUclcOldFile = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::ABSTRACT_UCLC)) . $abstract_uclc->file_name;
                    if(file_exists($abstractUclcOldFile)) {
                        unlink($abstractUclcOldFile);
                    }
                    
                    $file = $data['abstract_uclc'];
                    $fileName = $student->npm.'-'.$ilmiahType.'-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                    $abstract_uclc->file_name = $fileName;
                    $abstract_uclc->file_display_name = $file->getClientOriginalName();
                    $abstract_uclc->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::ABSTRACT_UCLC)) . $abstract_uclc->file_name;
                    $abstract_uclc->uploaded_on = now();
                    $abstract_uclc->updated_at = now();
                    // move to folder
                    $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::ABSTRACT_UCLC)), $fileName);
                    $abstract_uclc->save();
                }

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $customRequest->messages('success', 'update'));
                return redirect($this->getRoute('list'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $customRequest->id), $e);
            }
        } else {
            $errors = $customRequest->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('edit', $customRequest->id), $errors);
        }
    }

    /**
     * Destroy request.
     *
     * @param model binding request
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function destroy(CustomRequest $customRequest)
    {
        try
        {
            if ($customRequest->status != RequestStatus::Draft) {
                throw new \Exception($customRequest->messages('failDeleteCauseOfStatus'));
            }

            // remove the file and delete the data (persetujuan, kartu bimbingan, lembar turnitin)
            $request_id = $customRequest->id;   

            $student = $customRequest->student()->first();
                   
            $lembar_persetujuan = RequestAttachmentTypeModel::where('request_id', $request_id)
                                    ->where('attachment_type', RequestAttachment::LEMBAR_PERSETUJUAN)
                                    ->first();
            $kartu_bimbingan = RequestAttachmentTypeModel::where('request_id', $request_id)
                                    ->where('attachment_type', RequestAttachment::KARTU_BIMBINGAN)
                                    ->first();
            $lembar_turnitin = RequestAttachmentTypeModel::where('request_id', $request_id)
                                    ->where('attachment_type', RequestAttachment::LEMBAR_TURNITIN)
                                    ->first();
            $lembar_plagiat = RequestAttachmentTypeModel::where('request_id', $request_id)
                                    ->where('attachment_type', RequestAttachment::ANTI_PLAGIAT)
                                    ->first();
            $foto_meteor = RequestAttachmentTypeModel::where('request_id', $request_id)
                                    ->where('attachment_type', RequestAttachment::FOTO_METEOR)
                                    ->first();
            $official_toeic = RequestAttachmentTypeModel::where('request_id', $request_id)
                                    ->where('attachment_type', RequestAttachment::TOEIC_OFFICIAL)
                                    ->first();
            $abstract_uclc = RequestAttachmentTypeModel::where('request_id', $request_id)
                                    ->where('attachment_type', RequestAttachment::ABSTRACT_UCLC)
                                    ->first();
           
                                    // dd('aw');
            $persetujuan = $this->getStudentRequestAttachmentFolderPath($student, $customRequest->type , $request_id, RequestAttachment::getString(RequestAttachment::LEMBAR_PERSETUJUAN)) . $lembar_persetujuan->file_name;                        
            if(file_exists($persetujuan)) {
                unlink($persetujuan);    
            }            
            $lembar_persetujuan->delete();
            
            $kartuBimbingan = $this->getStudentRequestAttachmentFolderPath($student, $customRequest->type, $request_id, RequestAttachment::getString(RequestAttachment::KARTU_BIMBINGAN)) . $kartu_bimbingan->file_name;
            if(file_exists($kartuBimbingan)) {
                unlink($kartuBimbingan);
            }
            $kartu_bimbingan->delete();

            $lembarTurnitin = $this->getStudentRequestAttachmentFolderPath($student, $customRequest->type, $request_id, RequestAttachment::getString(RequestAttachment::LEMBAR_TURNITIN)) . $lembar_turnitin->file_name;
            if(file_exists($lembarTurnitin)) {
                unlink($lembarTurnitin);
            }
            $lembar_turnitin->delete();

            $lembarPlagiat = $this->getStudentRequestAttachmentFolderPath($student, $customRequest->type, $request_id, RequestAttachment::getString(RequestAttachment::ANTI_PLAGIAT)) . $lembar_plagiat->file_name;
            if(file_exists($lembarPlagiat)) {
                unlink($lembarPlagiat);
            }
            $lembar_plagiat->delete();

            $fotoMeteor = $this->getStudentRequestAttachmentFolderPath($student, $customRequest->type, $request_id, RequestAttachment::getString(RequestAttachment::FOTO_METEOR)) . $foto_meteor->file_name;
            if(file_exists($fotoMeteor)) {
                unlink($fotoMeteor);
            }
            $foto_meteor->delete();

            $officialToeic = $this->getStudentRequestAttachmentFolderPath($student, $customRequest->type, $request_id, RequestAttachment::getString(RequestAttachment::TOEIC_OFFICIAL)) . $official_toeic->file_name;
            if(file_exists($officialToeic)) {
                unlink($officialToeic);
            }
            $official_toeic->delete();

            $abstractUclc = $this->getStudentRequestAttachmentFolderPath($student, $customRequest->type, $request_id, RequestAttachment::getString(RequestAttachment::ABSTRACT_UCLC)) . $abstract_uclc->file_name;
            if(file_exists($abstractUclc)) {
                unlink($abstractUclc);
            }
            $abstract_uclc->delete();
            
            $customRequest->delete();

            // redirect
            $this->setFlashMessage('success', $customRequest->messages('success', 'delete'));
            return redirect($this->getRoute('list'));

        } catch (\Exception $e) {
            $errors = new MessageBag([$customRequest->messages('failDelete')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
    }

    /**
     * Update Request Status (actually used for submit request).
     *
     * @param CustomRequest $customRequest
     *
     * @return string
     */
    public function updateRequestStatus(CustomRequest $customRequest)
    {
        DB::beginTransaction();
        try
        {
            if ($customRequest->status == RequestStatus::Draft) {
                $customRequest->status = RequestStatus::Verification;
                $customRequest->status_keuangan = 0;
                // do this after finance better
                // $customRequest->expiry_date = Carbon::today()->addWeeks(1);
            } else {
                abort(404);
            }

            $should_update_status = true;
            $parents_valid = true;

            $student = \Auth::guard('student')->user();

            // ijazah s1 validation
            $ijazahS1 = $student->attachments()->where('type', AttachmentType::IjazahS1)->first();
            
            $existing_degree = $student->existing_degree;

            // requests validation
            $verifyingRequest = $student->requests()
                                        ->where('type', $customRequest->type)
                                        ->where('status', RequestStatus::Verification)->first();
            $isStudentSemesterActive = $student->semester()->first()->is_active;
            $isStudentProfileValid = $student->getStudentProfileValidStatus();

            // parents validation
            $father = $student->parents()->where('type', ParentType::Father)->first();
            $mother = $student->parents()->where('type', ParentType::Mother)->first();

            if ($verifyingRequest != null || !$isStudentSemesterActive || !$isStudentProfileValid) {
                $should_update_status = false;
            }

            if ($father === null || $mother === null) {
                $parents_valid = false;
            }

            if ($customRequest->type == CreationType::Tesis) {
                if ($ijazahS1 == null || $existing_degree == null) {
                    $this->setFlashMessage('danger', $customRequest->messages('failSubmitTesisRequestLimitAttachmentAndDegree'));

                    // redirect
                    return redirect($this->getRoute('list'));
                }
            }
            
            if ($should_update_status && $parents_valid) {
                $customRequest->save();

                $adminAddress = \Config::get('customconfig.admin_address');
                // send request submitted information email to student
                Mail::to($customRequest->student()->first())->send(new RequestSubmitted($customRequest, 'mahasiswa_akhir'));
                // send request submitted information email to admin
                // Mail::to($adminAddress)->send(new RequestSubmitted($customRequest, 'admin'));
                Mail::to($adminAddress)->send(new RequestSubmitted($customRequest, 'finance_validation'));
                // send request submitted information email to finance
                // $financeEmail = 'Finance@uib.ac.id';
                $financeEmail = 'stevenyeo70@gmail.com';
                Mail::to($financeEmail)->send(new RequestSubmitted($customRequest, 'finance'));
                $this->setFlashMessage('success', $customRequest->messages('success', 'update'));
            } elseif (!$should_update_status) {
                if ($verifyingRequest != null) {
                    $this->setFlashMessage('danger', $customRequest->messages('failSubmitRequestLimitError'));
                }

                if (!$isStudentSemesterActive) {
                    $this->setFlashMessage('danger', $customRequest->messages('failSubmitRequestSemesterInactiveError'));
                }
                
                if (!$isStudentProfileValid) {
                    $this->setFlashMessage('danger', $student->messages('studentProfileInvalidIssue'));
                }
            } elseif (!$parents_valid) {
                $this->setFlashMessage('danger', $customRequest->messages('failSubmitRequestParentInvalidError'));
            }

            DB::commit();
            // redirect
            return redirect($this->getRoute('list'));
        } catch (\Exception $e) {
            DB::rollback();
            $errors = new MessageBag([$customRequest->messages('failSubmitRequest')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
    }

    /**
     * Show the skripsi request's data.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewRequest(CustomRequest $customRequest)
    {
        $breadcrumbs = $this->getBreadCrumbs('viewSkripsiRequest');
        $title = $this->getTitle('viewSkripsiRequest');
        $sub_title = $this->getSubTitle('viewSkripsiRequest');
        $request_css = "active";
        $skripsi_request_css = "active";
        $student = $customRequest->student()->first();
        $study_program = $student->studyProgram()->first();
        $request_type = CreationType::getString($customRequest->type);
        $request_status = RequestStatus::getString($customRequest->status);
        $back_route = $this->getRoute('list');
        $is_skripsi_or_tesis_request = 'yes';

        // set object of persetujuan attachment and kartu bimbingan object and lembar turnitin, anti plagiat, foto meteor, toeic, abstract uclc
        $lembar_persetujuan = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::LEMBAR_PERSETUJUAN)
                                ->first();
        $kartu_bimbingan = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::KARTU_BIMBINGAN)
                                ->first();
        $lembar_turnitin = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::LEMBAR_TURNITIN)
                                ->first();
        $lembar_plagiat = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::ANTI_PLAGIAT)
                                ->first();
        $foto_meteor = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::FOTO_METEOR)
                                ->first();
        $abstract_uclc = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::ABSTRACT_UCLC)
                                ->first();
        $official_toeic = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::TOEIC_OFFICIAL)
                                ->first();
        

        return view('admin.request.view_request')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('request_css', $request_css)
                ->with('request', $customRequest)
                ->with('student', $student)
                ->with('study_program', $study_program)
                ->with('request_type', $request_type)
                ->with('request_status', $request_status)
                ->with('back_route', $back_route)
                ->with('skripsi_request_css', $skripsi_request_css)
                ->with('is_skripsi_or_tesis_request', $is_skripsi_or_tesis_request)
                ->with('lembar_persetujuan', $lembar_persetujuan)
                ->with('kartu_bimbingan', $kartu_bimbingan)
                ->with('lembar_turnitin', $lembar_turnitin)
                ->with('lembar_plagiat', $lembar_plagiat)
                ->with('foto_meteor', $foto_meteor)
                ->with('abstract_uclc', $abstract_uclc)
                ->with('official_toeic', $official_toeic);
    }

    /**
     * Get all routes.
     *
     * @param int $id
     *
     * @return string
     */
    public function getRoute($key, $id = null)
    {
        switch ($key) {
            case 'list':
                return route('student.request.skripsi');
            case 'create':
                return route('student.request.skripsi.create');
            case 'edit':
                return route('student.request.skripsi.edit', $id);
            case 'destroy':
                return route('student.request.skripsi.destroy', $id);
            case 'update_request_status':
                return route('student.request.skripsi.update.status', $id);
            case 'view':
                return route('request.request.skripsi.view', $id);
            
            default:
                # code...
                break;
        }
    }

    /**
     * Get a refactored status labels for request datatable.
     *
     * @param CustomRequest $request
     *
     * @return string
     */
    private function getRecordsStatusLabel(CustomRequest $request)
    {
        $status = RequestStatus::getString($request->status);

        if ($request->status == RequestStatus::Verification) {
            $extra_class = 'info';
        } elseif ($request->status == RequestStatus::Accept || $request->status == RequestStatus::AcceptProdi || $request->status == RequestStatus::AcceptFinance) {
            $extra_class = 'success';
        } elseif ($request->status == RequestStatus::Reject || $request->status == RequestStatus::RejectBySistem || $request->status == RequestStatus::RejectProdi || $request->status == RequestStatus::RejectFinance) {
            $extra_class = 'danger';
        } elseif ($request->status == RequestStatus::Draft) {
            $extra_class = 'warning';
        }

        return "<span class='label label-{$extra_class}'>{$status}</span>";
    }

    /**
     * Get a refactored type labels for request datatable.
     *
     * @param CustomRequest $request
     *
     * @return string
     */
    private function getRecordsTypeLabel(CustomRequest $request)
    {
        $type = CreationType::getString($request->type);

        if ($request->type == CreationType::Skripsi) {
            $extra_class = 'success';
        } else {
            $extra_class = 'primary';
        }

        return "<span class='label label-{$extra_class}'>{$type}</span>";
    }

    /**
     * Get a refactored action buttons for skripsi requests datatable.
     *
     * @param CustomRequest $request
     *
     * @return string
     */
    private function getRecordsActionsButtons(CustomRequest $customRequest)
    {
        $update = $this->getRoute('update_request_status', $customRequest->id);
        $view =  $this->getRoute('view', $customRequest->id);
        $request_type =  $customRequest->type;
        $creation_type = json_encode(CreationType::getStringsExcept(CreationType::KP));
        $glowingBtnClass = '';

        if ($customRequest->status == RequestStatus::Draft) {
            $btn_label = "Serahkan";
            $glowingBtnClass = 'glowing-button';
        }

        if (isset($btn_label)) {
            return $this->getActionsButtons($customRequest)."<a title='SERAHKAN PENDAFTARAN' class='btn btn-primary update-request-status-confirmation {$glowingBtnClass} m-t-3' data-toggle='modal' data-types='{$creation_type}' data-type='{$request_type}' data-url='{$update}' data-id='{$customRequest->id}' data-target='#update-request-status-confirmation-modal'><span class='fa fa-money'></span> {$btn_label} </a>";
        } else {
            // return $this->getActionsButtons($request);
            return "<a href='{$view}' title='LIHAT' class='btn btn-info'><span class='fa fa-eye'></span> Lihat </a>";
        }
    }

    /**
     * Get a refactored action buttons for skripsi requests datatable.
     *
     * @param CustomRequest $request
     *
     * @return string
     */
    public function getActionsButtons($model, array $extraClassToAdd = [])
    {
        $modelId = $model->id;
        $edit =  $this->getRoute('edit', $modelId);
        $destroy =  $this->getRoute('destroy', $modelId);
        $deleteClass = '';

        if (count($extraClassToAdd) > 0) {
            foreach ($extraClassToAdd as $key => $value) {
                if ($extraClassToAdd[$key] = 'delete') {
                    $deleteClass = $value;
                }
            }
        }
        if ($model->status == RequestStatus::Draft) {
            return "<a href='{$edit}' title='EDIT' class='btn btn-warning'><span class='fa fa-pencil-square-o'></span> Edit </a>
                   <a title='HAPUS' class='btn btn-danger delete-confirmation {$deleteClass}' data-toggle='modal' data-url='{$destroy}' data-id='{$modelId}' data-target='#delete-confirmation-modal'><span class='fa fa-trash-o'></span> Hapus </a>";
        }
        return "<a href='javascript:;' title='EDIT' class='btn btn-warning disabled'><span class='fa fa-pencil-square-o'></span> Edit </a>
                   <a title='HAPUS' class='btn btn-danger delete-confirmation disabled {$deleteClass}'><span class='fa fa-trash-o'></span> Hapus </a>";
    }

    /**
     * preview request attachment method
     */
    public function previewRequestAttachment($id) {
        $student = \Auth::guard('student')->user();

        $requestAttachment = new RequestAttachmentTypeModel;
        $requestAttachmentFile = $requestAttachment->whereRaw('id = ?', $id)->first();
        $requestId = $requestAttachmentFile->request_id;
        $attachmentType = $requestAttachmentFile->attachment_type;
        $fileName = $requestAttachmentFile->file_name;

        // get Type by request
        $request = CustomRequest::where('id', $requestId)->first();

        if($requestAttachmentFile == null) {
            abort(404);
        }

        try {
            $filePath = $this->getStudentRequestAttachmentFolderPath($student, $request->type, $requestId, RequestAttachment::getString($attachmentType)) . $fileName;
            $contentType = explode(".", $fileName)[1];
            // jpeg, png, jpg, pdf
            if($contentType == 'jpeg' || $contentType == 'JPEG') {
                $contentType = 'image/jpeg';
            } else if($contentType == 'png' || $contentType == 'PNG') {
                $contentType = 'image/png';
            } else if($contentType == 'jpg' || $contentType == 'JPG') {
                $contentType = 'image/jpg';
            } else if($contentType == 'pdf' || $contentType == 'PDF') {
                $contentType = 'application/pdf';
            }
            return response()->make(file_get_contents($filePath), 200, ['Content-Type' => $contentType]);
        } catch(\Exception $e) {
            $request = new CustomRequest;
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $requestId), new \Exception($request->messages('fileNotFoundOnDirectory')));
        }
    }
}

