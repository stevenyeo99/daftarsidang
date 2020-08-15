<?php

namespace App\Http\Controllers;

use App\Enums\ConsumptionType;
use App\Enums\Gender;
use App\Enums\ParentType;
use App\Enums\SemesterType;
use App\Enums\TogaSize;
use App\Enums\WorkState;
use App\Http\Controllers\MasterController;
use App\Models\Company;
use App\Models\CustomParent;
use App\Models\Semester;
use App\Models\StudyProgram;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\MessageBag;

class UserStudentController extends MasterController
{
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
     * Get student profile view.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $breadcrumbs = $this->getBreadCrumbs('studentProfile');
        $title = $this->getTitle('studentProfile');
        $sub_title = $this->getSubTitle('studentProfile');
        $genders = Gender::getStrings();
        $semesters = Semester::all()->pluck('text', 'id');
        $study_programs = StudyProgram::all()->pluck('name', 'id');
        $work_statuses = WorkState::getStrings();
        $toga_sizes = TogaSize::getStrings();
        $consumption_types = ConsumptionType::getStrings();
        $student_profile_css = "active";
        $btn_label = "Ubah";

        $student = \Auth::guard('student')->user();
        $father = $student->parents()->where('type', ParentType::Father)->first();
        $mother = $student->parents()->where('type', ParentType::Mother)->first();

        $parentCurrentCompany = $this->getParentCompany();

