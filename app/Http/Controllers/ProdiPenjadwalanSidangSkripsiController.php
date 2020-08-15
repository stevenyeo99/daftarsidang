<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\MasterController;
use App\Enums\RequestStatus;
use App\Enums\CreationType;
use App\Models\Request as CustomRequest;
use App\Models\PenjadwalanSidang as Jadwal;
use App\Models\ProdiUser;
use App\Models\ProdiUserAssignment;
use App\Models\StudyProgram;
use DateTime;
use Carbon\Carbon;
use App\Mail\ProdiPenjadwalan;
use App\Mail\ProdiPerubahanJadwal;
use App\Mail\ReschedulePenjadwalanNotification;
use Log;
use App\Models\OldPenjadwalanSidang as HistoryJadwal;
use App\Enums\StatusSidang;
use App\Models\BeritaAcaraReport;

class ProdiPenjadwalanSidangSkripsiController extends MasterController {

    public function __construct() {
        $this->middleware('auth:prodis');
    }

    public function index() {
        $breadcrumbs = $this->getBreadCrumbs('penjadwalanSidangSkripsi');
        $title = $this->getTitle('penjadwalanSidangSkripsi');
        $sub_title = $this->getSubTitle('penjadwalanSidangSkripsi');
        $penjadwalan_css = 'active';
        $jadwalSkripsi_css = 'active';

        return view("prodi.schedule.skripsi", compact('breadcrumbs', 'title', 'sub_title', 'penjadwalan_css', 'jadwalSkripsi_css'));
    }

    public function getListOfPenjadwalanSkripsi(Request $request) {
        $requests = Jadwal::join('requests AS req', 'penjadwalan_sidang.request_id', '=', 'req.id')
                            ->join('students AS sdt', 'req.student_id', '=', 'sdt.id')
                            ->join('study_programs AS sp', 'sdt.study_program_id', '=', 'sp.id')
                            ->leftJoin('prodi_user_assignment AS pembimbing_assignment', 'pembimbing_assignment.id', '=', 'penjadwalan_sidang.dosen_pembimbing_id')
                            ->leftJoin('prodi_user AS pembimbing', 'pembimbing.id', '=', 'pembimbing_assignment.prodi_user_id')
                            ->leftJoin('prodi_user_assignment AS penguji_assignment', 'penguji_assignment.id', '=', 'penjadwalan_sidang.dosen_penguji_id')
                            ->leftJoin('prodi_user AS penguji', 'penguji.id', '=', 'penguji_assignment.prodi_user_id')
                            ->leftJoin('ruangan_sidang AS rs', 'penjadwalan_sidang.ruangan_sidang_id', '=', 'rs.id')
                            ->selectRaw('penjadwalan_sidang.*, sdt.npm AS npm, sdt.name AS name, pembimbing.username AS dosen_pembimbing_name,
                                    penguji.username AS dosen_penguji_name, CONCAT("Gedung ", rs.gedung, " - ", rs.ruangan) AS ruangan_sidang')
                            ->where('req.type', CreationType::Skripsi)
                            ->where(function($query) {
                                $query->where('req.status_sidang', StatusSidang::Waiting)
                                ->orWhere('req.status_sidang', StatusSidang::Done);
                            }) 
                            ->orderBy('penjadwalan_sidang.id', 'desc');

        return Datatables::of($requests)
                    ->setRowClass(function() {
                        return "custom-tr-text-ellipsis";
                    })
                    ->filterColumn('npm', function($query, $keyword) {
                        $query->whereRaw("CONCAT(sdt.npm, '-', sdt.npm) LIKE ?", ["%{$keyword}%"]);
                    })
                    ->filterColumn('name', function($query, $keyword) {
                        $query->whereRaw("CONCAT(sdt.name, '-', sdt.name) LIKE ?", ["%{$keyword}%"]);
                    })
                    ->filterColumn('dosen_pembimbing_name', function($query, $keyword) {
                        $query->whereRaw("CONCAT(pembimbing.username, '-', pembimbing.username) LIKE ?", ["%{$keyword}%"]);
                    })
                    ->filterColumn('dosen_penguji_name', function($query, $keyword) {
                        $query->whereRaw("CONCAT(penguji.username, '-', penguji.username) LIKE ?", ["%{$keyword}%"]);
                    })
                    ->editColumn('tanggal_sidang', function($requests) {
                        if($requests->tanggal_sidang != null) {
                            $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $requests->tanggal_sidang);
                            $formattedDateTime = $myDateTime->format('d-m-Y h:i A');
                            return [
                                'display' => $formattedDateTime,
                                'datetime' => strtotime($requests->tanggal_sidang)
                            ];
                        } else {
                            return [
                                'display' => '',
                                'datetime' => ''
                            ];
                        }                        
                    })
                    ->editColumn('status_penjadwalan', function(Jadwal $jadwal) {
                        return $this->getRecordsStatusLabel($jadwal);
                    })
                    ->filterColumn('tanggal_sidang', function($query, $keyword) {
                        $keyword = DateTime::createFromFormat('d-M-Y', $keyword)->format('Y-m-d');
                        $query->whereDate("tanggal_sidang", $keyword);
                    })
                    ->addColumn('actions', function(Jadwal $jadwal) {
                        return $this->getActionsButton($jadwal);
                    })
                    ->rawColumns(['actions', 'status_penjadwalan'])
                    ->make(true);
    }

