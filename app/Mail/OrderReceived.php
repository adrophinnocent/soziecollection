<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address', 'orders@soziecollection.co.tz'), config('app.name', 'Sozie Collection')),
            subject: __('Oda Yako Imepokelewa #:number | Sozie Collection', ['number' => $this->order->order_number]),
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
        $itemsHtml = '';

        foreach ($order->items as $item) {
            $itemsHtml .= "<tr>
                <td style='padding: 8px; border-bottom: 1px solid #D8C9B8;'>{$item->product_name} ({$item->variant_size})</td>
                <td style='padding: 8px; border-bottom: 1px solid #D8C9B8; text-align: center;'>{$item->quantity}</td>
                <td style='padding: 8px; border-bottom: 1px solid #D8C9B8; text-align: right;'>TZS ".number_format($item->subtotal, 0, '.', ',').'</td>
            </tr>';
        }

        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #F8F5EF; padding: 24px; border: 1px solid #D8C9B8;'>
            <h2 style='color: #29241F; font-family: Georgia, serif;'>Ahsante kwa Oda Yako!</h2>
            <p style='color: #555;'>Habari <strong>{$order->customer_name}</strong>,</p>
            <p style='color: #555;'>Tumpokea oda yako namba <strong>#{$order->order_number}</strong> katika Sozie Collection.</p>

            <table style='width: 100%; border-collapse: collapse; margin-top: 16px; background-color: #ffffff;'>
                <thead>
                    <tr style='background-color: #29241F; color: #A8895F;'>
                        <th style='padding: 10px; text-align: left;'>Bidhaa</th>
                        <th style='padding: 10px; text-align: center;'>Idadi</th>
                        <th style='padding: 10px; text-align: right;'>Bei</th>
                    </tr>
                </thead>
                <tbody>
                    {$itemsHtml}
                </tbody>
            </table>

            <div style='margin-top: 16px; text-align: right; font-size: 16px; color: #29241F;'>
                <strong>Jumla Kuu: TZS ".number_format($order->total_amount, 0, '.', ',')."</strong>
            </div>

            <p style='margin-top: 24px; color: #777; font-size: 12px;'>Mji/Anwani ya Utoaji: {$order->city} - {$order->shipping_address}</p>
            <p style='color: #777; font-size: 12px;'>Sozie Collection | Haute Parfumerie Tanzania</p>
        </div>";
    }
}
