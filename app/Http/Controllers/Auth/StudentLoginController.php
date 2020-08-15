<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Gender;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudyProgram;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;

class StudentLoginController extends Controller
{
    use RedirectsUsers;

    /**
     * Where to redirect students after login.
     *
     * @var string
     */
    protected $redirectTo = '/student/request/kp';

    /**
     * [URI ]Where to make post request to get student's information.
     *
     * @var string
     */
    protected $studentAPIEndPoint = 'http://apps.uib.ac.id/portal/api/v1/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:student')->except('logout');
    }

    /**
     * Show the application's login form of student.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.student-login');
    }

    /**
     * Handle a login request of student to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // Validate the form data
        $this->validate($request, [
            'npm'   => 'required|numeric|digits_between:7,10',
            'password' => 'required'
        ]);

        $client = new Client();
        $data = Input::all();

        DB::beginTransaction();
        try {
            $existingStudent = Student::where('npm', $request->npm)->first();

            $response = $client->request('POST', $this->studentAPIEndPoint, [
                'json' => [
                    'username' => $request->npm,
                    'password' => $request->password
                ]
            ]);

            // dd(json_decode($response->getBody()), $request->password, bcrypt($request->password));
            $responseBody = json_decode($response->getBody());
            if ($existingStudent == null) {
                    $student = new Student();
                    $student->npm = $responseBody->id; // store portal's npm
                    $student->name = $responseBody->name; // store portal's name
                    $student->birthdate = $responseBody->bday; // store portal's bday
                    $student->religion = $responseBody->religion; // store portal's religion
                    $student->address = $responseBody->address; // store portal's address
                    $student->phone_number = $responseBody->phone; // store portal's phone
                    $student->password = bcrypt($request->password);

                    if ($responseBody->gender == "L") { // store portal's gender
                        $student->sex = Gender::Male;
                    } else {
                        $student->sex = Gender::Female;
                    }

                    $studyProgram = StudyProgram::where('name', $responseBody->major)->first();
                    if ($studyProgram != null) {
                        $student->study_program_id = $studyProgram->id; // store portal's major
                    }
                    
                    $student->save();
                    DB::commit();
            }

            // update student's credentials everytime student login to sync portal's credentials
            $existingStudent->npm = $responseBody->id; // store portal's npm
            $existingStudent->name = $responseBody->name; // store portal's name
            $existingStudent->password = bcrypt($request->password);
            $existingStudent->save();
            DB::commit();

        } catch (RequestException $e) {
            DB::rollback();
            // dd('pertama', $e);
            if ($e->getResponse() == null) {
                return $this->sendFailedNetworkResponse($request);
            }

            // Catch all 4XX errors 
            // To catch exactly error 400 use 
            if ($e->getResponse()->getStatusCode() == '400') {
                // if unsuccessful, then redirect back to the login with the form data
                return $this->sendFailedLoginResponse($request);
            }

            // You can check for whatever error status code you need 
        } catch (\Exception $e) {
            DB::rollback();
            // dd('kedua', $e);
            // There was another exception.
            // if unsuccessful, then redirect back to the login with the form data
            return $this->sendFailedLoginResponse($request);
        }
        // dd($client, $data, $existingStudent);
        
        // Attempt to log the student in
        if (Auth::guard('student')->attempt(['npm' => $request->npm, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();
            // if successful, then redirect to their intended location
            return redirect()->intended($this->redirectPath());
        }


        // if unsuccessful, then redirect back to the login with the form data
        return $this->sendFailedLoginResponse($request);
    }


    /**
     * Log the student out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();

        return redirect()->route('student.login');
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'npm' => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the failed network response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedNetworkResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'npm' => 'Error when connecting to uib\'s portal',
        ]);
    }
}
