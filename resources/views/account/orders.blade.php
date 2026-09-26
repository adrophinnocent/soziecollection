@extends('layouts.app')

@section('title', __('My Orders | Sozie Collection'))

@section('content')
@include('account._sidebar_layout', [
    'account_title' => __('My Orders'),
    'account_subtitle' => __('Every order with Sozie Collection. Track delivery status, view invoices, and reorder your favorite fragrances with a single click.')
])
@endsection

@section('account_content')
<div class="glass-panel border border-[#D8C9B8] polygon-card bg-[#F8F5EF] p-5 shadow-md mb-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <form action="{{ route('orders.track.submit') }}" method="POST" class="flex flex-wrap items-stretch gap-2">
                @csrf
                <input type="text" name="order_number" placeholder="{{ __('Find by order number (e.g. SOZ-20260924-001)') }}"
                       class="min-w-[280px] bg-white border border-[#D8C9B8] text-[11px] text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold placeholder:font-bold">
                <button type="submit"
                        class="px-4 py-2 bg-[#29241F] text-white text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn hover:bg-[#A8895F] transition-all">
                    {{ __('Search') }}
                </button>
            </form>
        </div>
        <a href="{{ route('shop.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#A8895F] text-white text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn hover:bg-[#29241F] shadow-md">
            <i data-lucide="store" class="w-3.5 h-3.5"></i>
            {{ __('Order New Fragrance') }}
        </a>
    </div>
</div>

<div class="glass-panel border border-[#D8C9B8] polygon-card bg-[#F8F5EF] p-6 shadow-md">
    @if($orders->isEmpty())
    <div class="py-20 text-center">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-[#A8895F]/10 border border-[#A8895F]/30 flex items-center justify-center">
            <i data-lucide="package-open" class="w-10 h-10 text-[#A8895F]/60"></i>
        </div>
        <h3 class="font-serif font-bold text-2xl text-[#29241F] mb-2">{{ __('Your fragrance journey is just beginning') }}</h3>
        <p class="text-sm text-gray-500 font-medium max-w-md mx-auto mb-7">
            {{ __('Once you place an order, it will appear here with real-time tracking from atelier prep through final delivery to your doorstep.') }}
        </p>
        <a href="{{ route('shop.index') }}"
           class="inline-block px-7 py-3 bg-[#A8895F] text-white text-xs font-extrabold uppercase tracking-[0.3em] polygon-btn hover:bg-[#29241F] shadow-lg">
            {{ __('Discover the Collection') }}
        </a>
    </div>
    @else
    <div class="space-y-5">
        @foreach($orders as $order)
        <div class="border border-[#D8C9B8] bg-white polygon-card p-5 hover:shadow-md transition-shadow">
            <div class="flex flex-wrap items-start justify-between gap-4 mb-4 pb-4 border-b border-[#D8C9B8]/60">
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="font-mono font-extrabold text-sm tracking-wider text-[#29241F]">{{ $order->order_number }}</span>
                        <span class="inline-block px-3 py-1 rounded text-[9px] uppercase tracking-[0.18em] font-extrabold {{ $order->statusBadgeClass }}">
                            {{ $order->statusLabel }}
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-4 mt-2 text-[11px] text-gray-600 font-bold">
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="calendar-days" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                            {{ $order->created_at?->format('F d, Y') ?? '—' }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                            {{ $order->shipping_city ?? ($order->address_city ?? __('City not set')) }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="list-checks" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                            {{ $order->items?->count() ?? 0 }} {{ __('items') }}
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-extrabold text-gray-500 uppercase tracking-[0.2em] block mb-1">{{ __('Order Total') }}</span>
                    <span class="font-serif font-bold text-2xl text-[#A8895F]">TZS {{ number_format($order->total_amount, 0) }}</span>
                </div>
            </div>

            @if($order->items?->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-3 mb-4">
                @foreach($order->items->take(5) as $item)
                <div class="flex flex-col gap-1.5 text-center">
                    <div class="aspect-square bg-[#EDE5D8] polygon-card border border-[#D8C9B8] overflow-hidden">
                        @if(!empty($item->product?->primary_image))
                        <img src="{{ $item->product->primary_image }}" data-sozie-fallback loading="lazy" decoding="async" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-[#A8895F]/60">
                            <i data-lucide="bottle-wine" class="w-6 h-6"></i>
                        </div>
                        @endif
                    </div>
                    <div class="min-h-0">
                        <p class="text-[10px] font-bold text-[#29241F] line-clamp-2 leading-tight">{{ $item->name }}</p>
                        <p class="text-[9px] text-[#A8895F] font-extrabold mt-0.5">
                            @if(!empty($item->size))<span class="uppercase">{{ $item->size }}</span> • @endif
                            × {{ $item->quantity }}
                        </p>
                    </div>
                </div>
                @endforeach
                @if($order->items->count() > 5)
                <div class="aspect-square bg-[#29241F] text-[#F8F5EF] polygon-card border border-[#A8895F]/30 flex flex-col items-center justify-center gap-1">
                    <span class="font-serif font-bold text-xl">+{{ $order->items->count() - 5 }}</span>
                    <span class="text-[9px] uppercase tracking-[0.2em] font-extrabold text-[#A8895F]">{{ __('More') }}</span>
                </div>
                @endif
            </div>
            @endif

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('account.orders.show', $order->order_number) }}"
                   class="px-4 py-2 bg-[#A8895F] text-white text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn hover:bg-[#29241F] transition-all inline-flex items-center gap-2">
                    <i data-lucide="receipt-text" class="w-3.5 h-3.5"></i>
                    {{ __('View Order Details') }}
                </a>
                <a href="{{ route('orders.track.submit') }}"
                   onclick="event.preventDefault(); document.getElementById('track-form-{{ $order->order_number }}').submit();"
                   class="px-4 py-2 bg-white border border-[#A8895F]/50 text-[#29241F] text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn hover:bg-[#EDE5D8] transition-all inline-flex items-center gap-2">
                    <i data-lucide="truck" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                    {{ __('Track Shipment') }}
                </a>
                <form id="track-form-{{ $order->order_number }}" action="{{ route('orders.track.submit') }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="order_number" value="{{ $order->order_number }}">
                    <input type="hidden" name="tracking_contact" value="{{ $order->phone ?? $order->email ?? '' }}">
                </form>
            </div>
        </div>
        @endforeach
    </div>

    @if($orders->hasPages())
    <div class="pt-6 mt-4 border-t border-[#D8C9B8]/60">
        {{ $orders->links() }}
    </div>
    @endif
    @endif
</div>
@endsection
