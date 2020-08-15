<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MasterController;
use App\Models\TurnitinFile;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use App\Models\Request as CustomRequest;

class TurnitinFileController extends MasterController
{
    /**
     * Create a new controller instance
     * 
     */
    public function __construct() {
        $this->middleware('auth:lib');
    }

    public function index() {
        $breadcrumbs = $this->getBreadCrumbs('turnitinKPList');
        $title = $this->getTitle('turnitinKPList');
        $sub_title = $this->getSubTitle('turnitinKPList');
        $turnitin_css = "active";
        $turnitin_kp_css = "active";

        return view('library.turnitin.kp.turnitin_kp')
        ->with('breadcrumbs', $breadcrumbs)
        ->with('title', $title)
        ->with('sub_title', $sub_title)
        ->with('turnitin_css', $turnitin_css)
        ->with('turnitin_kp_css', $turnitin_kp_css);
    }

    /**
     * retrieve data
     * 
     */
    public function getListOfTurnitinKPFiles(Request $request) {
        $requests = TurnitinFile::join('library_user', 'turnitin_files.uploaded_by', '=', 'library_user.id')
            ->select([
                'turnitin_files.*',
                'library_user.username AS uploaded_by_user_name'
            ])->orderBy('turnitin_files.uploaded_on', 'desc');
        
        return Datatables::of($requests)
                ->setRowClass(function() {
                    return "custom-tr-text-ellipsis";
                })
                ->editColumn('uploaded_on', function($requests) {
                    return [
                        'display' => date('d-M-Y', strtotime($requests->uploaded_on)),
                        'date' => strtotime($requests->uploaded_on)
                    ];
                })
                ->filterColumn('uploaded_on', function($query, $keyword) {
                    $keyword = date('Y-m-d', strtotime($keyword));
                    $query->whereRaw("CAST(uploaded_on AS DATE) = ?", ["{$keyword}"]);
                }) 
                ->filterColumn('uploaded_by_user_name', function($query, $keyword) {
                    $query->whereRaw("CONCAT(library_user.username, '-', library_user.username) like ?", ["%{$keyword}%"]);
                })
                ->addColumn('actions', function(TurnitinFile $turnitin_file) {
                    return $this->getActionsButtons($turnitin_file);
                })
                ->rawColumns(['actions'])
                ->make(true);
    }

    /**
     * get turnitin kp blade view
     * 
     */
    public function create() {
        $breadcrumbs = $this->getBreadCrumbs('turnitinKPCreate');
        $title = $this->getTitle('turnitinKPCreate');
        $sub_title = $this->getSubTitle('turnitinKPCreate');
        $turnitin_css = "active";
        $turnitin_kp_css = "active";
        $btn_label = "Buat";

        return view('library.turnitin.kp.turnitin_upload')
            ->with('breadcrumbs', $breadcrumbs)
            ->with('title', $title)
            ->with('sub_title', $sub_title)
            ->with('turnitin_css', $turnitin_css)
            ->with('turnitin_kp_css', $turnitin_kp_css)
            ->with('btn_label', $btn_label);
    }

