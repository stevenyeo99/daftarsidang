<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MasterController;
use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;
use Illuminate\Support\MessageBag;

class StudyProgramController extends MasterController
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
     * Show the study programs list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = $this->getBreadCrumbs('studyProgramList');
        $title = $this->getTitle('studyProgramList');
        $sub_title = $this->getSubTitle('studyProgramList');
        $master_css = "active";
        $program_study_css = "active";
        $faculties = Faculty::all()->pluck('name', 'id');

        return view('admin.study_program.study_program')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('master_css', $master_css)
                ->with('program_study_css', $program_study_css)
                ->with('faculties', $faculties);
    }

    /**
     * Get study programs list From Ajax.
     *
     * @param Request ajax request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudyProgramList(Request $request)
    {
        $studyPrograms = StudyProgram::join('users', 'study_programs.created_by', '=', 'users.id')
                         ->join('faculties AS fclt', 'study_programs.faculty_id', '=', 'fclt.id')
                         ->select([
                            'study_programs.*',
                            'users.username AS created_by_user',
                            'fclt.id AS faculty_id',
                            'fclt.name AS faculty',
                         ]);

        return Datatables::of($studyPrograms)
                        ->setRowClass(function () {
                            return "custom-tr-text-ellipsis";
                        })
                        ->filterColumn('created_by_user', function ($query, $keyword) {
                            $query->whereRaw("CONCAT(users.username,'-',users.username) like ?", ["%{$keyword}%"]);
                        })
                        ->filterColumn('faculty', function ($query, $keyword) {
                            $query->whereRaw("CONCAT(fclt.id,'-',fclt.id) like ?", ["%{$keyword}%"]);
                        })
                        ->addColumn('actions', function (StudyProgram $studyProgram)  {
                            return $this->getActionsButtons($studyProgram);
                        })
                        ->rawColumns(['actions'])
                        ->make(true);
    }

    /**
     * Get create new study program view.
     *
     * @param empty
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = $this->getBreadCrumbs('createProdi');
        $title = $this->getTitle('createProdi');
        $sub_title = $this->getSubTitle('createProdi');
        $master_css = "active";
        $program_study_css = "active";
        $btn_label = "Buat";
        $faculties = Faculty::all()->pluck('name', 'id');

        return view('admin.study_program.create-or-edit-study_program')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('master_css', $master_css)
                ->with('program_study_css', $program_study_css)
                ->with('faculties', $faculties)
                ->with('btn_label', $btn_label);
    }

    /**
     * Save new study program.
     *
     * @param empty
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function store()
    {
        $user = \Auth::guard()->user();
        $studyProgram = new StudyProgram;
        $data = Input::all();

        if ($studyProgram->validate($studyProgram, $data, $studyProgram->messages('validation'))) {
            DB::beginTransaction();
            try {
                // store
                $studyProgram->code = Input::get('code');
                $studyProgram->name = Input::get('name');
                $studyProgram->faculty_id = Input::get('faculty');
                $studyProgram->created_by = $user->id;
                $studyProgram->save();

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $studyProgram->messages('success', 'create'));
                return redirect($this->getRoute('list'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('create'), $e);
            }
        } else {
            $errors = $studyProgram->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('create'), $errors);
        }
    }

    /**
     * Edit study program.
     *
     * @param model binding studyProgram
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function edit(StudyProgram $studyProgram)
    {
        $breadcrumbs = $this->getBreadCrumbs('editProdi');
        $title = $this->getTitle('editProdi');
        $sub_title = $this->getSubTitle('editProdi');
        $master_css = "active";
        $program_study_css = "active";
        $btn_label = "Ubah";
        $faculties = Faculty::all()->pluck('name', 'id');
        $selected_faculty = $studyProgram->faculty()->first();

        return view('admin.study_program.create-or-edit-study_program')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('master_css', $master_css)
                ->with('program_study_css', $program_study_css)
                ->with('studyProgram', $studyProgram)
                ->with('faculties', $faculties)
                ->with('selected_faculty', $selected_faculty)
                ->with('btn_label', $btn_label);
    }

    /**
     * Update study program.
     *
     * @param model binding studyProgram, Request request
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function update(StudyProgram $studyProgram, Request $request)
    {
        $data = Input::all();

        if ($studyProgram->validate($studyProgram, $data, $studyProgram->messages('validation'))) {
            DB::beginTransaction();

            try {
                // store
                $studyProgram->code = Input::get('code');
                $studyProgram->name = Input::get('name');
                $studyProgram->faculty_id = Input::get('faculty');
                $studyProgram->save();

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $studyProgram->messages('success', 'update'));
                return redirect($this->getRoute('list'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $studyProgram->id), $e);
            }
        } else {
            $errors = $studyProgram->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('edit', $studyProgram->id), $errors);
        }
    }

    /**
     * Destroy study program.
     *
     * @param model binding studyProgram
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function destroy(StudyProgram $studyProgram)
    {
        try
        {
            $studyProgram->delete();

            // redirect
            $this->setFlashMessage('success', $studyProgram->messages('success', 'delete'));
            return redirect($this->getRoute('list'));

        } catch (\Exception $e) {
            $errors = new MessageBag([$studyProgram->messages('failDelete')]);
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
                return route('prodis');
            case 'create':
                return route('prodis.create');
            case 'edit':
                return route('prodis.edit', $id);
            case 'destroy':
                return route('prodis.destroy', $id);
            
            default:
                # code...
                break;
        }
    }
}
