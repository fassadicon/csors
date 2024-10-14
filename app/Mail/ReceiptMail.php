<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Order;
use App\Models\Caterer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $paymentStatus;

    /**
     * Create a new message instance.
     */
    public function __construct($order, $paymentStatus)
    {
        $this->order = Order::find($order);

        $this->order->load([
            'caterer',
            'orderItems',
            'payments',
            'orderItems',
            'user',
        ]);

        $this->paymentStatus = $paymentStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->order->caterer->email, $this->order->caterer->name),
            replyTo: [
                new Address($this->order->caterer->email, $this->order->caterer->user->full_name),
            ],
            subject: 'CSORS Receipt',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.receipt-mail',
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
