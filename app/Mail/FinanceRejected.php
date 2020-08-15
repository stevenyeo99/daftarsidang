<?php

namespace App\Mail;

use App\Enums\CreationType;
use App\Models\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FinanceRejected extends Mailable {

    use Queueable, SerializesModels;

    /**
     * The custom request instance.
     *
     * @var Request $customRequest
     */
    public $customRequest;

    /**
     * The student instance.
     *
     * @var Student $student
     */
    public $student;

    /**
     * The role instance.
     *
     * @var string $role
     */
    public $role;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $customRequest, string $role)
    {
        $this->customRequest = $customRequest;
        $this->role = $role;
        $this->student = $customRequest->student()->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Pemberitahuan Validasi Gagal Tagihan Keuangan Mahasiswa')
                    ->view('emails.requests.finance_rejected')
                    ->with('creation_type', CreationType::getString($this->customRequest->type));
    }
}