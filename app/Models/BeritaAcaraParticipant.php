<?php

namespace App\Models;

use App\Models\BaseModel;

class BeritaAcaraParticipant extends BaseModel
{
    protected $table = 'berita_acara_participant';

    public $timestamps = false;

    protected $errors;

    public function report() {
        return $this->belongsTo('App\Models\BeritaAcaraReport', 'berita_acara_report_id');
    }

    public function revisi() {
        return $this->hasMany('App\Models\BeritaAcaraNoteRevisi');
    }

    public function dosen() {
        return $this->belongsTo('App\Models\ProdiUser', 'participant_id');
    }
}
