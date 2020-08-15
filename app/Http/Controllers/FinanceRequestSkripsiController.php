<?php

namespace App\Http\Controllers;

use App\Enums\CreationType;
use App\Enums\RequestStatus;
use App\Enums\SessionStatus as SessionStatusEnum;
use App\Http\Controllers\MasterController;
use App\Mail\RequestAccepted;
use App\Mail\FinanceRejected;
use App\Mail\RequestSubmitted;
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

class FinanceRequestSkripsiController extends MasterController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:finance');
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
        $statuses = RequestStatus::getFinanceStrings();
        $defaultStatusSelection = 0;
        // $types = CreationType::getStringsExcept(CreationType::KP);
        $types = ['1' => 'Skripsi', '2' => 'Tesis'];
        $statuses = ['0' => 'Sedang Verifikasi', '2' => 'Diterima oleh finance', '1' => 'Ditolak oleh finance'];
        return view('finance.request.skripsi')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('request_css', $request_css)
                ->with('statuses', $statuses)
                ->with('defaultStatusSelection', $defaultStatusSelection)
                ->with('types', $types)
                ->with('skripsi_request_css', $skripsi_request_css);
    }

    public function getSkripsiRequestList(Request $request) {
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
                            'requests.status_keuangan AS finance_status',
                        ])
                        ->where(function($query) {
                            $query->where('requests.type', CreationType::Skripsi)
                                ->orWhere('requests.type', CreationType::Tesis);
                        })
                        ->where('requests.status', '!=', RequestStatus::Draft);
        
        return Datatables::of($requests)
                        ->setRowClass(function() {
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
                        ->filterColumn('status', function($query, $keyword) {
                            $query->whereRaw("CONCAT(requests.status_keuangan, '-',  requests.status_keuangan) like ?", ["%{$keyword}%"]);
                        })
                        ->editColumn('status', function(CustomRequest $request) {
                            return $this->getRecordsStatusLabel($request);
                        })
                        ->addColumn('actions', function (CustomRequest $request)  {
                            return $this->getActionsButtons($request, ['reject' => 'm-t-3', 'accept' => 'm-t-3']);
                        })
                        ->rawColumns(['actions', 'type', 'status', 'session_status_status'])
                        ->make(true);
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
                return route('finance.request.skripsi');
            case 'view':
                return route('finance.request.skripsi.view', $id);
            case 'accept':
                return route('finance.request.skripsi.accept', $id);
            case 'reject':
                return route('finance.request.skripsi.reject', $id);
            default:
                # code...
                break;
        }
    }

    public function getActionsButtons($model, array $extraClassToAdd = [])
    {
        $model_id = $model->id;
        $view =  $this->getRoute('view', $model_id);
        
        if ($model->status == RequestStatus::Verification) {

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

        if (isset($accept) && isset($reject)) {
            return "<a href='{$view}' title='LIHAT' class='btn btn-info'><span class='fa fa-eye'></span> Lihat </a>
                    <a href='{$accept}' title='TERIMA' class='btn btn-success finance-accept-confirmation {$acceptClass}' data-toggle='modal' data-url='{$accept}' data-id='{$model_id}' data-target='#finance-accept-confirmation-modal'><span class='fa fa-check'></span> Terima </a>
                   <a title='TOLAK' class='btn btn-danger finance-reject-confirmation {$rejectClass}' data-toggle='modal' data-url='{$reject}' data-id='{$model_id}' data-target='#finance-reject-confirmation-modal'><span class='fa fa-times'></span> Tolak </a>";
        }
        
        return "<a href='{$view}' title='LIHAT' class='btn btn-info'><span class='fa fa-eye'></span> Lihat </a>";
    }

    private function getRecordsStatusLabel(CustomRequest $request)
    {
        $status = 'Sedang Verifikasi';
        $extra_class = 'info';
        $statusIndex = $request->finance_status;
        if($statusIndex == 1) {
            $status = 'Ditolak oleh finance';
            $extra_class = 'danger';
        } else if($statusIndex == 2) {
            $status = 'Diterima oleh finance';
            $extra_class = 'success';
        }

        // if ($request->status_keuangan == RequestStatus::Verification) {
        //     $extra_class = 'info';
        // } elseif ($request->status == RequestStatus::Accept || $request->status == RequestStatus::AcceptProdi || $request->status == RequestStatus::AcceptFinance) {
        //     $extra_class = 'success';
        // } elseif ($request->status == RequestStatus::Reject || $request->status == RequestStatus::RejectProdi || $request->status == RequestStatus::RejectBySistem || $request->status == RequestStatus::RejectFinance) {
        //     $extra_class = 'danger';
        // } elseif ($request->status == RequestStatus::Draft) {
        //     $extra_class = 'warning';
        // }

        return "<span class='label label-{$extra_class}'>{$status}</span>";
    }

    public function viewRequest(CustomRequest $customRequest) {
        $breadcrumbs = $this->getBreadCrumbs('viewSkripsiRequest');
        $title = $this->getTitle('viewSkripsiRequest');
        $sub_title = $this->getSubTitle('viewSkripsiRequest');
        $request_css = "active";
        $skripsi_request_css = "active";
        $student = $customRequest->student()->first();
        $angkatan = substr($student->npm, 0, 2);
        $study_program = $student->studyProgram()->first();
        $request_type = CreationType::getString($customRequest->type);
        $request_status = RequestStatus::getString($customRequest->status);


        return view('finance.request.view')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('request_css', $request_css)
                ->with('request', $customRequest)
                ->with('angkatan', $angkatan)
                ->with('student', $student)
                ->with('study_program', $study_program)
                ->with('request_status', $request_status)
                ->with('skripsi_request_css', $skripsi_request_css);
    }

    /**
     * finance accept request
     */
    public function acceptRequest(CustomRequest $customRequest) {
        $financeUserId = \Auth::guard('finance')->user()->id;

        DB::beginTransaction();

        try {
            if ($customRequest->status == RequestStatus::Verification) {
                $customRequest->status = RequestStatus::AcceptFinance;
                $customRequest->expiry_date = Carbon::today()->addWeeks(1);
                $customRequest->review_finance_user_id = $financeUserId;
                $customRequest->status_keuangan = 2;
            } else {
                abort(404);
            }

            $customRequest->save();

            $adminAddress = \Config::get('customconfig.admin_address');

            // send request accepted information email to student
            // Mail::to($customRequest->student()->first())->send(new RequestSubmitted($customRequest, 'student'));

            // send request accepted information email to admin
            Mail::to($adminAddress)->send(new RequestAccepted($customRequest, 'finance_validation_success'));

            DB::commit();

            // redirect
            $this->setFlashMessage('success', $customRequest->messages('acceptFinanceRequest'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            DB::rollback();
            $errors = new MessageBag([$customRequest->messages('failSendMail')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
    }

    /**
     * finance reject request
     */
    public function rejectRequest(CustomRequest $customRequest) {
        $financeUserId = \Auth::guard('finance')->user()->id;
       
        DB::beginTransaction();

        try {
            $data = Input::all();

            if($customRequest->status == RequestStatus::Verification) {
                $customRequest->status = RequestStatus::RejectFinance;
                $customRequest->expiry_date = null;
                $customRequest->review_finance_user_id = $financeUserId;
                $customRequest->status_keuangan = 1;
            } else {
                abort(404);
            }

            $customRequest->reject_reason = strlen($data['reject_reason']) > 0 ? $data['reject_reason'] : 'ALASAN KOSONG...';
            $customRequest->save();

            $adminAddress = \Config::get('customconfig.admin_address');
            // send request rejected information email to student
            Mail::to($customRequest->student()->first())->send(new FinanceRejected($customRequest, 'student'));
            // send request rejected information to baak
            Mail::to($adminAddress)->send(new FinanceRejected($customRequest, 'admin'));

            DB::commit();
            // redirect
            $this->setFlashMessage('success', $customRequest->messages('rejectFinanceRequest'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            DB::rollback();
            $errors = new MessageBag([$customRequest->messages('failSendMail')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
    }
}