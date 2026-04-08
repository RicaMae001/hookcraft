<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OutForDeliveryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

 public function __construct($order)
{
    $this->order = \App\Models\Order::with(['coordinator', 'orderItems.product'])
                    ->find($order->id);
}
    public function envelope(): Envelope
    {
        $orderNumber = 'ORD-' . str_pad($this->order->id, 5, '0', STR_PAD_LEFT);

        return new Envelope(
            subject: '🚚 Your Order #' . $orderNumber . ' is Out for Delivery!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.out-for-delivery',
        );
    }
}