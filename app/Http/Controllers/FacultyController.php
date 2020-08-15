<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MasterController;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;
use Illuminate\Support\MessageBag;

class FacultyController extends MasterController
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
     * Show the faculty list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = $this->getBreadCrumbs('facultyList');
        $title = $this->getTitle('facultyList');
        $sub_title = $this->getSubTitle('facultyList');
        $master_css = "active";
        $faculty_css = "active";

        return view('admin.faculty.faculty')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('master_css', $master_css)
                ->with('faculty_css', $faculty_css);
    }

    /**
     * Get faculty list From Ajax.
     *
     * @param Request ajax request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFacultyList(Request $request)
    {
        $faculties = Faculty::join('users', 'faculties.created_by', '=', 'users.id')
                         ->select([
                            'faculties.*',
                            'users.username AS created_by_user',
                         ]);

        return Datatables::of($faculties)
                        ->setRowClass(function () {
                            return "custom-tr-text-ellipsis";
                        })
                        ->filterColumn('created_by_user', function ($query, $keyword) {
                            $query->whereRaw("CONCAT(users.username,'-',users.username) like ?", ["%{$keyword}%"]);
                        })
                        ->addColumn('actions', function (Faculty $faculty)  {
                            return $this->getActionsButtons($faculty);
                        })
                        ->rawColumns(['actions'])
                        ->make(true);
    }

    /**
     * Get create new faculty view.
     *
     * @param empty
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = $this->getBreadCrumbs('createFaculty');
        $title = $this->getTitle('createFaculty');
        $sub_title = $this->getSubTitle('createFaculty');
        $master_css = "active";
        $faculty_css = "active";
        $btn_label = "Buat";

        return view('admin.faculty.create-or-edit-faculty')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('master_css', $master_css)
                ->with('faculty_css', $faculty_css)
                ->with('btn_label', $btn_label);
    }

    /**
     * Save new faculty.
     *
     * @param empty
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function store()
    {
        $user = \Auth::guard()->user();
        $faculty = new Faculty;
        $data = Input::all();

        if ($faculty->validate($faculty, $data, $faculty->messages('validation'))) {
            DB::beginTransaction();
            try {
                // store
                $faculty->name = Input::get('name');
                $faculty->created_by = $user->id;
                $faculty->save();

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $faculty->messages('success', 'create'));
                return redirect($this->getRoute('list'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('create'), $e);
            }
        } else {
            $errors = $faculty->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('create'), $errors);
        }
    }

    /**
     * Edit faculty.
     *
     * @param model binding faculty
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function edit(Faculty $faculty)
    {
        $breadcrumbs = $this->getBreadCrumbs('editFaculty');
        $title = $this->getTitle('editFaculty');
        $sub_title = $this->getSubTitle('editFaculty');
        $master_css = "active";
        $faculty_css = "active";
        $btn_label = "Ubah";

        return view('admin.faculty.create-or-edit-faculty')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('master_css', $master_css)
                ->with('faculty_css', $faculty_css)
                ->with('faculty', $faculty)
                ->with('btn_label', $btn_label);
    }

    /**
     * Update faculty.
     *
     * @param model binding faculty, Request request
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function update(Faculty $faculty, Request $request)
    {
        $data = Input::all();

        if ($faculty->validate($faculty, $data, $faculty->messages('validation'))) {
            DB::beginTransaction();
            try {
                // store
                $faculty->name = Input::get('name');
                $faculty->save();

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $faculty->messages('success', 'update'));
                return redirect($this->getRoute('list'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $faculty->id), $e);
            }
        } else {
            $errors = $faculty->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('edit', $faculty->id), $errors);
        }
    }

    /**
     * Destroy faculty.
     *
     * @param model binding faculty
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function destroy(Faculty $faculty)
    {
        try
        {
            $faculty->delete();

            // redirect
            $this->setFlashMessage('success', $faculty->messages('success', 'delete'));
            return redirect($this->getRoute('list'));

        } catch (\Exception $e) {
            $errors = new MessageBag([$faculty->messages('failDelete')]);
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
                return route('faculties');
            case 'create':
                return route('faculties.create');
            case 'edit':
                return route('faculties.edit', $id);
            case 'destroy':
                return route('faculties.destroy', $id);
            
            default:
                # code...
                break;
        }
    }
}
