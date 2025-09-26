<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TouristPasswordResetNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ]));

        return (new MailMessage)
            ->subject('🔐 Reset Your TripMate Password')
            ->view('emails.tourist_password_reset', [
                'user' => $notifiable,
                'resetUrl' => $resetUrl,
                'token' => $this->token
            ]);
    }
}