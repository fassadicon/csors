<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCustomer extends Mailable
{
    use Queueable, SerializesModels;

    public $first_name;
    public $last_name;
    public $email;
    /**
     * Create a new message instance.
     */
    public function __construct($first_name, $email, $last_name)
    {
        $this->first_name = $first_name;
        $this->email = $email;
        $this->last_name = $last_name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CSORS - New Customer Joined!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.new-customer',
            with: [
                'first_name' => $this->first_name,
                'email' => $this->email,
                'last_name' => $this->last_name
            ]
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
