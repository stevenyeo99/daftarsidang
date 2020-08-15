<?php

namespace App\Models;

use App\Models\BaseModel;

class PengesahanAttachment extends BaseModel
{
    protected $table = 'pengesahan_attachment';

    protected $fillable = ['request_id', 'file_name', 'file_display_name', 'file_path', 'uploaded_on'];
    
    // for preventing from timestamp inserted while doing insert or updating
    public $timestamps = false;

    protected $errors;

    public function request() {
        return $this->belongsTo('App\Models\Request', 'request_id');
    }
}
