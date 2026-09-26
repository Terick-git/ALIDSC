<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmationMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Message $message) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nous avons bien reçu votre message - ALIDSC',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.confirmation-message',
            with: [
                'contact' => $this->message,  // ← on renomme en "contact"
            ],
        );
    }
}