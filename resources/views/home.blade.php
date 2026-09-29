@extends('layouts.app')

@section('title', __('Sozie Collection | Luxury Perfumes & Signature Scents'))

@section('content')

@php
    $configuredSlides = $heroSlides
        ->filter(fn (array $slide): bool => filled($slide['image'] ?? null) || filled($slide['mobile_image'] ?? null))
        ->values();

    $configuredGallerySlides = $gallerySlides
        ->filter(fn (array $slide): bool => filled($slide['image'] ?? null) || filled($slide['mobile_image'] ?? null))
        ->values();

    $campaignSlide = $configuredSlides->first();
@endphp

<!-- ================================================================= -->
<!-- SECTION 01: HERO / SPLASH EXPERIENCE -->
<!-- ================================================================= -->
<section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden pt-10 pb-20 border-b border-[#E8DED0] bg-[#F5F0E8]"
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
                <template x-if="slide.mobile_image || slide.image">
                    <img :src="slide.mobile_image || slide.image"
                         data-sozie-fallback
                         alt=""
                         class="w-full h-full object-cover md:hidden">
                </template>
                <template x-if="slide.image || slide.mobile_image">
                    <img :src="slide.image || slide.mobile_image"
                         data-sozie-fallback
                         alt=""
                         class="hidden w-full h-full object-cover md:block">
                </template>
            </div>
        </template>

        <!-- Vignette Gradient for Text Readability -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#F5F0E8] via-[#F5F0E8]/90 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#F5F0E8] via-transparent to-[#F5F0E8]/80"></div>

        <!-- Translucent Geometric Polygon Panels -->
        <div class="absolute -top-20 -right-20 w-[600px] h-[700px] bg-gradient-to-br from-[#B99A5B]/15 via-[#E8DED0]/30 to-[#8F6E3B]/10 polygon-hero transform rotate-12 blur-sm"></div>

        <!-- Animated Floating Geometric Polygons -->
        <div class="absolute top-1/4 left-10 w-24 h-24 border border-[#B99A5B]/40 polygon-card animate-float opacity-60"></div>
        <div class="absolute bottom-1/3 right-12 w-36 h-36 border border-[#B99A5B]/30 polygon-card-reverse animate-float opacity-40" style="animation-delay: 2s;"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10 w-full relative">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <!-- Hero Content (Left) -->
            <div class="lg:col-span-7 space-y-6 text-left">

                <div x-show="currentSlide.eyebrow" class="inline-flex items-center gap-2 glass-card border border-[#B99A5B]/40 px-3.5 py-1.5 polygon-badge shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-[#B99A5B] animate-ping"></span>
                    <span class="text-[11px] font-extrabold tracking-[0.25em] text-[#8F6E3B] uppercase"
                          x-text="currentSlide.eyebrow"></span>
                </div>

                <div class="space-y-2">
                    <h2 class="text-xs sm:text-sm font-extrabold tracking-[0.4em] text-[#8F6E3B] uppercase">{{ __('SOZIE COLLECTION') }}</h2>
                    <h1 class="font-serif font-bold text-4xl sm:text-6xl lg:text-7xl leading-tight text-[#211E1A] tracking-tight">
                        <span x-text="currentSlide.headline"></span>
                        <template x-if="currentSlide.highlight_text">
                            <span class="gold-gradient-text italic font-normal block mt-1" x-text="currentSlide.highlight_text"></span>
                        </template>
                    </h1>
                </div>

                <p x-show="currentSlide.description" class="text-[#5C5248] text-sm sm:text-base leading-relaxed max-w-xl font-bold"
                   x-text="currentSlide.description"></p>

                <!-- CTAs -->
                <div class="flex flex-wrap items-center gap-4 pt-4">
                    <a :href="currentSlide.button_link"
                       class="px-8 py-4 bg-[#8F6E3B] border border-[#8F6E3B] text-white font-extrabold text-xs tracking-[0.25em] uppercase polygon-btn shadow-xl shadow-[#B99A5B]/20 hover:bg-[#211E1A] flex items-center gap-3">
                        <span x-text="currentSlide.button_text">{{ __('EXPLORE COLLECTION') }}</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>

                    <a x-show="currentSlide.secondary_button_text"
                       :href="currentSlide.secondary_button_link"
                       class="px-8 py-4 bg-[#17130F] border border-[#B99A5B]/50 text-[#EDE5D8] font-extrabold text-xs tracking-[0.2em] uppercase polygon-btn hover:bg-[#E8DED0] hover:text-[#12100E] transition-all backdrop-blur-md flex items-center gap-2 shadow-sm">
                        <i data-lucide="sparkles" class="w-4 h-4 text-[#8F6E3B]"></i>
                        <span x-text="currentSlide.secondary_button_text">{{ __('FIND YOUR SCENT') }}</span>
                    </a>
                </div>

                <!-- Hero Metrics -->
                <div class="grid grid-cols-3 gap-6 pt-8 border-t border-[#E8DED0] max-w-lg">
                    <div class="glass-card p-3 polygon-card text-center border border-[#E8DED0] bg-[#17130F]">
                        <span class="font-serif font-bold text-2xl text-[#8F6E3B]">{{ __('12+ hrs') }}</span>
                        <span class="block text-[10px] text-[#5C5248] uppercase tracking-widest font-extrabold">{{ __('Longevity') }}</span>
                    </div>
                    <div class="glass-card p-3 polygon-card text-center border border-[#E8DED0] bg-[#17130F]">
                        <span class="font-serif font-bold text-2xl text-[#8F6E3B]">100%</span>
                        <span class="block text-[10px] text-[#5C5248] uppercase tracking-widest font-extrabold">{{ __('Authentic Notes') }}</span>
                    </div>
                    <div class="glass-card p-3 polygon-card text-center border border-[#E8DED0] bg-[#17130F]">
                        <span class="font-serif font-bold text-2xl text-[#8F6E3B]">{{ __('Fast') }}</span>
                        <span class="block text-[10px] text-[#5C5248] uppercase tracking-widest font-extrabold">{{ __('Doorstep Delivery') }}</span>
                    </div>
                </div>

            </div>

            <!-- Hero Visual Feature Bottle (Right) with SLIDING PICTURE -->
            <div class="lg:col-span-5 relative flex justify-center">
                <div class="relative w-80 h-[420px] sm:w-96 sm:h-[480px] group">

                    <!-- Golden Polygon Outer Frame -->
                    <div class="absolute inset-0 bg-[#E8DED0] polygon-card border-2 border-[#B99A5B] gold-glow-lg transition-all duration-500 group-hover:scale-[1.02]"></div>

                    <!-- Main Image Container Box -->
                    <div class="absolute inset-2 bg-[#17130F] polygon-card overflow-hidden shadow-2xl border border-[#B99A5B]/40">

                        <!-- Sliding Perfume Images inside Frame -->
                        <template x-for="(slide, index) in slides" :key="'bottle-' + index">
                            <div x-show="activeSlide === index"
                                 x-transition:enter="transition ease-out duration-700 transform"
                                 x-transition:enter-start="opacity-0 translate-x-8 scale-105"
                                 x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                                 x-transition:leave="transition ease-in duration-500 transform"
                                 x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-x-8 scale-95"
                                 class="absolute inset-0 w-full h-full flex items-center justify-center p-4">

                                <img :src="slide.image || slide.mobile_image || '{{ asset('images/product-placeholder.svg') }}'"
                                     data-sozie-fallback
                                     :alt="slide.title || 'Sozie Perfume'"
                                     class="w-full h-full object-contain filter drop-shadow-2xl transform hover:scale-105 transition-transform duration-700">

                                <div class="absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-[#211E1A]/80 via-[#211E1A]/30 to-transparent"></div>
                            </div>
                        </template>

                        <!-- Floating Glass Product Tag -->
                        <div class="absolute bottom-4 left-4 right-4 glass-panel p-3.5 polygon-card border border-[#B99A5B]/40 flex justify-between items-center z-20 shadow-2xl backdrop-blur-md">
                            <div>
                                <span class="text-[10px] text-[#8F6E3B] font-extrabold uppercase tracking-widest block" x-text="currentSlide.badge || 'SIGNATURE'"></span>
                                <h4 class="font-serif font-bold text-[#F8F5EF] text-base tracking-wide" x-text="currentSlide.name || currentSlide.title || 'SOZIE COLLECTION'"></h4>
                            </div>
                            <span class="text-xs font-extrabold text-[#8F6E3B] font-mono" x-text="currentSlide.price || ''"></span>
                        </div>

                    </div>

                    <!-- Slide Controls / Navigation Dots -->
                    <template x-if="slides.length > 1">
                        <div class="absolute -bottom-8 left-0 right-0 flex justify-center items-center gap-2 z-20">
                            <template x-for="(slide, index) in slides" :key="'dot-' + index">
                                <button @click="activeSlide = index"
                                        :class="activeSlide === index ? 'w-8 bg-[#8F6E3B]' : 'w-2 bg-[#211E1A]/30 hover:bg-[#211E1A]/60'"
                                        class="h-2 rounded-full transition-all duration-300"
                                        :aria-label="'Slide ' + (index + 1)"></button>
                            </template>
                        </div>
                    </template>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- ================================================================= -->
<!-- SECTION 03: SIGNATURE SCENTS (ASYMMETRIC ARRANGEMENT) -->
<!-- ================================================================= -->
<section class="py-24 relative overflow-hidden border-b border-[#E8DED0] bg-[#F5F0E8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <!-- Left Large Campaign Image Frame -->
            <div class="lg:col-span-6 relative">
                <div class="w-full h-[500px] glass-panel p-2 polygon-card border border-[#B99A5B]/40 shadow-2xl gold-glow bg-[#17130F]">
                    <div class="w-full h-full polygon-card overflow-hidden relative flex items-center justify-center p-4 bg-[#F5F0E8]">
                        @if($campaignSlide && ($campaignSlide['image'] ?? $campaignSlide['mobile_image']))
                        <img src="{{ $campaignSlide['image'] ?? $campaignSlide['mobile_image'] }}" data-sozie-fallback loading="lazy" decoding="async"
                             alt="{{ $campaignSlide['headline'] ?? __('Sozie Signature Scent') }}"
                             class="w-full h-full object-contain filter drop-shadow-2xl">
                        @else
                        <div class="w-full h-full bg-gradient-to-t from-[#211E1A] via-[#8F6E3B] to-[#F5F0E8] flex items-center justify-center">
                            <span class="font-serif font-bold text-3xl text-white">SOZIE COLLECTION</span>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-[#211E1A]/90 via-[#211E1A]/30 to-transparent opacity-90"></div>
                        <div class="absolute bottom-8 left-8 right-8 text-white space-y-1 z-10">
                            <span class="text-xs font-extrabold text-[#E8DED0] tracking-[0.3em] uppercase block">{{ __('Sozie Signature Scent') }} &bull; {{ __('CROWN JEWEL COLLECTION') }}</span>
                            <h3 class="font-serif font-bold text-3xl text-white">SOZIE GOLDEN AURA</h3>
                            <p class="text-xs text-[#E8DED0] mt-2 line-clamp-2 font-medium">{{ __('Kashmiri saffron, warm honeycomb, and crystal amber blended to perfection.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Showcase Card Details -->
            <div class="lg:col-span-6 space-y-8">
                <div class="space-y-3">
                    <span class="text-xs font-extrabold text-[#8F6E3B] uppercase tracking-[0.3em] block">{{ __('ARTISANAL FRAGRANCE BLENDS') }}</span>
                    <h2 class="font-serif font-bold text-4xl text-[#211E1A]">{{ __('SIGNATURE SCENTS ARRANGEMENT') }}</h2>
                    <p class="text-[#5C5248] text-sm leading-relaxed font-semibold">
                        {{ __('Each bottle of Sozie Collection signature perfume is handcrafted with raw botanical essences and rare aromatic resins, guaranteeing a multi-layered scent experience that evolves throughout your day.') }}
                    </p>
                </div>

                <div class="space-y-4">
                    @foreach($featuredProducts->take(2) as $fp)
                    <div class="glass-card p-4 sm:p-5 polygon-card border border-[#E8DED0] flex gap-4 items-center hover:border-[#B99A5B] transition-all bg-[#17130F]">
                        <!-- Unclipped Spacious Vertical Image Container for Perfume Bottle -->
                        <div class="w-24 h-32 sm:w-28 sm:h-36 bg-[#F5F0E8] polygon-card overflow-hidden border border-[#E8DED0] flex items-center justify-center p-2 shrink-0">
                            <img src="{{ $fp->primary_image }}" data-sozie-fallback loading="lazy" decoding="async" alt="{{ $fp->name }}" class="w-full h-full object-contain filter drop-shadow-xl">
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="flex justify-between items-start gap-2">
                                <h4 class="font-serif font-bold text-base sm:text-lg text-[#EDE5D8] truncate">{{ $fp->name }}</h4>
                                <span class="text-sm font-extrabold text-[#8F6E3B] shrink-0">{{ $fp->formatted_price }}</span>
                            </div>
                            <p class="text-xs text-[#B5A897] font-bold mt-1 truncate">{{ __('Top:') }} {{ $fp->top_notes }}</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <button @click="addToCart({{ $fp->id }}, '{{ $fp->default_size }}')"
                                        class="px-3.5 py-1.5 bg-[#8F6E3B] text-white text-[10px] font-extrabold uppercase tracking-wider polygon-btn hover:bg-[#211E1A] transition-colors">
                                    {{ __('ADD TO CART') }}
                                </button>
                                <button @click="$dispatch('open-quickview', { id: {{ $fp->id }} })"
                                        class="px-3.5 py-1.5 bg-[#F5F0E8] border border-[#E8DED0] text-[#211E1A] text-[10px] font-bold uppercase tracking-wider polygon-btn hover:bg-[#17130F] hover:text-white">
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
<section class="py-20 relative border-b border-[#E8DED0] bg-[#F5F0E8] overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="flex flex-col md:flex-row justify-between items-end mb-12">
            <div>
                <span class="text-xs font-extrabold text-[#8F6E3B] uppercase tracking-[0.3em] block mb-1">{{ __('MOST LOVED CREATIONS') }}</span>
                <h2 class="font-serif font-bold text-3xl sm:text-4xl text-[#211E1A]">{{ __('BEST SELLING PERFUMES') }}</h2>
            </div>
            <a href="{{ route('shop.index', ['collection' => 'best_seller']) }}"
               class="text-xs font-extrabold text-[#8F6E3B] uppercase tracking-widest flex items-center gap-1 hover:text-[#211E1A] mt-4 md:mt-0">
                <span>{{ __('VIEW ALL BEST SELLERS') }}</span>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        </div>

        <!-- Product Cards Grid: Spacious Vertical Cards for Mobile & Desktop -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($bestSellers as $product)
            <div class="glass-panel p-4 sm:p-5 polygon-card border border-[#E8DED0] group hover:border-[#B99A5B] transition-all duration-300 flex flex-col justify-between relative bg-[#17130F] shadow-xs">

                <!-- Discount Badge -->
                @if($product->discount_percentage)
                <span class="absolute top-6 left-6 z-20 bg-[#8F6E3B] text-white text-[9px] font-extrabold uppercase px-2.5 py-1 polygon-badge shadow-md">
                    -{{ $product->discount_percentage }}%
                </span>
                @endif

                <!-- Wishlist Heart Button -->
                <button @click="toggleWishlist({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: '{{ $product->formatted_price }}', image: '{{ $product->primary_image }}' })"
                        :class="isInWishlist({{ $product->id }}) ? '[&_svg]:fill-rose-600 [&_svg]:text-rose-600' : ''"
                        class="absolute top-6 right-6 z-20 p-2 bg-[#17130F]/90 rounded-full text-[#EDE5D8] hover:text-rose-600 transition-colors shadow-sm border border-[#E8DED0]">
                    <i data-lucide="heart" class="w-4 h-4"></i>
                </button>

                <div>
                    <!-- Image Frame: Spacious, unclipped, tall vertical container for complete perfume bottle display -->
                    <div class="w-full h-72 sm:h-84 lg:h-96 bg-[#F5F0E8] polygon-card overflow-hidden mb-4 relative border border-[#E8DED0] flex items-center justify-center p-4">
                        <img src="{{ $product->primary_image }}" data-sozie-fallback loading="lazy" decoding="async"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-contain filter drop-shadow-2xl group-hover:scale-105 transition-transform duration-500">

                        <!-- Quick View Overlay Button -->
                        <div class="absolute inset-0 bg-[#211E1A]/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 p-3 backdrop-blur-xs">
                            <button @click="$dispatch('open-quickview', { id: {{ $product->id }} })"
                                    class="px-4 py-2 bg-[#8F6E3B] text-white font-extrabold text-xs uppercase tracking-wider polygon-btn hover:bg-[#211E1A]">
                                {{ __('QUICK VIEW') }}
                            </button>
                        </div>
                    </div>

                    <!-- Category Tag -->
                    <span class="text-[10px] font-extrabold text-[#8F6E3B] uppercase tracking-widest block mb-1">
                        {{ $product->category ? $product->category->name : __('Signature') }}
                    </span>

                    <a href="{{ route('shop.show', $product->slug) }}">
                        <h3 class="font-serif font-bold text-xl text-[#EDE5D8] group-hover:text-[#8F6E3B] transition-colors">
                            {{ $product->name }}
                        </h3>
                    </a>

                    <p class="text-xs text-[#B5A897] font-bold mt-1 line-clamp-1">{{ __('Notes:') }} {{ $product->top_notes }}</p>
                </div>

                <!-- Price & Action -->
                <div class="pt-4 mt-4 border-t border-[#E8DED0] flex items-center justify-between">
                    <div>
                        <span class="text-base sm:text-sm font-extrabold text-[#EDE5D8] block">{{ $product->formatted_price }}</span>
                        @if($product->discount_price)
                        <span class="text-[10px] text-[#7A6E63] line-through font-semibold">{{ $product->formatted_original_price }}</span>
                        @endif
                    </div>

                    <button @click="addToCart({{ $product->id }}, '{{ $product->default_size }}')"
                            class="p-2.5 bg-[#8F6E3B] text-white polygon-btn hover:bg-[#211E1A] transition-colors shadow-md">
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
<section id="scent-finder" class="py-24 relative overflow-hidden bg-[#F5F0E8] border-b border-[#E8DED0]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" x-data="scentFinder()" x-init="findMatch()">

        <div class="glass-panel-gold p-8 sm:p-12 polygon-card border border-[#B99A5B]/40 shadow-xl bg-[#17130F]">

            <div class="text-center mb-8">
                <span class="text-xs font-extrabold text-[#8F6E3B] uppercase tracking-[0.3em] block mb-2">{{ __('PERSONAL FRAGRANCE CONSULTATION') }}</span>
                <h2 class="font-serif font-bold text-3xl sm:text-5xl text-[#EDE5D8]">{{ __('FIND YOUR SIGNATURE SCENT Title') }}</h2>
                <p class="text-xs sm:text-sm text-[#B5A897] mt-3 max-w-lg mx-auto font-bold">
                    {{ __('Select your preferred scent profile to discover matching signature perfumes from our collection.') }}
                </p>
            </div>

            <div class="space-y-8">

                <!-- Scent Preference Options -->
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-widest text-[#8F6E3B] mb-3 text-center">{{ __('Select Your Scent Personality') }}</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <template x-for="s in ['Floral', 'Woody', 'Fresh', 'Vanilla', 'Spicy', 'Sweet', 'Oriental', 'Citrus']" :key="s">
                            <button @click="chooseScent(s)"
                                    :class="selectedScent === s ? 'bg-[#8F6E3B] text-white border-[#8F6E3B] font-extrabold shadow-md' : 'bg-[#17130F] text-[#EDE5D8] border-[#E8DED0] hover:border-[#8F6E3B] font-bold'"
                                    class="py-3 px-4 border text-xs uppercase tracking-wider polygon-btn transition-all">
                                <span x-text="s"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Match Results Box -->
                <div x-show="matches.length > 0" x-transition class="mt-8 pt-8 border-t border-[#E8DED0]">
                    <h3 class="font-serif font-bold text-2xl text-[#EDE5D8] text-center mb-6">{{ __('YOUR PERFECT FRAGRANCE MATCH') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <template x-for="p in matches" :key="p.id">
                            <div class="glass-card p-3.5 polygon-card border border-[#E8DED0] text-center flex flex-col justify-between bg-[#17130F]">
                                <div>
                                    <div class="w-full h-52 bg-[#F5F0E8] polygon-card mb-3 border border-[#E8DED0] flex items-center justify-center p-3">
                                        <img :src="p.images ? p.images[0] : p.campaign_image" data-sozie-fallback loading="lazy" decoding="async" class="w-full h-full object-contain filter drop-shadow-xl">
                                    </div>
                                    <h4 class="font-serif font-bold text-base text-[#EDE5D8]" x-text="p.name"></h4>
                                    <p class="text-[10px] text-[#8F6E3B] uppercase tracking-widest font-extrabold mt-1" x-text="p.scent_type + ' • ' + p.fragrance_family"></p>
                                </div>
                                <div class="mt-3 pt-3 border-t border-[#E8DED0] flex justify-between items-center gap-1">
                                    <span class="text-xs font-extrabold text-[#EDE5D8]" x-text="'TZS ' + Number(p.price).toLocaleString()"></span>
                                    <button @click="addToCart(p.id)" class="px-3 py-1.5 bg-[#8F6E3B] text-white text-[10px] font-extrabold uppercase polygon-btn hover:bg-[#211E1A]">
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
<section class="py-20 relative border-b border-[#E8DED0] bg-[#F5F0E8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-xs font-extrabold text-[#8F6E3B] uppercase tracking-[0.3em] block mb-1">{{ __('FRESH FROM OUR ATELIER') }}</span>
                <h2 class="font-serif font-bold text-3xl sm:text-4xl text-[#211E1A]">{{ __('NEW ARRIVALS') }}</h2>
            </div>
            <a href="{{ route('shop.index', ['collection' => 'new_arrival']) }}"
               class="text-xs font-extrabold text-[#8F6E3B] uppercase tracking-widest hover:text-[#211E1A]">
                {{ __('VIEW ALL NEW ARRIVALS') }} &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($newArrivals as $product)
            <div class="glass-panel p-4 sm:p-5 polygon-card border border-[#E8DED0] group hover:border-[#B99A5B] transition-all duration-300 flex flex-col justify-between relative bg-[#17130F] shadow-xs">

                <span class="absolute top-6 right-6 z-20 bg-[#8F6E3B] text-white text-[9px] font-extrabold uppercase px-2 py-0.5 polygon-badge">
                    {{ __('NEW') }}
                </span>

                <div>
                    <!-- Image Frame: Spacious vertical display -->
                    <div class="w-full h-72 sm:h-84 lg:h-96 bg-[#F5F0E8] polygon-card overflow-hidden mb-4 relative border border-[#E8DED0] flex items-center justify-center p-4">
                        <img src="{{ $product->primary_image }}" data-sozie-fallback loading="lazy" decoding="async"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-contain filter drop-shadow-2xl group-hover:scale-105 transition-transform duration-500">

                        <div class="absolute inset-0 bg-[#211E1A]/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 p-3 backdrop-blur-xs">
                            <button @click="$dispatch('open-quickview', { id: {{ $product->id }} })"
                                    class="px-4 py-2 bg-[#8F6E3B] text-white font-extrabold text-xs uppercase tracking-wider polygon-btn hover:bg-[#211E1A]">
                                {{ __('QUICK VIEW') }}
                            </button>
                        </div>
                    </div>

                    <span class="text-[10px] font-extrabold text-[#8F6E3B] uppercase tracking-widest block mb-0.5">
                        {{ $product->category ? $product->category->name : __('New Release') }}
                    </span>

                    <a href="{{ route('shop.show', $product->slug) }}">
                        <h3 class="font-serif font-bold text-xl text-[#EDE5D8] group-hover:text-[#8F6E3B] transition-colors">
                            {{ $product->name }}
                        </h3>
                    </a>

                    <p class="text-xs text-[#B5A897] font-bold mt-1 line-clamp-1">{{ $product->top_notes }}</p>
                </div>

                <div class="pt-4 mt-4 border-t border-[#E8DED0] flex items-center justify-between">
                    <span class="text-sm font-extrabold text-[#EDE5D8]">{{ $product->formatted_price }}</span>

                    <button @click="addToCart({{ $product->id }}, '{{ $product->default_size }}')"
                            class="p-2.5 bg-[#8F6E3B] text-white polygon-btn hover:bg-[#211E1A] transition-colors shadow-md">
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
<section class="py-24 relative overflow-hidden border-b border-[#E8DED0] bg-[#F5F0E8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <div class="lg:col-span-6 space-y-6">
                <span class="text-xs font-extrabold text-[#8F6E3B] uppercase tracking-[0.3em] block">{{ __('LUXURY CRAFTSMANSHIP') }}</span>
                <h2 class="font-serif font-bold text-4xl sm:text-5xl text-[#211E1A] leading-tight">
                    {{ __('THE SOZIE EXPERIENCE') }}:<br>
                    <span class="gold-gradient-text italic font-normal">{{ __('ARTISTRY IN EVERY DROP') }}</span>
                </h2>
                <p class="text-[#5C5248] text-sm leading-relaxed font-bold">
                    {{ __('We believe fragrance is more than a scent—it is an invisible armor of confidence and personal expression. Every bottle in the Sozie Collection is formulated with master perfumery techniques, incorporating pure botanical oils, rare spices, and long-wearing amber accords.') }}
                </p>

                <div class="grid grid-cols-2 gap-4 pt-4">
                    <div class="glass-card p-4 polygon-card border border-[#E8DED0] bg-[#17130F]">
                        <i data-lucide="shield-check" class="w-6 h-6 text-[#8F6E3B] mb-2"></i>
                        <h4 class="font-serif font-bold text-[#EDE5D8] text-base">{{ __('Pure Quality') }}</h4>
                        <p class="text-[11px] text-[#B5A897] mt-1 font-bold">{{ __('Authentic concentrated perfume oils and extracts.') }}</p>
                    </div>

                    <div class="glass-card p-4 polygon-card border border-[#E8DED0] bg-[#17130F]">
                        <i data-lucide="gem" class="w-6 h-6 text-[#8F6E3B] mb-2"></i>
                        <h4 class="font-serif font-bold text-[#EDE5D8] text-base">{{ __('Artistic Design') }}</h4>
                        <p class="text-[11px] text-[#B5A897] mt-1 font-bold">{{ __('Architectural geometric bottles and casing.') }}</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6">
                <div class="relative w-full h-[450px] glass-panel p-3 polygon-card border border-[#B99A5B]/40 gold-glow bg-[#17130F]">
                    @if($campaignSlide && ($campaignSlide['image'] ?? $campaignSlide['mobile_image']))
                    <img src="{{ $campaignSlide['image'] ?? $campaignSlide['mobile_image'] }}" data-sozie-fallback loading="lazy" decoding="async"
                         alt="{{ $campaignSlide['headline'] ?? __('The Sozie Experience') }}"
                         class="w-full h-full object-contain polygon-card border border-[#E8DED0] p-4 bg-[#F5F0E8]">
                    @else
                    <div class="w-full h-full polygon-card border border-[#E8DED0] bg-gradient-to-br from-[#B99A5B]/20 via-[#E8DED0] to-[#F5F0E8]"></div>
                    @endif
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ================================================================= -->
<!-- SECTION 08: CUSTOMER REVIEWS -->
<!-- ================================================================= -->
<section class="py-20 relative border-b border-[#E8DED0] bg-[#F5F0E8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-extrabold text-[#8F6E3B] uppercase tracking-[0.3em] block mb-2">{{ __('VERIFIED REVIEWS') }}</span>
            <h2 class="font-serif font-bold text-3xl sm:text-4xl text-[#211E1A]">{{ __('WHAT OUR CLIENTS SAY') }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($reviews as $rev)
            <div class="glass-card p-6 polygon-card border border-[#E8DED0] flex flex-col justify-between bg-[#17130F]">
                <div>
                    <div class="flex text-[#8F6E3B] gap-1 mb-3">
                        @for($i=0; $i<$rev->rating; $i++)
                        <i data-lucide="star" class="w-4 h-4 fill-[#8F6E3B]"></i>
                        @endfor
                    </div>
                    <p class="text-xs text-[#EDE5D8] italic leading-relaxed font-semibold">"{{ $rev->comment }}"</p>
                </div>

                <div class="mt-6 pt-4 border-t border-[#E8DED0] flex items-center justify-between">
                    <div>
                        <span class="font-serif font-bold text-sm text-[#EDE5D8] block">{{ $rev->customer_name }}</span>
                        <span class="text-[9px] text-emerald-400 font-extrabold uppercase tracking-wider">{{ __('Verified Purchase') }}</span>
                    </div>
                    <span class="text-[10px] text-[#B5A897] font-bold">{{ $rev->product ? $rev->product->name : __('Sozie Perfume') }}</span>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

<!-- ================================================================= -->
<!-- SECTION 09: CAMPAIGN GALLERY SLIDER (DYNAMICALLY RENDERED WHEN BANNERS EXIST) -->
<!-- ================================================================= -->
@if($configuredGallerySlides->count() > 0)
<section class="py-20 relative border-b border-[#E8DED0] bg-[#F5F0E8] overflow-hidden"
         x-data="gallerySlider({{ \Illuminate\Support\Js::from($configuredGallerySlides) }})"
         x-init="initSlider()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-10 gap-4">
            <div>
                <span class="text-xs font-extrabold text-[#8F6E3B] uppercase tracking-[0.3em] block mb-1">{{ __('INSTAGRAM & CAMPAIGN VISUALS') }}</span>
                <h2 class="font-serif font-bold text-3xl sm:text-4xl text-[#211E1A]">#SOZIECOLLECTION GALLERY</h2>
            </div>

            <template x-if="canPage">
                <div class="flex items-center gap-3">
                    <button @click="prevSlide()" class="p-2.5 bg-[#17130F] border border-[#E8DED0] text-[#8F6E3B] hover:bg-[#8F6E3B] hover:text-white transition-colors polygon-btn">
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </button>
                    <button @click="nextSlide()" class="p-2.5 bg-[#17130F] border border-[#E8DED0] text-[#8F6E3B] hover:bg-[#8F6E3B] hover:text-white transition-colors polygon-btn">
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </button>
                </div>
            </template>
        </div>

        <div class="overflow-hidden relative rounded-xl">
            <div class="flex transition-transform duration-700 ease-in-out gap-4"
                 :style="'transform: translateX(-' + (currentIndex * (100 / itemsToShow)) + '%);'">

                <template x-for="(slide, i) in slides" :key="'camp-' + i">
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 flex-shrink-0">
                        <div class="h-80 glass-panel polygon-card overflow-hidden group relative border border-[#E8DED0] bg-[#17130F] shadow-lg p-3 flex items-center justify-center">
                            <img :src="slide.image || slide.mobile_image" data-sozie-fallback :alt="slide.headline || 'Campaign Visual'"
                                 class="w-full h-full object-contain filter drop-shadow-xl group-hover:scale-105 transition-transform duration-700">

                            <div class="absolute top-3 left-3 z-10 bg-[#17130F]/90 backdrop-blur-md border border-[#E8DED0] text-[9px] font-extrabold text-[#8F6E3B] uppercase px-2.5 py-1 polygon-badge flex items-center gap-1.5">
                                <i data-lucide="camera" class="w-3 h-3 text-[#8F6E3B]"></i>
                                <span x-text="slide.eyebrow || '@sozie_collection'"></span>
                            </div>

                            <div class="absolute inset-0 bg-gradient-to-t from-[#211E1A] via-[#211E1A]/80 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col justify-end p-5 backdrop-blur-xs">
                                <span class="text-[10px] text-[#E8DED0] font-extrabold uppercase tracking-widest block" x-text="slide.highlight_text"></span>
                                <h4 class="font-serif font-bold text-lg text-white" x-text="slide.headline"></h4>
                                <p class="text-xs text-[#E8DED0] mt-0.5 font-medium" x-text="slide.subtitle"></p>
                                <a :href="slide.button_link || @js(route('shop.index'))" class="mt-3 inline-block py-1.5 px-3 bg-[#8F6E3B] text-white text-[10px] font-extrabold uppercase tracking-wider polygon-btn text-center hover:bg-[#E8DED0] hover:text-[#211E1A]">
                                    {{ __('Shop Scent') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </template>

            </div>
        </div>

    </div>
</section>
@endif

<!-- ================================================================= -->
<!-- SECTION 10: FINAL CTA -->
<!-- ================================================================= -->
<section class="py-24 relative overflow-hidden bg-gradient-to-r from-[#211E1A] via-[#3D352E] to-[#211E1A] text-white">
    <div class="max-w-4xl mx-auto px-4 text-center space-y-6 relative z-10">
        <span class="text-xs font-extrabold text-[#B99A5B] uppercase tracking-[0.4em] block">{{ __('READY TO ELEVATE YOUR SCENT?') }}</span>
        <h2 class="font-serif font-bold text-5xl sm:text-6xl text-white">{{ __('WEAR YOUR SIGNATURE.') }}</h2>
        <p class="text-[#E8DED0] text-sm max-w-lg mx-auto font-medium">
            {{ __('Experience luxury perfumes delivered directly to your doorstep with instant order processing and direct WhatsApp communication.') }}
        </p>
        <div>
            <a href="{{ route('shop.index') }}"
               class="inline-block px-12 py-4 bg-[#B99A5B] text-white border-2 border-[#B99A5B] font-extrabold text-xs tracking-[0.3em] uppercase polygon-btn hover:bg-[#E8DED0] hover:text-[#211E1A] shadow-2xl transition-all">
                {{ __('SHOP SOZIE NOW') }}
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function heroSlider(serverSlides) {
        const defaultSlides = [
            {
                image: null,
                mobile_image: null,
                name: 'SOZIE ELEGANCE',
                price: 'TZS 55,000',
                badge: 'SIGNATURE RELEASE',
                eyebrow: @js(__('THE ATELIER VISUAL EXPERIENCE')),
                headline: @js(__('YOUR SCENT.')),
                highlight_text: @js(__('YOUR SIGNATURE.')),
                description: @js(__('Hero Description')),
                button_text: @js(__('EXPLORE COLLECTION')),
                button_link: @js(route('shop.index')),
                secondary_button_text: @js(__('FIND YOUR SCENT')),
                secondary_button_link: '#scent-finder'
            }
        ];

        const initialSlides = (Array.isArray(serverSlides) && serverSlides.length > 0) ? serverSlides : defaultSlides;

        return {
            activeSlide: 0,
            slides: initialSlides,
            timer: null,
            get currentSlide() {
                return this.slides[this.activeSlide] || this.slides[0] || defaultSlides[0];
            },
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

    function gallerySlider(serverSlides) {
        return {
            currentIndex: 0,
            itemsToShow: 4,
            slides: serverSlides || [],
            get canPage() {
                return this.slides.length > this.itemsToShow;
            },
            initSlider() {
                this.updateItemsToShow();
                window.addEventListener('resize', () => this.updateItemsToShow());
                if (this.canPage) {
                    setInterval(() => {
                        this.nextSlide();
                    }, 4000);
                }
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
