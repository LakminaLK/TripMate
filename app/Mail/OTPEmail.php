<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTPEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $otp;

    // Constructor to pass user and OTP to the email
    public function __construct($user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    // Build the email
    public function build()
    {
        return $this->subject('Your OTP Code')
                    ->view('emails.otp')
                    ->with([
                        'name' => $this->user->name,
                        'otp' => $this->otp,
                    ]);
    }
}
