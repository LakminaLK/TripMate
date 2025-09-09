<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TourCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $tourist;
    public $hotel;

    /**
     * Create a new message instance.
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🌟 How was your trip? Share your experience!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->subject('Your tour has been completed - Share your experience!')
                    ->view('emails.tour-completed')
                    ->with([
                        'booking' => $this->booking,
                        'hotel' => $this->booking->hotel,
                        'tourist' => $this->booking->tourist
                    ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
