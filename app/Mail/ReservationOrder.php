<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationOrder extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $caterer;
    public $dateOrdered;
    public $start;
    public $end;
    /**
     * Create a new message instance.
     */
    public function __construct($order, $caterer, $dateOrdered, $start, $end)
    {
        $this->order = Order::find($order);

        $this->order->load([
            'caterer',
            'orderItems',
            'payments',
            'orderItems',
            'user',
        ]);

        $this->caterer = $caterer;
        $this->dateOrdered = $dateOrdered;
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Order',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.reservation-order',
            with: [
                'order' => $this->order,
                'caterer' => $this->caterer,
                'created_at' => $this->dateOrdered,
                'start' => $this->start,
                'end' => $this->end
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
