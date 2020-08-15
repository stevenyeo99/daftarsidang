<?php

namespace App\Http\Controllers;

use App\Enums\CreationType;
use App\Enums\RequestStatus;
use App\Enums\SessionStatus as SessionStatusEnum;
use App\Enums\StatusSidang;
use App\Enums\RequestAttachmentType as RequestAttachment;
use App\Http\Controllers\MasterController;
use App\Mail\RequestAccepted;
use App\Mail\RequestRejected;
use App\Mail\RequestProdiSchedule;
use App\Mail\CancelSidangInvitation;
use App\Models\Request as CustomRequest;
use App\Models\SessionStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;
use App\Models\BeritaAcaraReport as Acara;
use App\Models\BeritaAcaraParticipant as Penguji;
use App\Models\BeritaAcaraNoteRevisi as NoteRevisi;
use App\Models\RequestAttachment as RequestAttachmentTypeModel;
use App\Models\PenjadwalanSidang as Jadwal;
use App\Models\ProdiUserAssignment;
use App\Models\RuanganSidang;
use App\Models\OldPenjadwalanSidang as History;
use App\Models\ProdiUser;
use Datetime;

class RequestSkripsiController extends MasterController
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
        $statuses = RequestStatus::getStringsExcept(RequestStatus::Draft);
        $defaultStatusSelection = RequestStatus::AcceptFinance;
        $session_statuses = StatusSidang::getStrings();
        $defaultSessionStatus = StatusSidang::Waiting;
        $types = CreationType::getStringsExcept(CreationType::KP);

        return view('admin.request.skripsi')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('request_css', $request_css)
                ->with('statuses', $statuses)
                ->with('defaultStatusSelection', $defaultStatusSelection)
                ->with('defaultSessionStatus', $defaultSessionStatus)
                ->with('session_statuses', $session_statuses)
                ->with('types', $types)
                ->with('skripsi_request_css', $skripsi_request_css);
    }

    /**
     * Get skripsi request List From Ajax.
     *
     * @param CustomRequest ajax request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSkripsiRequestList(Request $request)
    {
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
                         ->where(function ($query) {
                            $query->where('requests.type', CreationType::Skripsi)
                                  ->orWhere('requests.type', CreationType::Tesis);
                         })
                         ->where('requests.status', '!=', RequestStatus::Draft);

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
                        ->filterColumn('type', function ($query, $keyword) {
                            $query->whereRaw("CONCAT(requests.type,'-',requests.type) like ?", ["%{$keyword}%"]);
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
        $is_skripsi_or_tesis_request = true;
        $request_status = RequestStatus::getString($customRequest->status);
        $back_route = $this->getRoute('list');

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
     * Accept Request.
     *
     * @param CustomRequest $customRequest
     *
     * @return string
     */
    public function acceptRequest(CustomRequest $customRequest)
    {
        
        $studyProgram = $customRequest->student()->first()->studyprogram()->first();
        $study_program_id = $studyProgram->id;
        $prodiUserModel = new ProdiUser;
        $prodiUser = $prodiUserModel->where('study_programs_id', $study_program_id)->where('is_admin', 1)->first();
        $prodiUserAdminEmail = $prodiUser->email;

        DB::beginTransaction();
        try {
            if ($customRequest->status == RequestStatus::AcceptFinance) {
                $customRequest->status = RequestStatus::Accept;
                $customRequest->expiry_date = null;
            } else {
                abort(404);
            }

            $customRequest->save();

            $adminAddress = \Config::get('customconfig.admin_address');
            // send request accepted information email to student
            Mail::to($customRequest->student()->first())->send(new RequestAccepted($customRequest, 'student'));
            // send request for prodi to check and schedule
            Mail::to($prodiUserAdminEmail)->send(new RequestProdiSchedule($customRequest, $studyProgram->name));
            // send request accepted information email to admin
            Mail::to($adminAddress)->send(new RequestAccepted($customRequest, 'admin'));

            DB::commit();

            // redirect
            $this->setFlashMessage('success', $customRequest->messages('acceptRequest'));
            return redirect($this->getRoute('list'));
        } catch (\Exception $e) {
            DB::rollback();
            $errors = new MessageBag([$customRequest->messages('failSendMail')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
    }

    /**
     * Reject Request.
     *
     * @param CustomRequest $customRequest
     *
     * @return string
     */
    public function rejectRequest(CustomRequest $customRequest)
    {
        DB::beginTransaction();
        try {
            $data = Input::all();

            if ($customRequest->status == RequestStatus::AcceptFinance) {
                $customRequest->status = RequestStatus::Reject;
                $customRequest->expiry_date = null;
            } else {
                abort(404);
            }

            $customRequest->reject_reason = strlen($data['reject_reason']) > 0 ? $data['reject_reason'] : 'ALASAN KOSONG...';
            $customRequest->save();

            $adminAddress = \Config::get('customconfig.admin_address');
            // send request rejected information email to student
            Mail::to($customRequest->student()->first())->send(new RequestRejected($customRequest, 'student'));

            // send request rejected information email to admin
            Mail::to($adminAddress)->send(new RequestRejected($customRequest, 'admin'));

            DB::commit();

            // redirect
            $this->setFlashMessage('success', $customRequest->messages('rejectRequest'));
            return redirect($this->getRoute('list'));
        } catch (\Exception $e) {
            DB::rollback();
            $errors = new MessageBag([$customRequest->messages('failSendMail')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
    }

    /**
     * for admin do cancel request
     */
    public function cancelRequest(CustomRequest $customRequest) {
        DB::beginTransaction();

        try {
            $data = Input::all();
            // update request table
            $customRequest->expiry_date = null;
            $customRequest->reject_reason = $data['cancel_reason'];
            $customRequest->status = RequestStatus::Reject;
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
                                ->where('prodi_user_assignment.id', $jadwal->dosen_penguji_id)->first();
                                
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

            // send mail to prodi
            Mail::to($jadwal->prodiAdmin()->first()->email)->send(new CancelSidangInvitation($jadwal, 'admin_prodi', $ruanganSidang));

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
     * Download all requests as excel.
     *
     * @param Request $request
     *
     * @return string
     */
    public function downloadRequestExcel(Request $request)
    {
        $data = Input::all();

        $result = CustomRequest::join('students AS stndt', 'requests.student_id', '=', 'stndt.id')
                         ->join('study_programs AS std_prgm', 'std_prgm.id', '=', 'stndt.study_program_id')
                         ->select([
                            'stndt.npm',
                            'stndt.name',
                            'stndt.toeic_grade',
                            'requests.title',
                            'requests.type',
                            'requests.created_at',
                            'requests.start_date',
                            'requests.end_date',
                            'requests.status',
                            'requests.mentor_name',
                            DB::raw('SUBSTR(stndt.npm, 1, 2) as generation'),
                            'stndt.email',
                            'std_prgm.name AS prog_name',
                         ])
                         ->where(function ($query) {
                            $query->where('requests.type', CreationType::Skripsi)
                                  ->orWhere('requests.type', CreationType::Tesis);
                         })
                         ->where('requests.status', '!=', RequestStatus::Draft);

        // Handle if user filter the datas
        if (isset($data['npm'])) {
            $result = $result->whereRaw("CONCAT(stndt.npm,'-',stndt.npm) like ?", ["%{$data['npm']}%"]);
        }

        if (isset($data['name'])) {
            $result = $result->whereRaw("CONCAT(stndt.name,'-',stndt.name) like ?", ["%{$data['name']}%"]);
        }

        if (isset($data['generation'])) {
            $result = $result->whereRaw("CONCAT(SUBSTR(stndt.npm, 1, 2),'-',SUBSTR(stndt.npm, 1, 2)) like ?", ["%{$data['generation']}%"]);
        }

        if (isset($data['program_study'])) {
            $result = $result->whereRaw("CONCAT(std_prgm.name,'-',std_prgm.name) like ?", ["%{$data['program_study']}%"]);
        }

        if (isset($data['title'])) {
            $result = $result->whereRaw("CONCAT(requests.title,'-',requests.title) like ?", ["%{$data['title']}%"]);
        }

        if (isset($data['status'])) {
            $result = $result->whereRaw("CONCAT(requests.status,'-',requests.status) like ?", ["%{$data['status']}%"]);
        }

        if (isset($data['email'])) {
            $result = $result->whereRaw("CONCAT(stndt.email,'-',stndt.email) like ?", ["%{$data['email']}%"]);
        }

        if (isset($data['type'])) {
            $result = $result->whereRaw("CONCAT(requests.type,'-',requests.type) like ?", ["%{$data['type']}%"]);
        }

        $result = $result->get();
        $result_array = array();

        // set locale date name
        setlocale(LC_TIME, 'id-ID');
        
        if (count($result) > 0) {
            foreach ($result as $key => $res) {
                $result_array[$key]['NPM'] = $res->npm;
                $result_array[$key]['Nama'] = $res->name;
                $result_array[$key]['Judul'] = $res->title;
                $result_array[$key]['Tipe'] = CreationType::getString($res->type);
                $result_array[$key]['Tanggal Daftar'] = strftime('%e %B %Y', strtotime($res->created_at));
                $result_array[$key]['Tanggal Mulai Bimbingan'] = strftime('%e %B %Y', strtotime($res->start_date));
                $result_array[$key]['Tanggal Akhir Bimbingan'] = strftime('%e %B %Y', strtotime($res->end_date));
                $result_array[$key]['Nilai TOEIC'] = $res->toeic_grade;
                $result_array[$key]['Status'] = RequestStatus::getString($res->status);
                $result_array[$key]['Dosen Pembimbing'] = $res->mentor_name;
                $result_array[$key]['Angkatan'] = $res->generation;
                $result_array[$key]['Email'] = $res->email;
                $result_array[$key]['Program Studi'] = $res->prog_name;
            }

            Excel::create('Laporan Pendaftaran Skripsi/Tesis', function($excel) use ($result_array) {

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
            })->export('xlsx');
        }

        // redirect
        $customRequest = new CustomRequest;
        $this->setFlashMessage('danger', $customRequest->messages('emptyExcelRequest'));
        return redirect($this->getRoute('list'));
    }

    /**
     * Reject all expired skripsi & tesis requests.
     *
     * @param empty
     *
     * @return empty
     */
    public function rejectExpiredRequests()
    {
        try {
            // change accept finance because skripsi/tesis expired date begin when finance accept the request
            $expiredRequests = CustomRequest::where('status', RequestStatus::AcceptFinance)
                                            ->whereDate('expiry_date', Carbon::today())
                                            ->where(function ($query) {
                                                $query->where('type', CreationType::Skripsi)
                                                      ->orWhere('type', CreationType::Tesis);
                                            })
                                            ->get();

            foreach ($expiredRequests as $key => $expiredRequest) {
                $expiredRequest->status = RequestStatus::RejectBySistem;
                $expiredRequest->expiry_date = null;
                $expiredRequest->save();
                Log::info('Sedang menolak pendaftaran '. CreationType::getString($expiredRequest->type) .' : '. $expiredRequest->student()->first()->npm . ' | '. $expiredRequest->student()->first()->name);
            }
        } catch(\Exception $e) {
            $errorStr = method_exists($e, 'getMessage') ? json_decode($e->getMessage()) : json_encode($e);
            Log::info($errorStr);
        }
    }

    /**
     * Redirect to view that used to change the session status of the requester.
     *
     * @param CustomRequest $customRequest
     *
     * @return empty
     */
    public function changeSessionStatus(CustomRequest $customRequest)
    {
        $breadcrumbs = $this->getBreadCrumbs('changeSkripsiSessionStatus');
        $title = $this->getTitle('changeSkripsiSessionStatus');
        $sub_title = $this->getSubTitle('changeSkripsiSessionStatus');
        $request_css = "active";
        $skripsi_request_css = "active";
        $btn_label = "Ubah";
        $student = $customRequest->student()->first();
        $session_status = $student->session_statuses()->where('type', $customRequest->type)->first();
        $statuses = SessionStatusEnum::getStrings();

        return view('admin.request.update-session-status')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('request_css', $request_css)
                ->with('skripsi_request_css', $skripsi_request_css)
                ->with('customRequest', $customRequest)
                ->with('btn_label', $btn_label)
                ->with('statuses', $statuses)
                ->with('session_status', $session_status);
    }

    /**
     * Update the session status of the requester.
     *
     * @param CustomRequest $customRequest
     *
     * @return empty
     */
    public function updateSessionStatus(CustomRequest $customRequest, Request $request)
    {
        $student = $customRequest->student()->first();
        $session_status = new SessionStatus;
        $is_fail_update_session_status = false;

        $existing_session_status = $student->session_statuses()->where('type', $customRequest->type)->first();
        if ($existing_session_status != null) {
            $session_status = $existing_session_status;
        }

        $data = Input::all();
        $data['type'] = $customRequest->type;
        $data['student'] = $student->id;

        if ($session_status->validate($session_status, $data, $session_status->messages('validation'))) {
            DB::beginTransaction();
            try {
                // store
                $session_status->status = $data['status'];
                $session_status->type = $data['type'];
                if ($session_status->status == SessionStatusEnum::Taked) 
                {
                    $session_status->date = Carbon::parse($data['date']);
                } elseif ($session_status->status == SessionStatusEnum::Cancelled) {
                    $customRequest->status = RequestStatus::Reject;
                    $customRequest->expiry_date = null;
                    $customRequest->reject_reason = 'Pembatalan Sidang';
                    $customRequest->save();

                    $is_fail_update_session_status = true;

                    $session_status->date = null;
                } else {
                    $session_status->date = null;
                }

                $session_status->student_id = $data['student'];
                if (!$is_fail_update_session_status) {
                    $session_status->save();
                } elseif ($is_fail_update_session_status && $existing_session_status != null) {
                    $existing_session_status->delete();
                }

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $session_status->messages('success', 'update'));
                return redirect($this->getRoute('list'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('change_session_status', $customRequest->id), $e);
            }
        } else {
            $errors = $session_status->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('change_session_status', $customRequest->id), $errors);
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
                return route('request.skripsi');
            case 'view':
                return route('request.skripsi.view', $id);
            case 'accept':
                return route('request.skripsi.accept', $id);
            case 'reject':
                return route('request.skripsi.reject', $id);
            case 'cancel':
                return route('request.skripsi.cancel', $id);
            
            default:
                # code...
                break;
        }
    }

    public function getActionsButtons($model, array $extraClassToAdd = [])
    {
        $model_id = $model->id;
        $view =  $this->getRoute('view', $model_id);
        
        if ($model->status == RequestStatus::AcceptFinance) {

            $accept =  $this->getRoute('accept', $model_id);
            $reject =  $this->getRoute('reject', $model_id);
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

        // if ($model->status === RequestStatus::Accept) {
        //     $change_session_status = $this->getRoute('change_session_status', $model_id);
        // }
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
        } elseif ($request->status == RequestStatus::Reject || $request->status == RequestStatus::RejectProdi || $request->status == RequestStatus::RejectBySistem || $request->status == RequestStatus::RejectFinance) {
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

        if ($request->type == CreationType::Skripsi) {
            $extra_class = 'success';
        } else {
            $extra_class = 'primary';
        }

        return "<span class='label label-{$extra_class}'>{$type}</span>";
    }

    /**
     * preview request attachment method
     */
    public function previewRequestAttachment($id) {
        $requestAttachment = new RequestAttachmentTypeModel;
        $requestAttachmentFile = $requestAttachment->whereRaw('id = ?', $id)->first();
        $requestId = $requestAttachmentFile->request_id;
        $attachmentType = $requestAttachmentFile->attachment_type;
        $fileName = $requestAttachmentFile->file_name;

        // get Type by request
        $request = CustomRequest::where('id', $requestId)->first();
        $student = $request->student()->first();

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
