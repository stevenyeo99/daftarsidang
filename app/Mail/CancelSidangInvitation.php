<?php

namespace App\Mail;

use App\Enums\CreationType;
use App\Models\Request;
use App\Models\PenjadwalanSidang;
use App\Models\OldPenjadwalanSidang;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\RuanganSidang;

class CancelSidangInvitation extends Mailable {

    use Queueable, SerializesModels;

    public $customRequest;

    public $student;

    public $prodi;

    public $jadwal;

    public $type;

    public $template;
    
    public $ruangan;

    public function __construct(PenjadwalanSidang $jadwal, $template, RuanganSidang $ruangan) 
    {
        $this->customRequest = $jadwal->request()->first();
        $this->student = $this->customRequest->student()->first();
        $this->prodi = $jadwal->prodiAdmin()->first()->studyprogram()->first()->name;
        $this->jadwal = $jadwal;
        $this->ruangan = $ruangan;
        $this->type = 'Tesis';
        $this->template = $template;
        if($this->customRequest->type == 0) {
            $this->type = 'Kerja Praktek';   
        } else if($this->customRequest->type == 1) {
            $this->type = 'Skripsi';
        }
    }

    /**
     * build message
     */
    public function build() {
        return $this->subject('Surat Pembatalan Undangan Sidang ' . $this->type)
            ->view('emails.requests.cancel_undangan_sidang')
            ->with('creation_type', CreationType::getString($this->customRequest->type));
    }
}