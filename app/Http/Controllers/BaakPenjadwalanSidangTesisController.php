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
use App\Enums\StatusSidang;
use App\Enums\SessionStatus as SessionStatusEnum;
use App\Models\Request as CustomRequest;
use App\Models\PenjadwalanSidang as Jadwal;
use App\Models\ProdiUser;
use App\Models\StudyProgram;
use DateTime;
use Carbon\Carbon;
use App\Models\RuanganSidang;
use App\Mail\SidangInvitation;
use App\Mail\CancelSidangInvitation;
use App\Models\ProdiUserAssignment;
use App\Models\OldPenjadwalanSidang as HistoryJadwal;
use Log;
use App\Models\BeritaAcaraReport as SidangReport;
use App\Models\BeritaAcaraParticipant as SidangPenguji;

class BaakPenjadwalanSidangTesisController extends MasterController {

    public function __construct() {
        $this->middleware('auth:web');
    }

    /**
     * first page
     */
    public function index() {
        $breadcrumbs = $this->getBreadCrumbs('penjadwalanSidangTesis');
        $title = $this->getTitle('penjadwalanSidangTesis');
        $sub_title = $this->getSubTitle('penjadwalanSidangTesis');
        $penjadwalan_css = 'active';
        $jadwalTesis_css = 'active';
        $ruanganSidang = RuanganSidang::all();
        $arrRuangan = [];
        foreach($ruanganSidang as $value) {
            $arrRuangan[$value->id] = 'Gedung ' . $value->gedung . ' - ' . $value->ruangan;
        } 
        return view('admin.schedule.tesis', compact('breadcrumbs', 'title', 'sub_title', 'penjadwalan_css', 'jadwalTesis_css', 'arrRuangan')); 
    }

