<?php

namespace App\Support;

use App\Models\Order;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

final class WhatsappOrderMessage
{
    public function build(Order $order): string
    {
        $order->loadMissing('items.product');

        $lines = [
            '🌸 *SOZIE COLLECTION — MPYA ODA*',
            '',
            '📦 *Nambari ya Oda:* '.$order->order_number,
            '👤 *Mteja:* '.$order->customer_name,
            '📱 *Simu:* '.$order->customer_phone,
            '📍 *Mji/Eneo:* '.$order->city.' - '.$order->shipping_address,
            '',
            '🧴 *BIDHAA ZILIZOAGIZWA:*',
        ];

        foreach ($order->items as $index => $item) {
            $lines[] = '';
            $lines[] = ($index + 1).'. *'.$item->product_name.'*';
            $lines[] = '   Size: '.($item->variant_size ?: 'Standard');
            $lines[] = '   Quantity: '.$item->quantity;
            $lines[] = '   Bei: TZS '.number_format((float) $item->subtotal, 0, '.', ',');

            if ($item->product_image_url) {
                $lines[] = '   📸 Picha: '.$item->product_image_url;
            }

            if ($item->product) {
                $lines[] = '   🔗 Bidhaa: '.route('shop.show', $item->product->slug);
            }
        }

        $lines[] = '';
        $lines[] = '💰 *JUMLA KUU:* TZS '.number_format((float) $order->total_amount, 0, '.', ',');
        $lines[] = '💳 *Njia ya Malipo:* '.$this->paymentLabel($order->payment_method);

        if (filled($order->notes)) {
            $lines[] = '📝 *Maelezo:* '.$order->notes;
        }

        $lines[] = '';
        $lines[] = '🎨 *Angalia design ya oda na picha zote:*';
        $lines[] = $this->shareUrl($order);

        return implode("\n", $lines);
    }

    public function shareUrl(Order $order): string
    {
        return URL::temporarySignedRoute(
            'orders.share',
            now()->addDays(7),
            ['order' => $order->getKey()],
        );
    }

    public function primaryImageUrl(Order $order): ?string
    {
        $order->loadMissing('items');

        return $order->items->first()?->product_image_url;
    }

    private function paymentLabel(?string $paymentMethod): string
    {
        return match ($paymentMethod) {
            'whatsapp' => 'WhatsApp',
            'mobile_money' => 'Mobile Money',
            'cash_on_delivery' => 'Cash on Delivery',
            default => Str::headline((string) $paymentMethod),
        };
    }
}