    /**
     * action label for status penjadwalan each column
     */
    private function getRecordsStatusLabel(Jadwal $jadwal) {
        $status = $jadwal->status_penjadwalan;

        if($status == 'ONGOING') {
            $name = 'Progress';
            $extra_class = 'warning';
        } else if($status == 'COMPLETED') {
            $name = 'Selesai';
            $extra_class = 'success';
        } else if($status == 'EXPIRED') {
            $name = 'Penjadwalan Lama';
            $extra_class = 'danger';
        }

        return "<span class='label label-${extra_class}'>${name}</span>";
    }

    /**
     * get actions button for penjadwalan sidang module
     */
    public function getActionsButton($model, array $extraClassToAdd = []) {
        $model_id = $model->id;
        $request_id = $model->request_id;
        $requestorModel = new CustomRequest;
        $requestor = $requestorModel->where('id', '=', $request_id)->first();
        $dateTimeNow = new DateTime();

        $viewRoute = $this->getRoute('view', $model_id);
        $jadwalRoute = $this->getRoute('jadwal', $model_id);
        
        if($model->status_penjadwalan == 'ONGOING' && $requestor->scheduled_status == 0) { // if tanggal_sidang not assigned yet display two button
            return "<a href='{$jadwalRoute}' title='ATUR' class='btn btn-warning'><span class='fa fa-calendar'></span> Jadwal </a>";
        } else if($model->status_penjadwalan == 'ONGOING' && ($requestor->scheduled_status == 2 || $requestor->scheduled_status == 1)) {
            return "<a href='{$jadwalRoute}' title='ATUR' class='btn btn-warning'><span class='fa fa-calendar'></span> Ulang Jadwal </a>";
        } else { // display the button for view only
            return "<a href='{$viewRoute}' title='LIHAT' class='btn btn-info'><span class='fa fa-eye'></span> Lihat </a>";
        }
    }

    /**
     * get route use when got redirect
     */
    public function getRoute($key, $id = null) {
        switch($key) {
            case 'list':
                return route('prodi.penjadwalan.skripsi');
            case 'view':
                return route('prodi.penjadwalan.view.skripsi', $id);
            case 'jadwal':
                return route('prodi.penjadwalan.assign.skripsi', $id);
            default:
                break;
        }
    }

