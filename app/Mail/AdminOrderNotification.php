<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address', 'orders@soziecollection.co.tz'), config('app.name', 'Sozie Collection')),
            subject: __('NEW ORDER ALERT #:number - TZS :amount', [
                'number' => $this->order->order_number,
                'amount' => number_format($this->order->total_amount, 0, '.', ','),
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtmlContent(),
        );
    }

    private function buildHtmlContent(): string
    {
        $order = $this->order->loadMissing('items');

        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #29241F; color: #EDE5D8; padding: 24px;'>
            <h2 style='color: #A8895F; font-family: Georgia, serif;'>New Order Received!</h2>
            <p><strong>Order Number:</strong> #{$order->order_number}</p>
            <p><strong>Customer:</strong> {$order->customer_name} ({$order->customer_phone})</p>
            <p><strong>Email:</strong> {$order->customer_email}</p>
            <p><strong>City & Address:</strong> {$order->city} - {$order->shipping_address}</p>
            <p><strong>Total Amount:</strong> TZS ".number_format($order->total_amount, 0, '.', ',')."</p>
            <p><strong>Payment Method:</strong> {$order->payment_method}</p>
        </div>";
    }
}
