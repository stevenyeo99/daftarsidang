<?php

namespace App\Mail;

use App\Enums\CreationType;
use App\Enums\MailParticipantType;
use App\Models\Request;
use App\Models\ProdiUser;
use App\Models\BeritaAcaraReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use DateTime;

class ReminderSidang extends Mailable {

    use Queueable, SerializesModels;

    public $customRequest;

    public $student;

    public $userType;

    public $prodiUser;

    public $beritaAcaraReport;

    public $penjadwalanSidang;

    public $ruanganSidang;

    public function __construct(BeritaAcaraReport $beritaAcaraReport, $userType, ProdiUser $prodiUser = null) {
        $this->customRequest = $beritaAcaraReport->request()->first();
        $this->student = $this->customRequest->student()->first();
        $this->userType = $userType;
        $this->prodiUser = $prodiUser;
        $this->beritaAcaraReport = $beritaAcaraReport;
        $this->penjadwalanSidang = $beritaAcaraReport->penjadwalan_sidang->first();
        // format tanggal waktu sidang to time
        $time = DateTime::createFromFormat('Y-m-d H:i:s', $this->penjadwalanSidang->tanggal_sidang)->format('h:i A');
        $this->penjadwalanSidang->tanggal_sidang = $time;
        $this->ruanganSidang = $this->penjadwalanSidang->ruangan_sidang()->first();
    }

    public function build() {
        return $this->subject('[Reminder]: Jadwal Sidang')
            ->view('emails.requests.reminder_sidang')
            ->with('creation_type', CreationType::getString($this->customRequest->type));
    }
}