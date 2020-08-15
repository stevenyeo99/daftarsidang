<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\MasterController;
use App\Models\BeritaAcaraReport as Acara;
use App\Models\BeritaAcaraParticipant as Penguji;
use App\Models\BeritaAcaraNoteRevisi as NoteRevisi;
use App\Models\RuanganSidang;
use App\Models\ProdiUser;
use App\Enums\CreationType;
use App\Enums\StatusSidang;
use App\Enums\RequestStatus;
use App\Enums\MailParticipantType;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Log;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
// mail
use Illuminate\Support\Facades\Mail;
use App\Mail\HasilSidangMahasiswa;
use App\Mail\NoteRevisiSidangMahasiswa;
use App\Mail\ReminderSidang;

class ProdiBeritaAcaraSidangKPController extends MasterController
{
    public function __construct() {
        $this->middleware('auth:prodis');
    }

    // status 
    // 0 new
    // 1 ongoing
    // 2 completed
    // 5 ongoing no submitted

    public function index() {
        $breadcrumbs = $this->getBreadCrumbs('beritaAcaraSidangKP');
        $title = $this->getTitle('beritaAcaraSidangKP');
        $sub_title = $this->getSubTitle('beritaAcaraSidangKP');
        $beritaAcaraMaster_css = 'active';
        $beritaAcaraKP_css = 'active';
        $listOfRuanganSidang = RuanganSidang::orderBy('gedung', 'ASC')->get();
        $ruanganSidangArr = [];
        foreach($listOfRuanganSidang as $object) {
            $ruanganSidangArr[$object->id] = 'Gedung ' . $object->gedung . ' - ' . $object->ruangan;
        }
        return view('prodi.berita_acara.kp.kp', compact('breadcrumbs', 'title', 'sub_title', 'beritaAcaraMaster_css', 'beritaAcaraKP_css', 'ruanganSidangArr'));
    }

