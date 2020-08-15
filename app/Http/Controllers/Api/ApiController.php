<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response as Res;
use App\Http\Controllers\Controller;
use Response;

/**
 * Class ApiController
 * @package App\Modules\Api\Lesson\Controllers
 */
class ApiController extends Controller
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() { }

    /**
     * @var int
     */
    protected $statusCode = Res::HTTP_OK;

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @param $message
     * @return json response
     */
    public function respondCreated($message, $data=null)
    {
        return $this->respond([
            'status' => 'success',
            'status_code' => Res::HTTP_CREATED,
            'message' => $message,
            'data' => $data
        ]);
    }


    /**
     * @param $message
     * @return json response
     */
    public function respondNotFound($message = 'Not Found!')
    {
        $this->setStatusCode(Res::HTTP_NOT_FOUND);

        return $this->respond([
            'status' => 'error',
            'status_code' => Res::HTTP_NOT_FOUND,
            'message' => $message,
        ]);
    }


    /**
     * @param $message
     * @return json response
     */
    public function respondInternalError($message)
    {
        $this->setStatusCode(Res::HTTP_INTERNAL_SERVER_ERROR);

        return $this->respond([
            'status' => 'error',
            'status_code' => Res::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $message,
        ]);
    }


    /**
     * @param $message, $error
     * @return json response
     */
    public function respondValidationError($message, $errors)
    {
        $this->setStatusCode(Res::HTTP_UNPROCESSABLE_ENTITY);
        
        return $this->respond([
            'status' => 'error',
            'status_code' => Res::HTTP_UNPROCESSABLE_ENTITY,
            'message' => $message,
            'data' => $errors
        ]);
    }

    /**
     * @param $data, string[] $headers
     * @return json response
     */
    public function respond($data, $headers = [])
    {
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @param $message
     * @return json response
     */
    public function respondWithError($message)
    {
        $this->setStatusCode(Res::HTTP_UNAUTHORIZED);
        
        return $this->respond([
            'status' => 'error',
            'status_code' => Res::HTTP_UNAUTHORIZED,
            'message' => $message,
        ]);
    }

    /**
     * @description: get the beautified message
     * @author: Jordy Julianto
     * @param: string $key, string $keyTwo
     * @return: string $message
     */
    protected function getBeautyMessage(string $key, string $keyTwo = null)
    {
        switch ($key) {
            case 'internal.server.error':
                return 'Internal Server error.';

            case 'error.occurred':
                return 'Terjadi kesalahan saat melakukan tindakan!';

            case 'validation.failed':
                return 'Validasi Gagal.';

            case 'error.when.saving':
                return 'Error ketika menyimpan '. $keyTwo .': ';

            case 'get.already.sessioned.students.success':
                return 'Mengambil mahasiswa - mahasiswa yang sudah pernah sidang dengan sukses!';

            default:
                break;
        }
    }
}
