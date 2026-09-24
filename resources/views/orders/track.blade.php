@extends('layouts.app')

@section('title', 'Track Order | Sozie Collection')

@section('content')

<div class="py-16 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-10">
        <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">ORDER TRACKING</span>
        <h1 class="font-serif font-bold text-4xl text-[#29241F]">TRACK YOUR SOZIE PARCEL</h1>
        <p class="text-xs text-gray-600 mt-2 font-semibold max-w-lg mx-auto">
            Enter your order number <span class="font-extrabold text-[#29241F]">(e.g. SOZ-20260924-001)</span>
            plus the phone number or email you used at checkout to view real-time delivery status.
        </p>
    </div>

    @if($errors->any())
    <div class="mb-7 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold polygon-card space-y-1.5">
        @foreach($errors->all() as $err)
        <p class="flex items-start gap-1.5">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 mt-0.5 flex-shrink-0"></i>
            <span>{{ $err }}</span>
        </p>
        @endforeach
    </div>
    @endif

    @if(session('error'))
    <div class="mb-7 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold polygon-card flex items-center gap-2">
        <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 flex-shrink-0"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if(! empty($lookupFailed))
    <div class="mb-7 p-5 bg-rose-50 border-2 border-rose-300 text-rose-800 text-xs font-bold polygon-card">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full bg-rose-100 border border-rose-200 flex items-center justify-center flex-shrink-0">
                <i data-lucide="shield-alert" class="w-5 h-5 text-rose-600"></i>
            </div>
            <div>
                <h5 class="font-serif font-bold text-base text-rose-900 mb-1">For your privacy, we couldn't display this order</h5>
                <p class="font-medium leading-relaxed mb-3">
                    Please double-check the order number and be sure to include the <strong>exact phone number or email address</strong> you used at checkout.
                    Signed in members can view all orders directly in the <a href="{{ route('account.orders') }}" class="underline text-rose-900 hover:text-rose-700 font-extrabold">My Orders page</a>.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <a href="https://wa.me/255700000000" target="_blank"
                       class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-emerald-900 text-white text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn border border-emerald-700">
                        <i data-lucide="message-circle" class="w-3.5 h-3.5 text-emerald-300"></i>
                        WhatsApp Support
                    </a>
                    @guest
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-[#29241F] text-white text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn">
                        <i data-lucide="log-in" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                        Sign In
                    </a>
                    @else
                    <a href="{{ route('account.orders') }}"
                       class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-[#29241F] text-white text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn">
                        <i data-lucide="package-search" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                        My Orders
                    </a>
                    @endguest
                    <a href="{{ route('shop.index') }}"
                       class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-[#A8895F] text-white text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn">
                        <i data-lucide="store" class="w-3.5 h-3.5"></i>
                        Shop Scents
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Search Form -->
    <form action="{{ route('orders.track.submit') }}" method="POST" class="glass-panel-gold p-7 polygon-card border-2 border-[#A8895F]/30 mb-10 bg-[#F8F5EF] shadow-xl">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="order_number" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                    Order Number
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="barcode" class="w-4 h-4 text-[#A8895F]/60"></i>
                    </div>
                    <input id="order_number" type="text" name="order_number"
                           value="{{ old('order_number', $orderNumber) }}"
                           required
                           placeholder="SOZ-YYYYMMDD-NNN or SZ-ORD-XXXXXX (e.g. SOZ-20260924-001)"
                           class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-4 py-3.5 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                </div>
            </div>

            <div>
                <label for="tracking_contact" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                    Phone or Email used at Checkout <span class="text-gray-500 font-normal normal-case tracking-normal">(Required for guest orders)</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="user-round-search" class="w-4 h-4 text-[#A8895F]/60"></i>
                    </div>
                    <input id="tracking_contact" type="text" name="tracking_contact"
                           value="{{ old('tracking_contact', $contact) }}"
                           placeholder="e.g. 0712345678 or you@example.com"
                           class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-4 py-3.5 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                </div>
                <p class="mt-2 text-[10px] text-gray-500 font-bold flex items-start gap-1.5">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#A8895F] flex-shrink-0 mt-0.5"></i>
                    For your privacy, Sozie never shows order details to anyone who cannot prove ownership with the exact contact info used for that order.
                </p>
            </div>

            <button type="submit"
                    class="w-full py-4 bg-[#A8895F] text-white font-extrabold text-xs uppercase tracking-[0.3em] polygon-btn text-center block shadow-xl shadow-[#A8895F]/30 hover:bg-[#29241F] active:scale-[0.99] transition-all inline-flex items-center justify-center gap-2">
                <i data-lucide="truck" class="w-4 h-4"></i>
                Track My Order Status
            </button>

            @guest
            <div class="pt-3 mt-1 border-t border-[#D8C9B8]/70 text-center">
                <a href="{{ route('login') }}"
                   class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-[#A8895F] hover:text-[#29241F] transition-colors flex items-center justify-center gap-1.5">
                    <i data-lucide="user-round-check" class="w-4 h-4"></i>
                    Already a member? Sign in to see all orders automatically →
                </a>
            </div>
            @endguest
        </div>
    </form>

    @if($order)
    <div class="glass-panel p-8 polygon-card border border-[#D8C9B8] space-y-6 bg-[#F8F5EF] shadow-lg">
        <div class="flex flex-wrap justify-between items-start gap-4 pb-4 border-b border-[#D8C9B8]">
            <div>
                <span class="text-[10px] text-gray-600 uppercase tracking-[0.25em] block font-extrabold mb-1">Order Number</span>
                <span class="font-mono font-bold text-2xl tracking-wider text-[#29241F]">{{ $order->order_number }}</span>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#A8895F] text-white font-extrabold text-xs uppercase tracking-wider polygon-badge shadow">
                <i data-lucide="circle-dot" class="w-3.5 h-3.5"></i>
                {{ strtoupper($order->statusLabel ?? $order->status) }}
            </span>
        </div>

        <!-- Status Timeline -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center py-3">
            @php
                $statuses = [
                    ['code' => 'placed', 'label' => 'Placed', 'icon' => 'clipboard-list'],
                    ['code' => 'processing', 'label' => 'Preparing', 'icon' => 'flask-conical'],
                    ['code' => 'shipped', 'label' => 'Shipped', 'icon' => 'truck'],
                    ['code' => 'delivered', 'label' => 'Delivered', 'icon' => 'gift'],
                ];
                $statusLower = strtolower($order->status);
                $currentIndex = match($statusLower) {
                    'placed','new','pending' => 0,
                    'processing','confirmed','paid' => 1,
                    'shipped','in_transit','dispatched' => 2,
                    'delivered','completed' => 3,
                    default => -1,
                };
                $isCancelled = $statusLower === 'cancelled';
            @endphp

            @if($isCancelled)
            <div class="col-span-4 py-4 px-3 bg-rose-50 border border-rose-200 polygon-card">
                <p class="font-extrabold text-rose-700 uppercase tracking-[0.2em] flex items-center justify-center gap-1.5 text-sm">
                    <i data-lucide="ban" class="w-4 h-4"></i>
                    This order has been cancelled
                </p>
            </div>
            @endif

            @foreach($statuses as $idx => $st)
            @php
                $isDone = !$isCancelled && $idx <= $currentIndex;
                $isCurrent = !$isCancelled && $idx === $currentIndex;
            @endphp
            <div class="space-y-2">
                <div class="w-11 h-11 mx-auto rounded-full flex items-center justify-center font-bold text-xs shadow-sm
                     {{ $isDone ? 'bg-[#A8895F] text-white ring-2 ring-offset-2 ring-[#F8F5EF] ring-[#A8895F]/30' : 'bg-white text-gray-400 border-2 border-[#D8C9B8]' }}">
                    <i data-lucide="{{ $st['icon'] }}" class="w-4.5 h-4.5"></i>
                </div>
                <div class="space-y-0.5">
                    <span class="block text-[10px] font-extrabold uppercase tracking-[0.15em]
                          {{ $isDone ? 'text-[#A8895F]' : 'text-gray-400' }}">
                        {{ $st['label'] }}
                    </span>
                    @if($isCurrent && !$isCancelled)
                    <span class="block text-[9px] text-[#A8895F] font-extrabold uppercase tracking-widest animate-pulse">Now</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="pt-4 border-t border-[#D8C9B8] text-xs text-gray-700 space-y-2 font-bold">
            <div class="flex justify-between">
                <span>Customer:</span>
                <strong class="text-[#29241F] font-serif text-sm">{{ $order->customer_name }}</strong>
            </div>
            <div class="flex justify-between">
                <span>Delivery Address:</span>
                <strong class="text-[#29241F] font-serif text-sm max-w-[55%] text-right leading-snug">{{ $order->city }} — {{ $order->shipping_address }}</strong>
            </div>
            <div class="flex justify-between">
                <span>Total Amount:</span>
                <strong class="text-[#A8895F] font-extrabold text-sm">TZS {{ number_format($order->total_amount, 0) }}</strong>
            </div>
        </div>

        <div class="pt-4 flex flex-wrap gap-2.5 justify-end">
            <a href="{{ route('orders.show', $order->order_number) }}?tracking_contact={{ urlencode($contact) }}"
               class="px-5 py-2.5 bg-[#A8895F] text-white text-[10px] font-extrabold uppercase tracking-[0.22em] polygon-btn hover:bg-[#29241F] shadow-md inline-flex items-center gap-1.5">
                <i data-lucide="receipt-text" class="w-3.5 h-3.5"></i>
                View Full Order Details
            </a>
        </div>
    </div>
    @endif

</div>

<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
