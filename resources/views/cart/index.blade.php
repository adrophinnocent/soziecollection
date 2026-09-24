@extends('layouts.app')

@section('title', 'Your Shopping Cart | Sozie Collection')

@section('content')

<div class="py-12 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="mb-8 border-b border-[#D8C9B8] pb-4">
        <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">SHOPPING SELECTION</span>
        <h1 class="font-serif font-bold text-3xl sm:text-4xl text-[#29241F]">YOUR FRAGRANCE CART</h1>
    </div>

    @if(empty($cart))
    <div class="glass-panel p-16 text-center polygon-card border border-[#D8C9B8] bg-[#F8F5EF]">
        <i data-lucide="shopping-bag" class="w-12 h-12 text-[#A8895F] mx-auto mb-3"></i>
        <h3 class="font-serif font-bold text-2xl text-[#29241F]">Your cart is currently empty</h3>
        <p class="text-xs text-gray-700 mt-2 font-semibold">Discover signature scents in our perfume catalog.</p>
        <a href="{{ route('shop.index') }}" class="inline-block mt-6 px-8 py-3 bg-[#A8895F] text-white font-extrabold text-xs uppercase polygon-btn hover:bg-[#29241F]">
            EXPLORE PERFUMES
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Cart Items Table -->
        <div class="lg:col-span-8 space-y-4">
            @foreach($cart as $item)
            <div class="navy-card p-4 polygon-card border border-[#D8C9B8] flex flex-col sm:flex-row gap-4 items-center justify-between bg-[#F8F5EF]">
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-20 h-20 object-cover polygon-card border border-[#D8C9B8]">
                    <div>
                        <h3 class="font-serif font-bold text-lg text-[#29241F]">{{ $item['name'] }}</h3>
                        <span class="text-xs font-extrabold text-[#A8895F] block">{{ $item['size'] }}</span>
                        <span class="text-xs text-gray-700 font-semibold">TZS {{ number_format($item['price'], 0) }} per unit</span>
                    </div>
                </div>

                <div class="flex items-center gap-6 w-full sm:w-auto justify-between sm:justify-end">
                    <!-- Quantity Controls -->
                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-1">
                        @csrf
                        <input type="hidden" name="cart_key" value="{{ $item['cart_key'] }}">
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                               onchange="this.form.submit()"
                               class="w-16 bg-white border border-[#D8C9B8] text-xs text-[#29241F] font-extrabold text-center py-1.5 focus:outline-none focus:border-[#A8895F]">
                    </form>

                    <span class="font-serif font-bold text-base text-[#29241F]">
                        TZS {{ number_format($item['price'] * $item['quantity'], 0) }}
                    </span>

                    <form action="{{ route('cart.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="cart_key" value="{{ $item['cart_key'] }}">
                        <button type="submit" class="text-gray-400 hover:text-rose-600 p-2">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Summary & Proceed -->
        <div class="lg:col-span-4">
            <div class="glass-panel-gold p-6 polygon-card border border-[#A8895F]/40 space-y-4 bg-[#F8F5EF]">
                <h3 class="font-serif font-bold text-xl text-[#29241F] pb-3 border-b border-[#D8C9B8]">ORDER SUMMARY</h3>

                @php
                    $subtotal = array_reduce($cart, function($acc, $i) { return $acc + ($i['price'] * $i['quantity']); }, 0);
                    $shipping = 5000;
                    $total = $subtotal + $shipping;
                @endphp

                <div class="space-y-2 text-xs text-gray-700 font-bold">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>TZS {{ number_format($subtotal, 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Flat Delivery (Dar es Salaam)</span>
                        <span>TZS {{ number_format($shipping, 0) }}</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-[#D8C9B8] flex justify-between items-center">
                    <span class="font-serif font-bold text-base text-[#29241F]">TOTAL</span>
                    <span class="font-serif font-bold text-2xl text-[#A8895F]">TZS {{ number_format($total, 0) }}</span>
                </div>

                <a href="{{ route('checkout.index') }}"
                   class="w-full py-4 bg-[#A8895F] text-white font-extrabold text-xs uppercase tracking-[0.2em] polygon-btn text-center block hover:bg-[#29241F] shadow-xl shadow-[#A8895F]/20">
                    PROCEED TO CHECKOUT
                </a>
            </div>
        </div>

    </div>
    @endif

</div>

@endsection
