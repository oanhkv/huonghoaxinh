<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\ContactReply;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReplied extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage, public ContactReply $reply)
    {
    }

    public function envelope(): Envelope
    {
        $base = trim((string) ($this->reply->subject ?: $this->contactMessage->subject ?: 'Phản hồi từ cửa hàng'));

        return new Envelope(
            subject: 'Re: '.$base
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact.replied',
            with: [
                'm' => $this->contactMessage,
                'r' => $this->reply,
            ]
        );
    }
}

