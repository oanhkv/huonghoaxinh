<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage)
    {
    }

    public function envelope(): Envelope
    {
        $subject = trim((string) ($this->contactMessage->subject ?: 'Liên hệ từ website'));

        return new Envelope(
            subject: '[Hương Hoa Xinh] '.$subject
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact.received',
            with: [
                'm' => $this->contactMessage,
            ]
        );
    }
}

