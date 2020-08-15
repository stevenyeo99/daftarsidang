<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BaseModel extends Model
{
    /**
     * Validate data with optional custom messages.
     */
    public function validate($model, array $data, array $customMessage = [])
    {
        $validator = Validator::make($data, $this->rules($model), $customMessage);

        if ($validator->fails())
        {
            $this->errors = $validator->errors();
            return false;
        }
        return true;
    }

    /**
     * Get the errors from model validation.
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Get the validation rules when validating image.
     */
    public function imageRules(string $key = null)
    {
        switch ($key) {
            case 'ktp':
                return [
                    'ktpUploader' => 'required|mimes:jpeg,png,jpg,svg,pdf|max:1024', // for max 1mb
                ];
            case 'kartuKeluarga':
                return [
                    'kartuKeluargaUploader' => 'required|mimes:jpeg,png,jpg,svg,pdf|max:1024', // for max 1mb
                ];
            case 'aktaKelahiran':
                return [
                    'aktaKelahiranUploader' => 'required|mimes:jpeg,png,jpg,svg,pdf|max:1024', // for max 1mb
                ];
            case 'ijazahSMA':
                return [
                    'ijazahSMAUploader' => 'required|mimes:jpeg,png,jpg,svg,pdf|max:1024', // for max 1mb
                ];
            case 'ijazahS1':
                return [
                    'ijazahS1Uploader' => 'required|mimes:jpeg,png,jpg,svg,pdf|max:1024', // for max 1mb
                ];
            
            default:
                return [
                    'fileUploader' => 'required|mimes:jpeg,png,jpg,svg,pdf|max:1024', // for max 1mb
                ];
        }
    }

    /**
     * Validate when add records by import excel.
     */
    public function validateWithImageRules(array $data, string $key = null, array $customMessage = [])
    {
        $validator = Validator::make($data, $this->imageRules($key), $customMessage);

        if ($validator->fails())
        {
            $this->errors = $validator->errors();
            return false;
        }
        return true;
    }
}
