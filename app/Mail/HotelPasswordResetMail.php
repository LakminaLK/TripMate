<?php

namespace App\Mail;

use App\Models\Hotel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HotelPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $hotel;
    public $token;

    public function __construct(Hotel $hotel, string $token)
    {
        $this->hotel = $hotel;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Reset Your TripMate Hotel Password')
                    ->view('emails.hotel-password-reset');
    }
}
