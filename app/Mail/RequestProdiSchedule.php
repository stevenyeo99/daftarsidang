<?php

namespace App\Mail;

use App\Enums\CreationType;
use App\Models\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestProdiSchedule extends Mailable
{
    use Queueable, SerializesModels;

    public $customRequest;

    public $student;

    public $studyProgram;

    public $role = 'prodi';

    public function __construct(Request $customRequest, string $studyProgram) {
        $this->customRequest = $customRequest;
        $this->studyProgram = $studyProgram;
        $this->student = $customRequest->student()->first();
    }

    /**
     * build message
     */
    public function build() {
        return $this->subject('Pemberitahuan Mahasiswa Melakukan Pendaftaran Sidang Kepada Pihak Prodi')
                    ->view('emails.requests.prodi_submitted')
                    ->with('creation_type', CreationType::getString($this->customRequest->type));
    }
}