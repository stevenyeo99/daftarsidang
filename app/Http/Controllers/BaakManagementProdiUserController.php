<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Log;
use App\Http\Controllers\MasterController;
use App\Models\StudyProgram;
use App\Models\StudyProgramUsers as ProdiUser;
use App\Models\StudyProgramUserRoles as ProdiRole;

class BaakManagementProdiUserController extends MasterController {

    public function __construct() {
        $this->middleware('auth:web');
    }

    public function index() {
        $breadcrumbs = $this->getBreadCrumbs('userDosenList');
        $title = $this->getTitle('userDosenList');
        $sub_title = $this->getSubTitle('userDosenList');
        $admin_dosen_css = "active";
        $user_dosen_css = "active";
        $listOfStudyProgramsDropDowns = [];
        $listOfStudyProgramObjects = StudyProgram::all();
        foreach($listOfStudyProgramObjects as $studyProgram) {
            $listOfStudyProgramsDropDowns[$studyProgram->id] = $studyProgram->name;
        }
        return view('admin.prodi_user.prodi_user', compact('breadcrumbs', 'title', 'sub_title', 'admin_dosen_css', 'user_dosen_css', 'listOfStudyProgramsDropDowns'));
    }

    /**
     * get study program users
     */
    public function getListOfStudyProgramUsers() {
        $studyProgramUsers = ProdiUser::join('study_program_user_roles AS roles', 'study_program_users.id', '=', 'roles.study_program_user_id')
                                        ->join('study_programs AS prodi', 'roles.study_program_id', '=', 'prodi.id')
                                        ->select(["study_program_users.id AS id", 
                                        "study_program_users.nip AS nip", 
                                        "study_program_users.username AS username",
                                        // "study_program_users.first_name AS nama", 
                                        DB::raw('CONCAT(COALESCE(study_program_users.first_name, ""), " ", COALESCE(study_program_users.middle_name, ""), " ", COALESCE(study_program_users.last_name, "")) AS nama'),
                                        'study_program_users.email AS email',
                                        DB::raw("GROUP_CONCAT(DISTINCT prodi.name ORDER BY prodi.name ASC SEPARATOR ', ') AS study_program"),
                                        'study_program_users.is_admin AS type',
                                        ])
                                        // ->where('study_program_users.is_admin', 0)
                                        ->groupBy('id', 'nip', 'username', 'first_name', 'middle_name', 'last_name', 'email', 'is_admin')
                                        ->orderBy('nip', 'desc');

        return Datatables::of($studyProgramUsers)
                            ->setRowClass(function() {
                                return "custom-tr-text-ellipsis";
                            })
                            ->filterColumn('nip', function($query, $keyword) {
                                $query->whereRaw("CONCAT(study_program_users.nip, '-', study_program_users.nip) LIKE ?", ["%{$keyword}%"]);
                            })
                            ->filterColumn('username', function($query, $keyWord) {
                                $query->whereRaw("CONCAT(study_program_users.username, '-', study_program_users.username) LIKE ?", ["%{$keyWord}%"]);
                            })
                            ->filterColumn('nama', function($query, $keyWord) {
                                $query->whereRaw("CONCAT(study_progam_users.first_name, '-', study_program_users.first_name) LIKE ?", ["%{$keyWord}%"])
                                    ->orWhereRaw("CONCAT(study_program_users.middle_name, '-', study_program_users.middle_name) LIKE ?", ["%{$keyWord}%"])
                                    ->orWhereRaw("CONCAT(study_program_users.last_name, '-' study_program_users.last_name) LIKE ?", ["%{$keyWord}%"]);
                            })
                            ->filterColumn('email', function($query, $keyWord) {
                                $query->whereRaw("CONCAT(study_program_users.email, '-', study_program_users.email) LIKE ?", ["%{$keyWord}%"]);
                            })
                            ->filterColumn('study_program', function($query, $keyWord) {
                                $query->where('prodi.id', $keyWord);
                            })
                            ->filterColumn('type', function($query, $keyWord) {
                                $query->where('is_admin', $keyWord);
                            })
                            ->editColumn('type', function(ProdiUser $studyProgramUsers) { 
                                return $this->getUserTypeLabel($studyProgramUsers);
                            })
                            ->addColumn('actions', function(ProdiUser $studyProgramUsers) {
                                return $this->getActionButtons($studyProgramUsers);
                            })
                            ->rawColumns(['actions', 'type'])
                            ->make(true);        
        
    }

    /**
     * get action buttons
     */
    public function getActionButtons($model) {
        $modelId = $model->id;
        $edit = $this->getRoute('edit', $modelId);
        $destroy = $this->getRoute('destroy', $modelId);

        return "<a href='{$edit}' title='EDIT' class='btn btn-warning m-t-3'><span class='fa fa-pencil-square-o'></span> Edit</a>
        <a title='HAPUS' class='btn btn-danger delete-confirmation m-t-3' data-toggle='modal' data-url='{$destroy}' data-id='{$modelId}' data-target='#delete-confirmation-modal'><span class='fa fa-trash-o'></span> Hapus </a>";
    }

