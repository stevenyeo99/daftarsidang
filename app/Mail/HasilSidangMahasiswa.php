<?php

namespace App\Mail;

use App\Enums\CreationType;
use App\Models\BeritaAcaraReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HasilSidangMahasiswa extends Mailable 
{
    use Queueable, SerializesModels;

    public $beritaAcaraReport;

    public $student;

    public $type;

    public $isLulus;

    public function __construct(BeritaAcaraReport $beritaAcaraReport, $type, $isLulus) {
        $this->beritaAcaraReport = $beritaAcaraReport;
        $this->student = $beritaAcaraReport->request()->first()->student()->first();
        $this->type = $type;
        $this->isLulus = $isLulus;
    }

    public function build() {
        return $this->subject("Pemberitahuan Hasil Sidang")
            ->view('emails.requests.sidang_result');
    }
}