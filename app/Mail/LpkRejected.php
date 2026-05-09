<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LpkRejected extends Mailable
{
    use SerializesModels;

    public $adminName;
    public $lpkName;

    public function __construct($adminName, $lpkName)
    {
        $this->adminName = $adminName;
        $this->lpkName   = $lpkName;
    }

    public function build()
    {
        return $this->subject('❌ Pengajuan LPK Ditolak - Skilloka')
                    ->view('emails.lpk_rejected');
    }
}