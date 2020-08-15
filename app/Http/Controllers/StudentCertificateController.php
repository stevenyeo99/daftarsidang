<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MasterController;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;
use Illuminate\Support\MessageBag;

class StudentCertificateController extends MasterController
{
    private const STUDENT_CERTIFICATE_LIMIT = 5;

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
        $breadcrumbs = $this->getBreadCrumbs('studentCertificateList');
        $title = $this->getTitle('studentCertificateList');
        $sub_title = $this->getSubTitle('studentCertificateList');
        $master_css = "active";
        $certificate_css = "active";
        $should_show_create = true;

        $studentCertificates = \Auth::guard('student')->user()->certificates()->get();
        if (count($studentCertificates) >= self::STUDENT_CERTIFICATE_LIMIT) {
            $should_show_create = false;
        }

        return view('student.certificate.certificate')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('master_css', $master_css)
                ->with('certificate_css', $certificate_css)
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
        $breadcrumbs = $this->getBreadCrumbs('createCertificate');
        $title = $this->getTitle('createCertificate');
        $sub_title = $this->getSubTitle('createCertificate');
        $master_css = "active";
        $certificate_css = "active";
        $btn_label = "Buat";

        return view('student.certificate.create-or-edit-certificate')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('master_css', $master_css)
                ->with('certificate_css', $certificate_css)
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
        $certificate = new Certificate;
        $student = \Auth::guard('student')->user();

        $data = Input::all();
        $data['student'] = $student->id;

        $can_create = true;

        $studentCertificates = \Auth::guard('student')->user()->certificates()->get();
        if (count($studentCertificates) >= self::STUDENT_CERTIFICATE_LIMIT) {
            $can_create = false;
        }

        if ($certificate->validate($certificate, $data, $certificate->messages('validation'))) {
            DB::beginTransaction();
            try {
                if (!$can_create) {
                    throw new \Exception($certificate->messages('failStoreLimitException'));
                }
                // store
                $certificate->name = Input::get('name');
                $certificate->place = Input::get('place');
                $certificate->year = Input::get('year');
                $certificate->student_id = $data['student'];
                $certificate->save();

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $certificate->messages('success', 'create'));
                return redirect($this->getRoute('list'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('create'), $e);
            }
        } else {
            $errors = $certificate->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('create'), $errors);
        }
    }

    /**
     * Get Certificate List From Ajax.
     *
     * @param Request ajax request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCertificateList(Request $request)
    {
        $certificates = \Auth::guard('student')->user()->certificates()->get();

        return Datatables::of($certificates)
                        ->setRowClass(function () {
                            return "custom-tr-text-ellipsis";
                        })
                        ->addColumn('actions', function (Certificate $certificate)  {
                            return $this->getActionsButtons($certificate);
                        })
                        ->editColumn('place', function (Certificate $certificate) {
                            return strlen($certificate->place) > 0 ? $certificate->place : '-';
                        })
                        ->rawColumns(['actions'])
                        ->make(true);
    }

    /**
     * Edit certificate.
     *
     * @param model binding certificate
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function edit(Certificate $certificate)
    {
        $breadcrumbs = $this->getBreadCrumbs('editCertificate');
        $title = $this->getTitle('editCertificate');
        $sub_title = $this->getSubTitle('editCertificate');
        $master_css = "active";
        $certificate_css = "active";
        $btn_label = "Ubah";

        return view('student.certificate.create-or-edit-certificate')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('master_css', $master_css)
                ->with('certificate_css', $certificate_css)
                ->with('certificate', $certificate)
                ->with('btn_label', $btn_label);
    }

    /**
     * Update certificate.
     *
     * @param model binding certificate, Request request
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function update(Certificate $certificate, Request $request)
    {
        $student = \Auth::guard('student')->user();

        $data = Input::all();
        $data['student'] = $student->id;

        if ($certificate->validate($certificate, $data, $certificate->messages('validation'))) {
            DB::beginTransaction();
            try {
                // store
                $certificate->name = Input::get('name');
                $certificate->place = Input::get('place');
                $certificate->year = Input::get('year');
                $certificate->student_id = $data['student'];
                $certificate->save();

                DB::commit();
                // redirect
                $this->setFlashMessage('success', $certificate->messages('success', 'update'));
                return redirect($this->getRoute('list'));
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $certificate->id), $e);
            }
        } else {
            $errors = $certificate->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('edit', $certificate->id), $errors);
        }
    }

    /**
     * Destroy certificate.
     *
     * @param model binding certificate
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function destroy(Certificate $certificate)
    {
        try
        {
            $certificate->delete();

            // redirect
            $this->setFlashMessage('success', $certificate->messages('success', 'delete'));
            return redirect($this->getRoute('list'));

        } catch (\Exception $e) {
            $errors = new MessageBag([$certificate->messages('failDelete')]);
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
        		return route('student.certificate');
            case 'create':
                return route('student.certificate.create');
            case 'edit':
                return route('student.certificate.edit', $id);
            case 'destroy':
                return route('student.certificate.destroy', $id);
            
            default:
                # code...
                break;
        }
    }
}
