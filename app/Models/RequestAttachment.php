<?php

namespace App\Models;

use App\Models\BaseModel;

class RequestAttachment extends BaseModel
{
    protected $table = 'request_attachment';

    public $timestamps = false;

    protected $errors;

    public function request() {
        return $this->belongsTo('App\Models\Request', 'request_id');
    }
}