<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\MasterController;
use App\Models\BeritaAcaraReport as Acara;
use App\Models\BeritaAcaraParticipant as penguji;
use App\Models\BeritaAcaraNoteRevisi as NoteRevisi;
use App\Models\ProdiUser;
use App\Models\ProdiUserAssignment;
use App\Models\RuanganSidang;
use App\Enums\CreationType;
use App\Enums\StatusSidang;
use App\Enums\RequestStatus;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Log;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
use DateTime;

class BaakBeritaAcaraSidangTesisController extends MasterController {

    public function __construct() {
        $this->middleware('auth:web');
    }

    public function index() {
        $breadcrumbs = $this->getBreadCrumbs('beritaAcaraSidangTesis');
        $title = $this->getTitle('beritaAcaraSidangTesis');
        $sub_title = $this->getSubTitle('beritaAcaraSidangTesis');
        $beritaAcaraMaster_css = 'active';
        $beritaAcaraTesis_css = 'active';
        $listOfRuanganSidang = RuanganSidang::orderBy('gedung', 'ASC')->get();
        $ruanganSidangArr = [];
        foreach($listOfRuanganSidang as $object) {
            $ruanganSidangArr[$object->id] = 'Gedung ' . $object->gedung . ' - ' . $object->ruangan;
        }
        $statusBeritaAcaraArr = [
            0 => 'Baru',
            1 => 'Sedang Proses',
            2 => 'Selesai(Tersubmit)',
            5 => 'Selesai(Belum Tersubmit)'
        ];
        return view('admin.berita_acara.tesis.BaakBeritaAcaraTesis',
        compact('breadcrumbs', 'title', 'sub_title', 'beritaAcaraMaster_css', 'beritaAcaraTesis_css', 'ruanganSidangArr', 'statusBeritaAcaraArr'));
    }

     /**
     * get list berita acara sidang skripsi
     */
    public function getListBeritaAcaraSidangTesisBaak() {
        $listOfBeritaAcara = Acara::join('requests AS r', 'r.id', '=', 'berita_acara_report.request_id')
            ->join('prodi_user AS penguji', 'penguji.id', '=', 'berita_acara_report.penguji_user_id')
            ->join('prodi_user AS ketua_penguji', 'ketua_penguji.id', '=', 'berita_acara_report.ketua_penguji_user_id')
            ->join('students AS s', 's.id', '=', 'r.student_id')
            ->where('r.type', CreationType::Tesis)
            ->where(function($query) {
                $query->where('r.status' , '!=', RequestStatus::Reject)
                    ->where('r.status', '!=', RequestStatus::RejectBySistem)
                    ->where('r.status', '!=', RequestStatus::RejectFinance)
                    ->where('r.status', '!=', RequestStatus::RejectProdi);
            })
            ->selectRaw('berita_acara_report.id AS id, berita_acara_report.status AS status, s.name, s.npm, r.title,
            ketua_penguji.username AS ketua_penguji, penguji.username AS penguji, berita_acara_report.scheduled_at AS tanggal_sidang,
            CONCAT(berita_acara_report.nilai_index, " (", berita_acara_report.nilai_score, ")") AS nilai,
            berita_acara_report.pembimbing_submit_at AS penguji_submit, berita_acara_report.penguji_submit_at AS ketua_penguji_submit');

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
            ->editColumn('ketua_penguji', function($listOfBeritaAcara) {
                return $this->getKetuaPengujiAndPengujiLabelText($listOfBeritaAcara, 1);
            })
            ->filterColumn('ketua_penguji', function($query, $keyword) {
                $query->whereRaw("CONCAT(ketua_penguji.username, '-', ketua_penguji.username) LIKE ?", ["%{$keyword}%"]);
            })
            ->editColumn('penguji', function($listOfBeritaAcara) {
                return $this->getKetuaPengujiAndPengujiLabelText($listOfBeritaAcara, 0);
            })
            ->filterColumn('penguji', function($query, $keyword) {
                $query->whereRaw("CONCAT(penguji.username, '-', penguji.username) LIKE ?", ["%{$keyword}%"]);
            })
            ->editColumn('tanggal_sidang', function($listOfBeritaAcara) {
                $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $listOfBeritaAcara->tanggal_sidang);
                $formattedDateTime = $myDateTime->format('d-m-Y H:i A');
                return [
                    'display' => $formattedDateTime,
                    'datetime' => strtotime($listOfBeritaAcara->tanggal_sidang)
                ];
            })
            ->filterColumn('tanggal_sidang', function($query, $keyword) {
                $keyword = DateTime::createFromFormat('d-M-Y', $keyword)->format('Y-m-d');
                $query->whereDate('berita_acara_report.scheduled_at', $keyword);
            })
            ->editColumn('status', function($listOfBeritaAcara) {
                return $this->getStatusLabel($listOfBeritaAcara);
            })
            ->filterColumn('status', function($query, $keyword) {
                $query->where('berita_acara_report.status', $keyword);
            })
            ->addColumn('actions', function(Acara $beritaAcara) {
                return $this->getActionsButton($beritaAcara);
            })
            ->rawColumns(['actions', 'ketua_penguji', 'penguji', 'status'])
            ->make(true);
    }