    /**
     * get list of penjadwalan tesis data
     */
    public function getListOfPenjadwalanTesis() {
        $requests = Jadwal::join('requests as req', 'penjadwalan_sidang.request_id', '=', 'req.id')
                ->join('students AS sdt', 'req.student_id', '=', 'sdt.id')
                ->leftJoin('ruangan_sidang as rs', 'penjadwalan_sidang.ruangan_sidang_id', '=', 'rs.id')
                ->leftJoin('prodi_user_assignment AS pembimbing_assignment', 'pembimbing_assignment.id', '=', 'penjadwalan_sidang.dosen_pembimbing_id')
                ->leftJoin('prodi_user AS pembimbing', 'pembimbing.id', '=', 'pembimbing_assignment.prodi_user_id')
                ->leftJoin('prodi_user_assignment AS penguji_assignment', 'penguji_assignment.id', '=', 'penjadwalan_sidang.dosen_penguji_id')
                ->leftJoin('prodi_user AS penguji', 'penguji.id', '=', 'penguji_assignment.prodi_user_id')
                ->selectRaw('penjadwalan_sidang.*, sdt.npm AS npm, sdt.name AS name, pembimbing.username AS dosen_pembimbing_name, 
                penguji.username AS dosen_penguji_name, CONCAT("Gedung ", rs.gedung, " - ", rs.ruangan) AS ruangan_sidang')
                ->where('req.type', CreationType::Tesis)
                ->where(function($query) {
                   $query->where('req.status_sidang', StatusSidang::Waiting)
                   ->orWhere('req.status_sidang', StatusSidang::Done);
                })                
                ->where('req.scheduled_status', '!=', 0)
                ->orderBy('id', 'desc');

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
                ->filterColumn('tanggal_sidang', function($query, $keyword) {
                    $keyword = DateTime::createFromFormat('d-M-Y', $keyword)->format('Y-m-d');
                    $query->whereDate('tanggal_sidang', $keyword);
                })
                ->filterColumn('ruangan_sidang', function($query, $keyword) {
                    $query-where('penjadwalan_sidang.ruangan_sidang_id', $keyword);
                })
                ->filterColumn('is_set_ruangan', function($query, $keyword) {
                    if($keyword == '0') {
                        $query->whereRaw('penjadwalan_sidang.ruangan_sidang_id = 0 || penjadwalan_sidang.ruangan_sidang_id IS NULL');
                    } 
                    // else if($keyword == '1') {
                    //     $query->whereRaw('penjadwalan_sidang.ruangan_sidang_id != 0 AND penjadwalan_sidang.status_pengiriman IN (1, 4)');
                    // } 
                    else if($keyword == '1') {
                        $query->whereRaw('penjadwalan_sidang.status_pengiriman IN(1, 4)');
                    } else if($keyword == '2') {
                        $query->whereRaw('penjadwalan_sidang.status_pengiriman IN(2, 5)');
                    } 
                })
                ->editColumn('is_set_ruangan', function(Jadwal $jadwal) {
                    return $this->getRecordsStatusLabel($jadwal);
                })
                ->addColumn('actions', function(Jadwal $jadwal) {
                    return $this->getActionsButton($jadwal);
                })
                ->rawColumns(['actions', 'is_set_ruangan'])
                ->make(true);
    }

    /**
     * action label for status penjadwalan each column
     */
    private function getRecordsStatusLabel(Jadwal $jadwal) {
        $ruanganSidangId = $jadwal->ruangan_sidang_id;
        $statusPengirimanUndangan = $jadwal->status_pengiriman;

        if($ruanganSidangId == 0) {
            $name = 'Progress';
            $extra_class = 'warning';
        } else if($ruanganSidangId != 0) {
            $name = 'Selesai';
            $extra_class = 'info';
        } 

        if($statusPengirimanUndangan == 1 || $statusPengirimanUndangan == 4) {
            $name = 'Belum Kirim Undangan';
            $extra_class = 'danger';
        } else if($statusPengirimanUndangan == 2 || $statusPengirimanUndangan == 5) {
            $name = 'Telah Kirim Undangan';
            $extra_class = 'success';
        }

        return "<span class='label label-${extra_class}'>${name}</span>";
    }

    /**
     * get button to display on datatabess baak penjadwalan sidang baak Tesis
     */
    public function getActionsButton($model, array $extraClassToAdd = []) {
        $model_id = $model->id;
        $request_id = $model->request_id;
        $requestorModel = new CustomRequest;
        $requestor = $requestorModel->where('id', '=', $request_id)->first();
        $dateTimeNow = new DateTime();

        $viewRoute = $this->getRoute('view', $model_id);
        $jadwalRoute = $this->getRoute('jadwal', $model_id);
        $kirimUndangan = $this->getRoute('undangan', $model_id);

        $update = $this->getRoute('undangan', $model->id);
        $type = $model->sidang_type;
        $sidangType = 'Kerja Praktek';
        if($type == 1) {
            $sidangType = 'Skripsi';
        } else if($type == 2) {
            $sidangType = 'Tesis';
        } 

        // if already set the place to sidang need to display the button for send email to student and 2 dosen
        // display a button for baak to set jadwal (ruangan untuk sidang) 
        if($model->ruangan_sidang_id != 0 && $model->ruangan_sidang_id != null && $model->status_pengiriman != 2 && $model->status_pengiriman != 5 && $model->status_penjadwalan = 'COMPLETED') {
             return "<a href='{$jadwalRoute}' title='ATUR RUANGAN' class='btn btn-warning'><span class='fa fa-calendar'></span> Atur Ruangan </a>
             <a title='KIRIM UNDANGAN' class='btn btn-primary update-penjadwalan-status-confirmation glowing-button m-t-3' data-type='{$sidangType}' data-url='{$update}' data-target='#update-penjadwalan-status-confirmation-modal' data-toggle='modal'><span class='fa fa-envelope'></span> Kirim Undangan </a>";
        } else if(($model->status_pengiriman == 0 || $model->status_pengiriman == 1) || ($model->status_pengiriman == 4 || $model->status_pengiriman == 3)) {
            return "<a href='{$jadwalRoute}' title='ATUR RUANGAN' class='btn btn-warning'><span class='fa fa-calendar'></span> Atur Ruangan </a>";
        } else if($model->status_pengiriman == 2 || $model->status_pengiriman == 5) {
            return "<a title='LIHAT' class='btn btn-info' href='{$viewRoute}'><span class='fa fa-eye'></span> Lihat</a>";
        }
        
    }

    /**
     * get route for penjadwalan sidang on baak site
     */
    public function getRoute($key, $id = null) {
        switch($key) {
            case 'list':
                return route('baak.penjadwalan.tesis');
            case 'view':
                return route('baak.penjadwalan.view.tesis', $id);
            case 'jadwal':
                return route('baak.penjadwalan.assign.tesis', $id);
            case 'undangan':
                return route('baak.penjadwalan.invitation.tesis', $id);
            default:
                break;
        }
    }

    /**
     * get form for baak to change ruangan sidang
     */
    public function getPenjadwalanForStoreData(Jadwal $penjadwalan) {
        $breadcrumbs = $this->getBreadCrumbs('formulirPenjadwalanSidangTesis');
        $title = $this->getTitle('formulirPenjadwalanSidangTesis');
        $sub_title = $this->getSubTitle('formulirPenjadwalanSidangTesis');
        $penjadwalan_css = 'active';
        $jadwalTesis_css = 'active';

        // ruangan sidang model
        $ruangan_sidang_model = new RuanganSidang;
        $ruangan_sidang = $ruangan_sidang_model->orderBy('gedung', 'ASC')->get();
        $ruangan_sidang_arr = [];
        foreach($ruangan_sidang as $r) {
            $ruangan_sidang_arr[$r->id] = 'Gedung '.$r->gedung.' - '.$r->ruangan; 
        }
        $prodiUserModel = new ProdiUser;
        $prodiUserAssignmentModel = new ProdiUserAssignment;
        // set pembimbing value, penguji value, pembimbing back up value
        $pembimbingValue = $prodiUserAssignmentModel::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                            ->where('prodi_user_assignment.id', $penjadwalan->dosen_pembimbing_id)->first()->username;
        $dosenPengujiValue = $prodiUserAssignmentModel::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                            ->where('prodi_user_assignment.id', $penjadwalan->dosen_penguji_id)->first()->username;
        $pembimbingBAKValue = '';
        if($penjadwalan->dosen_pembimbing_backup != 0) {
            $pembimbingBAKValue = $prodiUserAssignmentModel::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                            ->where('prodi_user_assignment.id', $penjadwalan->dosen_pembimbing_backup)->first()->username;
        }
        // re-format tanngal sidang value to display on form
        $penjadwalan->tanggal_sidang = DateTime::createFromFormat('Y-m-d H:i:s', $penjadwalan->tanggal_sidang)->format('d-m-Y h:i A');
        return view('admin.schedule.tesis_schedule', compact('breadcrumbs', 'title', 'sub_title', 'penjadwalan_css', 'jadwalTesis_css', 'penjadwalan',
         'pembimbingValue', 'dosenPengujiValue', 'pembimbingBAKValue', 'ruangan_sidang_arr'));
    }

    /**
     * save ruangan value onto representive table
     */
    public function storePenjadwalanData(Jadwal $penjadwalan, Request $request) {
        // baak user
        $baak = \Auth::guard()->user();
        
        // need to validate that ruangan is used or not
        $ruangan_sidang_array = $penjadwalan::where('ruangan_sidang_id', $request->ruangan_sidang)
            ->where('id', '!=', $penjadwalan->id)
            ->where('status_penjadwalan', '!=', 'EXPIRED')
            ->whereDate('tanggal_sidang', DateTime::createFromFormat('Y-m-d H:i:s', $penjadwalan->tanggal_sidang)->format('Y-m-d'))
            ->get();

        $ruanganDipakai = false;
        $jamSidang = intVal(Carbon::createFromFormat('Y-m-d H:i:s', $penjadwalan->tanggal_sidang)->format('Hi'));
        
        $selisiJam = 100;
        $messagesValidationErrorTime = '';
        foreach($ruangan_sidang_array as $penjadwalanRuangan) {

            $milisecond = intVal(Carbon::createFromFormat('Y-m-d H:i:s', $penjadwalanRuangan->tanggal_sidang)->format('Hi'));
            $selisihMili = 0;

            if($milisecond > $jamSidang) {
                $selisihMili = $milisecond - $jamSidang;
            } else {
                $selisihMili = $jamSidang - $milisecond;
            }

            if($selisihMili >= $selisiJam) {
                continue;
            } else {
                $messagesValidationErrorTime = Carbon::createFromFormat('Y-m-d H:i:s', $penjadwalanRuangan->tanggal_sidang)->format('d/m/Y h:i A');
                $ruanganDipakai = true;
                break;
            }
        }

        // jika dipakai nampilkan pesan ke user
        $validateMessageBag = new MessageBag;
        if($ruanganDipakai) {
            // get formatted ruangan
            $ruanganSidangMdl = new RuanganSidang;
            $ruangan = $ruanganSidangMdl::where('id', $request->ruangan_sidang)->first();
            $ruanganWord = 'Gedung '.$ruangan->gedung.' - '.$ruangan->ruangan;
            $validateMessageBag->add('Ruangan sudah dipakai', $ruanganWord . ' Telah dipakai pada ' . $messagesValidationErrorTime);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('jadwal', $penjadwalan->id), $validateMessageBag);
        }
        
        // 0 = melakukan pengaturan ruangan
        // 1 = sudah atur ruangan
        // 2 = sudah kirim undangan
        // 3 = ulang mengatur ruangan
        // 4 = sudah ulang atur ruangan
        // 5 = sudah ulang kirim undangan
        DB::beginTransaction();
        try {
            $penjadwalan->updated_at = now();
            $penjadwalan->penempatan_by = $baak->id;
            $penjadwalan->ruangan_sidang_id = $request->ruangan_sidang;
            if($penjadwalan->status_pengiriman == 0 || $penjadwalan->status_pengiriman == 1) {
                $penjadwalan->status_pengiriman = 1;
            } else {
                $penjadwalan->status_pengiriman = 4;
            }
            $penjadwalan->save();

            DB::commit();

            // display success message
            $this->setFlashMessage('success', $penjadwalan->messages('success', 'schedule-place'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            DB::rollback();
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('jadwal'), $e);
        }
    }

    /**
     * view penjadwalan print page
     */
    public function viewPenjadwalan(Jadwal $penjadwalan) {
        $breadcrumbs = $this->getBreadCrumbs('lihatPenjadwalanSidangTesis');
        $title = $this->getTitle('lihatPenjadwalanSidangTesis');
        $sub_title = $this->getSubTitle('lihatPenjadwalanSidangTesis');
        $penjadwalan_css = 'active';
        $jadwalTesis_css = 'active';

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

        $backRoute = route('baak.penjadwalan.tesis');
        // dd($backRoute);

        return view('admin.schedule.view_tesis_schedule', 
                compact('breadcrumbs', 'title', 'sub_title', 'penjadwalan_css', 'jadwalTesis_css', 'backRoute',
                'penjadwalan', 'pengujiUserName', 'ketuaPengujiUserName', 'ruanganSidangVal'));
    }

    /**
     * send invitation to student and 2 dosen for sidang
     * // update data on penjadwalan sidang
     * // 1 sudah kirim surat undangan untuk sidang
     */
    public function sendInvitation(Jadwal $penjadwalan) {
        
        DB::beginTransaction();

        try {
            // set attribue save penjadwalan sidang table data
            $penjadwalan->updated_at = now();
            if($penjadwalan->status_pengiriman == 1) {
                $penjadwalan->status_pengiriman = 2;
            } else {
                $penjadwalan->status_pengiriman = 5;
            }            
            $penjadwalan->save();
            
            // edit tanggal sidang date time
            $dateTimeSidang = $penjadwalan->tanggal_sidang;
            // changed 20200308 
            $penjadwalan->tanggal_sidang = DateTime::createFromFormat('Y-m-d H:i:s', $penjadwalan->tanggal_sidang)->format('d/m/Y h:i A');

            $customRequest = $penjadwalan->request()->first();
            $student = $customRequest->student()->first();

            // admin baak email
            $adminAddress = \Config::get('customconfig.admin_address');
            $prodiUserAssignment = new ProdiUserAssignment;
            $prodiUser = new ProdiUser;

            // mahasiswa email
            $studentAddress = $student->email;
            
            // dospem email
            $penjadwalan->dospem = $prodiUserAssignment::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                                ->where('prodi_user_assignment.id', $penjadwalan->dosen_pembimbing_id)->first(); 
            $dosenpemAddress = $penjadwalan->dospem->email;
            
            // admin prodi email
            $adminProdiAddress = $penjadwalan->prodiAdmin()->first()->email;
            
            // dosen penguji email
            $penjadwalan->dospenguji = $prodiUserAssignment::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                                ->where('prodi_user_assignment.id', $penjadwalan->dosen_penguji_id)->first();
            $dosenPengujiAddress = $penjadwalan->dospenguji->email;

            // ruangan sidang
            $ruanganSidangModel = new RuanganSidang;
            $ruanganSidang = $ruanganSidangModel::where('id', $penjadwalan->ruangan_sidang_id)->first();

            // dospem id or back up id
            $dospemId = $penjadwalan->dospem->id;
            if($penjadwalan->dosen_pembimbing_backup != 0) {
                $dospemId = $prodiUserAssignment::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                    ->where('prodi_user_assignment.id', $penjadwalan->dosen_pembimbing_backup)->first()->id;
            }

            // store berita acara
            // insert berita acara report first
            // status 0 new, 1 ongoing, 2 completed
            $sidangReportExist = SidangReport::where('penjadwalan_sidang_id', $penjadwalan->id)->first();
            if($sidangReportExist == null) {
                $sidangReport = new SidangReport;
            } else {
                $sidangReport = $sidangReportExist;
            }            
            $sidangReport->request_id = $penjadwalan->request_id;
            $sidangReport->penjadwalan_sidang_id = $penjadwalan->id;
            $sidangReport->status = 0;
            $sidangReport->scheduled_at = $dateTimeSidang;
            $sidangReport->expired_at = Carbon::createFromFormat('Y-m-d H:i:s', $dateTimeSidang)->addDays(3)->format('Y-m-d H:i:s');            
            $sidangReport->penguji_user_id = $dospemId;
            $sidangReport->ketua_penguji_user_id = $penjadwalan->dospenguji->id;
            if($sidangReportExist == null) {
                $sidangReport->created_at = now();
            } else {
                $sidangReport->updated_at = now();
            }
            $sidangReport->save();

            // insert berita acara participant first
            // 0 dospem 1 dospenguji
            // delete then recreate
            SidangPenguji::where('berita_acara_report_id', $sidangReport->id)->delete();

            $sidangPenguji = new SidangPenguji;
            $sidangPenguji->berita_acara_report_id = $sidangReport->id;           
            $sidangPenguji->participant_id = $dospemId;
            $sidangPenguji->participant_type = 0;
            $sidangPenguji->have_revision = 0;
            $sidangPenguji->created_at = now();
            $sidangPenguji->save();

            $sidangKetuaPenguji = new SidangPenguji;
            $sidangKetuaPenguji->berita_acara_report_id = $sidangReport->id;
            $sidangKetuaPenguji->participant_id = $penjadwalan->dospenguji->id;
            $sidangKetuaPenguji->participant_type = 1;
            $sidangKetuaPenguji->have_revision = 0;
            $sidangKetuaPenguji->created_at = now();
            $sidangKetuaPenguji->save();            

            if($penjadwalan->status_pengiriman == 2) {
                // send mail to admin baak
                Mail::to($adminAddress)->send(new SidangInvitation($penjadwalan, 'admin_baak', $ruanganSidang));

                // send mail to admin prodi    
                Mail::to($adminProdiAddress)->send(new SidangInvitation($penjadwalan, 'admin_prodi', $ruanganSidang));

                // send mail to student
                Mail::to($studentAddress)->send(new SidangInvitation($penjadwalan, 'student', $ruanganSidang));

                // send mail to dospem or dospem bak
                if($penjadwalan->dosen_pembimbing_backup == 0) {
                    Mail::to($dosenpemAddress)->send(new SidangInvitation($penjadwalan, 'dospem', $ruanganSidang));
                } else {
                    // dospem back up email
                    $penjadwalan->dospemBAK = $prodiUserAssignment::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                                    ->where('prodi_user_assignment.id', $penjadwalan->dosen_pembimbing_backup)->first();
                    $dosenpemBAKAddress = $penjadwalan->dospemBAK->email;
                    Mail::to($dosenpemBAKAddress)->send(new SidangInvitation($penjadwalan, 'dospemBAK', $ruanganSidang));
                }

                // send mail to dospenguji
                Mail::to($dosenPengujiAddress)->send(new SidangInvitation($penjadwalan, 'dospenguji', $ruanganSidang));
            } else {
                // lanjut reschedule process send cancel notification and send new pihak berwewenang
                $history = HistoryJadwal::where('penjadwalan_sidang_id', $penjadwalan->id)->first();
                // changed 20200308 
                $history->tanggal_waktu_sidang = DateTime::createFromFormat('Y-m-d H:i:s', $history->tanggal_waktu_sidang)->format('d/m/Y h:i A');
                $penjadwalan->history = $history;
                // set dospem old attribute
                $penjadwalan->dospemOld = $prodiUserAssignment::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                                    ->where('prodi_user_assignment.id', $history->dosen_pembimbing_or_backup_id)->first();                
                // set dospenguji old attribute and email
                $penjadwalan->dospengujiOld = $prodiUserAssignment::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                                        ->where('prodi_user_assignment.id', $history->dosen_penguji_id)->first();
                $dosenPengujiOldAddress = $penjadwalan->dospengujiOld->email;
                // set dospem bak attribute and email
                $penjadwalan->dospemBAKOld = $prodiUserAssignment::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                                        ->where('prodi_user_assignment.id', $history->dosen_pembimbing_or_backup_id)->first();
                $dospemBAKOldAddress = $penjadwalan->dospemBAKOld->email;
                // current dospem bak if is used currentlly
                $dosenpemBAKAddress = '';
                if($penjadwalan->dosen_pembimbing_backup != 0) {
                    $penjadwalan->dospemBAK = $prodiUserAssignment::join('prodi_user', 'prodi_user_assignment.prodi_user_id', '=', 'prodi_user.id')
                                    ->where('prodi_user_assignment.id', $penjadwalan->dosen_pembimbing_backup)->first();
                    $dosenpemBAKAddress = $penjadwalan->dospemBAK->email;
                }

                // send mail to student 
                // validate if ruangan id or tanggal_time got changes will send email to student
                if(($penjadwalan->ruangan_sidang_id != $history->ruangan_sidang_id) || ($penjadwalan->tanggal_sidang != $history->tanggal_waktu_sidang)) {
                    // dd('kirim ulang penjadwalan jika ruangan dan jam diganti ke mahasiswa');
                    Mail::to($studentAddress)->send(new SidangInvitation($penjadwalan, 'student_new', $ruanganSidang));
                    
                    // kirim email ke dosen pembimbing/bak ato dosen penguji jika jadwal terubah
                    if($penjadwalan->dosen_penguji_id == $history->dosen_penguji_id) {
                        Mail::to($dosenPengujiAddress)->send(new SidangInvitation($penjadwalan, 'dospenguji_new', $ruanganSidang));
                    }

                    if($penjadwalan->dosen_pembimbing_id == $history->dosen_pembimbing_or_backup_id) {
                        Mail::to($dosenpemAddress)->send(new SidangInvitation($penjadwalan, 'dospem_new', $ruanganSidang));
                    }

                    if($penjadwalan->dosen_pembimbing_backup == $history->dosen_pembimbing_or_backup_id) {
                        Mail::to($dosenpemBAKAddress)->send(new SidangInvitation($penjadwalan, 'dospemBAK_new', $ruanganSidang));
                    }

                    Mail::to($adminAddress)->send(new SidangInvitation($penjadwalan, 'admin_baak_new', $ruanganSidang));

                    Mail::to($adminProdiAddress)->send(new SidangInvitation($penjadwalan, 'admin_prodi_new', $ruanganSidang));
                } 

                // send mail to old penguji for cancellation, send email to the new penguji user            
                if($penjadwalan->dosen_penguji_id != $history->dosen_penguji_id) {
                    // dd('dosen penguji diganti');                    
                    Mail::to($dosenPengujiOldAddress)->send(new CancelSidangInvitation($penjadwalan, 'old_dospenguji', $ruanganSidang));
                    
                    Mail::to($dosenPengujiAddress)->send(new SidangInvitation($penjadwalan, 'dospenguji', $ruanganSidang));
                }

                // send mail to old pembimbing back up if needed for not used
                // if current dospem is not use backup and current dospem id and history not same means = previouslly is using back up then not using back up
                if(($penjadwalan->dosen_pembimbing_backup == 0 && ($penjadwalan->dosen_pembimbing_id != $history->dosen_pembimbing_or_backup_id))) {
                    // dd('sebelumnya pakai back up skrg gak pakai backup lagi');
                    Mail::to($dospemBAKOldAddress)->send(new CancelSidangInvitation($penjadwalan, 'old_dospembak', $ruanganSidang));
                
                    Mail::to($dosenpemAddress)->send(new SidangInvitation($penjadwalan, 'dospem', $ruanganSidang));
                } 
                // if current dospem is using back up and previous history not same means = previouslly is using back up and not using pembimbing id then change into different backup
                else if(($penjadwalan->dosen_pembimbing_backup != 0 && (($penjadwalan->dosen_pembimbing_backup != $history->dosen_pembimbing_or_backup_id) && ($penjadwalan->dosen_pembimbing_id != $history->dosen_pembimbing_or_backup_id)))) {
                    // dd('kirim email jika dosen back up sebelumnya berbeda dengan yang skrg');
                    Mail::to($dospemBAKOldAddress)->send(new CancelSidangInvitation($penjadwalan, 'old_dospembak', $ruanganSidang));
                
                    Mail::to($dosenpemBAKAddress)->send(new SidangInvitation($penjadwalan, 'dospemBAK', $ruanganSidang));
                }
                                              
                // send mail to dospem if he got changes
                if($penjadwalan->dosen_pembimbing_backup != 0 && $history->dosen_pembimbing_or_backup_id == $penjadwalan->dosen_pembimbing_id) {
                    // dd('kirim email ketika ulang penjadwalan dosen pembimbing diganti dospem backup');
                    Mail::to($dosenpemAddress)->send(new CancelSidangInvitation($penjadwalan, 'old_dospem', $ruanganSidang));

                    Mail::to($dosenpemBAKAddress)->send(new SidangInvitation($penjadwalan, 'dospemBAK', $ruanganSidang));
                }               
            }

            DB::commit();

            $this->setFlashMessage('success', $penjadwalan->messages('success', 'send-invitation'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            Log::info($e);
            DB::rollback();
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('list'), $e);
        }
    }
}