    public function getUserTypeLabel($model) {
        $type = $model->type;
        if($type == 1) {
            return "<span class='label label-success'>Admin</span>";
        } else {
            return "<span class='label label-primary'>Dosen</span>";
        }
    }

    /**
     * create form user
     */
    public function create() {
        $breadcrumbs = $this->getBreadCrumbs('createDosenUser');
        $title = $this->getTitle('createDosenUser');
        $sub_title = $this->getSubTitle('createDosenUser');
        $admin_dosen_css = "active";
        $user_dosen_css = "active";
        $listOfStudyProgramObjects = StudyProgram::all();
        foreach($listOfStudyProgramObjects as $studyProgram) {
            $listOfStudyProgramsDropDowns[$studyProgram->id] = $studyProgram->name;
        }
        $btn_label = 'Buat';
        $is_admin = 0;
        return view('admin.prodi_user.create-or-edit-prodi_user', compact('breadcrumbs', 'title', 'sub_title', 'admin_dosen_css', 'user_dosen_css', 'listOfStudyProgramsDropDowns', 
                    'btn_label', 'is_admin'));
    }

    /**
     * create form admin
     */
    public function create_admin() {
        $breadcrumbs = $this->getBreadCrumbs('createDosenAdmin');
        $title = $this->getTitle('createDosenAdmin');
        $sub_title = $this->getSubTitle('createDosenAdmin');
        $admin_dosen_css = "active";
        $user_dosen_css = "active";
        $listOfStudyProgramObjects = StudyProgram::all();
        foreach($listOfStudyProgramObjects as $studyProgram) {
            $listOfStudyProgramsDropDowns[$studyProgram->id] = $studyProgram->name;
        }
        $btn_label = 'Buat';
        $is_admin = 1;
        return view('admin.prodi_user.create-or-edit-prodi_user', compact('breadcrumbs', 'title', 'sub_title', 'admin_dosen_css', 'user_dosen_css', 'listOfStudyProgramsDropDowns', 
                    'btn_label', 'is_admin'));
    }

    /**
     * store data
     */
    public function store(Request $request) {
        // admin authenticate user object
        $adminUser = \Auth::guard('web')->user();
        // admin user id
        $adminUserId = $adminUser->id;
        $prodiUser = new ProdiUser;
        $data = Input::all();

        $prodiUser->nip = Input::get('nip');
        $prodiUser->username = Input::get('username');
        $prodiUser->first_name = Input::get('first_name');
        $prodiUser->middle_name = Input::get('middle_name');
        $prodiUser->last_name = Input::get('last_name');
        $prodiUser->email = Input::get('email');
        $prodiUser->is_admin = 0;
        $prodiUser->gender = Input::get('gender');
        $prodiUser->password = Hash::make(Input::get('password'));
        $prodiUser->created_by = $adminUserId;
        $prodiUser->created_at = now();

        if($prodiUser->validate($prodiUser, $data, $prodiUser->messages('validation'))) {
            DB::beginTransaction();
            
            try {

                $prodiUser->save();
                /**
                 * study program user roles
                 */
                $listOfProdiGotSelected = Input::get('listOfProdis');
                $arrOfProdiUserRoles = [];
                if($listOfProdiGotSelected != null && $listOfProdiGotSelected != '') {
                    $arrOfProdiUserRoles = explode(",", $listOfProdiGotSelected);
                }
                
                $totalProdiUserNeedInsert = count($arrOfProdiUserRoles);
                for($i = 0; $i < $totalProdiUserNeedInsert; $i++) {
                    $studyProgramId = $arrOfProdiUserRoles[$i];
                    $prodiRole = new ProdiRole;
                    $prodiRole->study_program_user_id = $prodiUser->id;
                    $prodiRole->study_program_id = $studyProgramId;
                    $prodiRole->created_by = $adminUserId;
                    $prodiRole->created_at = now();
                    $prodiRole->save();
                }

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $prodiUser->messages('success', 'create'));
                return redirect($this->getRoute('list'));
            } catch(\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('create'), $e);
            }
        } else {
            $errors = $prodiUser->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('create'), $errors);
        }
    }

    // public function create_admin() {

    // }

    /**
     * Edit form
     */
    public function edit(ProdiUser $studyProgramUsers) {

    }

    /**
     * update data
     */
    public function update(ProdiUser $studyProgramUsers, Request $request) {

    }

    /**
     * delete specific data
     */
    public function destroy(ProdiUser $studyProgramUsers) {

    }

    /**
     * Get all routes.
     *
     * @param int $id
     *
     * @return string
     */
    public function getRoute($key, $id = null) {
        switch($key) {
            case 'list':
                return route('baak.prodi_users');
            case 'create':
                return route('baak_prodi.create');
            case 'edit':
                return route('baak_prodi.edit', $id);
            case 'destroy':
                return route('baak_prodi.destroy', $id);
            
            default:
                break;
        }
    }
}