    /**
     * store turnitin file data(new)
     * 
     */
    public function store(Request $request) {
        $user = \Auth::guard()->user();
        $user_id = $user->id;
        $turnitin_files = new TurnitinFile;         
        // get all post data element
        $data = Input::all();
        $file = $data['file'];
        $turnitin_files->npm = $data['npm'];
        $turnitin_files->type = $data['type'];
        $turnitin_files->file = $file;
        if($turnitin_files->validate($turnitin_files, $data, $turnitin_files->messages('validation'))) {
            
            // read file and store
            DB::beginTransaction();
            try {
                $fileName = $data['npm'].'-KP-'.time().'-'.(str_replace(' ', '', $file->getClientOriginalName()));
                                    
                // set object to be save                
                $turnitin_files->file_name = $fileName;
                $turnitin_files->file_display_name = $file->getClientOriginalName();
                $filePath = $this->getTurnitinFileFolderPath($turnitin_files) . $fileName;
                $turnitin_files->file_path = $filePath;
                $turnitin_files->uploaded_by = $user_id;
                $turnitin_files->uploaded_on = now();
                $turnitin_files->created_at = now();
                // dd($turnitin_files);
                // move to folder 
                $file->move(public_path() . $this->getTurnitinFileFolderPath($turnitin_files), $fileName);
                
                // store the data into db
                $turnitin_files->save();
                DB::commit();
                // redirect
                $this->setFlashMessage('success', $turnitin_files->messages('success', 'create'));
                return redirect($this->getRoute('list'));
            } catch(\Exception $e) {
                DB::rollback();
                return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('create'), $e);
            }
        } else {
            $errors = $turnitin_files->errors();
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('create'), $errors);
        }
    }

    /**
     * for retrieving on modify page
     * 
     */
    public function edit(TurnitinFile $turnitinFile) {
        $breadcrumbs = $this->getBreadCrumbs('turnitinKPEdit');
        $title = $this->getTitle('turnitinKPEdit');
        $sub_title = $this->getSubTitle('turnitinKPEdit');
        $turnitin_css = "active";
        $turnitin_kp_css = "active";
        $btn_label = "Ubah";

        return view('library.turnitin.kp.turnitin_upload', compact('turnitinFile', 'breadcrumbs', 'title', 'sub_title', 'turnitin_css', 'turnitin_kp_css', 'btn_label'));
    }

    /**
     * do update method
     */
    public function update(TurnitinFile $turnitinFile) {
        $user = \Auth::guard()->user();
        $user_id = $user->id;
        // business rule when doesnt change file will just update npm data
        $data = Input::all();
        // for check input file need to upload or not
        $fileUpload = $data['fileEditable'];
        // for file use to be upload
        // begin database transaction
        DB::beginTransaction();
        // try to set up the object property first like npm, id cause it will use on if or else section below
        $turnitinFile->npm = $data['npm'];
        try {
            if($fileUpload == '1') {
                $turnitinFile->file_upload = 'YES';
                if($turnitinFile->validate($turnitinFile, $data, $turnitinFile->messages('validation'))) {
                    // remove the old file
                    unlink(public_path() . $this->getTurnitinFileFolderPath($turnitinFile) . $turnitinFile->file_name);
                    $file = $data['file'];
                    $fileName = $turnitinFile->npm . '-KP-' . time() . '-' . (str_replace('', '', $file->getClientOriginalName()));
                    $turnitinFile->file_name = $fileName;
                    $turnitinFile->file_display_name = $file->getClientOriginalName();
                    $filePath = $this->getTurnitinFileFolderPath($turnitinFile) . $fileName;
                    $turnitinFile->file_path = $filePath;
                    $turnitinFile->uploaded_by = $user_id;
                    $turnitinFile->uploaded_on = now();
                    $turnitinFile->updated_at = now();
                    $file->move(public_path() . $this->getTurnitinFileFolderPath($turnitinFile), $fileName);
                    $turnitinFile->save();
                    DB::commit();
                    $this->setFlashMessage('success', $turnitinFile->messages('success', 'create'));
                    return redirect($this->getRoute('list'));
                } else {
                    $errors = $turnitinFile->errors();
                    return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $turnitinFile->id), $errors);
                }
            } else { // for file 0
                // dd('0');
                // use validate when no file changes section
                $turnitinFile->file_upload = 'NO';
                if($turnitinFile->validate($turnitinFile, $data, $turnitinFile->messages('validationNoFile'))) {
                    $turnitinFile->updated_at = now();
                    $turnitinFile->save();
                } else {
                    $errors = $turnitinFile->errors();
                    return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $turnitinFile->id), $errors);
                }   
            }

            DB::commit();
            // redirect
            $this->setFlashMessage('success', $turnitinFile->messages('success', 'update'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            DB::rollback();
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('edit', $turnitinFile->id), $e);
        }
    }
    
    /**
     * delete data
     */
    public function destroy(TurnitinFile $turnitinFile) {
        try {
            // validate type request kp already exist the data if already cannot be deleted
            $totalRequestExisting = CustomRequest::join('students AS s', 's.id', '=', 'requests.student_id')
                                        ->where('requests.type', 0)->count();
            if($totalRequestExisting != 0) {
                $errors = new MessageBag([$faculty->messages('failDelete')]);
                return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
            }

            unlink(public_path() . $this->getTurnitinFileFolderPath($turnitinFile) . $turnitinFile->file_name);
            $turnitinFile->delete();
            // redirect
            $this->setFlashMessage('success', $turnitinFile->messages('success', 'delete'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            $errors = new MessageBag([$faculty->messages('failDelete')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
    }

    /**
     * Get all routes
     */
    public function getRoute($key, $id = null) {
        switch($key) {
            case 'list':
                return route('turnitin_kp');
            case 'create':
                return route('turnitin_kp.create');
            case 'edit':
                return route('turnitin_kp.edit', $id);
            case 'destroy':
                return route('turnitin_kp.destroy', $id);
            default: 
                break;
        }
    }
    
    // download file turnitin
    public function downloadFileTurnitin($id) {
        $TurnitinFileModel = new TurnitinFile;
        $turnitinFile = $TurnitinFileModel->whereRaw('id = ?', $id)->first();
        if($turnitinFile == null) {
            abort(404);
        }

        try {
            $filePath = $this->getTurnitinFileFolderPath($turnitinFile).$turnitinFile->file_name;
            return Response::download(public_path().$filePath, $turnitinFile->file_display_name);
        } catch(\Exception $e) {
            $turnitinFile = new TurnitinFile;
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('list'), new \Exception($turnitinFile->messages('fileNotFoundOnDirectory')));
        }
    }

    // preview mime file on browser
    public function previewFileTurnitin($id) {
        $turnitinFileModel = new TurnitinFile;
        $turnitinFile = $turnitinFileModel->whereRaw('id = ?', $id)->first();
        if($turnitinFile == null) {
            abort(404);
        }

        try {
            $filePath = $this->getTurnitinFileFolderPath($turnitinFile).$turnitinFile->file_name;
            $contentType = explode(".", $turnitinFile->file_name)[1];
            // jpeg,png,jpg,pdf
            if($contentType == 'jpeg' || $contentType == 'JPEG') {
                $contentType = 'image/jpeg';
            } else if($contentType == 'png' || $contentType == 'PNG') {
                $contentType = 'image/png';
            } else if($contentType == 'jpg' || $contentType == 'JPG') {
                $contentType = 'image/jpg';
            } else if($contentType == 'pdf' || $contentType == 'PDF') {
                $contentType = 'application/pdf';
            }
            return response()->make(file_get_contents(public_path().$filePath), 200, ['Content-Type' => $contentType]);
        } catch(\Exception $e) {
            $turnitinFile = new TurnitinFile;
            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('list'), new \Exception($turnitinFile->messages('fileNotFoundOnDirectory')));
        }
    }

    /**
     * get Turnitin end point folder path
     */
    public function getTurnitinFileFolderPath(TurnitinFile $turnitinFiles) {
        return '/turnitin-attachments/'.$turnitinFiles->type.'/'.$turnitinFiles->npm.'/';
    } 
}
