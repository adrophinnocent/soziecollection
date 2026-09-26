@extends('layouts.app')

@section('title', __('Sozie Collection | Luxury Perfumes & Signature Scents'))

@section('content')

<!-- ================================================================= -->
<!-- SECTION 01: HERO / SPLASH EXPERIENCE (#EDE5D8 Warm Sand & #A8895F Champagne Gold) -->
<!-- ================================================================= -->
<section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden pt-10 pb-20 border-b border-[#D8C9B8] bg-[#EDE5D8]"
         x-data="heroSlider({{ \Illuminate\Support\Js::from($heroSlides) }})"
         x-init="startAutoSlide()">

    <!-- SLIDING BACKGROUND PICTURE OVERLAY -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <template x-for="(slide, index) in slides" :key="'bg-' + index">
            <div x-show="activeSlide === index"
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 scale-110"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-1000"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute inset-0 opacity-30 transition-all duration-1000">
                <img :src="slide.mobile_image || slide.image"
                     alt=""
                     class="w-full h-full object-cover md:hidden">
                <img :src="slide.image"
                     alt=""
                     class="hidden w-full h-full object-cover md:block">
            </div>
        </template>

        <!-- Vignette Gradient for Text Readability -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#EDE5D8] via-[#EDE5D8]/90 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#EDE5D8] via-transparent to-[#EDE5D8]/80"></div>

        <!-- Translucent Geometric Polygon Panels -->
        <div class="absolute -top-20 -right-20 w-[600px] h-[700px] bg-gradient-to-br from-[#A8895F]/20 via-[#D8C9B8]/30 to-[#F8F5EF]/40 polygon-hero transform rotate-12 blur-sm"></div>

        <!-- Animated Floating Geometric Polygons -->
        <div class="absolute top-1/4 left-10 w-24 h-24 border border-[#A8895F]/40 polygon-card animate-float opacity-60"></div>
        <div class="absolute bottom-1/3 right-12 w-36 h-36 border border-[#A8895F]/30 polygon-card-reverse animate-float opacity-40" style="animation-delay: 2s;"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10 w-full relative">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <!-- Hero Content (Left) -->
            <div class="lg:col-span-7 space-y-6 text-left">

                <div class="inline-flex items-center gap-2 bg-[#F8F5EF] border border-[#A8895F]/40 px-3.5 py-1.5 polygon-badge shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-[#A8895F] animate-ping"></span>
                    <span class="text-[11px] font-extrabold tracking-[0.25em] text-[#A8895F] uppercase"
                          x-text="slides[activeSlide].eyebrow">{{ __('THE ATELIER VISUAL EXPERIENCE') }}</span>
                </div>

                <div class="space-y-2">
                    <h2 class="text-xs sm:text-sm font-extrabold tracking-[0.4em] text-[#A8895F] uppercase">{{ __('SOZIE COLLECTION') }}</h2>
                    <h1 class="font-serif font-bold text-5xl sm:text-7xl lg:text-8xl leading-none text-[#29241F] tracking-tight">
                        <span x-text="slides[activeSlide].headline">{{ __('YOUR SCENT.') }}</span><br>
                        <span class="gold-gradient-text italic font-normal"
                              x-text="slides[activeSlide].highlight_text">{{ __('YOUR SIGNATURE.') }}</span>
                    </h1>
                </div>

                <p class="text-gray-700 text-sm sm:text-base leading-relaxed max-w-xl font-semibold"
                   x-text="slides[activeSlide].description">{{ __('Hero Description') }}</p>

                <!-- CTAs -->
                <div class="flex flex-wrap items-center gap-4 pt-4">
                    <a :href="slides[activeSlide].button_link"
                       class="px-8 py-4 bg-[#A8895F] border border-[#A8895F] text-white font-extrabold text-xs tracking-[0.25em] uppercase polygon-btn shadow-xl shadow-[#A8895F]/20 hover:bg-[#29241F] flex items-center gap-3">
                        <span x-text="slides[activeSlide].button_text">{{ __('EXPLORE COLLECTION') }}</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>

                    <a x-show="slides[activeSlide].secondary_button_text"
                       :href="slides[activeSlide].secondary_button_link"
                       class="px-8 py-4 bg-[#F8F5EF] border border-[#A8895F]/40 text-[#29241F] font-extrabold text-xs tracking-[0.2em] uppercase polygon-btn hover:bg-white transition-all backdrop-blur-md flex items-center gap-2 shadow-sm">
                        <i data-lucide="sparkles" class="w-4 h-4 text-[#A8895F]"></i>
                        <span x-text="slides[activeSlide].secondary_button_text">{{ __('FIND YOUR SCENT') }}</span>
                    </a>
                </div>

                <!-- Hero Metrics -->
                <div class="grid grid-cols-3 gap-6 pt-8 border-t border-[#D8C9B8] max-w-lg">
                    <div class="navy-card p-3 polygon-card text-center border border-[#D8C9B8] bg-[#F8F5EF]">
                        <span class="font-serif font-bold text-2xl text-[#A8895F]">{{ __('12+ hrs') }}</span>
                        <span class="block text-[10px] text-gray-700 uppercase tracking-widest font-extrabold">{{ __('Longevity') }}</span>
                    </div>
                    <div class="navy-card p-3 polygon-card text-center border border-[#D8C9B8] bg-[#F8F5EF]">
                        <span class="font-serif font-bold text-2xl text-[#A8895F]">100%</span>
                        <span class="block text-[10px] text-gray-700 uppercase tracking-widest font-extrabold">{{ __('Authentic Notes') }}</span>
                    </div>
                    <div class="navy-card p-3 polygon-card text-center border border-[#D8C9B8] bg-[#F8F5EF]">
                        <span class="font-serif font-bold text-2xl text-[#A8895F]">{{ __('Fast') }}</span>
                        <span class="block text-[10px] text-gray-700 uppercase tracking-widest font-extrabold">{{ __('Doorstep Delivery') }}</span>
                    </div>
                </div>

            </div>

            <!-- Hero Visual Feature Bottle (Right) with SLIDING PICTURE -->
            <div class="lg:col-span-5 relative flex justify-center">
                <div class="relative w-80 h-[420px] sm:w-96 sm:h-[480px] group">

                    <!-- Outer Frame in Champagne Gold -->
                    <div class="absolute inset-0 bg-[#D8C9B8] polygon-card border-2 border-[#A8895F] gold-glow-lg transition-all duration-500 group-hover:scale-[1.02]"></div>

                    <!-- Main Image Container Box -->
                    <div class="absolute inset-2 bg-[#F8F5EF] polygon-card overflow-hidden shadow-2xl border border-[#D8C9B8]">

                        <!-- Sliding Perfume Images inside Frame -->
                        <template x-for="(slide, index) in slides" :key="'bottle-' + index">
                            <div x-show="activeSlide === index"
                                 x-transition:enter="transition ease-out duration-700 transform"
                                 x-transition:enter-start="opacity-0 translate-x-8 scale-105"
                                 x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                                 x-transition:leave="transition ease-in duration-500 transform"
                                 x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-x-8 scale-95"
                                 class="absolute inset-0 w-full h-full">

                                <img :src="slide.mobile_image || slide.image"
                                     :alt="slides[activeSlide].headline"
                                     class="w-full h-full object-cover object-center transform hover:scale-110 transition-transform duration-700 md:hidden">
                                <img :src="slide.image"
                                     :alt="slides[activeSlide].headline"
                                     class="hidden w-full h-full object-cover object-center transform hover:scale-110 transition-transform duration-700 md:block">

                                <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-[#EDE5D8]/90 via-[#EDE5D8]/40 to-transparent"></div>
                            </div>
                        </template>

                        <!-- Floating Glass Product Tag -->
                        <div class="absolute bottom-4 left-4 right-4 glass-panel p-3.5 polygon-card border border-[#A8895F]/40 flex justify-between items-center z-20 shadow-2xl backdrop-blur-md bg-[#F8F5EF]">
                            <div>
                                <span class="text-[10px] text-[#A8895F] font-extrabold uppercase tracking-widest block"
                                      x-text="slides[activeSlide].eyebrow"></span>
                                <h4 class="font-serif font-bold text-[#29241F] text-base tracking-wide"
                                    x-text="slides[activeSlide].headline"></h4>
                            </div>
                            <span class="text-[10px] text-[#A8895F] font-extrabold uppercase text-right max-w-[120px] leading-tight"
                                  x-text="slides[activeSlide].button_text"></span>
                        </div>

                    </div>

                    <!-- Slide Controls / Navigation Dots -->
                    <div class="absolute -bottom-8 left-0 right-0 flex justify-center items-center gap-2 z-20">
                        <template x-for="(slide, index) in slides" :key="'dot-' + index">
                            <button @click="activeSlide = index"
                                    :class="activeSlide === index ? 'w-8 bg-[#A8895F]' : 'w-2 bg-[#29241F]/30 hover:bg-[#29241F]/60'"
                                    class="h-2 rounded-full transition-all duration-300"
                                    :aria-label="@js(__('Slide :number')).replace(':number', index + 1)"></button>
                        </template>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>


<!-- ================================================================= -->
<!-- SECTION 03: SIGNATURE SCENTS (CROWN JEWEL & ARTISANAL ARRANGEMENT) -->
<!-- ================================================================= -->
<section class="py-24 relative overflow-hidden border-b border-[#D8C9B8] bg-[#EDE5D8]">
    <div class="absolute inset-0 bg-radial from-[#A8895F]/15 via-[#EDE5D8] to-[#EDE5D8] pointer-events-none opacity-80"></div>
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#D8C9B8]/40 blur-[140px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <!-- Left Large Campaign Image Frame -->
            <div class="lg:col-span-6 relative">
                <div class="w-full h-[500px] glass-panel p-2 polygon-card border border-[#A8895F]/40 shadow-2xl gold-glow bg-[#F8F5EF]">
                    <div class="w-full h-full polygon-card overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&q=80&w=1000" loading="lazy" decoding="async"
                             alt="{{ __('Sozie Signature Scent') }}"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#29241F]/90 via-[#29241F]/30 to-transparent opacity-90"></div>
                        <div class="absolute bottom-8 left-8 right-8 text-white space-y-1">
                            <span class="text-xs font-extrabold text-[#D8C9B8] tracking-[0.3em] uppercase block">{{ __('Sozie Signature Scent') }} &bull; {{ __('CROWN JEWEL COLLECTION') }}</span>
                            <h3 class="font-serif font-bold text-3xl text-[#F8F5EF]">SOZIE GOLDEN AURA</h3>
                            <p class="text-xs text-[#D8C9B8] mt-2 line-clamp-2 font-medium">{{ __('Kashmiri saffron, warm honeycomb, and crystal amber blended to perfection.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Showcase Card Details -->
            <div class="lg:col-span-6 space-y-8">
                <div class="space-y-3">
                    <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block">{{ __('ARTISANAL FRAGRANCE BLENDS') }}</span>
                    <h2 class="font-serif font-bold text-4xl text-[#29241F]">{{ __('SIGNATURE SCENTS ARRANGEMENT') }}</h2>
                    <p class="text-gray-700 text-sm leading-relaxed font-semibold">
                        {{ __('Each bottle of Sozie Collection signature perfume is handcrafted with raw botanical essences and rare aromatic resins, guaranteeing a multi-layered scent experience that evolves throughout your day.') }}
                    </p>
                </div>

                <div class="space-y-4">
                    @foreach($featuredProducts->take(2) as $fp)
                    <div class="navy-card p-5 polygon-card border border-[#D8C9B8] flex gap-4 items-center hover:border-[#A8895F] transition-all bg-[#F8F5EF]">
                        <img src="{{ $fp->primary_image }}" loading="lazy" decoding="async" alt="{{ $fp->name }}" class="w-20 h-20 object-cover polygon-card border border-[#D8C9B8]">
                        <div class="flex-grow">
                            <div class="flex justify-between items-start">
                                <h4 class="font-serif font-bold text-lg text-[#29241F]">{{ $fp->name }}</h4>
                                <span class="text-base sm:text-sm font-extrabold text-[#A8895F]">{{ $fp->formatted_price }}</span>
                            </div>
                            <p class="text-xs text-gray-600 font-semibold mt-1">{{ __('Top:') }} {{ $fp->top_notes }}</p>
                            <div class="flex gap-2 mt-3">
                                <button @click="addToCart({{ $fp->id }}, '{{ $fp->default_size }}')"
                                        class="px-4 py-1.5 bg-[#A8895F] border border-[#A8895F] text-white text-[10px] font-extrabold uppercase tracking-wider polygon-btn hover:bg-[#29241F] transition-colors">
                                    {{ __('ADD TO CART') }}
                                </button>
                                <button @click="$dispatch('open-quickview', { id: {{ $fp->id }} })"
                                        class="px-4 py-1.5 bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] text-[10px] font-bold uppercase tracking-wider polygon-btn hover:bg-white">
                                    {{ __('QUICK VIEW') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
</section>


<!-- ================================================================= -->
<!-- SECTION 04: BEST SELLERS (MOST LOVED CREATIONS) -->
<!-- ================================================================= -->
<section class="py-20 relative border-b border-[#D8C9B8] bg-[#EDE5D8] overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="flex flex-col md:flex-row justify-between items-end mb-12">
            <div>
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">{{ __('MOST LOVED CREATIONS') }}</span>
                <h2 class="font-serif font-bold text-3xl sm:text-4xl text-[#29241F]">{{ __('BEST SELLING PERFUMES') }}</h2>
            </div>
            <a href="{{ route('shop.index', ['collection' => 'best_seller']) }}"
               class="text-xs font-extrabold text-[#A8895F] uppercase tracking-widest flex items-center gap-1 hover:text-[#29241F] mt-4 md:mt-0">
                <span>{{ __('VIEW ALL BEST SELLERS') }}</span>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        </div>

        <!-- Product Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($bestSellers as $product)
            <div class="navy-card p-4 polygon-card border border-[#D8C9B8] group hover:border-[#A8895F] transition-all duration-300 flex flex-col justify-between relative bg-[#F8F5EF]">

                <!-- Discount Badge -->
                @if($product->discount_percentage)
                <span class="absolute top-6 left-6 z-20 bg-[#29241F] text-white text-[9px] font-extrabold uppercase px-2.5 py-1 polygon-badge shadow-md">
                    -{{ $product->discount_percentage }}%
                </span>
                @endif

                <!-- Wishlist Heart Button -->
                <button @click="toggleWishlist({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: '{{ $product->formatted_price }}', image: '{{ $product->primary_image }}' })"
                        class="absolute top-6 right-6 z-20 p-2 bg-white/80 rounded-full text-[#29241F] hover:text-[#A8895F] transition-colors shadow-sm">
                    <i data-lucide="heart" class="w-4 h-4" :class="isInWishlist({{ $product->id }}) ? 'fill-[#A8895F] text-[#A8895F]' : ''"></i>
                </button>

                <div>
                    <!-- Image Frame -->
                    <div class="w-full h-64 bg-white polygon-card overflow-hidden mb-4 relative border border-[#D8C9B8]">
                        <img src="{{ $product->primary_image }}" loading="lazy" decoding="async"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                        <!-- Quick View Overlay Button -->
                        <div class="absolute inset-0 bg-[#29241F]/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 p-4 backdrop-blur-xs">
                            <button @click="$dispatch('open-quickview', { id: {{ $product->id }} })"
                                    class="px-4 py-2 bg-[#A8895F] border border-[#A8895F] text-white font-extrabold text-xs uppercase tracking-wider polygon-btn hover:bg-[#29241F]">
                                {{ __('QUICK VIEW') }}
                            </button>
                        </div>
                    </div>

                    <!-- Category Tag -->
                    <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-widest block mb-1">
                        {{ $product->category ? $product->category->name : __('Signature') }}
                    </span>

                    <a href="{{ route('shop.show', $product->slug) }}">
                        <h3 class="font-serif font-bold text-xl text-[#29241F] group-hover:text-[#A8895F] transition-colors">
                            {{ $product->name }}
                        </h3>
                    </a>

                    <p class="text-xs text-gray-600 font-medium mt-1 line-clamp-1">{{ __('Notes:') }} {{ $product->top_notes }}</p>
                </div>

                <!-- Price & Action -->
                <div class="pt-4 mt-4 border-t border-[#D8C9B8] flex items-center justify-between">
                    <div>
                        <span class="text-base sm:text-sm font-extrabold text-[#A8895F] block">{{ $product->formatted_price }}</span>
                        @if($product->discount_price)
                        <span class="text-[10px] text-gray-500 line-through font-semibold">{{ $product->formatted_original_price }}</span>
                        @endif
                    </div>

                    <button @click="addToCart({{ $product->id }}, '{{ $product->default_size }}')"
                            class="p-2.5 bg-[#A8895F] border border-[#A8895F] text-white polygon-btn hover:bg-[#29241F] transition-colors shadow-md">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    </button>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>

<!-- ================================================================= -->
<!-- SECTION 05: FIND YOUR SIGNATURE SCENT (PERSONAL CONSULTATION) -->
<!-- ================================================================= -->
<section id="scent-finder" class="py-24 relative overflow-hidden bg-[#EDE5D8] border-b border-[#D8C9B8]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" x-data="scentFinder()" x-init="findMatch()">

        <div class="bg-[#F8F5EF] p-8 sm:p-12 polygon-card border border-[#A8895F]/40 shadow-2xl">

            <div class="text-center mb-8">
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-2">{{ __('PERSONAL FRAGRANCE CONSULTATION') }}</span>
                <h2 class="font-serif font-bold text-3xl sm:text-5xl text-[#29241F]">{{ __('FIND YOUR SIGNATURE SCENT Title') }}</h2>
                <p class="text-xs sm:text-sm text-gray-700 mt-3 max-w-lg mx-auto font-semibold">
                    {{ __('Select your preferred scent profile to discover matching signature perfumes from our collection.') }}
                </p>
            </div>

            <div class="space-y-8">

                <!-- Scent Preference Options -->
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-widest text-[#A8895F] mb-3 text-center">{{ __('Select Your Scent Personality') }}</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <template x-for="s in ['Floral', 'Woody', 'Fresh', 'Vanilla', 'Spicy', 'Sweet', 'Oriental', 'Citrus']" :key="s">
                            <button @click="chooseScent(s)"
                                    :class="selectedScent === s ? 'bg-[#A8895F] text-white border-[#A8895F] font-extrabold shadow-md' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F] font-bold'"
                                    class="py-3 px-4 border text-xs uppercase tracking-wider polygon-btn transition-all">
                                <span x-text="s"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Match Results Box -->
                <div x-show="matches.length > 0" x-transition class="mt-8 pt-8 border-t border-[#D8C9B8]">
                    <h3 class="font-serif font-bold text-2xl text-[#29241F] text-center mb-6">{{ __('YOUR PERFECT FRAGRANCE MATCH') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <template x-for="p in matches" :key="p.id">
                            <div class="navy-card p-4 polygon-card border border-[#D8C9B8] text-center flex flex-col justify-between bg-white">
                                <div>
                                    <img :src="p.images ? p.images[0] : p.campaign_image" loading="lazy" decoding="async" class="w-full h-40 object-cover polygon-card mb-3 border border-[#D8C9B8]">
                                    <h4 class="font-serif font-bold text-lg text-[#29241F]" x-text="p.name"></h4>
                                    <p class="text-[10px] text-[#A8895F] uppercase tracking-widest font-extrabold mt-1" x-text="p.scent_type + ' • ' + p.fragrance_family"></p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-[#D8C9B8] flex justify-between items-center gap-1">
                                    <span class="text-sm sm:text-xs font-extrabold text-[#29241F]" x-text="'TZS ' + Number(p.price).toLocaleString()"></span>
                                    <button @click="addToCart(p.id)" class="px-3 py-1.5 bg-[#A8895F] border border-[#A8895F] text-white text-[10px] font-extrabold uppercase polygon-btn hover:bg-[#29241F]">
                                        {{ __('ADD TO CART') }}
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>

<!-- ================================================================= -->
<!-- SECTION 06: NEW ARRIVALS -->
<!-- ================================================================= -->
<section class="py-20 relative border-b border-[#D8C9B8] bg-[#EDE5D8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">{{ __('FRESH FROM OUR ATELIER') }}</span>
                <h2 class="font-serif font-bold text-3xl sm:text-4xl text-[#29241F]">{{ __('NEW ARRIVALS') }}</h2>
            </div>
            <a href="{{ route('shop.index', ['collection' => 'new_arrival']) }}"
               class="text-xs font-extrabold text-[#A8895F] uppercase tracking-widest hover:text-[#29241F]">
                {{ __('VIEW ALL NEW ARRIVALS') }} &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($newArrivals as $product)
            <div class="navy-card p-4 polygon-card border border-[#D8C9B8] group hover:border-[#A8895F] transition-all duration-300 flex flex-col justify-between relative bg-[#F8F5EF]">

                <span class="absolute top-6 right-6 z-20 bg-[#29241F] text-white text-[9px] font-extrabold uppercase px-2 py-0.5 polygon-badge">
                    {{ __('NEW') }}
                </span>

                <div>
                    <div class="w-full h-64 bg-white polygon-card overflow-hidden mb-4 relative border border-[#D8C9B8]">
                        <img src="{{ $product->primary_image }}" loading="lazy" decoding="async"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                        <div class="absolute inset-0 bg-[#29241F]/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 p-4 backdrop-blur-xs">
                            <button @click="$dispatch('open-quickview', { id: {{ $product->id }} })"
                                    class="px-4 py-2 bg-[#A8895F] border border-[#A8895F] text-white font-extrabold text-xs uppercase tracking-wider polygon-btn hover:bg-[#29241F]">
                                {{ __('QUICK VIEW') }}
                            </button>
                        </div>
                    </div>

                    <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-widest block mb-1">
                        {{ $product->category ? $product->category->name : __('New Release') }}
                    </span>

                    <a href="{{ route('shop.show', $product->slug) }}">
                        <h3 class="font-serif font-bold text-xl text-[#29241F] group-hover:text-[#A8895F] transition-colors">
                            {{ $product->name }}
                        </h3>
                    </a>

                    <p class="text-xs text-gray-600 font-medium mt-1 line-clamp-1">{{ __('Notes:') }} {{ $product->top_notes }}</p>
                </div>

                <div class="pt-4 mt-4 border-t border-[#D8C9B8] flex items-center justify-between">
                    <span class="text-base sm:text-sm font-extrabold text-[#A8895F]">{{ $product->formatted_price }}</span>

                    <button @click="addToCart({{ $product->id }}, '{{ $product->default_size }}')"
                            class="p-2.5 bg-[#A8895F] border border-[#A8895F] text-white polygon-btn hover:bg-[#29241F] transition-colors">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    </button>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>

<!-- ================================================================= -->
<!-- SECTION 07: THE SOZIE EXPERIENCE -->
<!-- ================================================================= -->
<section class="py-24 relative overflow-hidden border-b border-[#D8C9B8] bg-[#EDE5D8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <div class="lg:col-span-6 space-y-6">
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block">{{ __('LUXURY CRAFTSMANSHIP') }}</span>
                <h2 class="font-serif font-bold text-4xl sm:text-5xl text-[#29241F] leading-tight">
                    {{ __('THE SOZIE EXPERIENCE') }}:<br>
                    <span class="gold-gradient-text italic font-normal">{{ __('ARTISTRY IN EVERY DROP') }}</span>
                </h2>
                <p class="text-gray-700 text-sm leading-relaxed font-semibold">
                    {{ __('We believe fragrance is more than a scent—it is an invisible armor of confidence and personal expression. Every bottle in the Sozie Collection is formulated with master perfumery techniques, incorporating pure botanical oils, rare spices, and long-wearing amber accords.') }}
                </p>

                <div class="grid grid-cols-2 gap-4 pt-4">
                    <div class="navy-card p-4 polygon-card border border-[#D8C9B8] bg-[#F8F5EF]">
                        <i data-lucide="shield-check" class="w-6 h-6 text-[#A8895F] mb-2"></i>
                        <h4 class="font-serif font-bold text-[#29241F] text-base">{{ __('Pure Quality') }}</h4>
                        <p class="text-[11px] text-gray-600 mt-1 font-semibold">{{ __('Authentic concentrated perfume oils and extracts.') }}</p>
                    </div>

                    <div class="navy-card p-4 polygon-card border border-[#D8C9B8] bg-[#F8F5EF]">
                        <i data-lucide="gem" class="w-6 h-6 text-[#A8895F] mb-2"></i>
                        <h4 class="font-serif font-bold text-[#29241F] text-base">{{ __('Artistic Design') }}</h4>
                        <p class="text-[11px] text-gray-600 mt-1 font-semibold">{{ __('Architectural geometric bottles and casing.') }}</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6">
                <div class="relative w-full h-[450px] glass-panel p-3 polygon-card border border-[#A8895F]/40 gold-glow bg-[#F8F5EF]">
                    <img src="https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&q=80&w=1000" loading="lazy" decoding="async"
                         alt="{{ __('The Sozie Experience') }}"
                         class="w-full h-full object-cover polygon-card border border-[#D8C9B8]">
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ================================================================= -->
<!-- SECTION 08: CUSTOMER REVIEWS -->
<!-- ================================================================= -->
<section class="py-20 relative border-b border-[#D8C9B8] bg-[#EDE5D8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-2">{{ __('VERIFIED REVIEWS') }}</span>
            <h2 class="font-serif font-bold text-3xl sm:text-4xl text-[#29241F]">{{ __('WHAT OUR CLIENTS SAY') }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($reviews as $rev)
            <div class="navy-card p-6 polygon-card border border-[#D8C9B8] flex flex-col justify-between bg-[#F8F5EF]">
                <div>
                    <div class="flex text-[#A8895F] gap-1 mb-3">
                        @for($i=0; $i<$rev->rating; $i++)
                        <i data-lucide="star" class="w-4 h-4 fill-[#A8895F]"></i>
                        @endfor
                    </div>
                    <p class="text-xs text-gray-700 italic leading-relaxed font-semibold">"{{ $rev->comment }}"</p>
                </div>

                <div class="mt-6 pt-4 border-t border-[#D8C9B8] flex items-center justify-between">
                    <div>
                        <span class="font-serif font-bold text-sm text-[#29241F] block">{{ $rev->customer_name }}</span>
                        <span class="text-[9px] text-emerald-700 font-extrabold uppercase tracking-wider">{{ __('Verified Purchase') }}</span>
                    </div>
                    <span class="text-[10px] text-gray-600 font-bold">{{ $rev->product ? $rev->product->name : __('Sozie Perfume') }}</span>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

<!-- ================================================================= -->
<!-- SECTION 09: SOCIAL / CAMPAIGN GALLERY INTERACTIVE SLIDER -->
<!-- ================================================================= -->
<section class="py-20 relative border-b border-[#D8C9B8] bg-[#EDE5D8] overflow-hidden"
         x-data="gallerySlider()"
         x-init="initSlider()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-10 gap-4">
            <div>
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">{{ __('INSTAGRAM & CAMPAIGN VISUALS') }}</span>
                <h2 class="font-serif font-bold text-3xl sm:text-4xl text-[#29241F]">{{ __('#SOZIECOLLECTION GALLERY') }}</h2>
            </div>

            <!-- Slide Navigation Controls -->
            <div class="flex items-center gap-3">
                <button @click="prevSlide()" class="p-2.5 bg-[#F8F5EF] border border-[#D8C9B8] text-[#A8895F] hover:bg-[#A8895F] hover:text-white transition-colors polygon-btn">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </button>
                <button @click="nextSlide()" class="p-2.5 bg-[#F8F5EF] border border-[#D8C9B8] text-[#A8895F] hover:bg-[#A8895F] hover:text-white transition-colors polygon-btn">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <!-- Interactive Sliding Track -->
        <div class="overflow-hidden relative rounded-xl">
            <div class="flex transition-transform duration-700 ease-in-out gap-4"
                 :style="'transform: translateX(-' + (currentIndex * (100 / itemsToShow)) + '%);'">

                <template x-for="(slide, i) in slides" :key="'campaign-' + i">
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 flex-shrink-0">
                        <div class="h-80 navy-card polygon-card overflow-hidden group relative border border-[#D8C9B8] bg-[#F8F5EF] shadow-lg">
                            <img :src="slide.image" :alt="slide.title" loading="lazy" decoding="async"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                            <!-- Instagram Handle Badge -->
                            <div class="absolute top-3 left-3 z-10 bg-[#F8F5EF]/90 backdrop-blur-md border border-[#D8C9B8] text-[9px] font-extrabold text-[#A8895F] uppercase px-2.5 py-1 polygon-badge flex items-center gap-1.5">
                                <i data-lucide="instagram" class="w-3 h-3 text-[#A8895F]"></i>
                                <span x-text="slide.tag"></span>
                            </div>

                            <!-- Hover Overlay Card Details -->
                            <div class="absolute inset-0 bg-gradient-to-t from-[#29241F]/90 via-[#29241F]/60 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col justify-end p-5 backdrop-blur-xs text-white">
                                <span class="text-[10px] text-[#D8C9B8] font-extrabold uppercase tracking-widest block" x-text="slide.handle"></span>
                                <h4 class="font-serif font-bold text-lg text-white" x-text="slide.title"></h4>
                                <p class="text-xs text-gray-200 mt-0.5 font-medium" x-text="slide.subtitle"></p>
                                <a href="{{ route('shop.index') }}" class="mt-3 inline-block py-1.5 px-3 bg-[#A8895F] text-white text-[10px] font-extrabold uppercase tracking-wider polygon-btn text-center hover:bg-black">
                                    {{ __('Shop Scent') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </template>

            </div>
        </div>

        <!-- Slide Progress Dots -->
        <div class="flex justify-center items-center gap-2 mt-6">
            <template x-for="(slide, i) in slides" :key="'dot-camp-' + i">
                <button @click="currentIndex = i"
                        :class="currentIndex === i ? 'w-8 bg-[#A8895F]' : 'w-2 bg-[#29241F]/30 hover:bg-[#29241F]/60'"
                        class="h-2 rounded-full transition-all duration-300"></button>
            </template>
        </div>

    </div>
</section>

<!-- ================================================================= -->
<!-- SECTION 10: FINAL CTA -->
<!-- ================================================================= -->
<section class="py-24 relative overflow-hidden bg-gradient-to-r from-[#29241F] via-[#3a332d] to-[#29241F] text-[#F8F5EF]">
    <div class="max-w-4xl mx-auto px-4 text-center space-y-6 relative z-10">
        <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.4em] block">{{ __('READY TO ELEVATE YOUR SCENT?') }}</span>
        <h2 class="font-serif font-bold text-5xl sm:text-6xl text-[#F8F5EF]">{{ __('WEAR YOUR SIGNATURE.') }}</h2>
        <p class="text-[#D8C9B8] text-sm max-w-lg mx-auto font-medium">
            {{ __('Experience luxury perfumes delivered directly to your doorstep with instant order processing and direct WhatsApp communication.') }}
        </p>
        <div>
            <a href="{{ route('shop.index') }}"
               class="inline-block px-12 py-4 bg-[#A8895F] text-white border-2 border-[#A8895F] font-extrabold text-xs tracking-[0.3em] uppercase polygon-btn hover:bg-white hover:text-[#29241F] shadow-2xl transition-all">
                {{ __('SHOP SOZIE NOW') }}
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function heroSlider(serverSlides) {
        const fallbackSlides = [
            {
                image: 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&q=80&w=1200',
                mobile_image: null,
                eyebrow: @js(__('THE ATELIER VISUAL EXPERIENCE')),
                headline: @js(__('YOUR SCENT.')),
                highlight_text: @js(__('YOUR SIGNATURE.')),
                description: @js(__('Discover handcrafted fragrances designed to leave a memorable impression.')),
                button_text: @js(__('EXPLORE COLLECTION')),
                button_link: '{{ route('shop.index') }}',
                secondary_button_text: @js(__('FIND YOUR SCENT')),
                secondary_button_link: '#scent-finder'
            },
            {
                image: 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&q=80&w=1200',
                mobile_image: null,
                eyebrow: @js(__('LIMITED RESERVE')),
                headline: 'SOZIE NOIR',
                highlight_text: 'IMPERIAL.',
                description: @js(__('Bold woods, spice and amber for an unforgettable signature.')),
                button_text: @js(__('DISCOVER THE COLLECTION')),
                button_link: '{{ route('shop.index') }}',
                secondary_button_text: null,
                secondary_button_link: null
            },
            {
                image: 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&q=80&w=1200',
                mobile_image: null,
                eyebrow: @js(__('NEW ARRIVAL')),
                headline: 'SOZIE GOLDEN',
                highlight_text: 'AURA.',
                description: @js(__('Saffron, warm honeycomb and crystal amber blended to perfection.')),
                button_text: @js(__('SHOP THE NEW ARRIVAL')),
                button_link: '{{ route('shop.index') }}',
                secondary_button_text: null,
                secondary_button_link: null
            }
        ];

        return {
            activeSlide: 0,
            slides: serverSlides && serverSlides.length > 0 ? serverSlides : fallbackSlides,
            timer: null,
            startAutoSlide() {
                if (this.slides.length < 2) {
                    return;
                }

                this.timer = setInterval(() => {
                    this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                }, 4000);
            },
            destroy() {
                if (this.timer) {
                    clearInterval(this.timer);
                }
            }
        }
    }

    function scentFinder() {
        return {
            selectedScent: 'Floral',
            matches: [],
            chooseScent(scent) {
                this.selectedScent = scent;
                this.findMatch();
            },
            findMatch() {
                fetch(`{{ route("api.fragrance_finder") }}?scent_type=${this.selectedScent}`)
                    .then(res => res.json())
                    .then(data => {
                        this.matches = data.matches || [];
                    });
            }
        }
    }

    function gallerySlider() {
        return {
            currentIndex: 0,
            itemsToShow: 4,
            slides: [
                {
                    image: 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&q=80&w=800',
                    title: 'SOZIE ELEGANCE',
                    subtitle: @js(__('Floral Fruity • Eau de Parfum')),
                    tag: '@sozie_collection',
                    handle: '#SozieElegance'
                },
                {
                    image: 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&q=80&w=800',
                    title: 'SOZIE NOIR IMPERIAL',
                    subtitle: @js(__('Woody Oud • Extrait de Parfum')),
                    tag: '@sozie_collection',
                    handle: '#SozieNoir'
                },
                {
                    image: 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&q=80&w=800',
                    title: 'SOZIE GOLDEN AURA',
                    subtitle: @js(__('Saffron Amber • Limited Reserve')),
                    tag: '@sozie_collection',
                    handle: '#GoldenAura'
                },
                {
                    image: 'https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&q=80&w=800',
                    title: 'SOZIE VELVET ROSE',
                    subtitle: @js(__('Turkish Rose Gourmand')),
                    tag: '@sozie_collection',
                    handle: '#VelvetRose'
                },
                {
                    image: 'https://images.unsplash.com/photo-1616949755610-8c9bbc08f138?auto=format&fit=crop&q=80&w=800',
                    title: 'SOZIE ROYAL OUD OIL',
                    subtitle: @js(__('0% Alcohol Concentrated Elixir')),
                    tag: '@sozie_collection',
                    handle: '#RoyalOud'
                },
                {
                    image: 'https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&q=80&w=800',
                    title: 'SOZIE BLOSSOM BLISS',
                    subtitle: @js(__('Cherry Blossom & White Peach')),
                    tag: '@sozie_collection',
                    handle: '#BlossomBliss'
                }
            ],
            initSlider() {
                this.updateItemsToShow();
                window.addEventListener('resize', () => this.updateItemsToShow());
                setInterval(() => {
                    this.nextSlide();
                }, 3500);
            },
            updateItemsToShow() {
                if (window.innerWidth < 640) {
                    this.itemsToShow = 1;
                } else if (window.innerWidth < 768) {
                    this.itemsToShow = 2;
                } else if (window.innerWidth < 1024) {
                    this.itemsToShow = 3;
                } else {
                    this.itemsToShow = 4;
                }
            },
            nextSlide() {
                const maxIndex = this.slides.length - this.itemsToShow;
                if (this.currentIndex >= maxIndex) {
                    this.currentIndex = 0;
                } else {
                    this.currentIndex++;
                }
            },
            prevSlide() {
                const maxIndex = this.slides.length - this.itemsToShow;
                if (this.currentIndex <= 0) {
                    this.currentIndex = maxIndex > 0 ? maxIndex : 0;
                } else {
                    this.currentIndex--;
                }
            }
        }
    }
</script>
@endpush
