<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MasterController;
use App\Models\HardcoverKP;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Maatwebsite\Excel\Facades\Excel;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Input;
use DateTime;
use Carbon\Carbon;

class HardcoverKPController extends MasterController
{
    /**
     * Create a new controller instance
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:lib');
    }

    /**
     * show hardcover list
     * 
     */
    public function index() {
        $breadcrumbs = $this->getBreadCrumbs('hardcoverKPList');
        $title = $this->getTitle('hardcoverKPList');
        $sub_title = $this->getSubTitle('hardcoverKPList');
        $hardcover_css = "active";
        $hardcover_kp_css = "active";

        return view('library.hardcover.kp.hardcover_kp')
            ->with('breadcrumbs', $breadcrumbs)
            ->with('title', $title)
            ->with('sub_title', $sub_title)
            ->with('hardcover_css', $hardcover_css)
            ->with('hardcover_kp_css', $hardcover_kp_css);
    }

    /**
     * retrieve data
     * 
     */
    public function getListOfHardcoverKP(Request $request) {
        $requests = HardcoverKP::join('library_user', 'hardcover_kp.create_user_id', '=', 'library_user.id')
                    ->select([
                        'hardcover_kp.*',
                        'library_user.username AS created_by_user',
                    ]);
        
        return Datatables::of($requests)
                        ->setRowClass(function() {
                            return "custom-tr-text-ellipsis";
                        }) 
                        ->filterColumn('created_by_user', function($query, $keyword) {
                            $query->whereRaw("CONCAT(users.username, '-', users.username) like ?", ["%{$keyword}%"]);
                        })  
                        ->editColumn('tanggal_submit', function($requests) {
                            return [
                                'display' => date('d-M-Y', strtotime($requests->tanggal_submit)),
                                'date' => strtotime($requests->tanggal_submit)
                            ];
                        })
                        ->filterColumn('tanggal_submit', function($query, $keyword) {
                            $keyword = date('Y-m-d', strtotime($keyword));
                            $query->whereRaw("CAST(tanggal_submit AS DATE) = ?", ["{$keyword}"]);
                        })
                        ->editColumn('tanggal_validasi', function($requests) {
                            return [
                                'display' => date('d-M-Y', strtotime($requests->tanggal_validasi)),
                                'date' => strtotime($requests->tanggal_validasi)
                            ];
                        })   
                        ->filterColumn('tanggal_validasi', function($query, $keyword) {
                            $keyword = date('Y-m-d', strtotime($keyword));
                            $query->whereRaw("CAST(tanggal_validasi AS DATE) = ?", ["{$keyword}"]);
                        })                                             
                        ->addColumn('actions', function(HardcoverKP $HardcoverKP) {
                            return $this->getActionsDelete($HardcoverKP);
                        })
                        ->rawColumns(['actions'])
                        ->make(true);
    }

