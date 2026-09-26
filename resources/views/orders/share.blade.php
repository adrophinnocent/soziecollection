<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="sozie-storefront">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="color-scheme" content="dark">
    <meta name="theme-color" content="#0C0A09">
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
<body class="bg-[#0C0A09] text-[#EDE5D8] min-h-screen py-10 px-4">
    <main class="max-w-2xl mx-auto space-y-6">
        <header class="text-center space-y-3">
            <div class="w-16 h-16 mx-auto bg-[#221D19] polygon-card flex items-center justify-center shadow-lg">
                <span class="font-serif font-bold text-2xl text-[#A8895F]">S</span>
            </div>
            <p class="text-[10px] font-extrabold uppercase tracking-[0.3em] text-[#A8895F]">SOZIE COLLECTION ATELIER</p>
            <h1 class="font-serif font-bold text-4xl text-[#EDE5D8]">{{ __('Your Signature Order') }}</h1>
            <p class="text-xs font-bold text-[#B5A897]">{{ __('Order:') }} <span class="text-[#A8895F] font-mono">{{ $order->order_number }}</span></p>
        </header>

        <section class="bg-[#17130F] border border-[#A8895F]/40 polygon-card shadow-xl p-5 sm:p-8 space-y-5">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#322B23] pb-4">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#A8895F]">{{ __('ORDER DETAILS') }}</span>
                    <p class="text-sm font-extrabold text-[#EDE5D8]">{{ $order->customer_name }}</p>
                </div>
                <span class="px-3 py-1.5 bg-[#0C2119] border border-[#065F46] text-emerald-300 text-[10px] font-extrabold uppercase rounded">
                    {{ $order->status_label }}
                </span>
            </div>

            <div class="space-y-4">
                @foreach($order->items as $item)
                <article class="flex items-center gap-4 p-3 bg-[#17130F] border border-[#322B23] polygon-card">
                    @if($item->product_image_url)
                    {{-- This page is deliberately standalone (no Alpine, no layouts.app), so it
                         carries its own inline handler. `this.onerror = null` first, so a
                         placeholder that itself fails cannot start a loop. --}}
                    <img src="{{ $item->product_image_url }}" loading="lazy" decoding="async" alt="{{ $item->product_name }}" class="w-24 h-24 object-cover border border-[#322B23] shrink-0"
                         onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';">
                    @else
                    <div class="w-24 h-24 bg-[#0C0A09] border border-[#322B23] flex items-center justify-center shrink-0">
                        <span class="text-[10px] font-extrabold text-[#A8895F] text-center px-2">SOZIE</span>
                    </div>
                    @endif
                    <div class="flex-grow min-w-0">
                        <h2 class="font-serif font-bold text-lg text-[#EDE5D8]">{{ $item->product_name }}</h2>
                        <p class="text-xs font-bold text-[#A8895F]">{{ $item->variant_size }} • {{ __('Quantity:') }} {{ $item->quantity }}</p>
                        <p class="text-xs text-[#B5A897] font-semibold mt-1">{{ __('Unit price:') }} TZS {{ number_format($item->unit_price, 0) }}</p>
                        @if($item->product)
                        <a href="{{ route('shop.show', $item->product->slug) }}" class="inline-flex items-center gap-1 mt-2 text-[10px] font-extrabold uppercase tracking-wider text-[#A8895F] hover:text-[#F8F5EF]">
                            {{ __('View product') }}
                            <span aria-hidden="true">→</span>
                        </a>
                        @endif
                    </div>
                    <strong class="text-sm font-extrabold text-[#EDE5D8] shrink-0">TZS {{ number_format($item->subtotal, 0) }}</strong>
                </article>
                @endforeach
            </div>

            <div class="border-t border-[#322B23] pt-4 space-y-2 text-sm font-bold">
                <div class="flex justify-between text-[#B5A897]">
                    <span>{{ __('Subtotal') }}</span>
                    <span>TZS {{ number_format($order->subtotal, 0) }}</span>
                </div>
                <div class="flex justify-between text-[#B5A897]">
                    <span>{{ __('Delivery') }}</span>
                    <span>{{ (float) $order->shipping_cost > 0 ? 'TZS '.number_format($order->shipping_cost, 0) : __('Free') }}</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-[#322B23]">
                    <span class="font-serif font-bold text-lg text-[#EDE5D8]">{{ __('TOTAL') }}</span>
                    <span class="font-serif font-bold text-2xl text-[#A8895F]">TZS {{ number_format($order->total_amount, 0) }}</span>
                </div>
            </div>
        </section>

        <footer class="text-center space-y-3">
            <p class="text-xs text-[#B5A897] font-semibold">{{ __('Thank you for choosing Sozie Collection. We will reply to you on WhatsApp.') }}</p>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#221D19] text-white text-xs font-extrabold uppercase tracking-wider polygon-btn hover:bg-[#A8895F] hover:text-[#12100E]">
                {{ __('Visit Sozie Collection') }}
            </a>
        </footer>
    </main>
</body>
</html>
