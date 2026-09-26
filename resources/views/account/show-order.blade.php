@extends('layouts.app')

@section('title', __('Order :number | Sozie Collection', ['number' => $order->order_number]))

@section('content')
@include('account._sidebar_layout', [
    'account_title' => __('Order #:number', ['number' => $order->order_number]),
    'account_subtitle' => __('Complete order details, fragrance line-up, and real-time delivery status for this Sozie Collection order.')
])
@endsection

@section('account_content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('account.orders') }}"
           class="inline-flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-[0.2em] text-[#A8895F] hover:text-[#29241F] transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            {{ __('Back to All Orders') }}
        </a>
        <div class="flex flex-wrap items-center gap-2.5">
            <span class="inline-block px-4 py-1.5 rounded text-[10px] uppercase tracking-[0.2em] font-extrabold {{ $order->statusBadgeClass }} shadow-sm">
                <span class="flex items-center gap-1.5">
                    <i data-lucide="circle-dot" class="w-3.5 h-3.5"></i>
                    {{ $order->statusLabel }}
                </span>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 space-y-5">
            <div class="glass-panel-gold border-2 border-[#A8895F]/25 polygon-card bg-[#F8F5EF] p-6 shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-11 h-11 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center">
                        <i data-lucide="route" class="w-5 h-5 text-[#A8895F]"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-0.5">{{ __('Order Timeline') }}</span>
                        <h3 class="font-serif font-bold text-xl text-[#29241F]">{{ __('Live Delivery Progress') }}</h3>
                    </div>
                </div>

                <div class="relative pl-8 pb-1">
                    <ol class="relative border-l-2 border-[#D8C9B8] ml-2 space-y-7">
                        @php
                            $steps = [
                                ['key' => 'placed', 'icon' => 'clipboard-list', 'label' => __('Order Placed'), 'desc' => __('Your fragrance selections received & verified at the Sozie atelier.')],
                                ['key' => 'processing', 'icon' => 'flask-conical', 'label' => __('Processing'), 'desc' => __('Each bottle is hand-prepared, wrapped, and quality inspected by our team.')],
                                ['key' => 'shipped', 'icon' => 'truck', 'label' => __('Shipped'), 'desc' => __('Package in transit. Signature delivery scheduled to your saved address.')],
                                ['key' => 'delivered', 'icon' => 'gift', 'label' => __('Delivered'), 'desc' => __('Delivered! Enjoy your new signature scent. Welcome to Sozie Collection.')],
                            ];
                            $currentStep = match(strtolower($order->status)) {
                                'placed', 'pending' => 0,
                                'processing' => 1,
                                'shipped' => 2,
                                'delivered', 'completed' => 3,
                                default => -1,
                            };
                            $isCancelled = strtolower($order->status) === 'cancelled';
                        @endphp

                        @if($isCancelled)
                        <li class="relative">
                            <span class="absolute -left-[41px] flex h-9 w-9 items-center justify-center rounded-full bg-rose-600 border-4 border-[#F8F5EF] shadow-md">
                                <i data-lucide="ban" class="w-4 h-4 text-white"></i>
                            </span>
                            <h5 class="font-extrabold text-sm text-rose-700 uppercase tracking-[0.15em]">{{ __('Order Cancelled') }}</h5>
                            <p class="text-[11px] text-gray-600 font-medium mt-1 leading-relaxed">{{ __('This order was cancelled. Please contact Sozie Collection support for any questions.') }}</p>
                        </li>
                        @else
                        @foreach($steps as $idx => $step)
                        @php
                            $isDone = $idx <= $currentStep;
                            $isCurrent = $idx === $currentStep;
                        @endphp
                        <li class="relative">
                            <span class="absolute -left-[41px] flex h-9 w-9 items-center justify-center rounded-full border-4 border-[#F8F5EF] shadow-md
                                {{ $isDone ? 'bg-[#A8895F] text-white' : 'bg-[#EDE5D8] text-[#A8895F]/60 border-[#D8C9B8]' }}">
                                <i data-lucide="{{ $step['icon'] }}" class="w-4 h-4"></i>
                            </span>
                            <h5 class="font-extrabold text-sm uppercase tracking-[0.15em] {{ $isDone ? 'text-[#29241F]' : 'text-gray-400' }}">
                                {{ $step['label'] }}
                                @if($isCurrent)
                                <span class="ml-2 inline-block px-2 py-0.5 bg-[#A8895F]/20 text-[#A8895F] text-[9px] uppercase tracking-wider rounded animate-pulse font-extrabold">{{ __('Now') }}</span>
                                @endif
                            </h5>
                            <p class="text-[11px] {{ $isDone ? 'text-gray-600' : 'text-gray-400' }} font-medium mt-1 leading-relaxed">{{ $step['desc'] }}</p>
                        </li>
                        @endforeach
                        @endif
                    </ol>
                </div>
            </div>

            <div class="glass-panel border border-[#D8C9B8] polygon-card bg-[#F8F5EF] p-6 shadow-md">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">{{ __('Fragrances in This Order') }}</span>
                        <h3 class="font-serif font-bold text-xl text-[#29241F]">{{ __('Items Summary') }}</h3>
                    </div>
                    <span class="text-[11px] font-extrabold text-gray-500 uppercase tracking-wider">
                        {{ $order->items?->count() ?? 0 }} {{ __('item(s)') }}
                    </span>
                </div>

                <div class="space-y-3">
                    @if($order->items?->isEmpty())
                    <div class="text-center py-12 text-gray-500 text-xs font-bold">{{ __('No line items found.') }}</div>
                    @else
                    @foreach($order->items as $item)
                    <div class="flex gap-4 p-3 bg-white border border-[#D8C9B8] polygon-card hover:border-[#A8895F]/50 transition-colors">
                        <div class="w-20 h-20 flex-shrink-0 bg-[#EDE5D8] polygon-card border border-[#D8C9B8] overflow-hidden">
                            @if(!empty($item->product?->primary_image))
                            <img src="{{ $item->product->primary_image }}" loading="lazy" decoding="async" alt="{{ $item->name }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-[#A8895F]/60">
                                <i data-lucide="bottle-wine" class="w-7 h-7"></i>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                            <div>
                                <p class="text-xs font-extrabold text-[#29241F] leading-snug mb-1">{{ $item->name }}</p>
                                <div class="flex flex-wrap gap-2 text-[10px] font-bold">
                                    @if(!empty($item->size))
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-[#EDE5D8] text-[#29241F] rounded uppercase tracking-wider polygon-badge">
                                        {{ $item->size }}
                                    </span>
                                    @endif
                                    <span class="text-gray-500">SKU {{ $item->sku ?? ($item->product?->sku ?? '—') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-2">
                                <span class="text-[10px] font-bold text-gray-600 uppercase tracking-wider">{{ __('Qty:') }} {{ $item->quantity }}</span>
                                <span class="font-serif font-bold text-base text-[#A8895F]">
                                    TZS {{ number_format(($item->price ?? 0) * ($item->quantity ?? 1), 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>

        <aside class="space-y-5">
            <div class="glass-panel-gold border-2 border-[#A8895F]/25 polygon-card bg-[#F8F5EF] p-5 shadow-xl">
                <h4 class="font-serif font-bold text-lg text-[#29241F] mb-4 pb-3 border-b border-[#D8C9B8]">
                    {{ __('Payment & Totals') }}
                </h4>
                <dl class="space-y-3 text-[11px] font-bold">
                    <div class="flex justify-between text-gray-600">
                        <dt>{{ __('Subtotal') }}</dt>
                        <dd>TZS {{ number_format(($order->items?->sum(fn($i) => ($i->price ?? 0) * ($i->quantity ?? 1))) ?? 0, 0) }}</dd>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <dt>{{ __('Shipping Fee') }}</dt>
                        <dd>TZS {{ number_format($order->shipping_cost ?? 0, 0) }}</dd>
                    </div>
                    @if(!empty($order->discount_code) || !empty($order->discount_amount))
                    <div class="flex justify-between text-emerald-700">
                        <dt>{{ __('Discount') }} {{ !empty($order->discount_code) ? "($order->discount_code)" : '' }}</dt>
                        <dd>− TZS {{ number_format($order->discount_amount ?? 0, 0) }}</dd>
                    </div>
                    @endif
                    <div class="flex justify-between items-center pt-3 mt-3 border-t border-[#D8C9B8] text-[#29241F]">
                        <dt class="text-[10px] uppercase tracking-[0.2em] font-extrabold text-[#A8895F]">{{ __('Total Paid') }}</dt>
                        <dd class="font-serif font-bold text-2xl text-[#29241F]">
                            TZS {{ number_format($order->total_amount ?? 0, 0) }}
                        </dd>
                    </div>
                </dl>
                <div class="mt-4 pt-4 border-t border-[#D8C9B8] space-y-2">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-gray-500 font-bold uppercase tracking-wider">{{ __('Payment') }}</span>
                        <span class="font-extrabold text-[#29241F]">{{ $order->payment_method ?? __('Cash on Delivery') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-gray-500 font-bold uppercase tracking-wider">{{ __('Status') }}</span>
                        <span class="font-extrabold text-[#29241F]">{{ $order->payment_status ?? __('Pending') }}</span>
                    </div>
                </div>
            </div>

            <div class="glass-panel border border-[#D8C9B8] polygon-card bg-[#F8F5EF] p-5 shadow-md">
                <h4 class="font-serif font-bold text-base text-[#29241F] mb-4 pb-3 border-b border-[#D8C9B8] flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-[#A8895F]"></i>
                    {{ __('Delivery Address') }}
                </h4>
                <address class="not-italic text-xs text-gray-700 font-medium leading-relaxed space-y-1.5">
                    <p class="font-extrabold text-[#29241F] text-sm">
                        {{ $order->shipping_name ?? $user->name }}
                    </p>
                    @if(!empty($order->phone))
                    <p class="flex items-center gap-1.5 text-[11px] font-bold">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                        {{ $order->phone }}
                    </p>
                    @endif
                    @if(!empty($order->email))
                    <p class="flex items-center gap-1.5 text-[11px] font-bold">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                        {{ $order->email }}
                    </p>
                    @endif
                    <p class="pt-1">
                        {{ $order->shipping_address ?? $order->address_street ?? __('No street address recorded') }}
                    </p>
                    <p>{{ $order->shipping_city ?? $order->address_city ?? __('City') }}</p>
                </address>
            </div>

            <div class="flex flex-col gap-2.5">
                <a href="{{ route('shop.index') }}"
                   class="px-5 py-3 bg-[#A8895F] text-white text-[10px] font-extrabold uppercase tracking-[0.25em] polygon-btn hover:bg-[#29241F] transition-all text-center shadow-md inline-flex items-center justify-center gap-2">
                    <i data-lucide="store" class="w-4 h-4"></i>
                    {{ __('Order New Scents') }}
                </a>
                <a href="https://wa.me/{{ config('payment.whatsapp.phone_number') }}?text=Jambo%20Sozie%20Collection%2C%20natafuta%20msaada%20kwa%20order%20{{ $order->order_number }}"
                   target="_blank"
                   class="px-5 py-3 bg-emerald-900 text-white text-[10px] font-extrabold uppercase tracking-[0.25em] polygon-btn hover:bg-emerald-950 transition-all text-center inline-flex items-center justify-center gap-2 border border-emerald-700">
                    <i data-lucide="message-circle" class="w-4 h-4 text-emerald-300"></i>
                    {{ __('WhatsApp Support') }}
                </a>
            </div>
        </aside>
    </div>
</div>
@endsection
