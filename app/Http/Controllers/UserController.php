<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MasterController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;
use Illuminate\Support\MessageBag;

class UserController extends MasterController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the users list view.
     *
     * @param empty
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = $this->getBreadCrumbs('userList');
        $title = $this->getTitle('userList');
        $sub_title = $this->getSubTitle('userList');
        $admin_css = "active";
        $user_css = "active";
        $roles = Role::all()->pluck('name', 'id');

        return view('admin.user.user')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('admin_css', $admin_css)
                ->with('user_css', $user_css)
                ->with('roles', $roles);
    }

    /**
     * Get create new user view.
     *
     * @param empty
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = $this->getBreadCrumbs('createUser');
        $title = $this->getTitle('createUser');
        $sub_title = $this->getSubTitle('createUser');
        $admin_css = "active";
        $user_css = "active";
        $btn_label = "Buat";
        $roles = Role::all()->pluck('name', 'id');

        return view('admin.user.create-or-edit-user')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('admin_css', $admin_css)
                ->with('user_css', $user_css)
                ->with('btn_label', $btn_label)
                ->with('roles', $roles);
    }

    /**
     * Save new supplier.
     *
     * @param empty
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function store()
    {
        $user = new User;
        $data = Input::all();

        if ($user->validate($user, $data, $user->messages('validation'))) {
            DB::beginTransaction();
            try {
                // store
                $user->username = Input::get('username');
                $user->email = Input::get('email');
                $user->password = Hash::make(Input::get('password'));
                $user->save();

                $role = Role::findOrFail(Input::get('role'));
                $user->roles()->sync([$role->id]);

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $user->messages('success', 'create'));
                return redirect($this->getRoute('list'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('create'), $e);
            }
        } else {
            $errors = $user->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('create'), $errors);
        }
    }

    /**
     * Get User List From Ajax.
     *
     * @param Request ajax request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserList(Request $request)
    {
        $users = User::join('user_roles AS userRole', 'users.id', '=', 'userRole.user_id')
                         ->join('roles', 'userRole.role_id', '=', 'roles.id')
                         ->select([
                            'users.*',
                            'roles.id AS role_id',
                            'roles.name AS role_name',
                         ]);

        return Datatables::of($users)
                        ->setRowClass(function () {
                            return "custom-tr-text-ellipsis";
                        })
                        ->filterColumn('role_name', function ($query, $keyword) {
                            $query->whereRaw("CONCAT(role_id,'-',role_id) like ?", ["%{$keyword}%"]);
                        })
                        ->addColumn('actions', function (User $user)  {
                            return $this->getActionsButtons($user);
                        })
                        ->rawColumns(['actions'])
                        ->make(true);
    }

    /**
     * Edit user.
     *
     * @param model binding user
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function edit(User $user)
    {
        $breadcrumbs = $this->getBreadCrumbs('editUser');
        $title = $this->getTitle('editUser');
        $sub_title = $this->getSubTitle('editUser');
        $admin_css = "active";
        $user_css = "active";
        $btn_label = "Ubah";
        $roles = Role::all()->pluck('name', 'id');
        $selected_role = $user->roles()->first();

        return view('admin.user.create-or-edit-user')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('admin_css', $admin_css)
                ->with('user_css', $user_css)
                ->with('user', $user)
                ->with('btn_label', $btn_label)
                ->with('roles', $roles)
                ->with('selected_role', $selected_role);
    }

    /**
     * Update user.
     *
     * @param Request request
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function update(User $user, Request $request)
    {
        $data = Input::all();
        if (!Input::get('password')) {
            $data['password'] = str_random(6);
        }
        if (!Input::get('password_confirmation')) {
            $data['password_confirmation'] = $data['password'];
        }

        if ($user->validate($user, $data, $user->messages('validation'))) {
            DB::beginTransaction();
            try {
                // store
                $user->username = Input::get('username');
                $user->email = Input::get('email');
                if (Input::get('password')) {
	                $user->password = Hash::make(Input::get('password'));
                }
                $user->save();

                if ($user->id != \Auth::user()->id) {
                    $role = Role::findOrFail(Input::get('role'));
                    $user->roles()->sync([$role->id]);
                }


                DB::commit();
                // redirect
                $this->setFlashMessage('success', $user->messages('success', 'update'));
                return redirect($this->getRoute('list'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $user->id), $e);
            }
        } else {
            $errors = $user->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('edit', $user->id), $errors);
        }
    }

    /**
     * Destroy user.
     *
     * @param model binding user
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function destroy(User $user)
    {
        try
        {
        	if ($user->id == \Auth::user()->id) {
                throw new \Exception($user->messages('failDeleteSelf'));
        	}

            $user->roles()->sync([]);
            $user->delete();

            // redirect
            $this->setFlashMessage('success', $user->messages('success', 'delete'));
            return redirect($this->getRoute('list'));

        } catch (\Exception $e) {
            $errors = new MessageBag([$user->messages('failDelete')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
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
        		return route('users');
            case 'create':
                return route('users.create');
            case 'edit':
                return route('users.edit', $id);
            case 'destroy':
                return route('users.destroy', $id);
            
            default:
                # code...
                break;
        }
    }


    public function getActionsButtons($model, array $extraClassToAdd = [])
    {
        $modelId = $model->id;
        $edit =  $this->getRoute('edit', $modelId);
        $destroy =  $this->getRoute('destroy', $modelId);
        $deleteClass = '';

        if (count($extraClassToAdd) > 0) {
            foreach ($extraClassToAdd as $key => $value) {
                if ($extraClassToAdd[$key] = 'delete') {
                    $deleteClass = $value;
                }
            }
        }

        if (Gate::allows('is-superadmin')) {
            if ($modelId == \Auth::user()->id) {
                return "<a href='{$edit}' title='EDIT' class='btn btn-warning'><span class='fa fa-pencil-square-o'></span> Edit </a>
                       <a title='HAPUS' class='btn btn-danger delete-confirmation {$deleteClass} disabled' data-toggle='modal'><span class='fa fa-trash-o'></span> Hapus </a>";
            }

            return "<a href='{$edit}' title='EDIT' class='btn btn-warning'><span class='fa fa-pencil-square-o'></span> Edit </a>
                   <a title='HAPUS' class='btn btn-danger delete-confirmation {$deleteClass}' data-toggle='modal' data-url='{$destroy}' data-id='{$modelId}' data-target='#delete-confirmation-modal'><span class='fa fa-trash-o'></span> Hapus </a>";
        }
        return "<a href='javascript:;' title='EDIT' class='btn btn-warning disabled'><span class='fa fa-pencil-square-o'></span> Edit </a>
                   <a title='HAPUS' class='btn btn-danger delete-confirmation disabled {$deleteClass}'><span class='fa fa-trash-o'></span> Hapus </a>";

    }
}
