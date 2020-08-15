<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MasterController;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;
use Illuminate\Support\MessageBag;

class StudentAchievementController extends MasterController
{
	private const STUDENT_ACHIEVEMENT_LIMIT = 10;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Show the certificates list view.
     *
     * @param empty
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = $this->getBreadCrumbs('studentAchievementList');
        $title = $this->getTitle('studentAchievementList');
        $sub_title = $this->getSubTitle('studentAchievementList');
        $master_css = "active";
        $achievement_css = "active";
        $should_show_create = true;

        $studentAchievements = \Auth::guard('student')->user()->achievements()->get();
        if (count($studentAchievements) >= self::STUDENT_ACHIEVEMENT_LIMIT) {
            $should_show_create = false;
        }

        return view('student.achievement.achievement')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('master_css', $master_css)
                ->with('achievement_css', $achievement_css)
                ->with('should_show_create', $should_show_create);
    }

    /**
     * Get create new certificate view.
     *
     * @param empty
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = $this->getBreadCrumbs('createAchievement');
        $title = $this->getTitle('createAchievement');
        $sub_title = $this->getSubTitle('createAchievement');
        $master_css = "active";
        $achievement_css = "active";
        $btn_label = "Buat";

        return view('student.achievement.create-or-edit-achievement')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('master_css', $master_css)
                ->with('achievement_css', $achievement_css)
                ->with('btn_label', $btn_label);
    }

    /**
     * Save new certificate.
     *
     * @param empty
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function store()
    {
        $achievement = new Achievement;
        $student = \Auth::guard('student')->user();

        $data = Input::all();
        $data['student'] = $student->id;

        $can_create = true;

        $studentAchievements = \Auth::guard('student')->user()->achievements()->get();
        if (count($studentAchievements) >= self::STUDENT_ACHIEVEMENT_LIMIT) {
            $can_create = false;
        }

        if ($achievement->validate($achievement, $data, $achievement->messages('validation'))) {
            DB::beginTransaction();
            try {
                if (!$can_create) {
                    throw new \Exception($achievement->messages('failStoreLimitException'));
                }
                // store
                $achievement->name = Input::get('name');
                $achievement->place = Input::get('place');
                $achievement->year = Input::get('year');
                $achievement->student_id = $data['student'];
                $achievement->save();

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $achievement->messages('success', 'create'));
                return redirect($this->getRoute('list'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('create'), $e);
            }
        } else {
            $errors = $achievement->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('create'), $errors);
        }
    }

    /**
     * Get Achievement List From Ajax.
     *
     * @param Request ajax request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAchievementList(Request $request)
    {
        $achievements = \Auth::guard('student')->user()->achievements()->get();

        return Datatables::of($achievements)
                        ->setRowClass(function () {
                            return "custom-tr-text-ellipsis";
                        })
                        ->addColumn('actions', function (Achievement $achievement)  {
                            return $this->getActionsButtons($achievement);
                        })
                        ->editColumn('place', function (Achievement $achievement) {
                            return strlen($achievement->place) > 0 ? $achievement->place : '-';
                        })
                        ->rawColumns(['actions'])
                        ->make(true);
    }

    /**
     * Edit achievement.
     *
     * @param model binding achievement
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function edit(Achievement $achievement)
    {
        $breadcrumbs = $this->getBreadCrumbs('editAchievement');
        $title = $this->getTitle('editAchievement');
        $sub_title = $this->getSubTitle('editAchievement');
        $master_css = "active";
        $achievement_css = "active";
        $btn_label = "Ubah";

        return view('student.achievement.create-or-edit-achievement')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('master_css', $master_css)
                ->with('achievement_css', $achievement_css)
                ->with('achievement', $achievement)
                ->with('btn_label', $btn_label);
    }

    /**
     * Update achievement.
     *
     * @param model binding achievement, Request request
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function update(Achievement $achievement, Request $request)
    {
        $student = \Auth::guard('student')->user();

        $data = Input::all();
        $data['student'] = $student->id;

        if ($achievement->validate($achievement, $data, $achievement->messages('validation'))) {
            DB::beginTransaction();
            try {
                // store
                $achievement->name = Input::get('name');
                $achievement->place = Input::get('place');
                $achievement->year = Input::get('year');
                $achievement->student_id = $data['student'];
                $achievement->save();

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $achievement->messages('success', 'update'));
                return redirect($this->getRoute('list'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $achievement->id), $e);
            }
        } else {
            $errors = $achievement->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('edit', $achievement->id), $errors);
        }
    }

    /**
     * Destroy achievement.
     *
     * @param model binding achievement
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function destroy(Achievement $achievement)
    {
        try
        {
            $achievement->delete();

            // redirect
            $this->setFlashMessage('success', $achievement->messages('success', 'delete'));
            return redirect($this->getRoute('list'));

        } catch (\Exception $e) {
            $errors = new MessageBag([$achievement->messages('failDelete')]);
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
        		return route('student.achievement');
            case 'create':
                return route('student.achievement.create');
            case 'edit':
                return route('student.achievement.edit', $id);
            case 'destroy':
                return route('student.achievement.destroy', $id);
            
            default:
                # code...
                break;
        }
    }
}
