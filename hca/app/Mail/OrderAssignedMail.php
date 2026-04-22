<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $coordinator;

    public function __construct($order, $coordinator)
    {
        $this->order       = Order::with(['orderItems.product'])->find($order->id);
        $this->coordinator = $coordinator;
    }

    public function envelope(): Envelope
    {
        $orderNumber = 'ORD-' . str_pad($this->order->id, 5, '0', STR_PAD_LEFT);

        return new Envelope(
            subject: '🚀 Your Order #' . $orderNumber . ' is Being Processed!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-assigned',
        );
    }
}