<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MasterController;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use Log;
use App\Models\Hardcover;
use App\Enums\CreationType;
use App\Enums\HardcoverStatus;
use App\Models\StudyProgram;

class AdminHardcoverTesisController extends MasterController
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $breadcrumbs = $this->getBreadCrumbs('hardcoverTesisList');
        $title = $this->getTitle('hardcoverTesisList');
        $sub_title = $this->getSubTitle('hardcoverTesisList');
        $hardcover_css = "active";
        $hardcover_tesis_css = "active";
        $statusHardcover = HardcoverStatus::getDropdownStatus();
        $defaultStatusSelection = HardcoverStatus::Validated;
        return view('admin.hardcover.tesis.hardcover_tesis')
            ->with('breadcrumbs', $breadcrumbs)
            ->with('title', $title)
            ->with('sub_title', $sub_title)
            ->with('hardcover_css', $hardcover_css)
            ->with('hardcover_tesis_css', $hardcover_tesis_css)
            ->with('statusHardcover', $statusHardcover)
            ->with('defaultStatusSelection', $defaultStatusSelection);
    }

    /**
     * get list of hardcover tesis
     */
    public function getListOfHardcoverTesis(Request $request) {

        $requests = Hardcover::where('type', CreationType::Tesis)
            ->orderBy('tanggal_submit', 'DESC')
            ->select('hardcover_mahasiswa.*');

        // set locale date name
        setlocale(LC_TIME, 'id-ID');

        return Datatables::of($requests)
            ->setRowClass(function() {
                return "custom-tr-text-ellipsis";
            }) 
            ->editColumn('tanggal_submit', function($requests) {
                if($requests->tanggal_submit == null) {
                    return [
                        'display' => '',
                        'date' => strtotime($requests->tanggal_submit)
                    ];
                } else {
                    return [
                        'display' => strftime('%e %B %Y', strtotime($requests->tanggal_submit)),
                        'date' => strtotime($requests->tanggal_submit)
                    ];
                }                
            })
            ->filterColumn('tanggal_submit', function($query, $keyword) {
                $keyword = date('Y-m-d', strtotime($keyword));
                $query->whereRaw("CAST(tanggal_submit AS DATE) = ?", ["{$keyword}"]);
            })
            ->editColumn('tanggal_validasi', function($requests) {
                if($requests->tanggal_validasi == null) {
                    return [
                        'display' => '',
                        'date' => strtotime($requests->tanggal_validasi)
                    ];
                } else {
                    return [
                        'display' => strftime('%e %B %Y', strtotime($requests->tanggal_validasi)),
                        'date' => strtotime($requests->tanggal_validasi)
                    ];
                }
                
            })   
            ->filterColumn('tanggal_validasi', function($query, $keyword) {
                $keyword = date('Y-m-d', strtotime($keyword));
                $query->whereRaw("CAST(tanggal_validasi AS DATE) = ?", ["{$keyword}"]);
            })  
            ->editColumn('status', function($requests) {
                return $this->getRecordStatusLabel($requests);
            })           
            ->rawColumns(['status'])                                 
            ->make(true);
    }

    /**
     * retrieve validated status hard cover tesis by api
     */
    public function storeValidateHardcoverTesisByAPI() {
        $client = new Client();

        DB::beginTransaction();

        try {
            // dd($this->API_PASSWORD);
            $response = $client->request('POST', $this->hardcoverAPIEndPoint, [
                'json' => [
                    'type' => 2,
                    'id' => $this->API_ID,
                    'password' => $this->API_PASSWORD,
                    'status' => 8
                ]
            ]);

            $responseBody = json_decode($response->getBody());

            // loop through data to update if exist
            // else insert to table
            // dd($responseBody->data);
            foreach($responseBody->data as $object) 
            {
                $NPM = $object->NPM;
                // check npm already exist on hardcover tesis or not
                // if exist just update it
                // if not insert it
                $hardcover = Hardcover::where('npm', $NPM)
                    ->where('type', CreationType::Tesis)->first();
                // dd($object);
                if(isset($hardcover)) {
                    $hardcover->updated_at = now();
                } else {
                    $hardcover = new Hardcover;
                    $hardcover->created_at = now();
                }
                $hardcover->type = CreationType::Tesis;
                $hardcover->nama_mahasiswa = $object->author;
                $hardcover->npm = $object->NPM;
                $substr_npm_to_studyprogram = substr($object->NPM, 2, 2);
                $prodi = '';
                $studyProgramObject = StudyProgram::where('code', $substr_npm_to_studyprogram)
                                        ->first();
                if(isset($studyProgramObject)) 
                {
                    $prodi = $studyProgramObject->name;    
                }
                $hardcover->prodi = $prodi;
                
                $dospem = '';
                $dospem1 = $object->advisor1;
                $seperateComma = false;
                if($dospem1 != null && $dospem1 != '') {
                    $seperateComma = true;
                    $dospem = $dospem1;
                }

                $dospem2 = $object->advisor2;
                if($dospem2 != null && $dospem != '') {
                    if($seperateComma == true) {
                        $dospem .= ', '. $dospem2;
                    } else {
                        $dospem = $dospem2;
                    }
                }

                $hardcover->nama_pembimbing = $dospem;
                $timeSubmit = strtotime($object->submitDate);
                if($timeSubmit != 0) {
                    $hardcover->tanggal_submit = $object->submitDate;        
                }
                $timeValid = strtotime($object->validDate);
                if($timeValid != 0) {
                    $hardcover->tanggal_validasi = $object->validDate;
                }
                $hardcover->status = HardcoverStatus::Validated;
                $hardcover->save();
            }
            DB::commit();
            Log::info('Completed logic for read validated hardcover tesis api ' . now());
        } catch(\Exception $e) {
            DB::rollback();
            Log::error(now(). ' ' . $e);
        }
    }

    /**
     * retrieve ongoing status hard cover tesis by api
     */
    public function storeOngoingHardcoverTesisByAPI() {
        $client = new Client();

        DB::beginTransaction();

        try {
            $response = $client->request('POST', $this->hardcoverAPIEndPoint, [
                'json' => [
                    'type' => 2,
                    'id' => $this->API_ID,
                    'password' => $this->API_PASSWORD,
                    'status' => 0
                ]
            ]);

            $responseBody = json_decode($response->getBody());

            foreach($responseBody->data as $object) {
                $NPM = $object->NPM;

                $hardcover = Hardcover::where('npm', $NPM)
                    ->where('type', CreationType::Tesis)->first();
                if(isset($hardcover)) {
                    $hardcover->updated_at = now();
                } else {
                    $hardcover = new Hardcover;
                    $hardcover->created_at = now();
                }
                $hardcover->type = CreationType::Tesis;
                $hardcover->nama_mahasiswa = $object->author;
                $hardcover->npm = $object->NPM;
                $substr_npm_to_studyprogram = substr($object->NPM, 2, 2);
                $prodi = '';
                $studyProgramObject = StudyProgram::where('code', $substr_npm_to_studyprogram)
                                        ->first();
                if(isset($studyProgramObject)) {
                    $prodi = $studyProgramObject->name;    
                }
                $hardcover->prodi = $prodi;
                
                $dospem = '';
                $dospem1 = $object->advisor1;
                $seperateComma = false;
                if($dospem1 != null && $dospem1 != '') {
                    $seperateComma = true;
                    $dospem = $dospem1;
                }

                $dospem2 = $object->advisor2;
                if($dospem2 != null && $dospem != '') {
                    if($seperateComma == true) {
                        $dospem .= ', '. $dospem2;
                    } else {
                        $dospem = $dospem2;
                    }
                }
                
                $hardcover->nama_pembimbing = $dospem;
                $timeSubmit = strtotime($object->submitDate);
                if($timeSubmit != 0) {
                    $hardcover->tanggal_submit = $object->submitDate;        
                }
                $timeValid = strtotime($object->validDate);
                if($timeValid != 0) {
                    $hardcover->tanggal_validasi = $object->validDate;
                }
                $hardcover->status = HardcoverStatus::Ongoing;
                $hardcover->save();
            }
            DB::commit();
            Log::info('Completed logic for read ongoing hardcover Tesis api ' . now());
        } catch(\Exception $e) {
            DB::rollback();
            Log::error(now(). ' ' . $e);
        }
    }

    /**
     * download request excel
     */
    public function downloadRequestExcel(Request $request) {
        $data = Input::all();
        $status = $request->txtStatus;
        $result = Hardcover::where('type', CreationType::Tesis)
                    ->orderBy('tanggal_submit', 'DESC');
        if(isset($status)) {
           $result = Hardcover::where('type', CreationType::Tesis)
                    ->where('status', $status)
                    ->orderBy('tanggal_submit', 'DESC'); 
        }         
        
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
                $tanggal_submit = '';
                if($res->tanggal_submit != null) {
                    $tanggal_submit = strftime('%e %B %Y', strtotime($res->tanggal_submit));
                }
                $result_array[$key]['Tanggal Submit'] = $tanggal_submit;
                $tanggal_validasi = '';
                if($res->tanggal_validasi != null) {
                    $tanggal_validasi = strftime('%e %B %Y', strtotime($res->tanggal_validasi));
                }
                $result_array[$key]['Tanggal Validasi'] = $tanggal_validasi;
                $statusValue = 'Proses Sedang Berlansung';
                if($res->status == HardcoverStatus::Validated) {
                    $statusValue = 'Tervalidasi';
                }
                $result_array[$key]['Status'] = $statusValue;
            }

            Excel::create('Laporan Hardcover Tesis', function($excel) use ($result_array) {
                $excel->sheet('Sheet 1', function($sheet) use ($result_array) {
                    // fill the XLS with data
                    $sheet->fromArray($result_array, null, 'A1', true);

                    // Set Row Height
                    $sheet->setHeight(1, 25);

                    // Manipulate Row
                    $sheet->row(1, function($row) {
                        $row->setFontWeight('bold');
                        $row->setAlignment('center');
                        $row->setValignment('center');
                    });

                    // Freeze first Row
                    $sheet->freezeFirstRow();
                });
            })->export('xlsx');
        }

        // redirect
        $hardcoverTesis = new Hardcover;
        $this->setFlashMessage('danger', $hardcoverTesis->messages('emptyExcelRequest'));
        return redirect($hardcoverTesis->getRoute(CreationType::Tesis));
    }

    /**
     * get record status label
     */
    private function getRecordStatusLabel(Hardcover $hardcover) {
        $status = $hardcover->status;
        if($status == HardcoverStatus::Validated) {
            $extra_class = 'success';
        } else if($status == HardcoverStatus::Ongoing) {
            $extra_class = 'warning';
        }

        return "<span class='label label-${extra_class}'>${status}</span>";
    }
}