    /**
     * import hard cover kp data and store to the database
     */
    public function doImportHardCoverKPData(Request $request) {
        $user = \Auth::guard()->user();
        $user_id = $user->id;
        $hcKP = new HardcoverKP;

        $this->validate($request, [
            'hardcover_kp_import' => 'required|mimes:xlsx,xls,csv|max:2000',
        ], ['required' => 'file excel wajib dipilih untuk melakukan import data!', 'mimes' => 'file wajib ber-extensi xlsx, xls, csv', 'max' => 'File yang diupload maximal 2mb.']);

        // if file multipart is detected
        if($request->hasFile('hardcover_kp_import')) 
        {    
            // check the file type must be excel(xlsx, xls, csv)    
            $extension = File::extension($request->file('hardcover_kp_import')->getClientOriginalName());
            if($extension == "xlsx" || $extension == "xls" || $extension == "csv") {

                // ready the excel file content
                // first row will be detect as header of the file like title
                $path = $request->file('hardcover_kp_import')->getRealPath();
                $data = Excel::load($path, function($reader) {})->get();
                
                $insert = [];
                $errors = null;
                if(!empty($data) && $data->count()) {
                    $index = 0;
                    // start loop for each data that are on excel
                    // this array for which row that need to be messageBag on error message
                    $arr_namamahasiswa_required = [];
                    $arr_namamahasiswa_max = [];
                    $arr_npm_required = [];
                    $arr_npm_numeric = [];
                    $arr_npm_min = [];
                    $arr_prodi_required = [];
                    $arr_prodi_max = [];
                    $arr_namapembimbing_required = [];
                    $arr_namapembimbing_max = [];
                    $arr_tanggal_submit = [];
                    $arr_tanggal_validasi = [];
                    $allErrors = 0;

                    // if no data to be imported just throw error message to user
                    if(count($data) == 0) {
                        $no_data_message = new MessageBag;
                        $no_data_message.add('no_data', 'file tidak terdapat data, kembali cek file tersebut.');
                        return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors); 
                    }
                    // set locale date name
                    $indonesia = array('januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember');
                    $english = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
                    foreach($data as $key => $value) 
                    {                       
                        $rowNumber = $index+1;
                        // on excel data the date must be format example (25-Oct-2019)
                        $tanggal_submit = $value->tanggal_submit;
                        try {
                            $tanggal_submit = str_ireplace($indonesia, $english, $tanggal_submit);
                            $tanggal_submit = date_create_from_format('j F Y', $tanggal_submit);
                        } catch(\Exception $e) {
                            $tanggal_submit = null;
                        }
                        if($tanggal_submit != null) {
                            $tanggal_submit = $tanggal_submit->format('Y-m-d');
                        }
                        $tanggal_validasi = $value->tanggal_validasi;
                        try {
                            $tanggal_validasi = str_ireplace($indonesia, $english, $tanggal_validasi);
                            $tanggal_validasi = date_create_from_format('j F Y', $tanggal_validasi);
                        } catch(\Exception $e) {
                            $tanggal_validasi = null;
                        }
                        if($tanggal_validasi != null) {
                            $tanggal_validasi = $tanggal_validasi->format('Y-m-d');
                        }

                        // list the data body into an array
                        $insert[$index]= [
                            'nama_mahasiswa' => $value->nama,
                            'npm' => $value->npm,
                            'prodi' => $value->prodi,
                            'nama_pembimbing' => $value->pembimbing,
                            'tanggal_submit' => $tanggal_submit,
                            'tanggal_validasi' => $tanggal_validasi,
                        ];
                        
                        // validate the data if not valid will push into an array of that contain errors on specific row
                        if(!$hcKP->validate($hcKP, $insert[$index], $hcKP->messages('validation', null))) 
                        {
                            $errors = $hcKP;
                            $errorMessageObject = $errors->errors();
                            $arrayErrorMessageObject = $errorMessageObject->all();
                            
                            foreach($arrayErrorMessageObject as $k => $v) 
                            {
                                // case for what error message to be print and what line is the error message
                                switch($v) {
                                    case 'nama_mahasiswa.required':
                                        array_push($arr_namamahasiswa_required, $rowNumber);
                                        break;
                                    case 'nama_mahasiswa.max':
                                        array_push($arr_namamahasiswa_max, $rowNumber);
                                        break;
                                    case 'npm.required':
                                        array_push($arr_npm_required, $rowNumber);
                                        break;
                                    case 'npm.numeric':
                                        array_push($arr_npm_numeric, $rowNumber);
                                        break;
                                    case 'npm.min':
                                        array_push($arr_npm_min, $rowNumber);
                                        break;
                                    case 'prodi.required':
                                        array_push($arr_prodi_required, $rowNumber);
                                        break;
                                    case 'prodi.max':
                                        array_push($arr_prodi_max, $rowNumber);
                                        break;
                                    case 'nama_pembimbing.required':
                                        array_push($arr_namapembimbing_required, $rowNumber);
                                        break;
                                    case 'nama_pembimbing.max':
                                        array_push($arr_namapembimbing_max, $rowNumber);
                                        break;
                                    case 'tanggal_submit.required':
                                        array_push($arr_tanggal_submit, $rowNumber);
                                        break;
                                    case 'tanggal_validasi.required':
                                       array_push($arr_tanggal_validasi, $rowNumber);
                                        break;
                                    default:
                                        break;
                                } // end for switch case
                                $allErrors++;
                            } // end for loop of error message one                                   
                        } // end if of validate error one
                        $index++;
                    } // end the loop

                    // print messagebag error if got an error
                    if($allErrors > 0) 
                    {
                        $errors = new MessageBag();
                        if(count($arr_namamahasiswa_required) > 0) {
                            $value = implode(', ', $arr_namamahasiswa_required);
                            $errors->add('namamahasiswa_required', 'Pada barisan nomor ' . $value . ' kolum nama mahasiswa tidak diperbolehkan kosong atau tidak sesuai dengan format.');
                        }

                        if(count($arr_namamahasiswa_max) > 0) {
                            $value = implode(', ', $arr_namamahasiswa_max);
                            $errors->add('namamahasiswa_max', 'Pada barisan nomor ' . $value . ' kolum nama mahasiswa tidak boleh melebihi 255 huruf.');
                        }

                        if(count($arr_npm_required) > 0) {
                            $value = implode(', ', $arr_npm_required);
                            $errors->add('npm_required', 'Pada barisan nomor ' . $value . ' kolum npm tidak diperbolehkan kosong.');
                        }

                        if(count($arr_npm_numeric) > 0) {
                            $value = implode(', ', $arr_npm_numeric);
                            $errors->add('npm_numeric', 'Pada barisan nomor ' . $value . ' kolum npm harus berbentuk angka.');
                        }

                        if(count($arr_npm_min) > 0) {
                            $value = implode(', ' , $arr_npm_min);
                            $errors->add('npm_min', 'Pada barisan nomor ' . $value . ' kolum npm minimal 7 angka.');
                        }

                        if(count($arr_prodi_required) > 0) {
                            $value = implode(', ', $arr_prodi_required);
                            $errors->add('prodi_required', 'Pada barisan nomor ' . $value . ' kolum prodi tidak diperbolehkan kosong.');
                        }

                        if(count($arr_prodi_max) > 0) {
                            $value = implode(', ', $arr_prodi_max);
                            $errors->add('prodi_max', 'Pada barisan nomor ' . $value . ' kolum prodi tidak boleh melebihi 255 huruf.');
                        }

                        if(count($arr_namapembimbing_required) > 0) {
                            $value = implode(', ', $arr_namapembimbing_required);
                            $errors->add('namapembimbing_required', 'Pada barisan nomor ' . $value . ' kolum nama pembimbing tidak diperbolehkan kosong.');
                        }

                        if(count($arr_namapembimbing_max) > 0) {
                            $value = implode(', ', $arr_namapembimbing_max);
                            $errors->add('namapembimbing_max', 'Pada barisan nomor ' . $value . ' kolum nama pembimbing tidak boleh melebihi 255 huruf.');
                        }

                        if(count($arr_tanggal_submit) > 0) {
                            $value = implode(', ', $arr_tanggal_submit);
                            $errors->add('tanggal_submit', 'Pada barisan nomor ' . $value . ' tidak diperbolehkan kosong atau bukan format(dd/mm/yyyy) pada file excel.');
                        } 

                        if(count($arr_tanggal_validasi) > 0) {
                            $value = implode(', ', $arr_tanggal_validasi);
                            $errors->add('tanggal_validasi', 'Pada barisan nomor ' . $value . ' tidak diperbolehkan kosong atau bukan format(dd/mm/yyyy) pada file excel.');
                        }
                        
                        return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);    
                    } // end of if statement that contain error
                    else // this is where insert and update statement begin
                    {
                        DB::beginTransaction();
                        try 
                        {
                            // 1. check npm is exist or not on db
                            // 2. if exist update-not exist insert
                            for($i = 0; $i < count($insert); $i++) 
                            {
                                // declare each variable
                                $nama_mahasiswa = $insert[$i]['nama_mahasiswa'];
                                $npm = intval($insert[$i]['npm']);
                                $prodi = $insert[$i]['prodi'];
                                $nama_pembimbing = $insert[$i]['nama_pembimbing'];
                                $tanggal_submit = $insert[$i]['tanggal_submit'];
                                $tanggal_validasi = $insert[$i]['tanggal_validasi'];
                                
                                $hardcover_kp = HardcoverKP::where(['npm' => $npm])->first();                                                              
                                
                                // 2.1 do insert
                                if($hardcover_kp == null) 
                                {
                                    $kp = new HardcoverKP;
                                    $kp->nama_mahasiswa = $nama_mahasiswa;
                                    $kp->npm = $npm;
                                    $kp->prodi = $prodi;
                                    $kp->nama_pembimbing = $nama_pembimbing;
                                    $kp->tanggal_submit = $tanggal_submit;
                                    $kp->tanggal_validasi = $tanggal_validasi;
                                    $kp->create_user_id = $user_id;
                                    $kp->created_at = now();
                                    $kp->save();
                                }
                                // 2.1 do update
                                else 
                                {
                                    $id = $hardcover_kp->id; 
                                    $hardcover_kp = HardcoverKP::find($id);
                                    $hardcover_kp->nama_mahasiswa = $nama_mahasiswa;                                    
                                    $hardcover_kp->npm = $npm;                                   
                                    $hardcover_kp->prodi = $prodi;                                   
                                    $hardcover_kp->nama_pembimbing = $nama_pembimbing;                                    
                                    $hardcover_kp->tanggal_submit = $tanggal_submit;
                                    $hardcover_kp->tanggal_validasi = $tanggal_validasi;
                                    $hardcover_kp->last_modify_user_id = $user_id;                                    
                                    $hardcover_kp->updated_at = now();
                                    $hardcover_kp->save();
                                }                                
                            }
                            DB::commit();
                            // redirect
                            $this->setFlashMessage('success', $hcKP->messages('success', 'upload'));
                            return redirect($this->getRoute('list'));
                        }
                        catch(\Exception $e) 
                        {
                            DB::rollback();
                            return $this->parseErrorAndRedirectToRouteWithErrors($this->getRoute('list'), $e);
                        }                
                    }
                               
                    } // end of statement              
                }
            }
        }
        
    // method for doing delete specific hardcover data
    public function destroy(HardcoverKP $hardcoverKP) {
        try {
            $hardcoverKP->delete();

            // redirect
            $this->setFlashMessage('success', $hardcoverKP->messages('success', 'delete'));
            return redirect($this->getRoute('list'));
        } catch(\Exception $e) {
            $errors = new MessageBag([$hardcoverKP->messages('failDelete')]);
            return $this->redirectToRouteWithErrorsAndInputs($this->getRoute('list'), $errors);
        }
    }

    /**
     * download request excel
     */
    public function downloadRequestExcel(Request $request) {
        $data = Input::all();
        
        $result = HardcoverKP::orderBy('tanggal_submit', 'DESC');

        $result = $result->get();
        $result_array = array();

        // set locale date name
        setlocale(LC_TIME, 'id-ID');

        if(count($result) > 0) {
            foreach($result as $key => $res) {
                $result_array[$key]['Nama'] = $res->nama_mahasiswa;
                $result_array[$key]['NPM'] = $res->npm;
                $result_array[$key]['Prodi'] = $res->prodi;
                $result_array[$key]['Pembimbing'] = $res->nama_pembimbing;
                $result_array[$key]['Tanggal Submit'] = strftime('%e %B %Y', strtotime($res->tanggal_submit));
                $result_array[$key]['Tanggal Validasi'] = strftime('%e %B %Y', strtotime($res->tanggal_validasi));
            }

            Excel::create('Laporan Hardcover KP', function($excel) use($result_array) {
                $excel->sheet('Sheet 1', function($sheet) use ($result_array) {
                    // fill the XLS with data
                    $sheet->fromArray($result_array, null, 'A1', true);

                    // Set row height
                    $sheet->setHeight(1, 25);

                    // Manipulate Row
                    $sheet->row(1, function($row) {
                        $row->setFontWeight('bold');
                        $row->setAlignment('center');
                        $row->setValignment('center');
                    });

                    // Freeze first row
                    $sheet->freezeFirstRow();
                });
            })->export('xlsx');
        }
    }
    
    /**
     * get all routes
     */
    public function getRoute($key, $id = null) {
        switch($key) {
            case 'list':
                return route('hardcover_kp');
            case 'destroy':
                return route('hardcover_kp.destroy', $id);
            default:
                break;
        }
    }
}
