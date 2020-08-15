<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MasterController;
use App\Models\RuanganSidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;
use Illuminate\Support\MessageBag;
use App\Models\PenjadwalanSidang as Jadwal;

class RuanganSidangController extends MasterController
{
    public function __costruct() {
        $this->middleware('auth');
    }

    /**
     * show list of ruangan sidang
     */
    public function index() {
        $breadcrumbs = $this->getBreadCrumbs('ruanganSidangList');
        $title = $this->getTitle('ruanganSidangList');
        $sub_title = $this->getSubTitle('ruanganSidangList');
        $master_css = 'active';
        $ruangan_sidang_css = 'active';
        return view('admin.ruangan_sidang.ruangan_sidang', compact('breadcrumbs', 'title', 'sub_title', 'master_css', 'ruangan_sidang_css'));
    }

    /**
     * retrieve data datatables
     */
    public function getRuanganSidangList(Request $request) {
        $requests = RuanganSidang::join('users', 'ruangan_sidang.created_by', '=', 'users.id')
            ->select([
                'ruangan_sidang.*',
                'users.username AS created_by_user',
            ]);

        return Datatables::of($requests)
                ->setRowClass(function() {
                    return "custom-tr-text-ellipsis";
                })
                ->filterColumn('created_by_user', function($query, $keyWord) {
                    $query->whereRaw("CONCAT(users.username, '-', users.username) like ?", ["%{$keyWord}%"]);
                })
                ->addColumn('actions', function(RuanganSidang $rs) {
                    return $this->getActionsButtons($rs);
                })
                ->rawColumns(['actions'])
                ->make(true);
    }

    /**
     * go to view create function
     */
    public function create() {
        $breadcrumbs = $this->getBreadCrumbs('createRuanganSidang');
        $title = $this->getTitle('createRuanganSidang');
        $sub_title = $this->getSubTitle('createRuanganSidang');
        $master_css = 'active';
        $ruangan_sidang_css = 'active';
        $btn_label = 'Buat';

        return view('admin.ruangan_sidang.create-or-edit-ruangan_sidang', 
            compact('breadcrumbs', 'title', 'sub_title', 'master_css', 'ruangan_sidang_css', 'btn_label'));
    }

    /**
     * store new data method
     */
    public function store(Request $request) {
        $user = \Auth::guard()->user();
        $ruangan_sidang = new RuanganSidang;
        $data = Input::all();

        $ruangan_sidang->gedung = $data['gedung'];
        $ruangan_sidang->ruangan = $data['ruangan'];
        $ruangan_sidang->created_by = $user->id;
        $ruangan_sidang->created_at = now();
        if($ruangan_sidang->validate($ruangan_sidang, $data, $ruangan_sidang->messages('validation'))) {
            DB::beginTransaction();
            try {
                // store                 
                $ruangan_sidang->save();

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $ruangan_sidang->messages('success', 'create'));
                return redirect($this->getRoute('list'));
            } catch(\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('create'), $e);
            }
        } else {
            $errors = $ruangan_sidang->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('create'), $errors);
        }
    }

    public function edit(RuanganSidang $ruangan) {
        $breadcrumbs = $this->getBreadCrumbs('editRuanganSidang');
        $title = $this->getTitle('editRuanganSidang');
        $sub_title = $this->getSubTitle('editRuanganSidang');
        $btn_label = 'Ubah';

        return view('admin.ruangan_sidang.create-or-edit-ruangan_sidang', compact('breadcrumbs', 'title', 'sub_title', 'btn_label', 'ruangan'));
    }

    /**
     * update existing data
     */
    public function update(RuanganSidang $ruangan, Request $request) 
    {
        $data = Input::all();

        // set attribute here
        $ruangan->gedung = $data['gedung'];
        $ruangan->ruangan = $data['ruangan'];
        $ruangan->updated_at = now();

        if($ruangan->validate($ruangan, $data, $ruangan->messages('validation'))) {
            DB::beginTransaction();
            try {
                $ruangan->save();
                DB::commit();

                $this->setFlashMessage('success', $ruangan->messages('success', 'update'));
                return redirect($this->getRoute('list'));
            } catch(\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $ruangan->id), $e);
            }
        } else {
            $errors = $ruangan->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('edit', $ruangan->id), $errors);
        }
    }

    /**
     * delete data from db
     */
    public function destroy(RuanganSidang $ruangan) {
        try {
            // check penjadwalan sidang is there using this room
            $jadwalCount = Jadwal::where('ruangan_sidang_id', $ruangan->id)->count();
            if($jadwalCount == 0) {
                $errors = new MessageBag;
                $errors->add('Data ruangan ini telah dipakai');
                return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
            }

            $ruangan->delete();

            // redirect 
            $this->setFlashMessage('success', $ruangan->messages('success', 'delete'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            $errors = new MessageBag([$ruangan->messages('failDelete')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
    }

    /**
     * route
     */
    public function getRoute($key, $id = null) {
        switch($key) {
            case 'list':
                return route('ruangan');
            case 'create':
                return route('ruangan.create');
            case 'edit':
                return route('ruangan.edit', $id);
            case 'destroy':
                return route('ruangan.destroy', $id);
            default:
                break;
        }
    }
}
