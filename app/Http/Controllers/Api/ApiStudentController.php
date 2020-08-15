<?php

namespace App\Http\Controllers\Api;

use App\Enums\RequestStatus;
use App\Models\Student;
use App\Repository\Transformers\StudentTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Http\Response as Res;

class ApiStudentController extends ApiController
{
    /**
     * @var \App\Repository\Transformers\StudentTransformer
     * 
     */
    protected $studentTransformer;

    public function __construct(
        StudentTransformer $studentTransformer
    )
    {
        $this->studentTransformer = $studentTransformer;
    }
    
    /**
     * @description: Api get students that already "sidang" method
     * @author: Jordy Julianto
     * @param: null
     * @return: Json String response
     */
    public function getStudentAlreadySessionedList(Request $request)
    {
        $students = new Student;

        $data = $request->all();

        $validator = Validator::make($data, $students->getStudentAlreadySessionedListRules($students), $students->messages('validation.get.already.sessioned.list'));

        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->respondValidationError($this->getBeautyMessage('validation.failed'), $errors);
        } else {
            try {
		        $students = Student::join('requests', 'requests.student_id', '=', 'students.id')
		        				   ->join('session_statuses', 'session_statuses.student_id', '=', 'students.id')
			                       ->select([
			                          'students.id',
			                          'students.npm',
			                          'students.name',
			                          'students.email',
			                          'students.study_program_id',
			                          'requests.title AS request_title',
			                          'requests.status AS request_status',
			                          'requests.type AS request_type',
			                          'requests.mentor_name AS request_mentor_name',
			                          'session_statuses.date AS session_date',
			                       ])
			                       ->where('requests.type', '=', $request['request_type'])
			                       ->where('requests.status', '=', RequestStatus::Accept)
			                       ->where('session_statuses.type', '=', $request['request_type'])
			                       ->groupBy('students.id')
			                       ->groupBy('students.npm')
			                       ->groupBy('students.name')
			                       ->groupBy('students.email')
			                       ->groupBy('students.study_program_id')
			                       ->groupBy('requests.title')
			                       ->groupBy('requests.status')
			                       ->groupBy('requests.type')
			                       ->groupBy('requests.mentor_name')
			                       ->groupBy('session_statuses.date');

                $students = $students->when(isset($request['program_study_code']) && !is_null($request['program_study_code']), function ($students) use ($request) {
                    $students = $students->join('study_programs', 'students.study_program_id', '=', 'study_programs.id')
                    					 ->select([
                    					 ])
					                     ->select([
					                        'students.id',
					                        'students.npm',
					                        'students.name',
					                        'students.email',
					                        'students.study_program_id',
					                        'requests.title AS request_title',
					                        'requests.status AS request_status',
					                        'requests.type AS request_type',
					                        'requests.mentor_name AS request_mentor_name',
					                        'session_statuses.date AS session_date',
                    					 	'study_programs.name AS study_program_name',
					                     ])
                    					 ->where('study_programs.code', '=', $request['program_study_code'])
                    					 ->groupBy('study_programs.name');

                    return $students;
                });

                $students = $students->when(isset($request['generation']) && !is_null($request['generation']), function ($students) use ($request) {
                	return $students->get()->filter(function ($student, $key) use ($request) {
	                    return strtolower(substr($student->npm, 0, 2)) == strtolower($request['generation']);
                	});
                });

                if (!is_a($students, 'Illuminate\Database\Eloquent\Collection')) {
	                $students = $students->get();
                }

                $students->transform(function ($student) {
                    return $this->studentTransformer->transform($student);
                });

                $this->setStatusCode(Res::HTTP_OK);
                return $this->respond([
                    'status' => 'success',
                    'status_code' => $this->getStatusCode(),
                    'message' => $this->getBeautyMessage('get.already.sessioned.students.success'),
                    'data' => $students
                ]);
            }
            catch(\Exception $e) {
            	dd($e);
                return $this->respondInternalError($this->getBeautyMessage('error.occurred'));
            }
        }
    }
}
