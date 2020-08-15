<?php

namespace App\Mail;

use App\Enums\CreationType;
use App\Models\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestProdiReject extends Mailable {
    
    use Queueable, SerializesModels;

    public $customRequest;

    public $student;

    public $prodi;

    public $role;

    public function __construct(Request $customRequest, string $studyProgram, string $role) {
        $this->customRequest = $customRequest;
        $this->prodi = $studyProgram;
        $this->student = $customRequest->student()->first();
        $this->role = $role;
    }

    public function build() {
        return $this->subject('Pemberitahuan Validasi Status Pendaftaran')
            ->view('emails.requests.prodi_reject')
            ->with('creation_type', CreationType::getString($this->customRequest->type));
    }
}