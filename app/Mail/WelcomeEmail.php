<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome Email',
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'Emails',
            with: ['user' => $this->user],
        );
    }


    public function attachments(): array
    {
        return [];
    }
}
