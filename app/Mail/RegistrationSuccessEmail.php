<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationSuccessEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    // Constructor to pass user object
    public function __construct($user)
    {
        $this->user = $user;
    }

    // Build the email
    public function build()
    {
        return $this->subject('Welcome to Our Platform!')
                    ->view('emails.registration_success')
                    ->with([
                        'name' => $this->user->name,
                    ]);
    }
}