    /**
     * get list of berita acara for participant to access it
     */
    public function getListOfBeritaAcaraForEachParticipant() {
        $penguji = \Auth::guard('prodis')->user();
        $listOfBeritaAcara = Penguji::join('berita_acara_report AS master_acara', 'master_acara.id', '=', 'berita_acara_participant.berita_acara_report_id')
            ->join('requests AS r', 'r.id', '=', 'master_acara.request_id')
            ->join('students AS s', 's.id', '=', 'r.student_id')
            ->join('penjadwalan_sidang AS ps', 'ps.id', '=', 'master_acara.penjadwalan_sidang_id')
            ->join('ruangan_sidang AS rs', 'rs.id', '=', 'ps.ruangan_sidang_id')
            ->join('prodi_user AS pu', 'pu.id', '=', 'berita_acara_participant.participant_id')
            ->where('pu.id', $penguji->id)
            ->where('r.type', CreationType::KP)
            ->where(function($query) {
                $query->where('master_acara.status', 1)
                    ->orWhere('master_acara.status', 5);
            })
            ->where(function($query) {
                $query->where('r.status' , '!=', RequestStatus::Reject)
                    ->where('r.status', '!=', RequestStatus::RejectBySistem)
                    ->where('r.status', '!=', RequestStatus::RejectFinance)
                    ->where('r.status', '!=', RequestStatus::RejectProdi);
            })
            ->selectRaw('master_acara.status AS status, berita_acara_participant.id AS id, berita_acara_participant.participant_type, master_acara.pembimbing_submit_at, master_acara.penguji_submit_at, s.npm, s.name, r.title, CONCAT("Gedung ", rs.gedung, " - ", rs.ruangan) AS ruangan_sidang');

        return Datatables::of($listOfBeritaAcara)
            ->setRowClass(function() {
                return "custom-tr-text-ellipsis"; 
            })
            ->filterColumn('npm', function($query, $keyword) {
                $query->whereRaw("CONCAT(s.npm, '-', s.npm) LIKE ?", ["%{$keyword}%"]);
            })
            ->filterColumn('name', function($query, $keyword) {
                $query->whereRaw("CONCAT(s.name, '-', s.name) LIKE ?", ["%{$keyword}%"]);
            })
            ->filterColumn('title', function($query, $keyword) {
                $query->whereRaw("CONCAT(r.title, '-', r.title) LIKE ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ruangan_sidang', function($query, $keyword) {
                $query->where("rs.id", $keyword);
            })
            ->addColumn('actions', function(Penguji $participant) {
                return $this->getActionsButton($participant);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * get actions button
     */
    public function getActionsButton($model) {
        $model_id = $model->id;
        $formSidang = $this->getRoute('form', $model_id);     
        // if already submit dont need to display edit button again
        $type = $model->participant_type;
        $pengujiSubmitAt = $model->penguji_submit_at;
        $pembimbingSubmitAt = $model->pembimbing_submit_at;
        $formStatus = $model->status;
        $master = Acara::where('id', $model->report_id)->first();
        $canFillForm = false;
        if(($type == 0 && $pembimbingSubmitAt == null && $formStatus == 1) || ($type == 1 && $pengujiSubmitAt == null && $formStatus == 1)) {
            return "<a href='{$formSidang}' title='Sidang' class='btn btn-primary glowing-button'>Sidang</a>"; 
        } 
               
    }

    public function getRoute($key, $id = null) {
        switch($key) {
            case 'list':
                return route('prodi.berita.acara.kp');
            case 'form':
                return route('prodi.berita_acara_form.kp', $id);
            case 'save':
                return '';
            default:
                break;
        }
    }

    /**
     * for prototype design berita acara form
     */
    public function formSidang(Penguji $participant) {
        if(!Carbon::parse($participant->report()->first()->scheduled_at)->lt(Carbon::now())) {
            $acara = new Acara;
            $errors = new MessageBag([$acara->messages('fail', 'formNotRightTiming')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }

        $breadcrumbs = $this->getBreadCrumbs('formulirBeritaAcaraSidangKP');
        $title = $this->getTitle('formulirBeritaAcaraSidangKP');
        $sub_title = $this->getSubTitle('formulirBeritaAcaraSidangKP');
        $beritaAcaraMaster_css = 'active';
        $beritaAcaraKP_css = 'active';
        $beritaAcaraMaster = $participant->report()->first();
        $listOfNoteRevision = $participant->revisi()->get();

        return view('prodi.berita_acara.kp.kp_form', compact('beritaAcaraMaster', 'participant', 'listOfNoteRevision',
         'breadcrumbs', 'title', 'sub_title', 'beritaAcaraMaster_css', 'beritaAcaraKP_css'));
    }

    // store form sidang
    public function isiFormSidang(Penguji $participant, Request $request) {
        // update master report column
        try {
            DB::beginTransaction();
            $participantType = $participant->participant_type;            
            $acara = Acara::where('id', $participant->berita_acara_report_id)->first();
            if($participantType == 1) {
                $acara->nilai_score = $request->get('nilai_score');
                $acara->nilai_index = $request->get('nilai_index');
                $acara->nilai_ip = $request->get('nilai_ip');
                $acara->penguji_submit_at = now();
                $acara->score_submit_at = now();
                $acara->scored_by = $participant->participant_id;
            } else {
                $acara->pembimbing_submit_at = now();
            }
            $acara->updated_at = now();
            if($acara->pembimbing_submit_at != null && $acara->penguji_submit_at != null) {
                $acara->status = 2;
            }
            $acara->save();
            
            // update participant table
            $hasRevision = $request->get('txtNeedRevision');
            $participant->have_revision = intVal($hasRevision);
            $participant->updated_at = now();
            $participant->save();

            // store revisi table
            $totalRevision = $request->get('txtTotalRevision');
            if($totalRevision != '0') {
                for($i = 1; $i <= intVal($totalRevision); $i++) {
                    $noteRevision = $request->get('txtNoteRevisi_' . $i);
                    if($noteRevision == null) {
                        $noteRevision = '';
                    }

                    $noteRevisi = new NoteRevisi;
                    $noteRevisi->berita_acara_participant_id = $participant->id;
                    $noteRevisi->note_revisi = $noteRevision;
                    $noteRevisi->created_at = now();
                    $noteRevisi->save();
                }
            }
            
            $studentEmaillAddress = $acara->request()->first()->student()->first()->email;
            $beritaAcaraParticipant = $participant->dosen()->first();
            $listOfNoteRevision = NoteRevisi::where('berita_acara_participant_id', $participant->id)->get();
            $arrayNotes = [];
            foreach($listOfNoteRevision as $object) {
                array_push($arrayNotes, $object->note_revisi);
            }

            $requestSidang = $acara->request()->first();
            // send email to student about the revision and the student is pass or not
            if($participantType == 1) {
                // update table request buat status kelulusan atau tidaknya
                $requestSidang->status_lulus = intVal($request->get('txtStatusLulus'));
                $requestSidang->updated_at = now();
                
                // send note revisi if requeired to mahasiswa
                if(count($arrayNotes) > 0) {
                    Mail::to($studentEmaillAddress)->send(new NoteRevisiSidangMahasiswa($acara, $beritaAcaraParticipant, $arrayNotes, 'Kerja Praktek'));
                }
                
                // send email to student the result
                $isLulus = 'YES';
                if($requestSidang->status_lulus == 13) {
                    $isLulus = 'NO';
                }
                Mail::to($studentEmaillAddress)->send(new HasilSidangMahasiswa($acara, 'Kerja Praktek', $isLulus));
            } else {
                // send note revisi if required to mahasiswa
                if(count($arrayNotes) > 0) {
                    Mail::to($studentEmaillAddress)->send(new NoteRevisiSidangMahasiswa($acara, $beritaAcaraParticipant, $arrayNotes, 'Kerja Praktek'));
                }
            }

            $requestSidang->status_sidang = StatusSidang::Done;
            $requestSidang->save();

            // update sidang finish

            DB::commit();
            $this->setFlashMessage('success', $acara->messages('success', 'save'));
            return redirect($this->getRoute('list'));            
        } catch(\Exception $e) {
            dd($e);
            DB::rollback();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $e);
        }
    }

    /**
     * Method for running schedule that prodi participant envolve can retrieve on the list of the form
     */
    public function scheduleBeritaAcaraForm() {
        $listSidangHariIni = Acara::join('requests AS r', 'r.id', '=', 'berita_acara_report.request_id')
            ->where('r.type', CreationType::KP)
            ->where('berita_acara_report.status', 0)
            ->whereDate('berita_acara_report.scheduled_at', Carbon::today())
            ->selectRaw('berita_acara_report.*')->get();

        Log::info(Carbon::today() . ': got ' . $listSidangHariIni->count() . ' data');

        foreach($listSidangHariIni as $sidang) {
            try {    
                $studentObject = $sidang->request()->first()->student()->first();
                $studyProgramId = $studentObject->study_program_id;          
                $customRequest = $sidang->request()->first();            
                $studentEmailAddress = $customRequest->student()->first()->email;
                $pengujiId = $sidang->penguji_user_id;
                $ketuaPengujiId = $sidang->ketua_penguji_user_id;
                // get penguji and ketua penguji email address
                $pengujiObject = ProdiUser::where('id', $pengujiId)->first();
                $ketuaPengujiObject = ProdiUser::where('id', $ketuaPengujiId)->first();
                
                $sidang->status = 1;
                $sidang->updated_at = now();

                // send email for reminder to mahasiswa, penguji, ketua penguji, prodi
                // send to prodi first
                $prodiAdminByProdi = ProdiUser::where('study_programs_id', $studyProgramId)
                                                ->where('is_admin', 1)
                                                ->get();
                foreach($prodiAdminByProdi as $admin) {
                    $prodiAdminEmailAddress = $admin->email;
                    if($prodiAdminEmailAddress != null && $prodiAdminEmailAddress != '') {
                        Mail::to($prodiAdminEmailAddress)->send(new ReminderSidang($sidang, MailParticipantType::ADMIN_PRODI, $admin));
                    }
                }               

                // send to participant
                Mail::to($pengujiObject->email)->send(new ReminderSidang($sidang, MailParticipantType::DOSEN_PEMBIMBING, $pengujiObject));
                Mail::to($ketuaPengujiObject->email)->send(new ReminderSidang($sidang, MailParticipantType::DOSEN_PENGUJI, $ketuaPengujiObject));

                // send to student
                Mail::to($studentEmailAddress)->send(new ReminderSidang($sidang, MailParticipantType::MAHASISWA));

                $sidang->save();
                Log::info('in object iteration on id ' . $sidang->id);
            } catch(\Exception $e) {
                Log::info($e);
                Log::info('terjadi error pada saat update status berita acara agar bisa ditampilkan pada pihak yang berwewenang pada ' . Carbon::today() . ' dengan id ' . $sidang->id );
            }
        }
    }

    /**
     * update set form tidak bisa diakses
     */
    public function expiredDateForSubmitForm() {
        $listSidangFormHaveNotSubmitted = Acara::join('requests AS r', 'r.id', '=', 'berita_acara_report.request_id')
            ->where('r.type', CreationType::KP)
            ->where('berita_acara_report.status', 1)
            ->whereDate('berita_acara_report.expired_at', Carbon::today())
            ->selectRaw('berita_acara_report.*')->get();

        Log::info(Carbon::today() . ': got' . $listSidangFormHaveNotSubmitted->count(). ' data');
        foreach($listSidangFormHaveNotSubmitted as $expiredForm) {
            try {
                if($expiredForm->penguji_submit_at != null && $expiredForm->pembimbing_submit_at != null) {
                    $expiredForm->status = 2;                    
                } else {
                    $expiredForm->status = 5;
                }
                $expiredForm->updated_at = now();
                $expiredForm->save();
            } catch(\Exception $e) {
                Log::info($e);
                Log::info('terjadi error pada saat update status form berita acara yang belum submit untuk tidak bisa diakses');
            }
        }
    }
}
