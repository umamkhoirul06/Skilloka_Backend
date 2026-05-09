<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LpkVerified extends Mailable
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
        return $this->subject('🎉 LPK Anda Telah Diverifikasi - Skilloka')
                    ->view('emails.lpk_verified');
    }
}