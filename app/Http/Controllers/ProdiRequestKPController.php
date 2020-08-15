<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\MasterController;
use App\Enums\CreationType;
use App\Enums\RequestStatus;
use App\Enums\SessionStatus as SessionStatusEnum;
use App\Enums\StatusSidang;
use App\Enums\RequestAttachmentType as RequestAttachment;
use App\Models\Request as CustomRequest;
use App\Mail\RequestProdiConfirm;
use App\Mail\RequestProdiReject;
use App\Mail\CancelSidangInvitation;
use App\Models\PenjadwalanSidang as Jadwal;
use App\Models\OldPenjadwalanSidang as History;
use App\Models\ProdiUserAssignment;
use Log;
use DateTime;
use App\Models\RuanganSidang;
use App\Models\BeritaAcaraReport as Acara;
use App\Models\BeritaAcaraParticipant as Penguji;
use App\Models\BeritaAcaraNoteRevisi as NoteRevisi;
use App\Models\RequestAttachment as RequestAttachmentTypeModel;

class ProdiRequestKPController extends MasterController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:prodis');
    }

    public function index() {
        $breadcrumbs = $this->getBreadCrumbs('kpRequest');
        $title = $this->getTitle('kpRequest');
        $sub_title = $this->getSubTitle('kpRequest');
        $request_css = "active";
        $kp_request_css = "active";
        $statuses = RequestStatus::getStringsExcept(RequestStatus::Draft);
        $defaultStatusSelection = RequestStatus::Accept;
        $session_statuses = StatusSidang::getStrings();
        return view('prodi.request.kp', compact('breadcrumbs', 'title', 'sub_title', 'request_css', 'kp_request_css', 
    'statuses', 'defaultStatusSelection', 'session_statuses'));
    }

    /**
     * datatable ajax retrieve
     */
    public function getKPRequestList(Request $request) {
        $prodiId = \Auth::guard('prodis')->user()->study_programs_id;
        $requests = CustomRequest::join('students AS stndt', 'requests.student_id', '=', 'stndt.id')
                         ->join('study_programs AS std_prgm', 'std_prgm.id', '=', 'stndt.study_program_id')
                         ->select([
                            'requests.id',
                            'requests.status',
                            'requests.type',
                            'requests.title',
                            'stndt.npm AS npm',
                            DB::raw('SUBSTR(stndt.npm, 1, 2) as generation'),
                            'stndt.name AS name',
                            'stndt.email AS email',
                            'std_prgm.name AS study_program_name',
                            'requests.status_sidang AS session_status_status',
                         ])
                         ->where('requests.type', CreationType::KP)
                         ->where('requests.status', '!=', RequestStatus::Draft)
                         ->where('stndt.study_program_id', '=', $prodiId);

        return Datatables::of($requests)
                ->setRowClass(function () {
                    return "custom-tr-text-ellipsis";
                })
                ->filterColumn('generation', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(SUBSTR(stndt.npm, 1, 2),'-',SUBSTR(stndt.npm, 1, 2)) like ?", ["%{$keyword}%"]);
                })
                ->filterColumn('npm', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(stndt.npm,'-',stndt.npm) like ?", ["%{$keyword}%"]);
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(stndt.name,'-',stndt.name) like ?", ["%{$keyword}%"]);
                })
                ->filterColumn('email', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(stndt.email,'-',stndt.email) like ?", ["%{$keyword}%"]);
                })
                ->filterColumn('study_program_name', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(std_prgm.name,'-',std_prgm.name) like ?", ["%{$keyword}%"]);
                })
                ->filterColumn('session_status_status', function ($query, $keyword) {
                    $query->where('status_sidang', $keyword);
                })
                ->editColumn('status', function(CustomRequest $request) {
                    return $this->getRecordsStatusLabel($request);
                })
                ->editColumn('session_status_status', function(CustomRequest $request) {
                    return $this->getRecordsSessionStatusLabel($request);
                })
                ->editColumn('type', function(CustomRequest $request) {
                    return $this->getRecordsTypeLabel($request);
                })
                ->addColumn('actions', function (CustomRequest $request)  {
                    return $this->getActionsButtons($request, ['reject' => 'm-t-3', 'accept' => 'm-t-3']);
                })
                ->rawColumns(['actions', 'type', 'status', 'session_status_status'])
                ->make(true);
    }

    /**
     * view request
     */
    public function viewRequest(CustomRequest $customRequest) {
        $breadcrumbs = $this->getBreadCrumbs('viewKpRequest');
        $title = $this->getTitle('viewKpRequest');
        $sub_title = $this->getSubTitle('viewKpRequest');
        $request_css = "active";
        $kp_request_css = "active";
        $student = $customRequest->student()->first();
        $study_program = $student->studyProgram()->first();
        $request_type = CreationType::getString($customRequest->type);
        $request_status = RequestStatus::getString($customRequest->status);
        $back_route = $this->getRoute('list');
        // set object of persetujuan attachment and kartu bimbingan object and lembar turnitin
        $lembar_persetujuan = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::LEMBAR_PERSETUJUAN)
                                ->first();
        $kartu_bimbingan = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::KARTU_BIMBINGAN)
                                ->first();
        $lembar_turnitin = RequestAttachmentTypeModel::where('request_id', $customRequest->id)
                                ->where('attachment_type', RequestAttachment::LEMBAR_TURNITIN)
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
                ->with('kp_request_css', $kp_request_css)
                ->with('lembar_persetujuan', $lembar_persetujuan)
                ->with('kartu_bimbingan', $kartu_bimbingan)
                ->with('lembar_turnitin', $lembar_turnitin);
    }

    /**
     * preview request attachment method
     */
    public function previewRequestAttachment($id) {
        $requestAttachment = new RequestAttachmentTypeModel;
        $requestAttachmentFile = $requestAttachment->whereRaw('id = ?', $id)->first();
        $requestId = $requestAttachmentFile->request_id;
        $student = $requestAttachmentFile->request()->first()->student()->first();
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
     * get action button
     */
    public function getActionsButtons($model, array $extraClassToAdd = [])
    {
        $model_id = $model->id;
        $view =  $this->getRoute('view', $model_id);
        
        // if already accept by baak for the first time prodi can accept is that correct or not
        // if not correct prodi will reject the request
        if ($model->status == RequestStatus::Accept) {

            $accept = $this->getRoute('accept', $model_id);
            $reject = $this->getRoute('reject', $model_id);
            $rejectClass = '';
            $acceptClass = '';

            if (count($extraClassToAdd) > 0) {
                foreach ($extraClassToAdd as $key => $value) {
                    if ($extraClassToAdd[$key] = 'reject') {
                        $rejectClass = $value;
                    }
                    if ($extraClassToAdd[$key] = 'accept') {
                        $acceptClass = $value;
                    }
                }
            }
        }

        // for now only can change session status if prodi already accept and not sidang yet
        if ($model->status == RequestStatus::AcceptProdi && $model->session_status_status == StatusSidang::Waiting) {
            $cancel = $this->getRoute('cancel', $model_id);
        }

        if (isset($accept) && isset($reject)) {
            return "<a href='{$view}' title='LIHAT' class='btn btn-info'><span class='fa fa-eye'></span> Lihat </a>
                    <a href='{$accept}' title='TERIMA' class='btn btn-success accept-confirmation {$acceptClass}' data-toggle='modal' data-url='{$accept}' data-id='{$model_id}' data-target='#accept-confirmation-modal'><span class='fa fa-check'></span> Terima </a>
                   <a title='TOLAK' class='btn btn-danger reject-confirmation {$rejectClass}' data-toggle='modal' data-url='{$reject}' data-id='{$model_id}' data-target='#reject-confirmation-modal'><span class='fa fa-times'></span> Tolak </a>";
        }

        if (isset($cancel)) {
            return "<a href='{$view}' title='LIHAT' class='btn btn-info'><span class='fa fa-eye'></span> Lihat </a>
                    <a title='BATAL SIDANG' class='btn btn-danger cancel-confirmation m-t-3' data-toggle='modal' data-url='{$cancel}' data-id='{$model_id}' data-target='#cancel-confirmation-modal'><span class='fa fa-remove'></span> Batal Sidang </a>";
        }

        return "<a href='{$view}' title='LIHAT' class='btn btn-info'><span class='fa fa-eye'></span> Lihat </a>";
    }

    /**
     * get Record status label on display
     */
    private function getRecordsStatusLabel(CustomRequest $request)
    {
        $status = RequestStatus::getString($request->status);
        
        if ($request->status == RequestStatus::Verification) {
            $extra_class = 'info';
        } elseif ($request->status == RequestStatus::Accept || $request->status == RequestStatus::AcceptProdi) {
            $extra_class = 'success';
        } elseif ($request->status == RequestStatus::Reject || $request->status == RequestStatus::RejectBySistem || $request->status == RequestStatus::RejectProdi) {
            $extra_class = 'danger';
        } elseif ($request->status == RequestStatus::Draft) {
            $extra_class = 'warning';
        }

        return "<span class='label label-{$extra_class}'>{$status}</span>";
    }

    /**
     * Get a refactored session status labels for request datatable.
     *
     * @param CustomRequest $request
     *
     * @return string
     */
    private function getRecordsSessionStatusLabel(CustomRequest $request)
    {
        if ($request->session_status_status === 0) {
            return "<span class='label label-warning'>Belum Sidang</span>";
        }

        $status = StatusSidang::getString($request->session_status_status);

        if ($request->session_status_status == StatusSidang::Done) {
            $extra_class = 'success';
        } elseif ($request->session_status_status == StatusSidang::Cancel) {
            $extra_class = 'danger';
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
        $extra_class = 'primary';

        return "<span class='label label-{$extra_class}'>{$type}</span>";
    }

    /**
     * prodi accepted request
     */
    // 0 ongoing, 1 scheduled, 2 re-scheduled
    public function acceptRequest(CustomRequest $customRequest) {
        DB::beginTransaction();
        try {
            // update status request kp to be prodi accepted
            // need to update validation date on request table column
            // update tanggal_validasi_prodi
            if($customRequest->status == RequestStatus::Accept) {
                $customRequest->status = RequestStatus::AcceptProdi;
                $customRequest->tanggal_validasi_prodi = now();
                $customRequest->scheduled_status = 0;
            } else {
                abort(404);
            }
            $customRequest->save();

            // get study program name
            $prodi = \Auth::guard('prodis')->user();
            
            // need to insert to the table penjadwalan
            $jadwal = new Jadwal;
            $jadwal->request_id = $customRequest->id;
            // this one is store using prodi_user_assignment_id
            $jadwal->dosen_pembimbing_id = $customRequest->mentor_id;
            $jadwal->penjadwalan_by = $prodi->id;
            $jadwal->created_at = now();
            $jadwal->sidang_type = CreationType::KP;
            $jadwal->status_penjadwalan = 'ONGOING';
            $jadwal->save();

            $adminAddress = \Config::get('customconfig.admin_address');
            // send request accepted information email to admin baak
            Mail::to($adminAddress)->send(new RequestProdiConfirm($customRequest, $prodi->studyProgram()->first()->name));
            
            DB::commit();

            // redirect 
            $this->setFlashMessage('success', $customRequest->messages('success', 'penjadwalan'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            Log::info($e);
            $errors = new MessageBag([$customRequest->messages('failSendMail')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
    }

    /**
     * Reject kp request by prodi
     */
    public function rejectRequest(CustomRequest $customRequest) {
        DB::beginTransaction();
        try {
            $data = Input::all();
            
            if($customRequest->status = RequestStatus::Accept) {
                $customRequest->status = RequestStatus::RejectProdi;
            } else {
                abort(404);
            }

            $customRequest->reject_reason = strlen($data['reject_reason']) > 0 ? $data['reject_reason'] : 'ALASAN KOSONG...';
            $customRequest->save();

            $adminAddress = \Config::get('customconfig.admin_address');
            // get study program name
            $prodi = \Auth::guard('prodis')->user()->studyProgram()->first()->name;
            
            // send request rejected information email to student
            Mail::to($customRequest->student()->first())->send(new RequestProdiReject($customRequest, $prodi, 'student'));

            // send request rejected information reject prodi to admin baak
            // Kak Nafi said no need and steven suggest need LOL
            Mail::to($adminAddress)->send(new RequestProdiReject($customRequest, $prodi, 'admin'));

            DB::commit();

            // redirect 
            $this->setFlashMessage('success', $customRequest->messages('rejectRequest'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            DB::rollback();
            $errors = new MessageBag([$customRequest->messages('failSendMail')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
    }

    /**
     * cancel sidang kp by prodi
     */
    public function cancelRequest(CustomRequest $customRequest, Request $request) {
        DB::beginTransaction();
        try {
            $data = Input::all();
            // update requests table 
            $customRequest->expiry_date = null;
            $customRequest->reject_reason = $data['cancel_reason'];
            $customRequest->status = RequestStatus::RejectProdi;
            $customRequest->status_sidang = StatusSidang::Cancel;
            $customRequest->updated_at = now();
            $customRequest->save();

            // check penjadwalan table
            $jadwal = Jadwal::where('request_id', $customRequest->id)->first();
            $adminAddress = \Config::get('customconfig.admin_address');

            $ruanganSidang = new RuanganSidang;

            // check jadwal is being sent to the participant or not
            if($jadwal->status_pengiriman >= 2 && $jadwal->status_pengiriman <= 5) 
            {
                // format tanggal sidang for display on mail html
                $jadwal->tanggal_sidang = DateTime::createFromFormat('Y-m-d H:i:s', $jadwal->tanggal_sidang)->format('d/m/Y H:i A');                
                // set dospem, backup, penguji object
                $jadwal->dospem = ProdiUserAssignment::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                                ->where('prodi_user_assignment.id', $jadwal->dosen_pembimbing_id)->first();
                $jadwal->dospenguji = ProdiUserAssignment::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                                ->where('prodi_user_assignment.id', $jadwal->dosen_pembimbing_id)->first();
                               
                $ruanganSidang = RuanganSidang::where('id', $jadwal->ruangan_sidang_id)->first();

                // try sending cancelling mail to participant
                // sending mail to student first
                Mail::to($customRequest->student()->first()->email)->send(new CancelSidangInvitation($jadwal, 'student', $ruanganSidang));
                // check is using pembimbing or bak one then send the cancel email
                if($jadwal->dosen_pembimbing_backup == 0) {
                    Mail::to($jadwal->dospem->email)->send(new CancelSidangInvitation($jadwal, 'dospem', $ruanganSidang));
                } else {
                    $jadwal->dospemBAK = ProdiUserAssignment::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                                ->where('prodi_user_assignment.id', $jadwal->dosen_pembimbing_backup)->first();
                    Mail::to($jadwal->dospemBAK->email)->send(new CancelSidangInvitation($jadwal, 'dospemBAK', $ruanganSidang));
                }
                // send email to dospenguji
                Mail::to($jadwal->dospenguji->email)->send(new CancelSidangInvitation($jadwal, 'dospenguji', $ruanganSidang));
            }

            // send mail to admin baak
            Mail::to($adminAddress)->send(new CancelSidangInvitation($jadwal, 'admin_baak', $ruanganSidang));

            // delete the penjadwalan data
            // check the history got data or not
            $history = History::where('penjadwalan_sidang_id', $jadwal->id)->first();
            
            // delete berita acara sidang
            // check berita acara is using or not
            $beritaAcaraReport = Acara::where('request_id', $customRequest->id)->first();

            if(isset($beritaAcaraReport)) {
                $beritaAcaraParticipant = Penguji::where('berita_acara_report_id', $beritaAcaraReport->id)->delete();
                $beritaAcaraReport->delete();
            }
            
            if(isset($history)) {
                $history->delete();
            }
            
            $jadwal->delete();
    
            DB::commit();
            // redirect
            $this->setFlashMessage('success', $customRequest->messages('success', 'cancel'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            DB::rollback();
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('list'), $e);
        }
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
                return route('prodi.request.kp');
            case 'view':
                return route('prodi.request.kp.view', $id);
            case 'accept':
                return route('prodi.request.kp.accept', $id);
            case 'reject':
                return route('prodi.request.kp.reject', $id);
            case 'change_session_status':
                return route('prodi.request.kp.change.session.status', $id);
            case 'cancel':
                return route('prodi.request.kp.cancel', $id);
            default:
                # code...
                break;
        }
    }
}
