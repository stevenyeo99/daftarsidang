<?php

namespace App\Models;

use App\Models\BaseModel;

class KartuBimbingan extends BaseModel
{
    protected $table = 'kartu_bimbingan';

    protected $fillable = ['request_id', 'file_name', 'file_display_name', 'file_path', 'uploaded_on'];

    public $timestamps = false;

    protected $errors;

    public function request() {
        return $this->belongsTo('App\Models\Request', 'request_id');
    }
}
