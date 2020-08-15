<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReschedulePenjadwalanNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $prodi;

    public $type;

    public function __construct(string $prodi, string $type) {
        $this->prodi = $prodi;
        $this->type = $type;
    }

    /**
     * build message
     */
    public function build() {
        return $this->subject('Pemberitahuan Pihak Prodi melakukan penjadwalan ulang H-3 sidang ' . $this->type)
            ->view('emails.requests.reschedule-notification');
    }
}