    /**
     * get penjadwalan for store date page
     */
    public function getPenjadwalanForStoreData(Jadwal $penjadwalan) {
        $prodiId = \Auth::guard('prodis')->user()->study_programs_id;
        $prodiUserAssignmentModel = new ProdiUserAssignment;
        $prodiUserModel = new ProdiUser;
        $studyProgramId = $penjadwalan->request()->first()->student()->first()->study_program_id;
        $listOfProdiUser = $prodiUserAssignmentModel::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                                                    ->where('prodi_user_assignment.study_program_id', $studyProgramId)->where('prodi_user.is_admin', 0)
                                                    ->selectRaw('prodi_user_assignment.*, prodi_user.id AS original_id, prodi_user.username')->get();
    
        $breadcrumbs = $this->getBreadCrumbs('formulirPenjadwalanSidangSkripsi');
        $title = $this->getTitle('formulirPenjadwalanSidangSkripsi');
        $sub_title = $this->getSubTitle('formulirPenjadwalanSidangSkripsi');
        $penjadwalan_css = 'active';
        $jadwalSkripsi_css = 'active';

        $pembimbingValue = $prodiUserAssignmentModel::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                                                    ->where('prodi_user_assignment.id', $penjadwalan->dosen_pembimbing_id)->first()->username;
        
        $listOfProdiDDL = [];
        foreach($listOfProdiUser as $value) {
            if($penjadwalan->dosen_pembimbing_id != $value->id) {
                $listOfProdiDDL[$value->id] = $value->username;
            }
        }

        // reformatted tanggalsidang value for display on formulir if not null
        if($penjadwalan->tanggal_sidang != null) {
            $penjadwalan->tanggal_sidang = Carbon::createFromFormat('Y-m-d H:i:s', $penjadwalan->tanggal_sidang)->format('d-m-Y h:i A');
        }

        $isRevision = 'NO';
        if($penjadwalan->tanggal_revisi_sidang != null) {
            $isRevision = 'YES';
        }

        return view('prodi.schedule.skripsi_schedule', compact('breadcrumbs', 'title', 'sub_title', 'penjadwalan_css', 
        'jadwalSkripsi_css', 'penjadwalan', 'listOfProdiDDL', 'pembimbingValue', 'isRevision'));
    }

