<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Symfony\Component\Mime\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Address as Mail;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $emailmessage;

    /**
     * Create a new message instance.
     */
    public function __construct(array $emailmessage)
    {
        //
        $this->emailmessage = $emailmessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Mail('olayori045@gmail.com', 'Olayori'),
            // replyTo: [
            //     new Mail('taylor@example.com', 'Taylor Otwell'),
            // ],
            subject: 'OTP for your account',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email',
            with: [
                'emailmessage' => $this->emailmessage,
            ],
        );
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
