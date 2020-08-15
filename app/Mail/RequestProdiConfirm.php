<?php

namespace App\Mail;

use App\Enums\CreationType;
use App\Models\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestProdiConfirm extends Mailable
{
    use Queueable, SerializesModels;

    public $customRequest;

    public $student;

    public $prodi;

    public function __construct(Request $customRequest, $prodi) {
        $this->customRequest = $customRequest;
        $this->student = $customRequest->student()->first();
        $this->prodi = $prodi;
    }

    /**
     * build message on email template
     */
    public function build() {
        return $this->subject('Pemberitahuan Validasi Status Pendaftaran')
                    ->view('emails.requests.prodi_confirm')
                    ->with('creation_type', CreationType::getString($this->customRequest->type));
    }
}