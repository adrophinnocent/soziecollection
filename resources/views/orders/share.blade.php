<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="color-scheme" content="light">
    <meta name="theme-color" content="#EDE5D8">
    <title>{{ __('Sozie Collection | Order :order', ['order' => $order->order_number]) }}</title>

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Sozie Collection">
    <meta property="og:title" content="{{ __('Sozie Collection • Order :order', ['order' => $order->order_number]) }}">
    <meta property="og:description" content="{{ __('Order card for: :items', ['items' => $order->items->pluck('product_name')->join(', ')]) }}">
    <meta property="og:url" content="{{ request()->url() }}">
    @if($shareImageUrl)
    <meta property="og:image" content="{{ $shareImageUrl }}">
    <meta property="og:image:alt" content="{{ $order->items->first()?->product_name }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ __('Sozie Collection • Order :order', ['order' => $order->order_number]) }}">
    <meta name="twitter:description" content="{{ __('See your items and your order design.') }}">
    <meta name="twitter:image" content="{{ $shareImageUrl }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#EDE5D8] text-[#29241F] min-h-screen py-10 px-4">
    <main class="max-w-2xl mx-auto space-y-6">
        <header class="text-center space-y-3">
            <div class="w-16 h-16 mx-auto bg-[#29241F] polygon-card flex items-center justify-center shadow-lg">
                <span class="font-serif font-bold text-2xl text-[#A8895F]">S</span>
            </div>
            <p class="text-[10px] font-extrabold uppercase tracking-[0.3em] text-[#A8895F]">SOZIE COLLECTION ATELIER</p>
            <h1 class="font-serif font-bold text-4xl text-[#29241F]">{{ __('Your Signature Order') }}</h1>
            <p class="text-xs font-bold text-gray-600">{{ __('Order:') }} <span class="text-[#A8895F] font-mono">{{ $order->order_number }}</span></p>
        </header>

        <section class="bg-[#F8F5EF] border border-[#A8895F]/40 polygon-card shadow-xl p-5 sm:p-8 space-y-5">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#D8C9B8] pb-4">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#A8895F]">{{ __('ORDER DETAILS') }}</span>
                    <p class="text-sm font-extrabold text-[#29241F]">{{ $order->customer_name }}</p>
                </div>
                <span class="px-3 py-1.5 bg-emerald-100 border border-emerald-300 text-emerald-900 text-[10px] font-extrabold uppercase rounded">
                    {{ $order->status_label }}
                </span>
            </div>

            <div class="space-y-4">
                @foreach($order->items as $item)
                <article class="flex items-center gap-4 p-3 bg-white border border-[#D8C9B8] polygon-card">
                    @if($item->product_image_url)
                    <img src="{{ $item->product_image_url }}" loading="lazy" decoding="async" alt="{{ $item->product_name }}" class="w-24 h-24 object-cover border border-[#D8C9B8] shrink-0">
                    @else
                    <div class="w-24 h-24 bg-[#EDE5D8] border border-[#D8C9B8] flex items-center justify-center shrink-0">
                        <span class="text-[10px] font-extrabold text-[#A8895F] text-center px-2">SOZIE</span>
                    </div>
                    @endif
                    <div class="flex-grow min-w-0">
                        <h2 class="font-serif font-bold text-lg text-[#29241F]">{{ $item->product_name }}</h2>
                        <p class="text-xs font-bold text-[#A8895F]">{{ $item->variant_size }} • {{ __('Quantity:') }} {{ $item->quantity }}</p>
                        <p class="text-xs text-gray-600 font-semibold mt-1">{{ __('Unit price:') }} TZS {{ number_format($item->unit_price, 0) }}</p>
                        @if($item->product)
                        <a href="{{ route('shop.show', $item->product->slug) }}" class="inline-flex items-center gap-1 mt-2 text-[10px] font-extrabold uppercase tracking-wider text-[#A8895F] hover:text-[#29241F]">
                            {{ __('View product') }}
                            <span aria-hidden="true">→</span>
                        </a>
                        @endif
                    </div>
                    <strong class="text-sm font-extrabold text-[#29241F] shrink-0">TZS {{ number_format($item->subtotal, 0) }}</strong>
                </article>
                @endforeach
            </div>

            <div class="border-t border-[#D8C9B8] pt-4 space-y-2 text-sm font-bold">
                <div class="flex justify-between text-gray-600">
                    <span>{{ __('Subtotal') }}</span>
                    <span>TZS {{ number_format($order->subtotal, 0) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>{{ __('Delivery') }}</span>
                    <span>{{ (float) $order->shipping_cost > 0 ? 'TZS '.number_format($order->shipping_cost, 0) : __('Free') }}</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-[#D8C9B8]">
                    <span class="font-serif font-bold text-lg text-[#29241F]">{{ __('TOTAL') }}</span>
                    <span class="font-serif font-bold text-2xl text-[#A8895F]">TZS {{ number_format($order->total_amount, 0) }}</span>
                </div>
            </div>
        </section>

        <footer class="text-center space-y-3">
            <p class="text-xs text-gray-600 font-semibold">{{ __('Thank you for choosing Sozie Collection. We will reply to you on WhatsApp.') }}</p>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#29241F] text-white text-xs font-extrabold uppercase tracking-wider polygon-btn hover:bg-[#A8895F]">
                {{ __('Visit Sozie Collection') }}
            </a>
        </footer>
    </main>
</body>
</html>
