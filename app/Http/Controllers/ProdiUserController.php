<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MasterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;
use Illuminate\Support\MessageBag;
use App\Models\StudyProgram;
use App\Models\ProdiUser;
use App\Models\ProdiUserAssignment;

class ProdiUserController extends MasterController
{
    /**
     * Create a noew controller instance
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:prodis');
    }

    public function index() {
        $admin = \Auth::guard()->user();
        $studyProgram = $admin->studyprogram()->get()[0];
        $breadcrumbs = $this->getBreadCrumbs('userDosenList');
        $title = $this->getTitle('userDosenList') . ' ' . $studyProgram->name . ' - user';
        $sub_title = $this->getSubTitle('userDosenList') . ' ' . $studyProgram->name;
        $admin_dosen_css = "active";
        $user_dosen_css = "active";

        return view('prodi.user.user', compact('studyProgram', 'breadcrumbs', 'title', 'sub_title', 'admin_dosen_css', 'user_dosen_css'));
    }

    /**
     * get user dosen list
     */
    public function getUserDosenList(Request $request) {
        $admin = \Auth::guard()->user();
        $studyProgram = $admin->studyprogram()->get()[0];
        $prodiUser = ProdiUser::join('prodi_user_assignment AS pua', 'pua.prodi_user_id', '=', 'prodi_user.id')
                        ->join('study_programs AS prodi', 'pua.study_program_id', '=', 'prodi.id')                        
                        ->where('prodi.id' ,'=', $studyProgram->id)
                        ->where('prodi_user.is_admin', '=', 0)
                        ->selectRaw('prodi_user.*, prodi.name AS prodi_name')->get();
        
        return Datatables::of($prodiUser)
                            ->setRowClass(function() {
                                return "custom-tr-text-ellipsis";
                            })
                            ->addColumn('actions', function(ProdiUser $user) {
                                $admin = \Auth::guard()->user();
                                $studyProgram = $admin->studyprogram()->get()[0];
                                return $this->getActionsButtonsProdi($user, $studyProgram->id);
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    /**
     * get prodi user user action buttons
     */
    public function getActionsButtonsProdi($model, $studyProgramId) {
        $modelId = $model->id;
        $edit = $this->getRoute('edit', $modelId);
        $destroy = $this->getRoute('destroy', $modelId);
        // if this user is not created by this prodi will route to assignment prodi form
        if($model->study_programs_id != $studyProgramId) {
            $prodiAssignModel = new ProdiUserAssignment;
            $prodiAssign = $prodiAssignModel::where('prodi_user_id', $modelId)->where('study_program_id', $studyProgramId)->first();

            $edit = $this->getRoute('reassign', $prodiAssign->id);            
        }

        if($model->study_programs_id != $studyProgramId) {
            $destroy = $this->getRoute('remove', $prodiAssign->id);
        }

        if(Gate::allows('is-prodi-admin')) {
            return "<a href='{$edit}' title='UBAH' class='btn btn-warning'><span class='fa fa-pencil-square-o'></span> Ubah </a>
                    <a title='HAPUS' class='btn btn-danger delete-confirmation' data-toggle='modal' data-url='{$destroy}' data-id='{$modelId}' data-target='#delete-confirmation-modal'><span class='fa fa-trash-o'></span> Hapus </a>";
        }
    }

    /**
     * open create user form
     */
    public function create() {
        $admin = \Auth::guard()->user();
        $studyProgram = $admin->studyprogram()->get()[0];
        $studyProgramId = $studyProgram->id;
        $studyProgramName = $studyProgram->name;
        $breadcrumbs = $this->getBreadCrumbs('createDosenUser');
        $title = $this->getTitle('createDosenUser');
        $sub_title = $this->getSubTitle('createDosenUser');
        $admin_dosen_css = "active";
        $user_dosen_css = "active";
        $btn_label = "Buat";

        return view('prodi.user.create-or-edit-user', compact('studyProgramId', 'studyProgramName', 'breadcrumbs', 'title', 'sub_title', 'admin_dosen_css', 'user_dosen_css', 'btn_label'));
    }

    /**
     * store data into database
     */
    public function store(Request $request) {
        $prodiUser = new ProdiUser;
        $prodiUserAssignment = new ProdiUserAssignment;
        $data = Input::all();

        $prodiUser->username = Input::get('username');
        $prodiUser->email = Input::get('email');
        $prodiUser->password = Hash::make(Input::get('password'));
        $prodiUser->is_admin = 0;
        $prodiUser->study_programs_id = Input::get('prodi_id');
        $prodiUser->created_at = now();
        $prodiUser->initial_name = Input::get('initial_name');

        if($prodiUser->validate($prodiUser, $data, $prodiUser->messages('validation'))) {
            DB::beginTransaction();
            try {
                
                $prodiUser->save();

                // store to table assignment
                $prodiUserAssignment->prodi_user_id = $prodiUser->id;
                $prodiUserAssignment->study_program_id = $prodiUser->study_programs_id;
                $prodiUserAssignment->created_at = now();
                $prodiUserAssignment->save();

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

    /**
     * assign
     * is user that already create by another prodi is called VIP
     * then this module is for assign this prodi to the user that are from another prodi created
     */
    public function assign() {        
        $prodiAdmin = \Auth::guard('prodis')->user();
        $studyProgram = $prodiAdmin->studyprogram()->first();
        $prodiUserModel = new ProdiUser;
        $prodiUserCreatedFromAnotherProdi = $prodiUserModel->leftJoin('prodi_user_assignment AS pa', function($join) {
                                                $join->on('pa.prodi_user_id', '=', 'prodi_user.id');
                                            })                                           
                                            ->where('prodi_user.study_programs_id', '!=', $studyProgram->id)
                                            ->where('pa.study_program_id', '!=', $studyProgram->id)
                                            ->where('is_admin', 0)->selectRaw('prodi_user.*, pa.id AS assignment_id')->get();
                                            
        $studyProgramName = $studyProgram->name;
        $breadcrumbs = $this->getBreadCrumbs('assignDosenUser');
        $title = $this->getTitle('assignDosenUser');
        $sub_title = $this->getSubTitle('assignDosenUser');
        
        $anotherProdiUserArr = [];
        $prodiAssignmentModel = new ProdiUserAssignment;
        foreach($prodiUserCreatedFromAnotherProdi as $val) {
            // validate by dosen prodi id on the table assignment is their assigned or not if yes dont need to put into list of array
            $prodi_user_id = $val->id;
            $prodiUserAssignment = $prodiAssignmentModel::where('prodi_user_id', $prodi_user_id)->where('study_program_id', $studyProgram->id)->get();
            if($prodiUserAssignment->count() == 0) {
                $anotherProdiUserArr[$prodi_user_id] = $val->username;
            }
        }

        return view('prodi.user.assign-user', compact('breadcrumbs', 'title', 'sub_title', 'studyProgram', 'prodiUserCreatedFromAnotherProdi', 'anotherProdiUserArr'));
    }

    /**
     * store assigning data to table
     */
    public function assigning(Request $request) {
        // $data = Input::all();
        DB::beginTransaction();
        $prodiUserAssignment = new ProdiUserAssignment;
        try {
            $prodiUserAssignment->prodi_user_id = $request->dosen_user_id;
            $prodiUserAssignment->study_program_id = $request->study_program_id;
            $prodiUserAssignment->created_at = now();
            $prodiUserAssignment->save();

            DB::commit();
            
            // redirect and set session message
            $this->setFlashMessage('success', $prodiUserAssignment->messages('success', 'assign'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            DB::rollback();
            $this->redirectToRouteWithErrorsAndInputs($this->getRoute('assign'), $e);
        }
    }

    /**
     * edit form assign prodi dosen
     */
    public function editAssign(ProdiUserAssignment $prodiAssign) {
        $prodiAdmin = \Auth::guard('prodis')->user();
        $studyProgram = $prodiAdmin->studyprogram()->first();
        $prodiUserModel = new ProdiUser;
        $prodiUserCreatedFromAnotherProdi = $prodiUserModel->leftJoin('prodi_user_assignment AS pa', function($join) {
                                                $join->on('pa.prodi_user_id', '=', 'prodi_user.id');
                                            })
                                            ->where('prodi_user.study_programs_id', '!=', $studyProgram->id)
                                            ->where('pa.study_program_id', '!=', $studyProgram->id)
                                            ->where('is_admin', 0)->selectRaw('prodi_user.*, pa.id AS assignment_id')->get();
                                            
        $studyProgramName = $studyProgram->name;
        $breadcrumbs = $this->getBreadCrumbs('assignDosenUser');
        $title = $this->getTitle('assignDosenUser');
        $sub_title = $this->getSubTitle('assignDosenUser');
        
        $anotherProdiUserArr = [];
        $prodiAssignmentModel = new ProdiUserAssignment;
        foreach($prodiUserCreatedFromAnotherProdi as $val) {
            $asId = $val->id;
            if($asId == $prodiAssign->prodi_user_id) {
                $anotherProdiUserArr[$asId] = $val->username;
            } else {
                $prodiUserAssignment = $prodiAssignmentModel::where('prodi_user_id', $asId)->where('study_program_id', $studyProgram->id)->get();
                if($prodiUserAssignment->count() == 0) {
                    $anotherProdiUserArr[$asId] = $val->username;
                }
            }
        }
        $admin_dosen_css = "active";
        $user_dosen_css = "active";

        return view('prodi.user.assign-user', compact('breadcrumbs', 'title', 'sub_title', 'anotherProdiUserArr', 'studyProgram', 'prodiAssign', 'admin_dosen_css', 'user_dosen_css'));
    }

    /**
     * update assign prodi dosen
     */
    public function updateAssign(ProdiUserAssignment $prodiAssign, Request $request) {
        DB::beginTransaction();
        try {
            $prodiAssign->prodi_user_id = $request->dosen_user_id;
            $prodiAssign->study_program_id = $request->study_program_id;
            $prodiAssign->updated_at = now();
            $prodiAssign->save();
            DB::commit();

            $this->setFlashMessage('success', $prodiAssign->messages('success', 'reassign'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            DB::rollback();
            $this->redirectToRouteWithErrorsAndInputs($this->getRoute('reassign', $prodiAssign->id), $e);
        }
    }

    /**
     * destroy prodi dosen assignable
     */
    public function destroyAssign(ProdiUserAssignment $prodiAssign) {
        try {
            DB::beginTransaction();
            $prodiAssign->delete();
            DB::commit();
            $this->setFlashMessage('success', $prodiAssign->messages('success', 'delete'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            DB::rollback();
            $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $e);
        } 
    }

    /**
     * edit prodi user
     */
    public function edit(ProdiUser $prodiUser) {
        $breadcrumbs = $this->getBreadCrumbs('editDosenUser');
        $admin = \Auth::guard()->user();
        $studyProgram = $admin->studyprogram()->get()[0];
        $studyProgramId = $studyProgram->id;
        $studyProgramName = $studyProgram->name;
        $title = $this->getTitle('editDosenUser');
        $sub_title = $this->getSubTitle('editDosenUser');
        $admin_dosen_css = "active";
        $user_dosen_css = "active";
        $btn_label = "Ubah";
        
        return view('prodi.user.create-or-edit-user', compact('prodiUser', 'studyProgramId', 'studyProgramName', 'breadcrumbs', 'title', 'sub_title', 'admin_dosen_css', 'user_dosen_css', 'btn_label'));
    }

    /**
     * update prodi user
     */
    public function update(ProdiUser $prodiUser, Request $request) {
        $data = Input::all();
        if(!Input::get('password')) {
            $data['password'] = str_random(6);   
        }
        if(!Input::get('password_confirmation')) {
            $data['password_confirmation'] = $data['password'];
        }
        
        if($prodiUser->validate($prodiUser, $data, $prodiUser->messages('validation'))) {
            DB::beginTransaction();
            try {
                // update
                $prodiUser->username = Input::get('username');
                $prodiUser->email = Input::get('email');
                if(Input::get('password')) {
                    $prodiUser->password = Hash::make(Input::get('password'));
                }
                $prodiUser->updated_at = now();
                $prodiUser->save();

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $prodiUser->messages('success', 'update'));
                return redirect($this->getRoute('list'));
            } catch(\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $prodiUser->id), $e);
            }
        } else {
            $errors = $prodiUser->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('edit', $prodiUser->id), $errors);
        }
    }

    /**
     * delete user prodi
     */
    public function destroy(ProdiUser $prodiUser) {
        try {
            DB::beginTransaction();
            $prodiAssignModel = new ProdiUserAssignment;
            $prodiAssign = $prodiAssignModel::where('prodi_user_id', $prodiUser->id)->first();
            $prodiAssign->delete(); 
            // need to check user is assigned to another table or not
            $prodiUser->delete();

            DB::commit();
            
            // redirect
            $this->setFlashMessage('success', $prodiUser->messages('success', 'delete'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            DB::rollback();
            $errors = new MessageBag([$prodiUser->messages('failDelete')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
    }

    /**
     * route after success or failed
     */
    public function getRoute($key, $id = null) {
        switch($key) {
            case 'list':
                return route('dosen');
            case 'assign':
                return route('dosen.assign');
            case 'create':
                return route('dosen.create');
            case 'edit':
                return route('dosen.update', $id);
            case 'reassign':
                return route('dosen.reassign', $id);
            case 'destroy':
                return route('dosen.destroy', $id);
            case 'remove':
                return route('dosen.remove', $id);
            default:
                break;
        }
    }
}
