@extends('layouts.app')

@section('title', __('Shop Perfumes | Sozie Collection'))

@section('content')

<!-- Header Banner -->
<div class="bg-gradient-to-r from-[#061338] via-[#0E2566] to-[#061338] border-b border-[#B39A84]/30 py-12 relative overflow-hidden transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <span class="text-xs font-extrabold text-[#B39A84] uppercase tracking-[0.3em] block mb-1">{{ __('SOZIE FRAGRANCE CATALOG') }}</span>
        <h1 class="font-serif font-bold text-4xl sm:text-5xl text-[#F9F0EE]">{{ __('THE FULL COLLECTION') }}</h1>
        <p class="text-xs sm:text-sm text-[#CFC7C8] mt-2 font-semibold">{{ __('Explore handcrafted women\'s, men\'s, and unisex luxury perfumes, oils, and gift sets.') }}</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- SIDEBAR FILTERS -->
        <div class="space-y-8">
            <form action="{{ route('shop.index') }}" method="GET" class="glass-panel p-6 polygon-card border border-[#B39A84]/30 space-y-6 bg-[#061338]">

                <!-- Search Input -->
                <div>
                    <label class="block text-xs font-extrabold text-[#B39A84] uppercase tracking-widest mb-2">{{ __('Search') }}</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Fragrance name, notes...') }}"
                           class="w-full bg-[#081944] border border-[#B39A84]/40 text-xs text-[#F9F0EE] px-3 py-2.5 focus:outline-none focus:border-[#B39A84] polygon-card font-bold">
                </div>

                <!-- Gender Filter -->
                <div>
                    <label class="block text-xs font-extrabold text-[#B39A84] uppercase tracking-widest mb-2">{{ __('Gender') }}</label>
                    <div class="space-y-2 text-xs text-[#F9F0EE]">
                        <label class="flex items-center gap-2 cursor-pointer font-bold">
                            <input type="radio" name="gender" value="" {{ !request('gender') ? 'checked' : '' }} class="accent-[#B39A84]">
                            <span>{{ __('All Genders') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-bold">
                            <input type="radio" name="gender" value="women" {{ request('gender') === 'women' ? 'checked' : '' }} class="accent-[#B39A84]">
                            <span>{{ __("Women's Fragrances") }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-bold">
                            <input type="radio" name="gender" value="men" {{ request('gender') === 'men' ? 'checked' : '' }} class="accent-[#B39A84]">
                            <span>{{ __("Men's Fragrances") }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-bold">
                            <input type="radio" name="gender" value="unisex" {{ request('gender') === 'unisex' ? 'checked' : '' }} class="accent-[#B39A84]">
                            <span>{{ __('Unisex') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-xs font-extrabold text-[#B39A84] uppercase tracking-widest mb-2">{{ __('Category') }}</label>
                    <select name="category" class="w-full bg-[#081944] border border-[#B39A84]/40 text-xs text-[#F9F0EE] px-3 py-2.5 focus:outline-none focus:border-[#B39A84] font-bold">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->products_count }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Scent Profile Filter -->
                <div>
                    <label class="block text-xs font-extrabold text-[#B39A84] uppercase tracking-widest mb-2">{{ __('Scent Note') }}</label>
                    <select name="scent_type" class="w-full bg-[#081944] border border-[#B39A84]/40 text-xs text-[#F9F0EE] px-3 py-2.5 focus:outline-none focus:border-[#B39A84] font-bold">
                        <option value="">{{ __('All Scent Notes') }}</option>
                        @foreach($scentTypes as $st)
                        <option value="{{ $st }}" {{ request('scent_type') === $st ? 'selected' : '' }}>
                            {{ $st }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Collections Filter -->
                <div>
                    <label class="block text-xs font-extrabold text-[#B39A84] uppercase tracking-widest mb-2">{{ __('Collection') }}</label>
                    <div class="space-y-2 text-xs text-[#F9F0EE]">
                        <label class="flex items-center gap-2 cursor-pointer font-bold">
                            <input type="radio" name="collection" value="" {{ !request('collection') ? 'checked' : '' }} class="accent-[#B39A84]">
                            <span>{{ __('All') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-bold">
                            <input type="radio" name="collection" value="best_seller" {{ request('collection') === 'best_seller' ? 'checked' : '' }} class="accent-[#B39A84]">
                            <span>{{ __('Best Sellers') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-bold">
                            <input type="radio" name="collection" value="new_arrival" {{ request('collection') === 'new_arrival' ? 'checked' : '' }} class="accent-[#B39A84]">
                            <span>{{ __('New Arrivals') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Sort By -->
                <div>
                    <label class="block text-xs font-extrabold text-[#B39A84] uppercase tracking-widest mb-2">{{ __('Sort By') }}</label>
                    <select name="sort" class="w-full bg-[#081944] border border-[#B39A84]/40 text-xs text-[#F9F0EE] px-3 py-2.5 focus:outline-none focus:border-[#B39A84] font-bold">
                        <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>{{ __('Featured') }}</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>{{ __('Newest Releases') }}</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-2 pt-2">
                    <button type="submit" class="w-full py-3 bg-[#B39A84] border border-[#B39A84] text-[#0A1E54] font-extrabold text-xs uppercase tracking-widest polygon-btn hover:bg-[#917B68] hover:text-white">
                        {{ __('APPLY FILTERS') }}
                    </button>
                    <a href="{{ route('shop.index') }}" class="w-full py-2 bg-white/5 border border-white/10 text-gray-300 font-bold text-xs uppercase tracking-widest polygon-btn text-center block hover:bg-white/10">
                        {{ __('RESET') }}
                    </a>
                </div>

            </form>
        </div>

        <!-- PRODUCTS GRID -->
        <div class="lg:col-span-3 space-y-6">

            <div class="flex justify-between items-center text-xs text-[#F9F0EE] font-bold">
                <span>{!! __('Showing <strong class="text-[#8F6E3B] font-extrabold">:count</strong> perfumes', ['count' => $products->total()]) !!}</span>
            </div>

            @if($products->isEmpty())
            <div class="glass-panel p-16 text-center polygon-card border border-[#B39A84]/30 bg-[#061338]">
                <i data-lucide="frown" class="w-12 h-12 text-[#B39A84] mx-auto mb-3"></i>
                <h3 class="font-serif font-bold text-2xl text-[#F9F0EE]">{{ __('No perfumes found') }}</h3>
                <p class="text-xs text-[#CFC7C8] mt-2 font-bold">{{ __('Try adjusting your filter preferences or search term.') }}</p>
                <a href="{{ route('shop.index') }}" class="inline-block mt-6 px-6 py-2.5 bg-[#B39A84] text-[#0A1E54] font-extrabold text-xs uppercase polygon-btn hover:bg-[#917B68] hover:text-white">
                    {{ __('View All Products') }}
                </a>
            </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                <div class="navy-card p-4 sm:p-5 polygon-card border border-[#B39A84]/40 group hover:border-[#B39A84] transition-all duration-300 flex flex-col justify-between relative bg-[#081944]">

                    @if($product->discount_percentage)
                    <span class="absolute top-6 left-6 z-20 bg-[#0A1E54] border border-[#B39A84] text-[#B39A84] text-[9px] font-extrabold uppercase px-2.5 py-1 polygon-badge shadow-md">
                        -{{ $product->discount_percentage }}%
                    </span>
                    @endif

                    <!-- Wishlist Heart Button -->
                    <button @click="toggleWishlist({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: '{{ $product->formatted_price }}', image: '{{ $product->primary_image }}' })"
                            :class="isInWishlist({{ $product->id }}) ? '[&_svg]:fill-rose-400 [&_svg]:text-rose-400' : ''"
                            class="absolute top-6 right-6 z-20 p-2 bg-black/60 rounded-full text-white hover:text-rose-400 transition-colors shadow-sm">
                        <i data-lucide="heart" class="w-4 h-4"></i>
                    </button>

                    <div>
                        <!-- Spacious Vertical Image Container for Mobile & Desktop -->
                        <div class="w-full h-72 sm:h-84 lg:h-96 bg-[#061338] polygon-card overflow-hidden mb-4 relative border border-[#B39A84]/30 flex items-center justify-center p-4">
                            <img src="{{ $product->primary_image }}" data-sozie-fallback loading="lazy" decoding="async"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-contain filter drop-shadow-2xl group-hover:scale-105 transition-transform duration-500">

                            <div class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 p-3 backdrop-blur-xs">
                                <button @click="$dispatch('open-quickview', { id: {{ $product->id }} })"
                                        class="px-4 py-2 bg-[#B39A84] border border-[#B39A84] text-[#0A1E54] font-extrabold text-xs uppercase tracking-wider polygon-btn hover:bg-[#917B68] hover:text-white">
                                    {{ __('QUICK VIEW') }}
                                </button>
                            </div>
                        </div>

                        <span class="text-[10px] font-extrabold text-[#B39A84] uppercase tracking-widest block mb-1">
                            {{ $product->gender }} • {{ $product->category ? $product->category->name : __('Fragrance') }}
                        </span>

                        <a href="{{ route('shop.show', $product->slug) }}">
                            <h3 class="font-serif font-bold text-xl text-[#F9F0EE] group-hover:text-[#B39A84] transition-colors">
                                {{ $product->name }}
                            </h3>
                        </a>

                        <p class="text-xs text-[#CFC7C8] font-bold mt-1 line-clamp-1">{{ $product->top_notes }}</p>
                    </div>

                    <div class="pt-4 mt-4 border-t border-[#B39A84]/30 flex items-center justify-between">
                        <div>
                            <span class="text-sm font-extrabold text-[#B39A84] block">{{ $product->formatted_price }}</span>
                            @if($product->discount_price)
                            <span class="text-[10px] text-gray-400 line-through font-semibold">{{ $product->formatted_original_price }}</span>
                            @endif
                        </div>

                        <button @click="addToCart({{ $product->id }}, '{{ $product->default_size }}')"
                                class="p-2.5 bg-[#B39A84] border border-[#B39A84] text-[#0A1E54] polygon-btn hover:bg-[#917B68] hover:text-white transition-colors shadow-md">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        </button>
                    </div>

                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-8">
                {{ $products->links() }}
            </div>
            @endif

        </div>

    </div>
</div>

@endsection
