<?php

namespace App\Mail;

use App\Enums\CreationType;
use App\Models\BeritaAcaraReport;
use App\Models\ProdiUser as BeritaAcaraParticipant;
use App\Models\BeritaAcaraNoteRevisi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NoteRevisiSidangMahasiswa extends Mailable
{
    use Queueable, SerializesModels;

    public $customRequest;

    public $student;

    public $beritaAcaraReport;

    public $beritaAcaraParticipant;

    public $beritaAcaraNoteRevisi;

    public $type;

    public function __construct(BeritaAcaraReport $beritaAcaraReport, BeritaAcaraParticipant $beritaAcaraParticipant, array $beritaAcaraNoteRevisi, $type) {
        $this->beritaAcaraReport = $beritaAcaraReport;
        $this->beritaAcaraParticipant = $beritaAcaraParticipant;
        $this->beritaAcaraNoteRevisi = $beritaAcaraNoteRevisi;
        $this->customRequest = $beritaAcaraReport->request()->first();
        $this->student = $this->customRequest->student()->first();
        $this->type = $type;
    }

    public function build() {
        return $this->subject("Pemberitahuan Revisi Sidang")
                ->view('emails.requests.note_revisi_sidang');
    }
}