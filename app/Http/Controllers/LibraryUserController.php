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
use App\Models\LibraryUser;

class LibraryUserController extends MasterController
{
    public function __construct() {
        $this->middleware('auth:lib');
    }

    public function index() {
        $admin = \Auth::guard()->user();
        $breadcrumbs = $this->getBreadCrumbs('libraryList');
        $title = $this->getTitle('libraryList');
        $sub_title = $this->getSubTitle('libraryList');
        $admin_css = "active";
        $user_css = "active";

        return view('library.user.user', compact('breadcrumbs', 'title', 'sub_title', 'admin_css', 'user_css'));
    }

    /**
     * get list of user library
     */
    public function getUserLibrary(Request $request) {
        $users = LibraryUser::where('is_admin', 0)->get();
        
        return Datatables::of($users)
            ->setRowClass(function() {
                return "custom-tr-text-ellipsis";
            })
            ->addColumn('actions', function(LibraryUser $user) {
                return $this->getActionsButtonsLibraryAdmin($user);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * get actions button
     */
    public function getActionsButtonsLibraryAdmin($model)
    {
        $modelId = $model->id;
        $edit = $this->getRoute('edit', $modelId);
        $destroy = $this->getRoute('destroy', $modelId);

        return "<a href='{$edit}' title='EDIT' class='btn btn-warning m-t-3'><span class='fa fa-pencil-square-o'></span> Edit </a>
        <a title='DELETE' class='btn btn-danger delete-confirmation' data-toggle='modal' data-url='{$destroy}' data-id='{$modelId}' data-target='#delete-confirmation-modal'><span class='fa fa-trash-o'></span> Hapus </a>";
    }

    public function create() {
        $breadcrumbs = $this->getBreadCrumbs('createLibraryUser');
        $title = $this->getTitle('createLibraryUser');
        $sub_title = $this->getSubTitle('createLibraryUser');
        $admin_css = "active";
        $user_css = "active";
        $btn_label = "Buat";
        return view('library.user.create-or-edit-user', compact('breadcrumbs', 'title', 'sub_title', 'admin_css', 'user_css', 'btn_label'));
    }

    public function store() {
        $user = new LibraryUser;
        $data = Input::all();

        if($user->validate($user, $data, $user->messages('validation'))) {
            DB::beginTransaction();
            try {
                $user->username = Input::get('username');
                $user->email = Input::get('email');
                $user->password = Hash::make(Input::get('password'));
                $user->is_admin = 0;
                $user->created_at = now();
                $user->save();

                DB::commit();

                // redirect
                $this->setFlashMessage('success', $user->messages('success', 'create'));
                return redirect($this->getRoute('list'));
            } catch(\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('create'), $e);
            }
        } else {
            $errors = $user->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('create'), $errors);
        }
    }

    public function edit(LibraryUser $libraryUser) {
        $breadcrumbs = $this->getBreadCrumbs('editLibraryUser');
        $title = $this->getTitle('editLibraryUser');
        $sub_title = $this->getSubTitle('editLibraryUser');
        $admin_css = "active";
        $user_css = "active";
        $btn_label = "Ubah";

        return view('library.user.create-or-edit-user', compact('breadcrumbs', 'title', 'sub_title', 'admin_css', 'user_css', 'btn_label', 'libraryUser'));
    }

    public function update(LibraryUser $libraryUser, Request $request) {
        $data = Input::all();
        if(!Input::get('password')) {
            $data['password'] = str_random(6);
        }
        if(!Input::get('password_confirmation')) {
            $data['password_confirmation'] = $data['password'];
        }

        if($libraryUser->validate($libraryUser, $data, $libraryUser->messages('validation'))) {
            DB::beginTransaction();
            try {
                $libraryUser->username = Input::get('username');
                $libraryUser->email = Input::get('email');
                if(Input::get('password')) {
                    $libraryUser->password = Hash::make(Input::get('password'));
                }
                $libraryUser->updated_at = now();
                $libraryUser->save();

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $libraryUser->messages('success', 'update'));
                return redirect($this->getRoute('list'));
            } catch(\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $libraryUser->id), $e);
            }
        } else {
            $errors = $libraryUser->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('edit', $libraryUser->id), $errors);
        }
    }

    public function destroy(LibraryUser $libraryUser) {
        try {
            $libraryUser->delete();
            // redirect
            $this->setFlashMessage('success', $libraryUser->messages('success', 'delete'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            $errors = new MessageBag([$libraryUser->messages('failDelete')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
    }

    /**
     * get route method
     */
    public function getRoute($key, $id = null) 
    {
        switch($key) {
            case 'list':
                return route('library_staff');
            case 'create':
                return route('library_staff.create');
            case 'edit':
                return route('library_staff.edit', $id);
            case 'destroy':
                return route('library_staff.destroy', $id);

            default:
                break;
        }
    }
}