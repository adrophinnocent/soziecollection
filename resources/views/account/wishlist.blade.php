@extends('layouts.app')

@section('title', __('Saved Wishlist | Sozie Collection'))

@section('content')
@include('account._sidebar_layout', [
    'account_title' => __('Saved Wishlist'),
    'account_subtitle' => __('Your curated collection of Sozie Collection fragrances. Save scents across devices and move them to cart whenever you\'re ready to own them.')
])
@endsection

@section('account_content')
<div class="glass-panel border border-[#322B23] polygon-card bg-[#17130F] p-5 shadow-md mb-5 flex flex-wrap items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center">
            <i data-lucide="heart" class="w-5 h-5 text-[#A8895F]"></i>
        </div>
        <div>
            <h3 class="font-serif font-bold text-lg text-[#EDE5D8]">{{ $wishlistItems->count() }} {{ __('Scents Saved') }}</h3>
            <p class="text-[11px] text-[#A89C8C] font-bold">{{ __('Your wishlist is synced to this account across every device.') }}</p>
        </div>
    </div>
    <a href="{{ route('shop.index') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#A8895F] text-[#12100E] text-[10px] font-extrabold uppercase tracking-[0.25em] polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF] shadow-md">
        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
        {{ __('Add New Scent') }}
    </a>
</div>

<div class="glass-panel border border-[#322B23] polygon-card bg-[#17130F] p-6 shadow-md">
    @if($wishlistItems->isEmpty())
    <div class="py-20 text-center">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-[#A8895F]/10 border border-[#A8895F]/30 flex items-center justify-center">
            <i data-lucide="heart-handshake" class="w-10 h-10 text-[#A8895F]"></i>
        </div>
        <h3 class="font-serif font-bold text-2xl text-[#EDE5D8] mb-2">{{ __('Your wishlist awaits beautiful scents') }}</h3>
        <p class="text-sm text-[#A89C8C] font-medium max-w-md mx-auto mb-7">
            {{ __('Tap the heart icon on any perfume card to curate your personal Sozie Collection shortlist — ready to order whenever inspiration strikes.') }}
        </p>
        <a href="{{ route('shop.index') }}"
           class="inline-block px-7 py-3 bg-[#A8895F] text-[#12100E] text-xs font-extrabold uppercase tracking-[0.3em] polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF] shadow-lg inline-flex items-center gap-2">
            <i data-lucide="store" class="w-4 h-4"></i>
            {{ __('Browse All Perfumes') }}
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($wishlistItems as $wItem)
        @php($product = $wItem->product)
        <div class="navy-card p-4 polygon-card flex flex-col bg-[#17130F] border border-[#322B23] relative overflow-hidden group hover:shadow-xl hover:border-[#A8895F]/50 transition-all">
            <div class="relative aspect-square bg-[#0C0A09] polygon-card overflow-hidden mb-4 border border-[#322B23]">
                @if(!empty($product?->primary_image))
                <a href="{{ $product ? route('shop.show', $product->slug) : '#' }}">
                    <img src="{{ $product->primary_image }}" data-sozie-fallback loading="lazy" decoding="async" alt="{{ $product?->name ?? __('Scent') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </a>
                @else
                <div class="w-full h-full flex flex-col items-center justify-center text-[#A8895F] gap-2">
                    <i data-lucide="bottle-wine" class="w-12 h-12"></i>
                    <span class="text-[10px] font-extrabold uppercase tracking-[0.25em]">{{ __('Scent Preview') }}</span>
                </div>
                @endif

                <form action="{{ route('wishlist.remove') }}" method="POST" class="absolute top-2 right-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $wItem->product_id }}">
                    <button type="submit"
                            class="w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-md text-rose-500 hover:text-[#9F1239] hover:bg-[#EDE5D8] transition-all border border-rose-900/50">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </form>

                @if(!empty($product?->category))
                <span class="absolute bottom-2 left-2 polygon-badge bg-[#29241F]/90 text-[#F8F5EF] text-[9px] font-extrabold uppercase tracking-[0.2em] px-2.5 py-1">
                    {{ $product->category }}
                </span>
                @endif
            </div>

            <div class="flex-1 flex flex-col min-h-0">
                <div class="mb-2">
                    @if($product)
                    <a href="{{ route('shop.show', $product->slug) }}"
                       class="font-serif font-bold text-base text-[#EDE5D8] leading-tight hover:text-[#A8895F] transition-colors line-clamp-2">
                        {{ $product->name }}
                    </a>
                    @else
                    <span class="font-serif font-bold text-base text-[#EDE5D8] leading-tight line-clamp-2">
                        {{ $wItem->product_id ? __('Product #:id', ['id' => $wItem->product_id]) : __('Saved Scent') }}
                    </span>
                    @endif
                    @if(!empty($product?->tagline))
                    <p class="text-[10px] text-[#A89C8C] font-medium line-clamp-1 mt-1">{{ $product->tagline }}</p>
                    @endif
                </div>
                <div class="mt-auto space-y-3 pt-3 border-t border-[#322B23]/60">
                    <div class="flex items-center justify-between">
                        <span class="font-serif font-bold text-xl text-[#A8895F]">
                            {{ $product?->formatted_price ?? 'TZS —' }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-[9px] font-extrabold uppercase tracking-[0.2em] text-emerald-400">
                            <i data-lucide="badge-check" class="w-3.5 h-3.5"></i>
                            {{ __('In Stock') }}
                        </span>
                    </div>
                    <form action="{{ route('wishlist.move_to_cart') }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $wItem->product_id }}">
                        <input type="hidden" name="quantity" value="1">
                        @if(!empty($product?->variants) && is_iterable($product->variants) && count((array)$product->variants) > 0)
                        <select name="size"
                                class="w-full mb-2 bg-[#0C0A09] border border-[#322B23] text-[10px] text-[#EDE5D8] px-2.5 py-1.5 font-bold uppercase tracking-wider focus:outline-none focus:border-[#A8895F] polygon-btn">
                            <option value="">{{ __('Select Size') }}</option>
                            @foreach((array)$product->variants as $variant)
                            @php($size = is_array($variant) ? ($variant['size'] ?? null) : (is_object($variant) ? ($variant->size ?? null) : null))
                            @if(!empty($size))
                            <option value="{{ $size }}">{{ strtoupper($size) }}</option>
                            @endif
                            @endforeach
                        </select>
                        @endif
                        <button type="submit"
                                class="w-full px-4 py-2.5 bg-[#A8895F] text-[#12100E] text-[10px] font-extrabold uppercase tracking-[0.22em] polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF] transition-all shadow-md inline-flex items-center justify-center gap-2">
                            <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i>
                            {{ __('Move to Cart') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