        return view('student.profile.profile')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('btn_label', $btn_label)
                ->with('genders', $genders)
                ->with('semesters', $semesters)
                ->with('study_programs', $study_programs)
                ->with('work_statuses', $work_statuses)
                ->with('toga_sizes', $toga_sizes)
                ->with('consumption_types', $consumption_types)
                ->with('father', $father)
                ->with('mother', $mother)
                ->with('parentCurrentCompany', $parentCurrentCompany)
                ->with('student_profile_css', $student_profile_css);
    }

    /**
     * Update student's profile data.
     *
     * @param Request request
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function updateProfile(Request $request)
    {
        $data = Input::all();
        $student = \Auth::guard('student')->user();

        $data['npm'] = $student->npm;
        $data['name'] = $student->name;
        $change_important_data = false;

        if ($student->validate($student, $data, $student->messages('validation'))) {
            DB::beginTransaction();
            try {
                // store
                $student->email = Input::get('email');
                $student->work_status = Input::get('work_status');
                $student->toga_size = Input::get('toga_size');
                $student->consumption_type = Input::get('consumption_type');
                $student->semester_id = Input::get('semester');
                $student->NIK = Input::get('NIK');
                $student->toeic_grade = Input::get('toeic_grade');
                
                if (!$student->is_profile_accurate) { // if the student submit the form with the "Perbaiki Data" mode
                    if (
                        $student->sex != Input::get('sex') ||
                        $student->birth_place != Input::get('birth_place') ||
                        $student->birthdate != Carbon::parse(Input::get('birthdate'))
                    ) {
                        $student->sex = Input::get('sex');
                        $student->birth_place = Input::get('birth_place');
                        $student->birthdate = Carbon::parse(Input::get('birthdate'));
                        $change_important_data = true;
                    }

                    $student->religion = Input::get('religion');
                    $student->study_program_id = Input::get('study_program');
                    $student->phone_number = Input::get('phone_number');
                    $student->address = Input::get('address');
                    $student->is_profile_accurate = true; // set student's profile accurate to true
                }

                if (Input::get('existing_degree') != null) {
                    $student->existing_degree = Input::get('existing_degree');
                    
                }
                if (Input::get('certification_degree') != null) {
                    $student->certification_degree = Input::get('certification_degree');
                }
                
                $student->profile_filled = true;

                if ($change_important_data) { // if the student changes important data
                    $student->must_fill_attachment = true;
                    // $this->setFlashMessage('danger', $student->messages('updateProfileAfterSetIsProfileAccurateFalse'));
                    $redirectRoute = $this->getRoute('attachment'); // redirect to attachment
                } else {
                    $this->setFlashMessage('success', $student->messages('success', 'update'));
                    $redirectRoute = $this->getRoute('profile');
                }

                if ($student->is_profile_accurate && (isset($data['is_profile_accurate_value']) && $data['is_profile_accurate_value'] == "false")) { // if student want to enter "Perbaiki Data" mode
                    $student->is_profile_accurate = false;
                    $this->setFlashMessage('success', $student->messages('success', 'updateIsProfileAccurateFalse'));
                }

                $student->save();
                DB::commit();

                // redirect
                return redirect($redirectRoute);
            }
            catch (\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('profile'), $e);
            }
        } else {
            $errors = $student->errors();
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('profile'), $errors);
        }
    }

    /**
     * Update student's company data.
     *
     * @param Request request
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function updateCompany(Request $request)
    {
        $data = Input::all();
        $student = \Auth::guard('student')->user();

        $companyData = [];
        $companyData['name'] = Input::get('company_name');
        $companyData['field'] = Input::get('company_field');
        $companyData['phone_number'] = Input::get('company_phone_number');
        $companyData['address'] = Input::get('company_address');

        $store_parent_company_data = $this->storeCompanyData($companyData, 'student');

        if (!is_object($store_parent_company_data)) { // if company stored
            
            $student->company_id = $store_parent_company_data;
            $student->save();
            $this->setMultipleFlashMessageArr('success', $student->messages('success', 'updateCompany'));
            $this->fireMultipleFlashMessage();
            $this->resetMultipleFlashMessageArr();
            return redirect($this->getRoute('profile'));
        } else {
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('profile'), $store_parent_company_data);
        }
    }

    /**
     * Update student's parent data.
     *
     * @param Request request
     *
     * @return \Illuminate\Http\HttpResponse
     */
    public function updateParent(Request $request)
    {
        $customParent = new CustomParent();

        $data = Input::all();
        $student = \Auth::guard('student')->user();

        // dummy array data for validation
        $fatherData = [];
        $fatherData['name'] = Input::get('father_name');
        $fatherData['type'] = ParentType::Father;
        $fatherData['student'] = $student->id;

        $motherData = [];
        $motherData['name'] = Input::get('mother_name');
        $motherData['type'] = ParentType::Mother;
        $motherData['student'] = $student->id;

        $companyData = [];
        $companyData['name'] = Input::get('company_name');
        $companyData['field'] = Input::get('company_field');
        $companyData['phone_number'] = Input::get('company_phone_number');
        $companyData['address'] = Input::get('company_address');

        $store_father_data = $this->storeParentData($fatherData, 'father');
        $store_mother_data = $this->storeParentData($motherData, 'mother');
        $store_parent_company_data = $this->storeCompanyData($companyData, 'parent');

        if (
            $companyData['name'] == null &&
            $companyData['field'] == null &&
            $companyData['phone_number'] == null &&
            $companyData['address'] == null
        ) {
            // store parents' data
            if (!is_object($store_father_data) && !is_object($store_mother_data)) { // if both pass
                $this->setFlashMessage('success', $customParent->messages('success', 'updateBothParent'));
                return redirect($this->getRoute('profile'));
            } elseif (!is_object($store_father_data)) { // if father pass
                $this->setFlashMessage('success', $customParent->messages('success', 'updateFather'));
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('profile'), $store_mother_data);
            } elseif (!is_object($store_mother_data)) { // if mother pass
                $this->setFlashMessage('success', $customParent->messages('success', 'updateMother'));
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('profile'), $store_father_data);
            } else { // both fails
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('profile'), $store_father_data);
            }
        }

        if (!is_object($store_parent_company_data)) { // if company stored
            // store parents' data
            if (!is_object($store_father_data) && !is_object($store_mother_data)) { // if both pass
                $father = CustomParent::find($store_father_data);
                $father->company_id = $store_parent_company_data;
                $father->save();

                $this->setMultipleFlashMessageArr('success', $customParent->messages('success', 'updateBothParent'));
                $this->fireMultipleFlashMessage();
                $this->resetMultipleFlashMessageArr();
                return redirect($this->getRoute('profile'));
            } elseif (!is_object($store_father_data)) { // if father pass
                $father = CustomParent::find($store_father_data);
                $father->company_id = $store_parent_company_data;
                $father->save();

                $this->setMultipleFlashMessageArr('success', $customParent->messages('success', 'updateFather'));
                $this->fireMultipleFlashMessage();
                $this->resetMultipleFlashMessageArr();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('profile'), $store_mother_data);
            } elseif (!is_object($store_mother_data)) { // if mother pass
                $mother = CustomParent::find($store_mother_data);
                $mother->company_id = $store_parent_company_data;
                $mother->save();

                $this->setMultipleFlashMessageArr('success', $customParent->messages('success', 'updateMother'));
                $this->fireMultipleFlashMessage();
                $this->resetMultipleFlashMessageArr();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('profile'), $store_father_data);
            } else { // both fails
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('profile'), $store_father_data);
            }
        } else {
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('profile'), $store_parent_company_data);
        }
    }

    /**
     * Student wanna update portal's data.
     *
     * @param empty
     *
     * @return \Illuminate\Http\HttpResponse
     */
     // public function setStudentIsProfileAccurateFalse()
     // {
     //    $student = \Auth::guard('student')->user();

     //    try
     //    {
     //        if (!$student->is_profile_accurate) {
     //            abort(404);
     //        }

     //        $student->is_profile_accurate = false;
     //        $student->save();

     //        // redirect
     //        $this->setFlashMessage('success', $student->messages('success', 'updateIsProfileAccurateFalse'));
     //        return redirect($this->getRoute('profile'));
     //    } catch (\Exception $e) {
     //        $errors = new MessageBag([$student->messages('failSetIsProfileAccurateFalse')]);
     //        return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('profile'), $errors);
     //    }
     // }

    /**
     * Store parent data with params given.
     *
     * @param array $data, string $key
     *
     * @return string
     */
    private function storeParentData(array $data, string $key)
    {
        $customParent = new CustomParent();
        $student = \Auth::guard('student')->user();
        $father = $student->parents()->where('type', ParentType::Father)->first();
        $mother = $student->parents()->where('type', ParentType::Mother)->first();

        switch ($key) {
            case 'father':
                if ($father != null) {
                    $customParent = CustomParent::find($father->id);
                }
                break;
            case 'mother':
                if ($mother != null) {
                    $customParent = CustomParent::find($mother->id);
                }
                break;
            
            default:
                # code...
                break;
        }

        if ($customParent == null) {
            $customParent = new CustomParent();
        }

        if ($customParent->validate($customParent, $data, $customParent->messages('validation'))) {
            DB::beginTransaction();
            try {
                // store
                $customParent->name = $data['name'];
                $customParent->type = $data['type'];
                $customParent->student_id = $data['student'];
                $customParent->save();

                DB::commit();
                return $customParent->id;
            }
            catch (\Exception $e) {
                DB::rollback();
                return $e;
            }
        } else {
            $errors = $customParent->errors();
            return $errors;
        }
    }

    /**
     * Store company data with params given.
     *
     * @param array $data, string $key
     *
     * @return string
     */
    private function storeCompanyData(array $data, string $key)
    {
        if ($data['name'] != null) {
            $company = Company::where('name', $data['name'])->first();
        }

        if (!isset($company) || $company == null) {
            $company = new Company;
        }

        if (isset($company->id)) {
            $this->setMultipleFlashMessageArr('warning', $company->messages('companyExistingIssue'));
            return $company->id;
        }

        if ($company->validate($company, $data, $company->messages('validation'))) {
            DB::beginTransaction();
            try {
                // store
                $company->name = $data['name'];
                $company->field = $data['field'];
                $company->address = $data['address'];
                $company->phone_number = $data['phone_number'];
                $company->save();

                DB::commit();
                return $company->id;
            }
            catch (\Exception $e) {
                DB::rollback();
                return $e;
            }
        } else {
            $errors = $company->errors();
            return $errors;
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
            case 'profile':
                return route('student.profile');
            case 'attachment':
                return route('student.attachment');
            
            default:
                break;
        }
    }

    /**
     * Redirect to route with errors and inputs.
     *
     * @param string $route, array $errors
     *
     * @return string
     */
    public function redirectToRouteWithErrorsAndInputs(string $route, $errors)
    {
        return redirect($route)
                ->with('errors', $errors);
    }

    /**
     * Get student's parent's company.
     *
     * @param string $route, array $errors
     *
     * @return string
     */
    public function getParentCompany()
    {
        $student = \Auth::guard('student')->user();
        $father = $student->parents()->where('type', ParentType::Father)->first();
        $mother = $student->parents()->where('type', ParentType::Mother)->first();

        $currentCompany = null;

        if ($mother != null) {
            if ($mother->company()->first() != null) {
                $currentCompany = $mother->company()->first();
            }
        }
        if ($father != null) {
            if ($father->company()->first() != null) {
                $currentCompany = $father->company()->first();
            }
        }

        return $currentCompany;
    }

    /**
     * Get student's company.
     *
     * @param string $route, array $errors
     *
     * @return string
     */
    public function getStudentCompany()
    {
        $student = \Auth::guard('student')->user();

        $currentCompany = null;

        if ($student->company()->first() != null) {
            $currentCompany = $student->company()->first();
        }

        return $currentCompany;
    }
}