    /**
     * For ketua penguji and penguji label text
     */
    public function getKetuaPengujiAndPengujiLabelText(Acara $beritaAcara, $type) {
        // for ketua penguji
        if($type == 1 && $beritaAcara->status == 5 && $beritaAcara->ketua_penguji_submit == null) {
            return "<span class='label label-danger'>{$beritaAcara->ketua_penguji}</span>";
        } else if($type == 1 && $beritaAcara->status == 5 && $beritaAcara->ketua_penguji_submit != null) {
            return "<span class='label label-success'>{$beritaAcara->ketua_penguji}</span>";
        } else if($type == 1) {
            return "<span>{$beritaAcara->ketua_penguji}</span>";
        }

        // for penguji
        if($type == 0 && $beritaAcara->status == 5 && $beritaAcara->penguji_submit == null) {
            return "<span class='label label-danger'>{$beritaAcara->penguji}</span>";
        } else if($type == 0 && $beritaAcara->status == 5 && $beritaAcara->penguji_submit != null) {
            return "<span class='label label-success'>{$beritaAcara->penguji}</span>";
        } else if($type == 0) {
            return "<span>{$beritaAcara->penguji}</span>";
        }
    }

    /**
     * for status label on each column
     */
    public function getStatusLabel(Acara $beritaAcara) {
        $beritaAcaraStatus = $beritaAcara->status;
        switch($beritaAcaraStatus) {
            case 0:
                return "<span class='label label-primary'>Baru</span>";
                break;
            case 1:
                return "<span class='label label-warning'>Sedang Proses</span>";
                break;
            case 2:
                return "<span class='label label-success'>Selesai(Tersubmit)</span>";
                break;
            case 5:
                return "<span class='label label-danger'>Selesai(Belum Tersubmit)</span>";
                break;
            default:
                break;
        }
    }

    /**
     * get action button
     */
    function getActionsButton(Acara $beritaAcara) {
        if($beritaAcara->status == 5) {
            $permission = $this->getRoute('update', $beritaAcara->id);
            return "<a title='BERI AKSES' href='' class='btn btn-success update-berita-acara-status-confirmation m-t-3' data-type='Tesis' data-url='{$permission}' data-target='#update-berita-acara-status-confirmation-modal' data-toggle='modal'><span class='fa fa-unlock'></span> Beri Akses</a>";
        }
        return "";
    }

        /**
     * get route function
     */
    public function getRoute($key1, $key2 = null) {
        switch($key1) {
            case 'list':
                return route('baak.berita_acara.tesis');
                break;
            case 'update':
                return route('baak.berita_acara.permission_tesis', $key2);
                break;
            case 'view':
                return route('baak.view_berita_acara.tesis');
                break;
            default: 
                break;
        }
    }

    /**
     * give permission for participant to submit form
     */
    public function givePermission(Acara $beritaAcara) {
        $user = \Auth::guard()->user();

        try {
            DB::beginTransaction();
            $beritaAcara->status = 1;
            $beritaAcara->permission_by = $user->id;
            $beritaAcara->permission_at = now();
            $beritaAcara->expired_at = Carbon::createFromFormat('Y-m-d H:i:s', $beritaAcara->expired_at)->addDays(3)->format('Y-m-d H:i:s');
            $beritaAcara->updated_at = now();
            $beritaAcara->save();
            
            DB::commit();
            $this->setFlashMessage('success', $beritaAcara->messages('success', 'permission'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            DB::rollback();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $e);
        }
    }
}