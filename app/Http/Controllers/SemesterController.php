<?php

namespace App\Http\Controllers;

use App\Enums\SemesterType;
use App\Http\Controllers\MasterController;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;
use Illuminate\Support\MessageBag;

class SemesterController extends MasterController
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
     * Show the semester list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = $this->getBreadCrumbs('semesterList');
        $title = $this->getTitle('semesterList');
        $sub_title = $this->getSubTitle('semesterList');
        $types = SemesterType::getStrings();
        $master_css = "active";
        $semester_css = "active";

        return view('admin.semester.semester')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('types', $types)
                ->with('master_css', $master_css)
                ->with('semester_css', $semester_css);
    }

    /**
     * Get semester list From Ajax.
     *
     * @param Request ajax request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSemesterList(Request $request)
    {
        $requests = Semester::join('users', 'semesters.created_by', '=', 'users.id')
                         ->select([
                            'semesters.*',
                            'users.username AS created_by_user',
                         ]);

        return Datatables::of($requests)
                        ->setRowClass(function () {
                            return "custom-tr-text-ellipsis";
                        })
                        ->addColumn('status', function (Semester $semester) {
                            return $this->getRecordsStatusLabel($semester);
                        })
                        ->filterColumn('status', function ($query, $keyword) {
                            if ($keyword == "0") {
                                $query->whereRaw("CONCAT(semesters.is_active,'-',semesters.is_active) like ?", ["%1%"]);
                            } else {
                                $query->whereRaw("CONCAT(semesters.is_active,'-',semesters.is_active) like ?", ["%0%"]);
                            }
                        })
                        ->filterColumn('created_by_user', function ($query, $keyword) {
                            $query->whereRaw("CONCAT(users.username,'-',users.username) like ?", ["%{$keyword}%"]);
                        })
                        ->editColumn('type', function(Semester $semester) {
                            return $this->getRecordsTypeLabel($semester);
                        })
                        ->addColumn('actions', function (Semester $semester)  {
                            return $this->getActionsButtons($semester);
                        })
                        ->rawColumns(['type', 'status', 'actions'])
                        ->make(true);
    }

    /**
     * Get create new semester view.
     *
     * @param empty
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = $this->getBreadCrumbs('createSemester');
        $title = $this->getTitle('createSemester');
        $sub_title = $this->getSubTitle('createSemester');
        $types = SemesterType::getStrings();
        $master_css = "active";
        $semester_css = "active";
        $btn_label = "Buat";

        return view('admin.semester.create-or-edit-semester')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('types', $types)
                ->with('master_css', $master_css)
                ->with('semester_css', $semester_css)
                ->with('btn_label', $btn_label);
    }

    /**
     * Save new semester.
     *
     * @param empty
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function store()
    {
        $user = \Auth::guard()->user();
        $semester = new Semester;
        $data = Input::all();
        $data['is_active'] = Input::get('is_active') != null ? Input::get('is_active') : 'off';

        if ($semester->validate($semester, $data, $semester->messages('validation'))) {
            DB::beginTransaction();
            try {
                $semesterTypeDuplicate = Semester::where('year', Input::get('year') . '/' . ((int)Input::get('year') + 1))
                                                 ->where('type', Input::get('type'))->first();

                if ($semesterTypeDuplicate != null) {
                    throw new \Exception($semester->messages('failStoreDuplicateException'));
                }

                // store
                $semester->year = Input::get('year') . '/' . ((int)Input::get('year') + 1);
                $semester->type = Input::get('type');
                $semester->is_active = Input::get('is_active') == 'on' ? true : false;
                $semester->text = Input::get('year') . '/' . ((int)Input::get('year') + 1) . ' - ' . SemesterType::getString(Input::get('type'));
                $semester->created_by = $user->id;
                $semester->save();

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $semester->messages('success', 'create'));
                return redirect($this->getRoute('list'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('create'), $e);
            }
        } else {
            $errors = $semester->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('create'), $errors);
        }
    }

    /**
     * Edit semester.
     *
     * @param model binding semester
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function edit(Semester $semester)
    {
        $breadcrumbs = $this->getBreadCrumbs('editSemester');
        $title = $this->getTitle('editSemester');
        $sub_title = $this->getSubTitle('editSemester');
        $types = SemesterType::getStrings();
        $master_css = "active";
        $semester_css = "active";
        $btn_label = "Ubah";
        $selected_type = $semester->type;

        return view('admin.semester.create-or-edit-semester')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('types', $types)
                ->with('master_css', $master_css)
                ->with('semester_css', $semester_css)
                ->with('semester', $semester)
                ->with('btn_label', $btn_label)
                ->with('selected_type', $selected_type);
    }

    /**
     * Update semester.
     *
     * @param model binding semester, Request request
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function update(Semester $semester, Request $request)
    {
        $data = Input::all();
        $data['is_active'] = Input::get('is_active') != null ? Input::get('is_active') : 'off';

        if ($semester->validate($semester, $data, $semester->messages('validation'))) {
            DB::beginTransaction();
            try {
                $semesterTypeDuplicate = Semester::where('year', Input::get('year') . '/' . ((int)Input::get('year') + 1))
                                                 ->where('type', Input::get('type'))->first();

                if ($semesterTypeDuplicate != null && $semesterTypeDuplicate->id != $semester->id) {
                    throw new \Exception($semester->messages('failStoreDuplicateException'));
                }

                // store
                $semester->year = Input::get('year') . '/' . ((int)Input::get('year') + 1);
                $semester->type = Input::get('type');
                $semester->is_active = Input::get('is_active') == 'on' ? true : false;
                $semester->text = Input::get('year') . '/' . ((int)Input::get('year') + 1) . ' - ' . SemesterType::getString(Input::get('type'));
                $semester->save();

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $semester->messages('success', 'update'));
                return redirect($this->getRoute('list'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $semester->id), $e);
            }
        } else {
            $errors = $semester->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('edit', $semester->id), $errors);
        }
    }

    /**
     * Destroy semester.
     *
     * @param model binding semester
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function destroy(Semester $semester)
    {
        try
        {
            $semester->delete();

            // redirect
            $this->setFlashMessage('success', $semester->messages('success', 'delete'));
            return redirect($this->getRoute('list'));

        } catch (\Exception $e) {
            $errors = new MessageBag([$semester->messages('failDelete')]);
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
                return route('semesters');
            case 'create':
                return route('semesters.create');
            case 'edit':
                return route('semesters.edit', $id);
            case 'destroy':
                return route('semesters.destroy', $id);
            
            default:
                # code...
                break;
        }
    }

    /**
     * Get a refactored type labels for semester datatable.
     *
     * @param Semester $semester
     *
     * @return string
     */
    private function getRecordsTypeLabel(Semester $semester)
    {
        $type = SemesterType::getString($semester->type);

        $extra_class = 'success';

        return "<span class='label label-{$extra_class}'>{$type}</span>";
    }

    /**
     * Get a refactored status labels for semester datatable.
     *
     * @param Semester $semester
     *
     * @return string
     */
    private function getRecordsStatusLabel(Semester $semester)
    {
        $is_active = $semester->is_active;

        if ($semester->is_active) {
            $extra_class = 'info';
            $label = "Aktif";
        } else {
            $extra_class = 'warning';
            $label = "Tidak Aktif";
        }

        return "<span class='label label-{$extra_class}'>{$label}</span>";
    }
}
