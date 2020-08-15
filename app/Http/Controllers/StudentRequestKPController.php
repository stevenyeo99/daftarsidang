<?php

namespace App\Http\Controllers;

use App\Enums\CreationType;
use App\Enums\ParentType;
use App\Enums\RequestStatus;
use App\Enums\StatusSidang;
use App\Enums\RequestAttachmentType as RequestAttachment;
use App\Http\Controllers\MasterController;
use App\Mail\RequestSubmitted;
use App\Models\Request as CustomRequest;
use App\Models\ProdiUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;
use Yajra\Datatables\Datatables;
use App\Models\RequestAttachment as RequestAttachmentTypeModel;

class StudentRequestKPController extends MasterController
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
     * Show the kp requests.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = $this->getBreadCrumbs('kpRequest');
        $title = $this->getTitle('kpRequest');
        $sub_title = $this->getSubTitle('kpRequest');
        $request_css = "active";
        $kp_request_css = "active";
        $statuses = RequestStatus::getStrings();
        $types = CreationType::getStrings();

        return view('student.request.kp')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('request_css', $request_css)
                ->with('statuses', $statuses)
                ->with('types', $types)
                ->with('kp_request_css', $kp_request_css);
    }

    /**
     * Get KP Request List From Ajax.
     *
     * @param CustomRequest ajax request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKPRequestList(Request $request)
    {
        $student = \Auth::guard('student')->user();
        $requests = $student->requests()
                            ->where('type', CreationType::KP);

        return Datatables::of($requests)
                        ->setRowClass(function () {
                            return "custom-tr-text-ellipsis";
                        })
                        ->editColumn('status', function(CustomRequest $request) {
                            return $this->getRecordsStatusLabel($request);
                        })
                        ->filterColumn('status', function($query, $keyword) {
                            $query->whereRaw("CONCAT(status,'-',status) like ?", ["%{$keyword}%"]);
                        })
                        ->editColumn('type', function(CustomRequest $request) {
                            return $this->getRecordsTypeLabel($request);
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
        $customRequest = new CustomRequest;
        $breadcrumbs = $this->getBreadCrumbs('createKpRequest');
        $title = $this->getTitle('createKpRequest');
        $sub_title = $this->getSubTitle('createKpRequest');
        $request_css = "active";
        $kp_request_css = "active";
        $btn_label = "Buat";
        $statuses = RequestStatus::getStrings();
        $types = CreationType::getStrings();
        $sessions = ['1' => '1','2' => '2','3' => '3'];
        
        // add list of attribute for dosen
        $prodiUser = new ProdiUser;
        $prodiUserModel = $prodiUser::leftJoin('prodi_user_assignment AS pua', 'pua.prodi_user_id', '=', 'prodi_user.id')
                                ->where('is_admin', 0)->where('pua.study_program_id', $student->study_program_id)
                                ->selectRaw('prodi_user.*, pua.id AS assignment_id')->get();
                                
        $prodiUserArray = [];
        foreach($prodiUserModel as $value) {
            // enhancement not store by prodi user id will be store assignment id 
            $prodiUserArray[$value->assignment_id] = $value->username;
        }

        // for this section check student has request kp on process or not dont allow student to multiple requesting
        $checkCustomRequest = CustomRequest::where('student_id', $student->id)
                        ->where(function($query) {
                            $query->where('status', '!=', RequestStatus::Reject)
                                ->where('status', '!=', RequestStatus::RejectProdi);
                        })->get();
        // for this section wheres student already complete their KP
        $checkCustomRequest1 = CustomRequest::where('student_id', $student->id)
                        ->where(function($query) {
                            $query->where('status_lulus', 8);
                        })->get();

        if($checkCustomRequest1->count() != 0) {
            $errors = new MessageBag([$customRequest->messages('failRequestKPAlreadyDone')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
        if($checkCustomRequest->count() != 0) {
            $errors = new MessageBag([$customRequest->messages('failRequestMultipleKP')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }

        return view('student.request.create-or-edit-kp-request')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('request_css', $request_css)
                ->with('kp_request_css', $kp_request_css)
                ->with('btn_label', $btn_label)
                ->with('statuses', $statuses)
                ->with('sessions', $sessions)
                ->with('types', $types)
                ->with('prodi_user', $prodiUserArray);
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
        $data['type'] = CreationType::KP;
        $data['status'] = RequestStatus::Draft;
        $data['student'] = $student->id;
        $customRequest->type = $data['type'];
        
        if ($customRequest->validate($customRequest, $data, $customRequest->messages('validationKP'))) {
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
                $customRequest->mentor_name = $data['mentor_name'];
                // will store mentor_id in the future
                $customRequest->mentor_id = $data['mentor_id'];
                $customRequest->student_id = $data['student'];
                // new for status sidang
                $customRequest->status_sidang = StatusSidang::Waiting;
                $customRequest->save();

                $requestId = $customRequest->id;
                // store row of persetujuan data to table
                $attachment = new RequestAttachmentTypeModel;
                $file = $data['lembar_persetujuan'];
                // $file
                $fileName = $student->npm.'-KP-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
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
                $attachment2 = new RequestAttachmentTypeModel;
                $file2 = $data['kartu_bimbingan'];
                // $file 
                $fileName2 = $student->npm.'-KP-'.time().'-'.(str_replace('', '', $file2->getClientOriginalName()));
                // set kartu bimbingan object
                $attachment2->request_id = $requestId;
                $attachment2->attachment_type = RequestAttachment::KARTU_BIMBINGAN;
                $attachment2->file_name = $fileName2;
                $attachment2->file_display_name = $file2->getClientOriginalName();
                $attachment2->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::KARTU_BIMBINGAN)) . $fileName2;
                $attachment2->uploaded_on = now();
                $attachment2->created_at = now();
                // move to folder
                $file2->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::KARTU_BIMBINGAN)), $fileName2);
                // store data into db
                $attachment2->save();

                // store row of kartu bimbingan data to table
                $attachment3 = new RequestAttachmentTypeModel;
                $file3 = $data['lembar_turnitin'];
                // $file 
                $fileName3 = $student->npm.'-KP-'.time().'-'.(str_replace('', '', $file3->getClientOriginalName()));
                // set kartu bimbingan object
                $attachment3->request_id = $requestId;
                $attachment3->attachment_type = RequestAttachment::LEMBAR_TURNITIN;
                $attachment3->file_name = $fileName3;
                $attachment3->file_display_name = $file3->getClientOriginalName();
                $attachment3->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::LEMBAR_TURNITIN)) . $fileName3;
                $attachment3->uploaded_on = now();
                $attachment3->created_at = now();
                // move to folder
                $file3->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $requestId, RequestAttachment::getString(RequestAttachment::LEMBAR_TURNITIN)), $fileName3);
                // store data into db
                $attachment3->save();

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
            // dd($errors);
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
        $breadcrumbs = $this->getBreadCrumbs('editKp');
        $title = $this->getTitle('editKp');
        $sub_title = $this->getSubTitle('editKp');
        $request_css = "active";
        $kp_request_css = "active";
        $btn_label = "Ubah";
        $statuses = RequestStatus::getStrings();
        $types = CreationType::getStrings();
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

        return view('student.request.create-or-edit-kp-request')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('request_css', $request_css)
                ->with('kp_request_css', $kp_request_css)
                ->with('customRequest', $customRequest)
                ->with('btn_label', $btn_label)
                ->with('statuses', $statuses)
                ->with('sessions', $sessions)
                ->with('types', $types)
                ->with('lembar_persetujuan', $lembar_persetujuan)
                ->with('kartu_bimbingan', $kartu_bimbingan)
                ->with('lembar_turnitin', $lembar_turnitin)
                ->with('prodi_user', $dospemArray);
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

        if($requestAttachmentFile == null) {
            abort(404);
        }

        try {
            $filePath = $this->getStudentRequestAttachmentFolderPath($student, CreationType::KP, $requestId, RequestAttachment::getString($attachmentType)) . $fileName;
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
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit'), new \Exception($request->messages('fileNotFoundOnDirectory')));
        }
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
        $data['type'] = CreationType::KP;
        $data['status'] = RequestStatus::Draft;
        $data['student'] = $student->id;
        // first for persetujuan, second for kartu bimbingan, third for turnitin
        $fileEditable = $data['fileEditable'];
        $fileEditable2 = $data['fileEditable2'];
        $fileEditable3 = $data['fileEditable3'];
        // dd($fileEditable . ' ' . $fileEditable2 . ' ' . $fileEditable3);
        // this for when file dont change
        if($fileEditable == '0' && $fileEditable2 == '0' && $fileEditable3 == '0') {
            if ($customRequest->validate($customRequest, $data, $customRequest->messages('validationNoPersetujuanBimbinganTurnitin'))) {
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
                    $customRequest->mentor_name = $data['mentor_name'];
                    // will store mentor_id in the future
                    $customRequest->mentor_id = $data['mentor_id'];
                    $customRequest->student_id = $data['student'];
                    $customRequest->save();
    
                    DB::commit();
                    // redirect
                    $this->setFlashMessage('success', $customRequest->messages('success', 'update'));
                    return redirect($this->getRoute('list'));
                }
                catch (\Exception $e) {
                    DB::rollback();
                    return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $customRequest->id), $e);
                }
            }
             else {
                $errors = $customRequest->errors();
                return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('edit', $customRequest->id), $errors);
            }
        } else { // this when file got changes only
            $customRequest->file_upload = "YES";
            $customRequest->file_upload2 = "YES";
            $customRequest->file_upload3 = "YES";
            
            // default all files got changes
            $messages = $customRequest->messages('validationNoPersetujuanBimbinganTurnitin');
            
            // 1. file lembar persetujuan and kartu bimbingan got changes
            if($fileEditable == '1' && $fileEditable2 == '1' && $fileEditable3 == '0') {
                $customRequest->file_upload3 = "NO";
                $messages = $customRequest->messages('validationNoPersetujuanBimbingan');
            } 
            // 2. file kartu bimbingan and lembar turnitin got changes
            else if($fileEditable == '0' && $fileEditable2 == '1' && $fileEditable3 == '1') {
                $customRequest->file_upload = "NO";
                $messages = $customRequest->messages('validationNoBimbinganTurnitin');
            } 
            // 3. file lembar persetujuan and lembar turnitin got changes
            else if($fileEditable == '1' && $fileEditable2 == '0' && $fileEditable3 == '1') {
                $customRequest->file_upload2 = "NO";
                $messages = $customRequest->messages('validationNoPersetujuanTurnitin');
            } 
            // 4. file lembar persetujuan got changes
            else if($fileEditable == '1' && $fileEditable2 == '0' && $fileEditable3 == '0') {
                $customRequest->file_upload2 = "NO";
                $customRequest->file_upload3 = "NO";
                $messages = $customRequest->messages('validationNoPersetujuan');
            }
            // 5. file kartu bimbingan got changes 
            else if($fileEditable == '0' && $fileEditable2 == '1' && $fileEditable3 == '0') {
                $customRequest->file_upload = "NO";
                $customRequest->file_upload3 = "NO"; 
                $messages = $customRequest->messages('validationNoBimbingan');
            } 
            // 6. file lembar turnitin got changes
            else if($fileEditable == '0' && $fileEditable2 == '0' && $fileEditable3 == '1') {
                $customRequest->file_upload = "NO";
                $customRequest->file_upload2 = "NO";
                $messages = $customRequest->messages('validationNoTurnitin');
            }
            
            if($customRequest->validate($customRequest, $data, $messages)) {
                DB::beginTransaction();
                try {
                    $customRequest->session = $data['session'];
                    $customRequest->type = $data['type'];
                    $customRequest->title = $data['title'];
                    $customRequest->status = $data['status'];
                    if($customRequest->session != '1') {
                        $customRequest->repeat_reason = $data['repeat_reason'];
                    }
                    $customRequest->mentor_name = $data['mentor_name'];
                    $customRequest->student_id = $data['student'];
                    $request_id = $customRequest->id;
                    $customRequest->save();
                    // lembar persetujuan
                    if($customRequest->file_upload == 'YES') {
                        $persetujuan = RequestAttachmentTypeModel::where('request_id', $request_id)
                            ->where('attachment_type', RequestAttachment::LEMBAR_PERSETUJUAN)
                            ->first();
                        unlink($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::LEMBAR_PERSETUJUAN)) . $persetujuan->file_name);
                    
                        $file = $data['lembar_persetujuan'];
                        $fileName = $student->npm.'-KP-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
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
                        $kartuBimbingan = RequestAttachmentTypeModel::where('request_id', $request_id)
                            ->where('attachment_type', RequestAttachment::KARTU_BIMBINGAN)
                            ->first();
                        unlink($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::KARTU_BIMBINGAN)) . $kartuBimbingan->file_name);
                    
                        $file = $data['kartu_bimbingan'];
                        $fileName = $student->npm.'-KP-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                        $kartuBimbingan->file_name = $fileName;
                        $kartuBimbingan->file_display_name = $file->getClientOriginalName();
                        $kartuBimbingan->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::KARTU_BIMBINGAN)) . $kartuBimbingan->file_name;
                        $kartuBimbingan->uploaded_on = now();
                        $kartuBimbingan->updated_at = now();
                        // move to folder
                        $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::KARTU_BIMBINGAN)), $fileName);
                        $kartuBimbingan->save();
                    }

                    // lembar turnitin
                    if($customRequest->file_upload3 == 'YES') {
                        $lembarTurnitin = RequestAttachmentTypeModel::where('request_id', $request_id)
                            ->where('attachment_type', RequestAttachment::LEMBAR_TURNITIN)
                            ->first();
                        unlink($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::LEMBAR_TURNITIN)) . $lembarTurnitin->file_name);
                    
                        $file = $data['lembar_turnitin'];
                        $fileName = $student->npm.'-KP-'.time().'-'.(str_replace('', '', $file->getClientOriginalName()));
                        $lembarTurnitin->file_name = $fileName;
                        $lembarTurnitin->file_display_name = $file->getClientOriginalName();
                        $lembarTurnitin->file_path = $this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::LEMBAR_TURNITIN)) . $lembarTurnitin->file_name;
                        $lembarTurnitin->uploaded_on = now();
                        $lembarTurnitin->updated_at = now();
                        // move to folder
                        $file->move($this->getStudentRequestAttachmentFolderPath($student, $data['type'], $request_id, RequestAttachment::getString(RequestAttachment::LEMBAR_TURNITIN)), $fileName);
                        $lembarTurnitin->save();
                    }

                    DB::commit();
                    // redirect
                    $this->setFlashMessage('success', $customRequest->messages('success', 'update'));
                    return redirect($this->getRoute('list'));
                } catch(\Exception $e) {
                    DB::rollback();
                    return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $customRequest->id), $e);
                }
            } else {
                $errors = $customRequest->errors();
                return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('edit', $customRequest->id), $errors);
            }
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
        $student = \Auth::guard('student')->user();
        try
        {
            if ($customRequest->status != RequestStatus::Draft) {
                throw new \Exception($customRequest->messages('failDeleteCauseOfStatus'));
            }

            // remove the file and delete the data (persetujuan, kartu bimbingan, lembar turnitin)
            $request_id = $customRequest->id;   
                   
            $lembar_persetujuan = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                    ->where('attachment_type', RequestAttachment::LEMBAR_PERSETUJUAN)
                                    ->first();
            $kartu_bimbingan = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                    ->where('attachment_type', RequestAttachment::KARTU_BIMBINGAN)
                                    ->first();
            $lembar_turnitin = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                    ->where('attachment_type', RequestAttachment::LEMBAR_TURNITIN)
                                    ->first();
           
            unlink($this->getStudentRequestAttachmentFolderPath($student, $customRequest->type , $request_id, RequestAttachment::getString(RequestAttachment::LEMBAR_PERSETUJUAN)) . $lembar_persetujuan->file_name);            
            $lembar_persetujuan->delete();
            unlink($this->getStudentRequestAttachmentFolderPath($student, $customRequest->type, $request_id, RequestAttachment::getString(RequestAttachment::KARTU_BIMBINGAN)) . $kartu_bimbingan->file_name);
            $kartu_bimbingan->delete();
            unlink($this->getStudentRequestAttachmentFolderPath($student, $customRequest->type, $request_id, RequestAttachment::getString(RequestAttachment::LEMBAR_TURNITIN)) . $lembar_turnitin->file_name);
            $lembar_turnitin->delete();
            
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
     * ketika mahasiswa menyerahkan request KP.
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
            /**
             * jika status draft maka update status menjadi verifikasi dan update column expiry date minggu depan
             */
            if ($customRequest->status == RequestStatus::Draft) {
                $customRequest->status = RequestStatus::Verification;
                $customRequest->expiry_date = Carbon::today()->addWeeks(1);
            } else {
                abort(404);
            }

            // variable harus update dan parent sementara valid
            $should_update_status = true;
            $parents_valid = true;

            // ambil object user
            $student = \Auth::guard('student')->user();
            
            // requests validation
            $isStudentSemesterActive = $student->semester()->first()->is_active;
            $isStudentProfileValid = $student->getStudentProfileValidStatus();

            // parents validation
            $father = $student->parents()->where('type', ParentType::Father)->first();
            $mother = $student->parents()->where('type', ParentType::Mother)->first();

            if (!$isStudentSemesterActive || !$isStudentProfileValid) {
                $should_update_status = false;
            }

            if ($father === null || $mother === null) {
                $parents_valid = false;
            }

            // jika harus update dan parent valid
            if ($should_update_status && $parents_valid) {
                $customRequest->save();

                $adminAddress = \Config::get('customconfig.admin_address');
                // send request submitted information email to student
                Mail::to($customRequest->student()->first())->send(new RequestSubmitted($customRequest, 'student'));
                
                // send request submitted information email to admin baak
                Mail::to($adminAddress)->send(new RequestSubmitted($customRequest, 'admin'));

                $this->setFlashMessage('success', $customRequest->messages('success', 'update'));
            } elseif (!$should_update_status) {
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
            // dd($e);
            DB::rollback();
            $errors = new MessageBag([$customRequest->messages('failSubmitRequest')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
    }

    /**
     * Show the kp request's data.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewRequest(CustomRequest $request)
    {
        $breadcrumbs = $this->getBreadCrumbs('viewKpRequest');
        $title = $this->getTitle('viewKpRequest');
        $sub_title = $this->getSubTitle('viewKpRequest');
        $request_css = "active";
        $kp_request_css = "active";
        $student = \Auth::guard('student')->user();
        $study_program = $student->studyProgram()->first();
        $request_type = CreationType::getString($request->type);
        $request_status = RequestStatus::getString($request->status);
        $back_route = $this->getRoute('list');
        // set object of persetujuan attachment and kartu bimbingan object and lembar turnitin
        $lembar_persetujuan = RequestAttachmentTypeModel::where('request_id', $request->id)
                                ->where('attachment_type', RequestAttachment::LEMBAR_PERSETUJUAN)
                                ->first();
        $kartu_bimbingan = RequestAttachmentTypeModel::where('request_id', $request->id)
                                ->where('attachment_type', RequestAttachment::KARTU_BIMBINGAN)
                                ->first();
        $lembar_turnitin = RequestAttachmentTypeModel::where('request_id', $request->id)
                                ->where('attachment_type', RequestAttachment::LEMBAR_TURNITIN)
                                ->first();

        return view('admin.request.view_request')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('request_css', $request_css)
                ->with('request', $request)
                ->with('student', $student)
                ->with('study_program', $study_program)
                ->with('request_type', $request_type)
                ->with('request_status', $request_status)
                ->with('back_route', $back_route)
                ->with('kp_request_css', $kp_request_css)
                ->with('lembar_persetujuan', $lembar_persetujuan)
                ->with('kartu_bimbingan', $kartu_bimbingan)
                ->with('lembar_turnitin', $lembar_turnitin);
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
                return route('student.request.kp');
            case 'create':
                return route('student.request.create');
            case 'edit':
                return route('student.request.edit', $id);
            case 'destroy':
                return route('student.request.destroy', $id);
            case 'update_request_status':
                return route('student.request.update.status', $id);
            case 'view':
                return route('request.request.kp.view', $id);
            
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
        } elseif ($request->status == RequestStatus::Accept || $request->status == RequestStatus::AcceptProdi) {
            $extra_class = 'success';
        } elseif ($request->status == RequestStatus::Reject || $request->status == RequestStatus::RejectBySistem || $request->status === RequestStatus::RejectProdi) {
            $extra_class = 'danger';
        } elseif ($request->status == RequestStatus::Draft || $request->status == RequestStatus::PersiapanSidang) {
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

        // if ($record->type == RequestStatus::getValue('KP')) {
        //     $extra_class = 'info';
        // } else {
        //     $extra_class = 'warning';
        // }
        $extra_class = 'success';

        return "<span class='label label-{$extra_class}'>{$type}</span>";
    }

    /**
     * Get a refactored action buttons for kp requests datatable.
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
        $creation_type = json_encode(CreationType::getStrings());
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
     * Get a refactored action buttons for kp requests datatable.
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
}
