<?php

namespace App\Mail;

use App\Models\Request;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class RequestCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Request $request,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(User::getSystemUser()->email, 'Ticketing System'),
            subject: 'Request ' . $this->request->id . ' has been opened for you',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.request-created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
