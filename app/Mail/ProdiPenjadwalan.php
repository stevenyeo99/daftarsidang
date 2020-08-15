<?php

namespace App\Mail;

use App\Enums\CreationType;
use App\Models\Request;
use App\Models\PenjadwalanSidang;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProdiPenjadwalan extends Mailable {
    
    use Queueable, SerializesModels;

    public $customRequest;

    public $student;

    public $prodi;

    // dosen pembimbing
    // for dospem no need to email him if got changes because he is VIP
    public $dospem;

    // dosen penguji
    public $dosPenguji;

    public $isUsingBackup;

    // dosen pembimbing backup
    public $dospemBAK;

    public $tanggalWaktuSidang;

    public function __construct(Request $customRequest, $prodi, 
                    $dospem, 
                    $dosPenguji,
                    $dospemBAK,
                    $isUsingBackup,
                    $tanggalWaktuSidang) 
    {
        $this->customRequest = $customRequest;
        $this->student = $customRequest->student()->first();
        $this->prodi = $prodi;
        $this->dospem = $dospem;
        $this->dosPenguji = $dosPenguji;
        $this->dospemBAK = $dospemBAK;
        $this->isUsingBackup = $isUsingBackup;
        $this->tanggalWaktuSidang = $tanggalWaktuSidang;
    }

    /**
     * build message
     */
    public function build() {
        return $this->subject('Pemberitahuan Jadwal Sidang telah dijadwalkan')
            ->view('emails.requests.prodi_penjadwalan')
            ->with('creation_type', CreationType::getString($this->customRequest->type));
    }
}