    /**
     * store penjadwalan data post
     */
    public function storePenjadwalanData(Jadwal $penjadwalan) {
        // prodi user
        $prodiUser = \Auth::guard('prodis')->user();
        $prodiUserModel = new ProdiUser;
        $prodiUserAssignmentModel = new ProdiUserAssignment;
        $data = Input::all();
        $studyProgram = new StudyProgram;
        $studyProgramName = $studyProgram::where('id', $prodiUser->study_programs_id)->first()->name;

        // dosen pembimbing previous data
        $dosenPembimbingObject = $prodiUserAssignmentModel::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                ->where('prodi_user_assignment.id', $penjadwalan->dosen_pembimbing_id)->selectRaw('prodi_user_assignment.*, prodi_user.id AS prodi_id, prodi_user.username')->first();
        $dosenPembimbing = $dosenPembimbingObject->username;
        $oldDosenPembimbingOrBAKId = $dosenPembimbingObject->id;

        $previousDosenPenguji = null;
        $previousDosenPembimbingBackUp = null;

        // old ruangan id and tanggal-waktu sidang
        $oldRuanganId = $penjadwalan->ruangan_sidang_id;
        if($oldRuanganId == null) {
            $oldRuanganId = 0;
        }
        $oldTanggalWaktuSidang = $penjadwalan->tanggal_sidang;

        // previous dosen penguji username and id
        $oldDosenPengujiId = 0;
        if($penjadwalan->dosen_penguji_id != null && $penjadwalan->dosen_penguji_id != '0') {
            $previousPengujiObject = $prodiUserAssignmentModel::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                ->where('prodi_user_assignment.id', $penjadwalan->dosen_penguji_id)->selectRaw('prodi_user_assignment.*, prodi_user.id AS prodi_id, prodi_user.username')->first();
            $previousDosenPenguji = $previousPengujiObject->username;
            $oldDosenPengujiId = $previousPengujiObject->id;
        }

        // previous dosen pembimbing BAK
        if($penjadwalan->dosen_pembimbing_backup != null && $penjadwalan->dosen_pembimbing_backup != '0') {
            $previousDosenPembimbingBackUpObject =  $prodiUserAssignmentModel::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                ->where('prodi_user_assignment.id', $penjadwalan->dosen_pembimbing_backup)->selectRaw('prodi_user_assignment.*, prodi_user.id AS prodi_id, prodi_user.username')->first();
            $previousDosenPembimbingBackUp = $previousDosenPembimbingBackUpObject->username;
            $oldDosenPembimbingOrBAKId = $previousDosenPembimbingBackUpObject->id;
        }

        // set jadwal object        
        $penjadwalan->dosen_penguji_id = $data['dosen_penguji_id'];
        
        // dosen penguji yang baru diassign
        $newDosenPenguji = $prodiUserAssignmentModel::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
            ->where('prodi_user_assignment.id', $penjadwalan->dosen_penguji_id)->first()->username;
        $penjadwalan->tanggal_sidang = $data['tanggal_sidang'];
        $penjadwalan->dosen_pembimbing_backup = $data['dosen_pembimbing_backup_id'];
        // dosen pembimbing back up akan ada username jika diassign
        $newDosenPembimbingBackUp = '';
        if($penjadwalan->dosen_pembimbing_backup != '0' && $penjadwalan->dosen_pembimbing_backup != null) {
            $newDosenPembimbingBackUp = $prodiUserAssignmentModel::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                ->where('prodi_user_assignment.id', $penjadwalan->dosen_pembimbing_backup)->first()->username;
        }  
        // $data['tanggal_sidang'] = substr($data['tanggal_sidang'], 0 , strlen($data['tanggal_sidang']) - 3);
        
        $penjadwalanDateCarbon = Carbon::createFromFormat('d-m-Y g:i a', $data['tanggal_sidang']);
        
        $penjadwalan->tanggal_sidang = $penjadwalanDateCarbon->format('Y-m-d H:i:s');
        
        // set position prodi 0
        $penjadwalan->position = 0;
        // validate reschedule atau first schedule
        $penjadwalan->needBackup = 'NO';
        $queryFilterDospem = 'penjadwalan_sidang.dosen_pembimbing_id';
        $valueFilterDospem = $penjadwalan->dosen_pembimbing_id;
        
        if(isset($data['dospem_backup_required'])) {
            $penjadwalan->needBackup = 'YES';                     
            $queryFilterDospem = 'penjadwalan_sidang.dosen_pembimbing_backup';
            $valueFilterDospem = $penjadwalan->dosen_pembimbing_backup;
        } else {
            $penjadwalan->dosen_pembimbing_backup = 0;   
        }

        // tanggal_sidang
        $tanggal_sidang = $penjadwalanDateCarbon->format('Y-m-d');
        $jam_sidang = intVal($penjadwalanDateCarbon->format('Hi'));
        $selisiJam = 100; // 100 = 1jam

        $penjadwalanModel = new Jadwal;
        
        // start check dosen penguji schedule used
        $getDataPenjadwalanToCheckDosenPengujiSchedule = $penjadwalanModel::join('requests as r', 'r.id', '=', 'penjadwalan_sidang.request_id')
            ->join('students as s', 's.id', '=', 'r.student_id')
            ->where('r.scheduled_status', '!=', 0)
            ->where('r.status_sidang', StatusSidang::Waiting)
            ->where(function($query) use ($penjadwalan) {
                $query->where('penjadwalan_sidang.dosen_pembimbing_id', $penjadwalan->dosen_penguji_id)
                    ->orWhere('penjadwalan_sidang.dosen_penguji_id', $penjadwalan->dosen_penguji_id)
                    ->orWhere('penjadwalan_sidang.dosen_pembimbing_backup', $penjadwalan->dosen_penguji_id);   
            })
            ->where('penjadwalan_sidang.id', '!=', $penjadwalan->id)
            ->whereDate('penjadwalan_sidang.tanggal_sidang', $tanggal_sidang)
            ->where('status_penjadwalan', '!=', 'EXPIRED')
            ->get()->toArray();

        $dosenPengujiUsed = false;
        $tanggal_sidang_dosen_penguji = '';
        for($i = 0; $i < count($getDataPenjadwalanToCheckDosenPengujiSchedule); $i++) 
        {
            $milisecond = intVal(Carbon::createFromFormat('Y-m-d H:i:s', $getDataPenjadwalanToCheckDosenPengujiSchedule[$i]['tanggal_sidang'])->format('Hi'));
            $selisihMili = 0;
            if($milisecond > $jam_sidang) {
                $selisihMili = $milisecond - $jam_sidang;
            } 
            else {
                $selisihMili = $jam_sidang - $milisecond;
            }
        
            // check is greater then 60
            if($selisihMili >= $selisiJam) {
                continue;
            } else {
                $tanggal_sidang_dosen_penguji = Carbon::createFromFormat('Y-m-d H:i:s', $getDataPenjadwalanToCheckDosenPengujiSchedule[$i]['tanggal_sidang'])->format('d-m-Y h:i A');
                $dosenPengujiUsed = true;
                break;
            }
                       
        }   
        // end of check dosen peguji used or not

        $getDataPenjadwalanToCheckDospemSchedule = $penjadwalanModel::join('requests as r', 'r.id', '=', 'penjadwalan_sidang.request_id')
            ->join('students as s', 's.id', '=', 'r.student_id')
            ->where('r.scheduled_status', '!=', 0)
            ->where('r.status_sidang', StatusSidang::Waiting)
            // by clause use dospem or back up dospem
            ->where(function($query) use ($valueFilterDospem) {
                $query->where('penjadwalan_sidang.dosen_pembimbing_id', $valueFilterDospem)
                    ->orWhere('penjadwalan_sidang.dosen_penguji_id', $valueFilterDospem)
                    ->orWhere('penjadwalan_sidang.dosen_pembimbing_backup', $valueFilterDospem);
            })
            ->where('penjadwalan_sidang.id', '!=', $penjadwalan->id)
            ->whereDate('penjadwalan_sidang.tanggal_sidang', $tanggal_sidang)
            ->where('status_penjadwalan', '!=', 'EXPIRED')
            ->get()->toArray();
        
        $dospemOrDospemBackUpUsed = false;
        $tanggal_sidang_dospemOrBackup = '';
        for($i = 0; $i < count($getDataPenjadwalanToCheckDospemSchedule); $i++) {
            $milisecond = intVal(Carbon::createFromFormat('Y-m-d H:i:s', $getDataPenjadwalanToCheckDospemSchedule[$i]['tanggal_sidang'])->format('Hi'));
            $selisihMili = 0;
            if($milisecond > $jam_sidang) {
                $selisihMili = $milisecond - $jam_sidang;
            } else {
                $selisihMili = $jam_sidang - $milisecond;
            }

            // check is greater then 6000
            if($selisihMili >= $selisiJam) {
                continue;
            } else {
                $tanggal_sidang_dospemOrBackup = Carbon::createFromFormat('Y-m-d H:i:s', $getDataPenjadwalanToCheckDospemSchedule[$i]['tanggal_sidang'])->format('d-m-Y h:i A');
                $dospemOrDospemBackUpUsed = true;
                break;
            }
        }
        // end of dospem/back up used or not

        // validate throw error message to user
        $validateMessageBag = new MessageBag;
        if($dosenPengujiUsed) {
            $validateMessageBag->add('dosen sudah dipakai', 'Dosen Penguji Sudah digunakan pada ' . $tanggal_sidang_dosen_penguji . ' silahkan ulang melakukan penjadwalan!');
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('jadwal', $penjadwalan->id), $validateMessageBag);
        } else if($penjadwalan->needBackup === 'YES' && $dospemOrDospemBackUpUsed == true) {
            $validateMessageBag->add('dosen sudah dipakai', 'Dosen Pembimbing Backup Sudah digunakan pada ' . $tanggal_sidang_dospemOrBackup . ' silahkan ulang melakukan penjadwalan!');
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('jadwal', $penjadwalan->id), $validateMessageBag);
        } else if($penjadwalan->needBackup === 'NO' && $dospemOrDospemBackUpUsed == true) {
            $validateMessageBag->add('dosen sudah dipakai', 'Dosen Pembimbing sudah digunakan pada ' . $tanggal_sidang_dospemOrBackup . ' silahkan ulang melakukan penjadwalan!');
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('jadwal', $penjadwalan->id), $validateMessageBag);
        }

        // history penjadwalan attribute will be used
        $history = new HistoryJadwal;
        $history->penjadwalan_sidang_id = $penjadwalan->id;
        $history->dosen_pembimbing_or_backup_id = $oldDosenPembimbingOrBAKId;
        $history->dosen_penguji_id = $oldDosenPengujiId;
        $history->ruangan_sidang_id = $oldRuanganId;
        $history->tanggal_waktu_sidang = $oldTanggalWaktuSidang;
        $history->created_at = now();

        // okay dosen can be used now do store data
        if($penjadwalan->validate($penjadwalan, $data, $penjadwalan->messages('validation'))) {
            DB::beginTransaction();
            try {
                // update request table
                $customRequestModel = new CustomRequest;
                $customRequest = $customRequestModel::where('id', $penjadwalan->request_id)->first();
                $scheduled_status = $customRequest->scheduled_status;
                $isRescheduled = false;
                $penjadwalan->status_penjadwalan = 'COMPLETED';
                $penjadwalan->updated_at = now();
                if($scheduled_status == 0 || $scheduled_status == null) {
                    $customRequest->scheduled_status = 1;                    
                    // status pengiriman
                    $penjadwalan->status_pengiriman = 0;
                    // scheduler will run to make prodi able to reschedule
                    $penjadwalan->tanggal_revisi_sidang = $penjadwalanDateCarbon->subDays(3)->format('Y-m-d H:i:s');
                    // scheduler run for make prodi cannot reschedule
                    $penjadwalan->tanggal_expired_revisi_sidang = $penjadwalanDateCarbon->addDays(1)->format('Y-m-d H:i:s');
                    // scheduler will set penjadwalan to be old data                    
                    $penjadwalan->tanggal_penjadwalan_expired = $penjadwalanDateCarbon->addDays(3)->format('Y-m-d H:i:s');
                } else if($penjadwalan->status_pengiriman == 1 || $penjadwalan->status_pengiriman == 0) { // when baak has not send the email yet
                    $isRescheduled = true;
                    // save history
                    $history->save();
                    // dd('damm');
                    $customRequest->scheduled_status = 2;
                    $penjadwalan->tanggal_penjadwalan_expired = $penjadwalanDateCarbon->addDays(1)->format('Y-m-d H:i:s');
                    // reset ruangan sidang id cause is reschedule
                    $penjadwalan->ruangan_sidang_id = null;
                } else {
                    // save history
                    $history->save();

                    // use this for when only the first reschedule got sent the email (surat undangan)
                    $isRescheduled = true;
                    $customRequest->scheduled_status = 2;
                    // status pengiriman
                    $penjadwalan->status_pengiriman = 3;
                    // scheduler will set penjadwalan to be old data   
                    $penjadwalan->tanggal_penjadwalan_expired = $penjadwalanDateCarbon->addDays(1)->format('Y-m-d H:i:s');
                    // reset ruangan sidang id cause is reschedule
                    $penjadwalan->ruangan_sidang_id = null;
                }

                // save request table
                $customRequest->save();
                // save the penjadwalan one
                $penjadwalan->save();
                
                // email to admin baak untuk atur ruangan
                $adminAddress = \Config::get('customconfig.admin_address');
                if($isRescheduled == false  && $customRequest->scheduled_status = 1) {
                    Mail::to($adminAddress)->send(new ProdiPenjadwalan($customRequest, $studyProgramName, 
                    $dosenPembimbing, 
                    $newDosenPenguji,
                    $newDosenPembimbingBackUp,
                    $penjadwalan->needBackup,
                    Carbon::createFromFormat('Y-m-d H:i:s', $penjadwalan->tanggal_sidang)->format('d/m/Y h:i A')));
                } else {
                    Mail::to($adminAddress)->send(new ProdiPerubahanJadwal($customRequest, $studyProgramName,
                    $dosenPembimbing,
                    $newDosenPenguji,
                    $newDosenPembimbingBackUp,
                    $penjadwalan->needBackup,
                    Carbon::createFromFormat('Y-m-d H:i:s', $penjadwalan->tanggal_sidang)->format('d/m/Y h:i A')));

                    // update berita acara report scheduled at to be null and expired at
                    $beritaAcaraReport = BeritaAcaraReport::where('request_id', $customRequest->id)->first();
                    if(isset($beritaAcaraReport)) {
                        $beritaAcaraReport->scheduled_at = null;
                        $beritaAcaraReport->expired_at = null;
                        $beritaAcaraReport->updated_at = now();
                        $beritaAcaraReport->save();
                    }
                }                 

                DB::commit();

                if($customRequest->scheduled_status == 1) {
                    // redirect
                    $this->setFlashMessage('success', $penjadwalan->messages('success', 'schedule'));
                } else {
                    $this->setFlashMessage('success', $penjadwalan->messages('success', 'reschedule'));
                }
                return redirect($this->getRoute('list'));
            } catch(\Exception $e) {
                dd($e);
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('jadwal', $penjadwalan->id), $e);
            }
            
        } else {
            $errors = $penjadwalan->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('jadwal', $penjadwalan->id), $errors);
        }       
    }

    /**
     * view penjadwalan print page
     */
    public function viewPenjadwalan(Jadwal $penjadwalan) {
        $breadcrumbs = $this->getBreadCrumbs('lihatPenjadwalanSidangSkripsi');
        $title = $this->getTitle('lihatPenjadwalanSidangSkripsi');
        $sub_title = $this->getSubTitle('lihatPenjadwalanSidangSkripsi');
        $penjadwalan_css = 'active';
        $jadwalSkripsi_css = 'active';

        // penjadwalan
        $pembimbingId = $penjadwalan->dosen_pembimbing_id;
        if($pembimbingId == 0) {
            $pembimbingId = $penjadwalan->dosen_pembimbing_backup;
        }

        $penguji = ProdiUserAssignment::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
            ->where('prodi_user_assignment.id', $pembimbingId)
            ->first();
        $pengujiUserName = '';
        if($penguji != null) {
            $pengujiUserName = $penguji->username;
        }

        $ketuaPenguji = ProdiUserAssignment::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
            ->where('prodi_user_assignment.id', $penjadwalan->dosen_penguji_id)
            ->first();
        $ketuaPengujiUserName = '';
        if($ketuaPenguji != null) {
            $ketuaPengujiUserName = $ketuaPenguji->username;
        }        
        
        $ruanganSidang = $penjadwalan->ruangan_sidang()->first();
        $ruanganSidangVal = '';
        if($ruanganSidang != null) {
            $ruanganSidangVal = 'Gedung ' . $ruanganSidang->gedung . ' - ' . $ruanganSidang->ruangan;
        }

        $penjadwalan->tanggal_sidang = DateTime::createFromFormat('Y-m-d H:i:s', $penjadwalan->tanggal_sidang)->format('d-m-Y h:i A');

        $backRoute = route('prodi.penjadwalan.skripsi');

        return view('admin.schedule.view_skripsi_schedule', 
                compact('breadcrumbs', 'title', 'sub_title', 'penjadwalan_css', 'jadwalSkripsi_css', 'backRoute',
                'penjadwalan', 'pengujiUserName', 'ketuaPengujiUserName', 'ruanganSidangVal'));
    }

    //
    //
    // scheduler section
    //
    //

    /**
     * notify prodi admin to update the data if needed
     * for h-3 only
     */
    public function notifyProdiAdminToRescheduleIfNeed() {
        $penjadwalanSidangModel = new Jadwal;
        $penjadwalanSidang = $penjadwalanSidangModel::where('status_penjadwalan', 'COMPLETED')
                            ->whereDate('tanggal_revisi_sidang', Carbon::today())
                            ->where('sidang_type', CreationType::Skripsi)
                            ->get();

        $needRevise = false;

        Log::info(Carbon::today() . ': got ' . $penjadwalanSidang->count() . ' data');
        foreach($penjadwalanSidang as $object) {            
            try {
                $object->updated_at = now();
                $object->status_penjadwalan = 'ONGOING';
                $object->save();
                $needRevise = true;
                Log::info('berhasil update status revisi ' . Carbon::today() . ' penjadwalan pada id: ' . $object->id);
            } catch(\Exception $e) {
                Log::info($e);
                Log::info('gagal update status penjadwalan untuk revisi ' . Carbon::today(). ' pada id '. $object->id);
            }
            
        }

        if($needRevise) {
            // get each prodi email
            $penjadwalanSidangDistinct = $penjadwalanSidangModel::distinct()->where('sidang_type', CreationType::Skripsi)->get(['penjadwalan_by']);
            foreach($penjadwalanSidangDistinct as $object) {
                $prodi_user = new ProdiUser;
                $prodiStudyProgram = $prodi_user::where('id', $object->penjadwalan_by)->first();
                $prodiAdmin = $prodiStudyProgram->email;
                $prodiName = $prodiStudyProgram->studyprogram()->first()->name;
                // do send email
                try {
                    $skripsi = 'Skripsi';
                    Mail::to($prodiAdmin)->send(new ReschedulePenjadwalanNotification($prodiName, $skripsi));
                    Log::info('berhasil kirim notif reschedule sidang ke prodi ' . $prodiName . ' waktu: '.Carbon::today());
                } catch(\Exception $e) {
                    Log::info($e);
                    Log::info('gagal kirim notif ke prodi '. $prodiName. ' waktu: '. Carbon::today());
                }
                
            }
        }
    }

    /**
     * update no revision probhited on h2
     */
    public function updateNoRevisionOnH2Request() {
        $penjadwalanSidangModel = new Jadwal;
        $penjadwalanSidang = $penjadwalanSidangModel::whereRaw('status_penjadwalan = "ONGOING"')
                            ->whereDate('tanggal_expired_revisi_sidang', Carbon::today())
                            ->where('sidang_type', CreationType::Skripsi)
                            ->get();

        foreach($penjadwalanSidang as $object) {
            try {
                $object->updated_at = now();
                $object->status_penjadwalan = 'COMPLETED';
                $object->save();
                Log::info('berhasil update status completed pada penjadwalan id : ' . $object->id);
            } catch(\Exception $e) {
                Log::info($e);
                Log::info('gagal update status completed pada penjadwalan id : ' . $object->id);
            }
        }
    }

    /**
     * update data penjadwalan yang sudah lewat dari hari sidang untuk ke data yang sudah lama
     */
    public function updatePenjadwalanIntoOldData() {
        $penjadwalanSidangModel = new Jadwal;
        $penjadwalanSidang = $penjadwalanSidangModel::whereRaw('status_penjadwalan = "COMPLETED"')
                            ->whereDate('tanggal_penjadwalan_expired', Carbon::today())
                            ->where('sidang_type', CreationType::Skripsi)
                            ->get();
        
        foreach($penjadwalanSidang as $object) {
            try {
                $object->updated_at = now();
                $object->status_penjadwalan = 'EXPIRED';
                $object->save();
                Log::info('berhasil update data yang sudah sidang ke data lama pada penjadwalan id : ' . $object->id);
            } catch(\Exception $e) {
                Log::info($e);
                Log::info('gagal update status expired pada penjadwalan id : ' + $object->id);
            }
        }
    }
}