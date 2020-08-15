<?php

namespace App\Http\Controllers;

use App\Enums\AttachmentType;
use App\Http\Controllers\MasterController;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Yajra\Datatables\Datatables;

class StudentAttachmentController extends MasterController
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
     * Show the attachment index view.
     *
     * @param empty
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = $this->getBreadCrumbs('studentAttachment');
        $title = $this->getTitle('studentAttachment');
        $sub_title = $this->getSubTitle('studentAttachment');
        $master_css = "active";
        $attachment_css = "active";
        $student = \Auth::guard('student')->user();
        $ktp = $student->attachments()->where('type', AttachmentType::KTP)->first();
        $kartuKeluarga = $student->attachments()->where('type', AttachmentType::KartuKeluarga)->first();
        $aktaKelahiran = $student->attachments()->where('type', AttachmentType::AktaKelahiran)->first();
        $ijazahSMA = $student->attachments()->where('type', AttachmentType::IjazahSMA)->first();
        $ijazahS1 = $student->attachments()->where('type', AttachmentType::IjazahS1)->first();

        return view('student.attachment.attachment')
                ->with('title', $title)
                ->with('sub_title', $sub_title)
                ->with('breadcrumbs', $breadcrumbs)
                ->with('master_css', $master_css)
                ->with('attachment_css', $attachment_css)
                ->with('student', $student)
                ->with('kartuKeluarga', $kartuKeluarga)
                ->with('aktaKelahiran', $aktaKelahiran)
                ->with('ijazahSMA', $ijazahSMA)
                ->with('ijazahS1', $ijazahS1)
                ->with('ktp', $ktp);
    }

    /**
     * Upload KTP.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadKTP(Request $request)
    {
        if ($request->has('ktpUploader')) {
            $attachment = new Attachment;
            $student = \Auth::guard('student')->user();
            $ktp = $student->attachments()->where('type', AttachmentType::KTP)->first();
            
            $data = Input::all();
            $data['student'] = $student->id;

            if ($attachment->validateWithImageRules($data, 'ktp', $attachment->messages('validation'))) {
                // read file and store
                DB::beginTransaction();
                try {
                    // delete existing image
                    if ($ktp != null) {
                        try {
                            $ktp->delete();
                            unlink($this->getStudentFileFolderPath($student).$ktp->file_name);
                        } catch (\Exception $e) {
                            throw new \Exception($attachment->messages('fileNotFoundOnDirectory'));
                            
                        }
                    }

                    //store
                    $image = $data['ktpUploader'];
                    // $imageName = time().$image->getClientOriginalName();
                    $imageName = $student->npm.'-KTP-'.time().'-'.(str_replace(' ', '', $image->getClientOriginalName()));

                    // move to folder
                    $image->move($this->getStudentFileFolderPath($student), $imageName);

                    $attachment->name = $imageName;
                    $attachment->type = AttachmentType::KTP;
                    $attachment->file_name = $imageName;
                    $attachment->student_id = $data['student'];
                    $attachment->save();
                    $this->updateStudentMustFillAttachmentField();

                    DB::commit();
                    // redirect
                    $this->setFlashMessage('success', $attachment->messages('success', 'import'));
                    return redirect($this->getRoute('list'));
                } catch (\Exception $e) {
                    DB::rollback();
                    return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('list'), $e);
                }
            } else {
                $errors = $attachment->errors();
                return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
            }
        }
    }

    /**
     * Download KTP.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadKTP(Request $request)
    {
        $student = \Auth::guard('student')->user();
        $ktp = $student->attachments()->where('type', AttachmentType::KTP)->first();
        
        if ($ktp == null) {
            abort(404);
        }

        try {
            $filePath = $this->getStudentFileFolderPath($student).$ktp->file_name;

            return Response::download($filePath, $ktp->file_name);
        } catch (\Exception $e) {
            $attachment = new Attachment;
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('list'), new \Exception($attachment->messages('fileNotFoundOnDirectory')));
            
        }
    }

    /**
     * Upload KK.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadKK(Request $request)
    {
        if ($request->has('kartuKeluargaUploader')) {
            $attachment = new Attachment;
            $student = \Auth::guard('student')->user();
            $kartuKeluarga = $student->attachments()->where('type', AttachmentType::KartuKeluarga)->first();
            
            $data = Input::all();
            $data['student'] = $student->id;

            if ($attachment->validateWithImageRules($data, 'kartuKeluarga', $attachment->messages('validation'))) {
                // read file and store
                DB::beginTransaction();
                try {
                    // delete existing image
                    if ($kartuKeluarga != null) {
                        $kartuKeluarga->delete();
                        unlink($this->getStudentFileFolderPath($student).$kartuKeluarga->file_name);
                    }

                    //store
                    $image = $data['kartuKeluargaUploader'];
                    // $imageName = time().$image->getClientOriginalName();
                    $imageName = $student->npm.'-KK-'.time().'-'.(str_replace(' ', '', $image->getClientOriginalName()));

                    // move to folder
                    $image->move($this->getStudentFileFolderPath($student), $imageName);

                    $attachment->name = $imageName;
                    $attachment->type = AttachmentType::KartuKeluarga;
                    $attachment->file_name = $imageName;
                    $attachment->student_id = $data['student'];
                    $attachment->save();
                    $this->updateStudentMustFillAttachmentField();

                    DB::commit();
                    // redirect
                    $this->setFlashMessage('success', $attachment->messages('success', 'import'));
                    return redirect($this->getRoute('list'));
                } catch (\Exception $e) {
                    DB::rollback();
                    return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('list'), $e);
                }
            } else {
                $errors = $attachment->errors();
                return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
            }
        }
    }

    /**
     * Download KK.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadKK(Request $request)
    {
        $student = \Auth::guard('student')->user();
        $kk = $student->attachments()->where('type', AttachmentType::KartuKeluarga)->first();
        
        if ($kk == null) {
            abort(404);
        }

        try {
            $filePath = $this->getStudentFileFolderPath($student).$kk->file_name;

            return Response::download($filePath, $kk->file_name);
        } catch (\Exception $e) {
            $attachment = new Attachment;
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('list'), new \Exception($attachment->messages('fileNotFoundOnDirectory')));
            
        }
    }

    /**
     * Upload AK.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadAK(Request $request)
    {
        if ($request->has('aktaKelahiranUploader')) {
            $attachment = new Attachment;
            $student = \Auth::guard('student')->user();
            $aktaKelahiran = $student->attachments()->where('type', AttachmentType::AktaKelahiran)->first();
            
            $data = Input::all();
            $data['student'] = $student->id;

            if ($attachment->validateWithImageRules($data, 'aktaKelahiran', $attachment->messages('validation'))) {
                // read file and store
                DB::beginTransaction();
                try {
                    // delete existing image
                    if ($aktaKelahiran != null) {
                        $aktaKelahiran->delete();
                        unlink($this->getStudentFileFolderPath($student).$aktaKelahiran->file_name);
                    }

                    //store
                    $image = $data['aktaKelahiranUploader'];
                    // $imageName = time().$image->getClientOriginalName();
                    $imageName = $student->npm.'-AK-'.time().'-'.(str_replace(' ', '', $image->getClientOriginalName()));

                    // move to folder
                    $image->move($this->getStudentFileFolderPath($student), $imageName);

                    $attachment->name = $imageName;
                    $attachment->type = AttachmentType::AktaKelahiran;
                    $attachment->file_name = $imageName;
                    $attachment->student_id = $data['student'];
                    $attachment->save();
                    $this->updateStudentMustFillAttachmentField();

                    DB::commit();
                    // redirect
                    $this->setFlashMessage('success', $attachment->messages('success', 'import'));
                    return redirect($this->getRoute('list'));
                } catch (\Exception $e) {
                    DB::rollback();
                    return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('list'), $e);
                }
            } else {
                $errors = $attachment->errors();
                return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
            }
        }
    }

    /**
     * Download AK.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadAK(Request $request)
    {
        $student = \Auth::guard('student')->user();
        $ak = $student->attachments()->where('type', AttachmentType::AktaKelahiran)->first();
        
        if ($ak == null) {
            abort(404);
        }

        try {
            $filePath = $this->getStudentFileFolderPath($student).$ak->file_name;

            return Response::download($filePath, $ak->file_name);
        } catch (\Exception $e) {
            $attachment = new Attachment;
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('list'), new \Exception($attachment->messages('fileNotFoundOnDirectory')));
            
        }
    }

    /**
     * Upload Ijazah SMA.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadIjazahSMA(Request $request)
    {
        if ($request->has('ijazahSMAUploader')) {
            $attachment = new Attachment;
            $student = \Auth::guard('student')->user();
            $ijazahSMA = $student->attachments()->where('type', AttachmentType::IjazahSMA)->first();
            
            $data = Input::all();
            $data['student'] = $student->id;

            if ($attachment->validateWithImageRules($data, 'ijazahSMA', $attachment->messages('validation'))) {
                // read file and store
                DB::beginTransaction();
                try {
                    // delete existing image
                    if ($ijazahSMA != null) {
                        $ijazahSMA->delete();
                        unlink($this->getStudentFileFolderPath($student).$ijazahSMA->file_name);
                    }

                    //store
                    $image = $data['ijazahSMAUploader'];
                    // $imageName = time().$image->getClientOriginalName();
                    $imageName = $student->npm.'-IJAZAHSMA-'.time().'-'.(str_replace(' ', '', $image->getClientOriginalName()));

                    // move to folder
                    $image->move($this->getStudentFileFolderPath($student), $imageName);

                    $attachment->name = $imageName;
                    $attachment->type = AttachmentType::IjazahSMA;
                    $attachment->file_name = $imageName;
                    $attachment->student_id = $data['student'];
                    $attachment->save();
                    $this->updateStudentMustFillAttachmentField();

                    DB::commit();
                    // redirect
                    $this->setFlashMessage('success', $attachment->messages('success', 'import'));
                    return redirect($this->getRoute('list'));
                } catch (\Exception $e) {
                    DB::rollback();
                    return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('list'), $e);
                }
            } else {
                $errors = $attachment->errors();
                return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
            }
        }
    }

    /**
     * Download Ijazah SMA.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadIjazahSMA(Request $request)
    {
        $student = \Auth::guard('student')->user();
        $ijazahSMA = $student->attachments()->where('type', AttachmentType::IjazahSMA)->first();
        
        if ($ijazahSMA == null) {
            abort(404);
        }

        try {
            $filePath = $this->getStudentFileFolderPath($student).$ijazahSMA->file_name;

            return Response::download($filePath, $ijazahSMA->file_name);
        } catch (\Exception $e) {
            $attachment = new Attachment;
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('list'), new \Exception($attachment->messages('fileNotFoundOnDirectory')));
            
        }
    }

    /**
     * Upload Ijazah S1.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadIjazahS1(Request $request)
    {
        if ($request->has('ijazahS1Uploader')) {
            $attachment = new Attachment;
            $student = \Auth::guard('student')->user();
            $ijazahS1 = $student->attachments()->where('type', AttachmentType::IjazahS1)->first();
            
            $data = Input::all();
            $data['student'] = $student->id;

            if ($attachment->validateWithImageRules($data, 'ijazahS1', $attachment->messages('validation'))) {
                // read file and store
                DB::beginTransaction();
                try {
                    // delete existing image
                    if ($ijazahS1 != null) {
                        $ijazahS1->delete();
                        unlink($this->getStudentFileFolderPath($student).$ijazahS1->file_name);
                    }

                    //store
                    $image = $data['ijazahS1Uploader'];
                    // $imageName = time().$image->getClientOriginalName();
                    $imageName = $student->npm.'-IJAZAHS1-'.time().'-'.(str_replace(' ', '', $image->getClientOriginalName()));

                    // move to folder
                    $image->move($this->getStudentFileFolderPath($student), $imageName);

                    $attachment->name = $imageName;
                    $attachment->type = AttachmentType::IjazahS1;
                    $attachment->file_name = $imageName;
                    $attachment->student_id = $data['student'];
                    $attachment->save();

                    DB::commit();
                    // redirect
                    $this->setFlashMessage('success', $attachment->messages('success', 'import'));
                    return redirect($this->getRoute('list'));
                } catch (\Exception $e) {
                    DB::rollback();
                    return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('list'), $e);
                }
            } else {
                $errors = $attachment->errors();
                return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
            }
        }
    }

    /**
     * Download Ijazah S1.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadIjazahS1(Request $request)
    {
        $student = \Auth::guard('student')->user();
        $ijazahS1 = $student->attachments()->where('type', AttachmentType::IjazahS1)->first();
        
        if ($ijazahS1 == null) {
            abort(404);
        }

        try {
            $filePath = $this->getStudentFileFolderPath($student).$ijazahS1->file_name;

            return Response::download($filePath, $ijazahS1->file_name);
        } catch (\Exception $e) {
            $attachment = new Attachment;
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('list'), new \Exception($attachment->messages('fileNotFoundOnDirectory')));
            
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
                return route('student.attachment');

            default:
                # code...
                break;
        }
    }

    /**
     * Update student must_fill_attachment field.
     *
     * @param empty
     *
     * @return string
     */
    public function updateStudentMustFillAttachmentField()
    {
        $student = \Auth::guard('student')->user();
        $ktp = $student->attachments()->where('type', AttachmentType::KTP)->first();
        $kartuKeluarga = $student->attachments()->where('type', AttachmentType::KartuKeluarga)->first();
        $ijazahSMA = $student->attachments()->where('type', AttachmentType::IjazahSMA)->first();
        $aktaKelahiran = $student->attachments()->where('type', AttachmentType::AktaKelahiran)->first();

        if ($ktp != null && $kartuKeluarga != null && $ijazahSMA != null && $aktaKelahiran != null) {
            $student->must_fill_attachment = false;
            $student->save();
        }
    }
}
