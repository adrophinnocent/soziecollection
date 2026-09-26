@extends('layouts.app')

@section('title', __('Order Confirmation #:order | Sozie Collection', ['order' => $order->order_number]))

@section('content')

<div class="py-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

    <div class="glass-panel-gold p-8 sm:p-12 polygon-card border border-[#A8895F]/40 shadow-2xl space-y-6 bg-[#17130F]">

        <div class="w-16 h-16 bg-[#A8895F] polygon-card flex items-center justify-center mx-auto text-[#12100E] gold-glow">
            <i data-lucide="check-circle" class="w-10 h-10"></i>
        </div>

        <div>
            <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">{{ __('Order received') }}</span>
            <h1 class="font-serif font-bold text-4xl text-[#EDE5D8]">{{ __('Thank you for choosing Sozie Collection!') }}</h1>
            <p class="text-xs sm:text-sm text-[#B5A897] mt-2 font-bold">
                {{ __('Your order number is') }} <strong class="text-[#A8895F] font-mono text-base font-extrabold">{{ $order->order_number }}</strong>
            </p>
        </div>

        <!-- WhatsApp Quick Action Button -->
        <div class="p-6 bg-emerald-900 border border-emerald-700 polygon-card max-w-xl mx-auto space-y-3 text-white">
            <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-300 block">{{ __('Confirm your order directly on WhatsApp') }}</span>
            <p class="text-xs text-gray-200 font-bold">
                {{ __('Tap the button below to send your full order details to our WhatsApp team for fast delivery.') }}
            </p>
            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
               class="inline-flex items-center justify-center gap-3 w-full py-3.5 bg-emerald-800 text-white font-extrabold text-xs uppercase tracking-[0.2em] polygon-btn hover:bg-emerald-950 shadow-xl">
                <i data-lucide="message-circle" class="w-5 h-5"></i>
                <span>{{ __('Send order on WhatsApp now') }}</span>
            </a>
            <a href="{{ $shareUrl }}" target="_blank" rel="noopener"
               class="inline-flex items-center justify-center gap-2 w-full py-2.5 bg-emerald-950/60 border border-emerald-700 text-emerald-100 text-[10px] font-extrabold uppercase tracking-wider polygon-btn hover:bg-emerald-900">
                <i data-lucide="image" class="w-4 h-4"></i>
                <span>{{ __('View order design and photos') }}</span>
            </a>
        </div>

        <!-- Order Summary Details -->
        <div class="text-left pt-6 border-t border-[#322B23] space-y-4">
            <h3 class="font-serif font-bold text-lg text-[#EDE5D8]">{{ __('Order information') }}</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-[#B5A897] font-bold">
                <div>
                    <span class="text-[#A89C8C] block font-extrabold">{{ __('Customer name:') }}</span>
                    <strong class="text-[#EDE5D8] font-serif text-sm">{{ $order->customer_name }}</strong>
                </div>
                <div>
                    <span class="text-[#A89C8C] block font-extrabold">{{ __('Phone:') }}</span>
                    <strong class="text-[#EDE5D8] font-serif text-sm">{{ $order->customer_phone }}</strong>
                </div>
                <div>
                    <span class="text-[#A89C8C] block font-extrabold">{{ __('City / Area:') }}</span>
                    <strong class="text-[#EDE5D8] font-serif text-sm">{{ $order->city }} - {{ $order->shipping_address }}</strong>
                </div>
                <div>
                    <span class="text-[#A89C8C] block font-extrabold">{{ __('Payment method:') }}</span>
                    <strong class="text-[#EDE5D8] uppercase">{{ $order->payment_method_label }}</strong>
                </div>
            </div>

            <!-- Items list -->
            <div class="space-y-2 pt-2">
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-wider block">{{ __('Items ordered:') }}</span>
                @foreach($order->items as $item)
                <div class="navy-card p-3 polygon-card flex items-center gap-3 text-xs border border-[#322B23] bg-[#17130F]">
                    @if($item->product_image_url)
                    <img src="{{ $item->product_image_url }}" data-sozie-fallback loading="lazy" decoding="async" alt="{{ $item->product_name }}" class="w-14 h-14 object-cover border border-[#322B23] shrink-0">
                    @endif
                    <div class="flex-grow min-w-0">
                        <strong class="text-[#EDE5D8] font-serif text-sm block truncate">{{ $item->product_name }}</strong>
                        <span class="text-[#A8895F] block text-[10px] font-extrabold">{{ $item->variant_size }} x {{ $item->quantity }}</span>
                    </div>
                    <span class="font-extrabold text-[#EDE5D8] shrink-0">TZS {{ number_format($item->subtotal, 0) }}</span>
                </div>
                @endforeach
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-[#322B23]">
                <span class="font-serif font-bold text-base text-[#EDE5D8]">{{ __('Grand total:') }}</span>
                <span class="font-serif font-bold text-2xl text-[#A8895F]">TZS {{ number_format($order->total_amount, 0) }}</span>
            </div>
        </div>

        <div class="pt-4 flex justify-center gap-4 text-xs">
            <a href="{{ route('orders.track', ['order_number' => $order->order_number]) }}" class="px-6 py-2.5 bg-[#17130F] border border-[#322B23] text-[#EDE5D8] uppercase font-extrabold polygon-btn hover:bg-[#221D19]">
                {{ __('Track your order') }}
            </a>
            <a href="{{ route('shop.index') }}" class="px-6 py-2.5 bg-[#A8895F] text-[#12100E] uppercase font-extrabold polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF]">
                {{ __('Back to shop') }}
            </a>
        </div>

    </div>

</div>

@endsection
