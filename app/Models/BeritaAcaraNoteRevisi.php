<?php

namespace App\Models;

use App\Models\BaseModel;

class BeritaAcaraNoteRevisi extends BaseModel
{
    protected $table = 'berita_acara_note_revisi';

    public $timestamps = false;

    protected $errors;

    public function scoredBy() {
        return $this->belongsTo('App\Models\BeritaAcaraParticipant', 'berita_acara_participant_id');
    }
}
