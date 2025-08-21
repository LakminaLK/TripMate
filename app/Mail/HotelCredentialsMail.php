<?php

namespace App\Mail;

use App\Models\Hotel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HotelCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public Hotel $hotel;
    public string $plainPassword;

    public function __construct(Hotel $hotel, string $plainPassword)
    {
        $this->hotel = $hotel;
        $this->plainPassword = $plainPassword;
    }

    public function build()
    {
        return $this->subject('Your TripMate Hotel Login')
            ->view('emails.hotel_credentials')
            ->with([
                'hotel' => $this->hotel,
                'username' => $this->hotel->username,
                'password' => $this->plainPassword,
                // if you host a dedicated hotel login route:
                'loginUrl' => route('hotel.login'),
            ]);
    }
}
