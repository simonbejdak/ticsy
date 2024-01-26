<?php

namespace App\Mail;

use App\Models\Incident;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IncidentCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Incident $incident,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(User::getSystemUser()->email, 'Ticketing System'),
            subject: 'Incident ' . $this->incident->id . ' has been opened for you',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.incident-created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
