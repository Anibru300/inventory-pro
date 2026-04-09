<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $verifyUrl;

    public function __construct(User $user, string $verifyUrl)
    {
        $this->user = $user;
        $this->verifyUrl = $verifyUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verifica tu correo - StockWolf',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verify-email',
            with: [
                'userName' => $this->user->fullName(),
                'verifyUrl' => $this->verifyUrl,
                'expiresIn' => '24 horas',
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
