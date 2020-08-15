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
use App\Models\FinanceUser;

class FinanceUserController extends MasterController {

    public function __construct() {
        $this->middleware('auth:finance');
    }

    public function index() {
        $admin = \Auth::guard()->user();
        $breadcrumbs = $this->getBreadCrumbs('financeList');
        $title = $this->getTitle('financeList');
        $sub_title = $this->getSubTitle('financeList');
        $admin_css = "active";
        $user_css = "active";

        return view('finance.user.user', compact('breadcrumbs', 'title', 'sub_title', 'admin_css', 'user_css'));
    }

    public function getUserFinance(Request $request) {
        $users = FinanceUser::where('is_admin', 0)->get();
        
        return Datatables::of($users)
            ->setRowClass(function() {
                return "custom-tr-text-ellipsis";
            })
            ->addColumn('actions', function(FinanceUser $user) {
                return $this->getActionsButtonsFinanceAdmin($user);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * get actions button
     */
    public function getActionsButtonsFinanceAdmin($model)
    {
        $modelId = $model->id;
        $edit = $this->getRoute('edit', $modelId);
        $destroy = $this->getRoute('destroy', $modelId);

        return "<a href='{$edit}' title='EDIT' class='btn btn-warning m-t-3'><span class='fa fa-pencil-square-o'></span> Edit </a>
        <a title='DELETE' class='btn btn-danger delete-confirmation' data-toggle='modal' data-url='{$destroy}' data-id='{$modelId}' data-target='#delete-confirmation-modal'><span class='fa fa-trash-o'></span> Hapus </a>";
    }

    /**
     * get route method
     */
    public function getRoute($key, $id = null) 
    {
        switch($key) {
            case 'list':
                return route('finance_user');
            case 'create':
                return route('finance_user.create');
            case 'edit':
                return route('finance_user.edit', $id);
            case 'destroy':
                return route('finance_user.destroy', $id);

            default:
                break;
        }
    }

    public function create() {
        $breadcrumbs = $this->getBreadCrumbs('createFinanceUser');
        $title = $this->getTitle('createFinanceUser');
        $sub_title = $this->getSubTitle('createFinanceUser');
        $admin_css = "active";
        $user_css = "active";
        $btn_label = "Buat";
        return view('finance.user.create-or-edit-user', compact('breadcrumbs', 'title', 'sub_title', 'admin_css', 'user_css', 'btn_label'));
    }

    public function store() {
        $user = new FinanceUser;
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

    public function edit(FinanceUser $financeUser) {
        $breadcrumbs = $this->getBreadCrumbs('editFinanceUser');
        $title = $this->getTitle('editFinanceUser');
        $sub_title = $this->getSubTitle('editFinanceUser');
        $admin_css = "active";
        $user_css = "active";
        $btn_label = "Ubah";

        return view('finance.user.create-or-edit-user', compact('breadcrumbs', 'title', 'sub_title', 'admin_css', 'user_css', 'btn_label', 'financeUser'));
    }

    public function update(FinanceUser $financeUser, Request $request) {
        $data = Input::all();
        if(!Input::get('password')) {
            $data['password'] = str_random(6);
        }
        if(!Input::get('password_confirmation')) {
            $data['password_confirmation'] = $data['password'];
        }

        if($financeUser->validate($financeUser, $data, $financeUser->messages('validation'))) {
            DB::beginTransaction();
            try {
                $financeUser->username = Input::get('username');
                $financeUser->email = Input::get('email');
                if(Input::get('password')) {
                    $financeUser->password = Hash::make(Input::get('password'));
                }
                $financeUser->updated_at = now();
                $financeUser->save();

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $financeUser->messages('success', 'update'));
                return redirect($this->getRoute('list'));
            } catch(\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $financeUser->id), $e);
            }
        } else {
            $errors = $financeUser->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('edit', $financeUser->id), $errors);
        }
    }

    public function destroy(FinanceUser $financeUser) {
        try {
            $financeUser->delete();
            // redirect
            $this->setFlashMessage('success', $financeUser->messages('success', 'delete'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            $errors = new MessageBag([$financeUser->messages('failDelete')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
    }
}