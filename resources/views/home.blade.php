@extends('layouts.app')

@section('title', __('Sozie Collection | Luxury Perfumes & Signature Scents'))

@section('content')

{{--
    Every image on this page is a design the owner uploaded through
    Admin -> Store Setup -> Website Content. Nothing photographic ships with the
    storefront, so each large frame below either shows a configured slide or
    falls back to the brand's own warm-sand and champagne-gold treatment, never
    to stock photography. A slide with no design yet still drives the copy, so
    $heroSlides is filtered only where an actual image is needed.
--}}
@php
    $configuredSlides = $heroSlides
        ->filter(fn (array $slide): bool => filled($slide['image'] ?? null) || filled($slide['mobile_image'] ?? null))
        ->values();

    $campaignSlide = $configuredSlides->first();
@endphp

<!-- ================================================================= -->
<!-- SECTION 01: HERO / SPLASH EXPERIENCE (#EDE5D8 Warm Sand & #A8895F Champagne Gold) -->
<!-- ================================================================= -->
<section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden pt-10 pb-20 border-b border-[#322B23] bg-[#0C0A09]"
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
                <!-- x-if, not x-show: a slide design that was never uploaded must not
                     leave an <img> in the document with no src to load. -->
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
        <div class="absolute inset-0 bg-gradient-to-r from-[#0C0A09] via-[#0C0A09]/90 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#0C0A09] via-transparent to-[#0C0A09]/80"></div>

        <!-- Translucent Geometric Polygon Panels -->
        <div class="absolute -top-20 -right-20 w-[600px] h-[700px] bg-gradient-to-br from-[#A8895F]/15 via-[#C5A059]/10 to-[#0C0A09]/40 polygon-hero transform rotate-12 blur-sm"></div>

        <!-- Animated Floating Geometric Polygons -->
        <div class="absolute top-1/4 left-10 w-24 h-24 border border-[#A8895F]/40 polygon-card animate-float opacity-60"></div>
        <div class="absolute bottom-1/3 right-12 w-36 h-36 border border-[#A8895F]/30 polygon-card-reverse animate-float opacity-40" style="animation-delay: 2s;"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10 w-full relative">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <!-- Hero Content (Left) -->
            <div class="lg:col-span-7 space-y-6 text-left">

                <div x-show="currentSlide.eyebrow" class="inline-flex items-center gap-2 bg-[#17130F] border border-[#A8895F]/40 px-3.5 py-1.5 polygon-badge shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-[#A8895F] animate-ping"></span>
                    <span class="text-[11px] font-extrabold tracking-[0.25em] text-[#A8895F] uppercase"
                          x-text="currentSlide.eyebrow"></span>
                </div>

                <div class="space-y-2">
                    <h2 class="text-xs sm:text-sm font-extrabold tracking-[0.4em] text-[#A8895F] uppercase">{{ __('SOZIE COLLECTION') }}</h2>
                    <h1 class="font-serif font-bold text-4xl sm:text-6xl lg:text-7xl leading-tight text-[#EDE5D8] tracking-tight">
                        <span x-text="currentSlide.headline"></span>
                        <template x-if="currentSlide.highlight_text">
                            <span class="gold-gradient-text italic font-normal block mt-1" x-text="currentSlide.highlight_text"></span>
                        </template>
                    </h1>
                </div>

                <p x-show="currentSlide.description" class="text-[#B5A897] text-sm sm:text-base leading-relaxed max-w-xl font-semibold"
                   x-text="currentSlide.description"></p>

                <!-- CTAs -->
                <div class="flex flex-wrap items-center gap-4 pt-4">
                    <a :href="currentSlide.button_link"
                       class="px-8 py-4 bg-[#A8895F] border border-[#A8895F] text-[#12100E] font-extrabold text-xs tracking-[0.25em] uppercase polygon-btn shadow-xl shadow-[#A8895F]/20 hover:bg-[#12100E] hover:text-[#F8F5EF] flex items-center gap-3">
                        <span x-text="currentSlide.button_text">{{ __('EXPLORE COLLECTION') }}</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>

                    <a x-show="currentSlide.secondary_button_text"
                       :href="currentSlide.secondary_button_link"
                       class="px-8 py-4 bg-[#17130F] border border-[#A8895F]/40 text-[#EDE5D8] font-extrabold text-xs tracking-[0.2em] uppercase polygon-btn hover:bg-[#221D19] transition-all backdrop-blur-md flex items-center gap-2 shadow-sm">
                        <i data-lucide="sparkles" class="w-4 h-4 text-[#A8895F]"></i>
                        <span x-text="currentSlide.secondary_button_text">{{ __('FIND YOUR SCENT') }}</span>
                    </a>
                </div>

                <!-- Hero Metrics -->
                <div class="grid grid-cols-3 gap-6 pt-8 border-t border-[#322B23] max-w-lg">
                    <div class="navy-card p-3 polygon-card text-center border border-[#322B23] bg-[#17130F]">
                        <span class="font-serif font-bold text-2xl text-[#A8895F]">{{ __('12+ hrs') }}</span>
                        <span class="block text-[10px] text-[#B5A897] uppercase tracking-widest font-extrabold">{{ __('Longevity') }}</span>
                    </div>
                    <div class="navy-card p-3 polygon-card text-center border border-[#322B23] bg-[#17130F]">
                        <span class="font-serif font-bold text-2xl text-[#A8895F]">100%</span>
                        <span class="block text-[10px] text-[#B5A897] uppercase tracking-widest font-extrabold">{{ __('Authentic Notes') }}</span>
                    </div>
                    <div class="navy-card p-3 polygon-card text-center border border-[#322B23] bg-[#17130F]">
                        <span class="font-serif font-bold text-2xl text-[#A8895F]">{{ __('Fast') }}</span>
                        <span class="block text-[10px] text-[#B5A897] uppercase tracking-widest font-extrabold">{{ __('Doorstep Delivery') }}</span>
                    </div>
                </div>

            </div>

            <!-- Hero Visual Feature Bottle (Right) with SLIDING PICTURE -->
            <div class="lg:col-span-5 relative flex justify-center">
                <div class="relative w-80 h-[420px] sm:w-96 sm:h-[480px] group">

                    <!-- Outer Frame in Champagne Gold -->
                    <div class="absolute inset-0 bg-[#322B23] polygon-card border-2 border-[#A8895F] gold-glow-lg transition-all duration-500 group-hover:scale-[1.02]"></div>

                    <!-- Main Image Container Box -->
                    <div class="absolute inset-2 bg-[#17130F] polygon-card overflow-hidden shadow-2xl border border-[#322B23]">

                        <!-- Brand plate. Stands in for the slide design until the owner
                             uploads one, so the frame keeps its size and never reads as
                             an empty image box. -->
                        <template x-if="! currentSlide.image && ! currentSlide.mobile_image">
                            <div class="absolute inset-0 bg-gradient-to-br from-[#A8895F]/20 via-[#2E2620] to-[#0C0A09]"></div>
                        </template>

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

                                <template x-if="slide.mobile_image || slide.image">
                                    <img :src="slide.mobile_image || slide.image"
                                         data-sozie-fallback
                                         :alt="slide.headline"
                                         class="w-full h-full object-cover object-center transform hover:scale-110 transition-transform duration-700 md:hidden">
                                </template>
                                <template x-if="slide.image || slide.mobile_image">
                                    <img :src="slide.image || slide.mobile_image"
                                         data-sozie-fallback
                                         :alt="slide.headline"
                                         class="hidden w-full h-full object-cover object-center transform hover:scale-110 transition-transform duration-700 md:block">
                                </template>

                                <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-[#0C0A09]/90 via-[#0C0A09]/40 to-transparent"></div>
                            </div>
                        </template>

                        <!-- Floating Glass Product Tag -->
                        <div class="absolute bottom-4 left-4 right-4 glass-panel p-3.5 polygon-card border border-[#A8895F]/40 flex justify-between items-center z-20 shadow-2xl backdrop-blur-md bg-[#17130F]">
                            <div>
                                <span class="text-[10px] text-[#A8895F] font-extrabold uppercase tracking-widest block"
                                      x-text="currentSlide.eyebrow"></span>
                                <h4 class="font-serif font-bold text-[#EDE5D8] text-base tracking-wide"
                                    x-text="currentSlide.headline"></h4>
                            </div>
                            <span class="text-[10px] text-[#A8895F] font-extrabold uppercase text-right max-w-[120px] leading-tight"
                                  x-text="currentSlide.button_text"></span>
                        </div>

                    </div>

                    <!-- Slide Controls / Navigation Dots. A single slide has nothing to
                         page through, so no indicator is drawn at all rather than one
                         dead dot. -->
                    <template x-if="slides.length > 1">
                        <div class="absolute -bottom-8 left-0 right-0 flex justify-center items-center gap-2 z-20">
                            <template x-for="(slide, index) in slides" :key="'dot-' + index">
                                <button @click="activeSlide = index"
                                        :class="activeSlide === index ? 'w-8 bg-[#A8895F]' : 'w-2 bg-[#29241F]/30 hover:bg-[#29241F]/60'"
                                        class="h-2 rounded-full transition-all duration-300"
                                        :aria-label="@js(__('Slide :number')).replace(':number', index + 1)"></button>
                            </template>
                        </div>
                    </template>

                </div>
            </div>

        </div>
    </div>
</section>


<!-- ================================================================= -->
<!-- SECTION 03: SIGNATURE SCENTS (CROWN JEWEL & ARTISANAL ARRANGEMENT) -->
<!-- ================================================================= -->
<section class="py-24 relative overflow-hidden border-b border-[#322B23] bg-[#0C0A09]">
    <div class="absolute inset-0 bg-radial from-[#A8895F]/15 via-[#0C0A09] to-[#0C0A09] pointer-events-none opacity-80"></div>
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#C5A059]/15 blur-[140px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <!-- Left Large Campaign Image Frame -->
            <div class="lg:col-span-6 relative">
                <div class="w-full h-[500px] glass-panel p-2 polygon-card border border-[#A8895F]/40 shadow-2xl gold-glow bg-[#17130F]">
                    <div class="w-full h-full polygon-card overflow-hidden relative">
                        @if($campaignSlide)
                        <img src="{{ $campaignSlide['image'] ?? $campaignSlide['mobile_image'] }}" loading="lazy" decoding="async"
                             alt="{{ $campaignSlide['headline'] ?? __('Sozie Signature Scent') }}"
                             class="w-full h-full object-cover">
                        @else
                        {{-- No slide design uploaded yet: the campaign frame keeps its
                             shape and becomes the brand's own editorial plate rather
                             than an empty image box. --}}
                        <div class="w-full h-full bg-gradient-to-t from-[#0C0A09] via-[#241E19] to-[#3d2f22]"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-[#29241F]/90 via-[#29241F]/30 to-transparent opacity-90"></div>
                        <div class="absolute bottom-8 left-8 right-8 text-white space-y-1">
                            <span class="text-xs font-extrabold text-[#A89C8C] tracking-[0.3em] uppercase block">{{ __('Sozie Signature Scent') }} &bull; {{ __('CROWN JEWEL COLLECTION') }}</span>
                            <h3 class="font-serif font-bold text-3xl text-[#F8F5EF]">SOZIE GOLDEN AURA</h3>
                            <p class="text-xs text-[#A89C8C] mt-2 line-clamp-2 font-medium">{{ __('Kashmiri saffron, warm honeycomb, and crystal amber blended to perfection.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Showcase Card Details -->
            <div class="lg:col-span-6 space-y-8">
                <div class="space-y-3">
                    <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block">{{ __('ARTISANAL FRAGRANCE BLENDS') }}</span>
                    <h2 class="font-serif font-bold text-4xl text-[#EDE5D8]">{{ __('SIGNATURE SCENTS ARRANGEMENT') }}</h2>
                    <p class="text-[#B5A897] text-sm leading-relaxed font-semibold">
                        {{ __('Each bottle of Sozie Collection signature perfume is handcrafted with raw botanical essences and rare aromatic resins, guaranteeing a multi-layered scent experience that evolves throughout your day.') }}
                    </p>
                </div>

                <div class="space-y-4">
                    @foreach($featuredProducts->take(2) as $fp)
                    <div class="navy-card p-5 polygon-card border border-[#322B23] flex gap-4 items-center hover:border-[#A8895F] transition-all bg-[#17130F]">
                        <img src="{{ $fp->primary_image }}" data-sozie-fallback loading="lazy" decoding="async" alt="{{ $fp->name }}" class="w-20 h-20 object-cover polygon-card border border-[#322B23]">
                        <div class="flex-grow">
                            <div class="flex justify-between items-start">
                                <h4 class="font-serif font-bold text-lg text-[#EDE5D8]">{{ $fp->name }}</h4>
                                <span class="text-base sm:text-sm font-extrabold text-[#A8895F]">{{ $fp->formatted_price }}</span>
                            </div>
                            <p class="text-xs text-[#B5A897] font-semibold mt-1">{{ __('Top:') }} {{ $fp->top_notes }}</p>
                            <div class="flex gap-2 mt-3">
                                <button @click="addToCart({{ $fp->id }}, '{{ $fp->default_size }}')"
                                        class="px-4 py-1.5 bg-[#A8895F] border border-[#A8895F] text-[#12100E] text-[10px] font-extrabold uppercase tracking-wider polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF] transition-colors">
                                    {{ __('ADD TO CART') }}
                                </button>
                                <button @click="$dispatch('open-quickview', { id: {{ $fp->id }} })"
                                        class="px-4 py-1.5 bg-[#100E0C] border border-[#322B23] text-[#EDE5D8] text-[10px] font-bold uppercase tracking-wider polygon-btn hover:bg-[#221D19]">
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
<section class="py-20 relative border-b border-[#322B23] bg-[#0C0A09] overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="flex flex-col md:flex-row justify-between items-end mb-12">
            <div>
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">{{ __('MOST LOVED CREATIONS') }}</span>
                <h2 class="font-serif font-bold text-3xl sm:text-4xl text-[#EDE5D8]">{{ __('BEST SELLING PERFUMES') }}</h2>
            </div>
            <a href="{{ route('shop.index', ['collection' => 'best_seller']) }}"
               class="text-xs font-extrabold text-[#A8895F] uppercase tracking-widest flex items-center gap-1 hover:text-[#F8F5EF] mt-4 md:mt-0">
                <span>{{ __('VIEW ALL BEST SELLERS') }}</span>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        </div>

        <!-- Product Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($bestSellers as $product)
            <div class="navy-card p-4 polygon-card border border-[#322B23] group hover:border-[#A8895F] transition-all duration-300 flex flex-col justify-between relative bg-[#17130F]">

                <!-- Discount Badge -->
                @if($product->discount_percentage)
                <span class="absolute top-6 left-6 z-20 bg-[#221D19] text-white text-[9px] font-extrabold uppercase px-2.5 py-1 polygon-badge shadow-md">
                    -{{ $product->discount_percentage }}%
                </span>
                @endif

                <!-- Wishlist Heart Button -->
                <button @click="toggleWishlist({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: '{{ $product->formatted_price }}', image: '{{ $product->primary_image }}' })"
                        :class="isInWishlist({{ $product->id }}) ? '[&_svg]:fill-[#A8895F] [&_svg]:text-[#A8895F]' : ''"
                        class="absolute top-6 right-6 z-20 p-2 bg-[#221D19]/90 rounded-full text-[#EDE5D8] hover:text-[#A8895F] transition-colors shadow-sm">
                    <i data-lucide="heart" class="w-4 h-4"></i>
                </button>

                <div>
                    <!-- Image Frame -->
                    <div class="w-full h-64 bg-[#17130F] polygon-card overflow-hidden mb-4 relative border border-[#322B23]">
                        <img src="{{ $product->primary_image }}" data-sozie-fallback loading="lazy" decoding="async"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                        <!-- Quick View Overlay Button -->
                        <div class="absolute inset-0 bg-[#29241F]/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 p-4 backdrop-blur-xs">
                            <button @click="$dispatch('open-quickview', { id: {{ $product->id }} })"
                                    class="px-4 py-2 bg-[#A8895F] border border-[#A8895F] text-[#12100E] font-extrabold text-xs uppercase tracking-wider polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF]">
                                {{ __('QUICK VIEW') }}
                            </button>
                        </div>
                    </div>

                    <!-- Category Tag -->
                    <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-widest block mb-1">
                        {{ $product->category ? $product->category->name : __('Signature') }}
                    </span>

                    <a href="{{ route('shop.show', $product->slug) }}">
                        <h3 class="font-serif font-bold text-xl text-[#EDE5D8] group-hover:text-[#A8895F] transition-colors">
                            {{ $product->name }}
                        </h3>
                    </a>

                    <p class="text-xs text-[#B5A897] font-medium mt-1 line-clamp-1">{{ __('Notes:') }} {{ $product->top_notes }}</p>
                </div>

                <!-- Price & Action -->
                <div class="pt-4 mt-4 border-t border-[#322B23] flex items-center justify-between">
                    <div>
                        <span class="text-base sm:text-sm font-extrabold text-[#A8895F] block">{{ $product->formatted_price }}</span>
                        @if($product->discount_price)
                        <span class="text-[10px] text-[#A89C8C] line-through font-semibold">{{ $product->formatted_original_price }}</span>
                        @endif
                    </div>

                    <button @click="addToCart({{ $product->id }}, '{{ $product->default_size }}')"
                            class="p-2.5 bg-[#A8895F] border border-[#A8895F] text-[#12100E] polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF] transition-colors shadow-md">
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
<section id="scent-finder" class="py-24 relative overflow-hidden bg-[#0C0A09] border-b border-[#322B23]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" x-data="scentFinder()" x-init="findMatch()">

        <div class="bg-[#17130F] p-8 sm:p-12 polygon-card border border-[#A8895F]/40 shadow-2xl">

            <div class="text-center mb-8">
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-2">{{ __('PERSONAL FRAGRANCE CONSULTATION') }}</span>
                <h2 class="font-serif font-bold text-3xl sm:text-5xl text-[#EDE5D8]">{{ __('FIND YOUR SIGNATURE SCENT Title') }}</h2>
                <p class="text-xs sm:text-sm text-[#B5A897] mt-3 max-w-lg mx-auto font-semibold">
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
                                    :class="selectedScent === s ? 'bg-[#A8895F] text-[#12100E] border-[#A8895F] font-extrabold shadow-md' : 'bg-[#17130F] text-[#EDE5D8] border-[#322B23] hover:border-[#A8895F] font-bold'"
                                    class="py-3 px-4 border text-xs uppercase tracking-wider polygon-btn transition-all">
                                <span x-text="s"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Match Results Box -->
                <div x-show="matches.length > 0" x-transition class="mt-8 pt-8 border-t border-[#322B23]">
                    <h3 class="font-serif font-bold text-2xl text-[#EDE5D8] text-center mb-6">{{ __('YOUR PERFECT FRAGRANCE MATCH') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <template x-for="p in matches" :key="p.id">
                            <div class="navy-card p-4 polygon-card border border-[#322B23] text-center flex flex-col justify-between bg-[#17130F]">
                                <div>
                                    <img :src="p.images ? p.images[0] : p.campaign_image" data-sozie-fallback loading="lazy" decoding="async" class="w-full h-40 object-cover polygon-card mb-3 border border-[#322B23]">
                                    <h4 class="font-serif font-bold text-lg text-[#EDE5D8]" x-text="p.name"></h4>
                                    <p class="text-[10px] text-[#A8895F] uppercase tracking-widest font-extrabold mt-1" x-text="p.scent_type + ' • ' + p.fragrance_family"></p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-[#322B23] flex justify-between items-center gap-1">
                                    <span class="text-sm sm:text-xs font-extrabold text-[#EDE5D8]" x-text="'TZS ' + Number(p.price).toLocaleString()"></span>
                                    <button @click="addToCart(p.id)" class="px-3 py-1.5 bg-[#A8895F] border border-[#A8895F] text-[#12100E] text-[10px] font-extrabold uppercase polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF]">
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
<section class="py-20 relative border-b border-[#322B23] bg-[#0C0A09]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">{{ __('FRESH FROM OUR ATELIER') }}</span>
                <h2 class="font-serif font-bold text-3xl sm:text-4xl text-[#EDE5D8]">{{ __('NEW ARRIVALS') }}</h2>
            </div>
            <a href="{{ route('shop.index', ['collection' => 'new_arrival']) }}"
               class="text-xs font-extrabold text-[#A8895F] uppercase tracking-widest hover:text-[#F8F5EF]">
                {{ __('VIEW ALL NEW ARRIVALS') }} &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($newArrivals as $product)
            <div class="navy-card p-4 polygon-card border border-[#322B23] group hover:border-[#A8895F] transition-all duration-300 flex flex-col justify-between relative bg-[#17130F]">

                <span class="absolute top-6 right-6 z-20 bg-[#221D19] text-white text-[9px] font-extrabold uppercase px-2 py-0.5 polygon-badge">
                    {{ __('NEW') }}
                </span>

                <div>
                    <div class="w-full h-64 bg-[#17130F] polygon-card overflow-hidden mb-4 relative border border-[#322B23]">
                        <img src="{{ $product->primary_image }}" data-sozie-fallback loading="lazy" decoding="async"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                        <div class="absolute inset-0 bg-[#29241F]/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 p-4 backdrop-blur-xs">
                            <button @click="$dispatch('open-quickview', { id: {{ $product->id }} })"
                                    class="px-4 py-2 bg-[#A8895F] border border-[#A8895F] text-[#12100E] font-extrabold text-xs uppercase tracking-wider polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF]">
                                {{ __('QUICK VIEW') }}
                            </button>
                        </div>
                    </div>

                    <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-widest block mb-1">
                        {{ $product->category ? $product->category->name : __('New Release') }}
                    </span>

                    <a href="{{ route('shop.show', $product->slug) }}">
                        <h3 class="font-serif font-bold text-xl text-[#EDE5D8] group-hover:text-[#A8895F] transition-colors">
                            {{ $product->name }}
                        </h3>
                    </a>

                    <p class="text-xs text-[#B5A897] font-medium mt-1 line-clamp-1">{{ __('Notes:') }} {{ $product->top_notes }}</p>
                </div>

                <div class="pt-4 mt-4 border-t border-[#322B23] flex items-center justify-between">
                    <span class="text-base sm:text-sm font-extrabold text-[#A8895F]">{{ $product->formatted_price }}</span>

                    <button @click="addToCart({{ $product->id }}, '{{ $product->default_size }}')"
                            class="p-2.5 bg-[#A8895F] border border-[#A8895F] text-[#12100E] polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF] transition-colors">
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
<section class="py-24 relative overflow-hidden border-b border-[#322B23] bg-[#0C0A09]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <div class="lg:col-span-6 space-y-6">
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block">{{ __('LUXURY CRAFTSMANSHIP') }}</span>
                <h2 class="font-serif font-bold text-4xl sm:text-5xl text-[#EDE5D8] leading-tight">
                    {{ __('THE SOZIE EXPERIENCE') }}:<br>
                    <span class="gold-gradient-text italic font-normal">{{ __('ARTISTRY IN EVERY DROP') }}</span>
                </h2>
                <p class="text-[#B5A897] text-sm leading-relaxed font-semibold">
                    {{ __('We believe fragrance is more than a scent—it is an invisible armor of confidence and personal expression. Every bottle in the Sozie Collection is formulated with master perfumery techniques, incorporating pure botanical oils, rare spices, and long-wearing amber accords.') }}
                </p>

                <div class="grid grid-cols-2 gap-4 pt-4">
                    <div class="navy-card p-4 polygon-card border border-[#322B23] bg-[#17130F]">
                        <i data-lucide="shield-check" class="w-6 h-6 text-[#A8895F] mb-2"></i>
                        <h4 class="font-serif font-bold text-[#EDE5D8] text-base">{{ __('Pure Quality') }}</h4>
                        <p class="text-[11px] text-[#B5A897] mt-1 font-semibold">{{ __('Authentic concentrated perfume oils and extracts.') }}</p>
                    </div>

                    <div class="navy-card p-4 polygon-card border border-[#322B23] bg-[#17130F]">
                        <i data-lucide="gem" class="w-6 h-6 text-[#A8895F] mb-2"></i>
                        <h4 class="font-serif font-bold text-[#EDE5D8] text-base">{{ __('Artistic Design') }}</h4>
                        <p class="text-[11px] text-[#B5A897] mt-1 font-semibold">{{ __('Architectural geometric bottles and casing.') }}</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6">
                <div class="relative w-full h-[450px] glass-panel p-3 polygon-card border border-[#A8895F]/40 gold-glow bg-[#17130F]">
                    @if($campaignSlide)
                    <img src="{{ $campaignSlide['image'] ?? $campaignSlide['mobile_image'] }}" loading="lazy" decoding="async"
                         alt="{{ $campaignSlide['headline'] ?? __('The Sozie Experience') }}"
                         class="w-full h-full object-cover polygon-card border border-[#322B23]">
                    @else
                    {{-- Same story as the campaign frame above: a designed warm-sand
                         plate instead of an empty picture. --}}
                    <div class="w-full h-full polygon-card border border-[#322B23] bg-gradient-to-br from-[#A8895F]/20 via-[#2E2620] to-[#0C0A09]"></div>
                    @endif
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ================================================================= -->
<!-- SECTION 08: CUSTOMER REVIEWS -->
<!-- ================================================================= -->
<section class="py-20 relative border-b border-[#322B23] bg-[#0C0A09]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-2">{{ __('VERIFIED REVIEWS') }}</span>
            <h2 class="font-serif font-bold text-3xl sm:text-4xl text-[#EDE5D8]">{{ __('WHAT OUR CLIENTS SAY') }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($reviews as $rev)
            <div class="navy-card p-6 polygon-card border border-[#322B23] flex flex-col justify-between bg-[#17130F]">
                <div>
                    <div class="flex text-[#A8895F] gap-1 mb-3">
                        @for($i=0; $i<$rev->rating; $i++)
                        <i data-lucide="star" class="w-4 h-4 fill-[#A8895F]"></i>
                        @endfor
                    </div>
                    <p class="text-xs text-[#B5A897] italic leading-relaxed font-semibold">"{{ $rev->comment }}"</p>
                </div>

                <div class="mt-6 pt-4 border-t border-[#322B23] flex items-center justify-between">
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
<!-- SECTION 09: SOCIAL / CAMPAIGN GALLERY INTERACTIVE SLIDER -->
<!-- ================================================================= -->
{{--
    The gallery is the owner's own slide photography, captioned with the copy they
    typed in Admin -> Store Setup -> Website Content. With no active slide
    carrying a design the whole section is withheld rather than rendered as an
    empty carousel with orphan arrows.
--}}
@if($configuredSlides->isNotEmpty())
<section class="py-20 relative border-b border-[#322B23] bg-[#0C0A09] overflow-hidden"
         x-data="gallerySlider({{ \Illuminate\Support\Js::from($configuredSlides) }})"
         x-init="initSlider()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-10 gap-4">
            <div>
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">{{ __('INSTAGRAM & CAMPAIGN VISUALS') }}</span>
                <h2 class="font-serif font-bold text-3xl sm:text-4xl text-[#EDE5D8]">{{ __('#SOZIECOLLECTION GALLERY') }}</h2>
            </div>

            <!-- Slide Navigation Controls. Hidden until there is more than one screen
                 of cards, so a single slide never leaves dead arrows behind. -->
            <template x-if="canPage">
                <div class="flex items-center gap-3">
                    <button @click="prevSlide()" class="p-2.5 bg-[#17130F] border border-[#322B23] text-[#A8895F] hover:bg-[#A8895F] hover:text-[#12100E] transition-colors polygon-btn">
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </button>
                    <button @click="nextSlide()" class="p-2.5 bg-[#17130F] border border-[#322B23] text-[#A8895F] hover:bg-[#A8895F] hover:text-[#12100E] transition-colors polygon-btn">
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </button>
                </div>
            </template>
        </div>

        <!-- Interactive Sliding Track -->
        <div class="overflow-hidden relative rounded-xl">
            <div class="flex transition-transform duration-700 ease-in-out gap-4"
                 :style="'transform: translateX(-' + (currentIndex * (100 / itemsToShow)) + '%);'">

                <template x-for="(slide, i) in slides" :key="'campaign-' + i">
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 flex-shrink-0">
                        <div class="h-80 navy-card polygon-card overflow-hidden group relative border border-[#322B23] bg-[#17130F] shadow-lg">
                            <img :src="slide.image" :alt="slide.title" data-sozie-fallback loading="lazy" decoding="async"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                            <!-- Instagram Handle Badge. The glyph is inlined, exactly like the
                                 footer social links, because the self-hosted Lucide build has no
                                 "instagram" icon: createIcons() warns and leaves the <i> empty, which
                                 is what made all six gallery badges render with no icon. -->
                            <div class="absolute top-3 left-3 z-10 bg-[#17130F]/90 backdrop-blur-md border border-[#322B23] text-[9px] font-extrabold text-[#A8895F] uppercase px-2.5 py-1 polygon-badge flex items-center gap-1.5">
                                <svg class="w-3 h-3 fill-[#A8895F] flex-shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-.059-1.28-.073-1.689-.073-4.949zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                                <span x-text="slide.tag"></span>
                            </div>

                            <!-- Hover Overlay Card Details -->
                            <div class="absolute inset-0 bg-gradient-to-t from-[#29241F]/90 via-[#29241F]/60 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col justify-end p-5 backdrop-blur-xs text-white">
                                <span class="text-[10px] text-[#A89C8C] font-extrabold uppercase tracking-widest block" x-text="slide.handle"></span>
                                <h4 class="font-serif font-bold text-lg text-white" x-text="slide.title"></h4>
                                <p class="text-xs text-gray-200 mt-0.5 font-medium" x-text="slide.subtitle"></p>
                                <a href="{{ route('shop.index') }}" class="mt-3 inline-block py-1.5 px-3 bg-[#A8895F] text-[#12100E] text-[10px] font-extrabold uppercase tracking-wider polygon-btn text-center hover:bg-black hover:text-[#F8F5EF]">
                                    {{ __('Shop Scent') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </template>

            </div>
        </div>

        <!-- Slide Progress Dots -->
        <template x-if="canPage">
            <div class="flex justify-center items-center gap-2 mt-6">
                <template x-for="(slide, i) in slides" :key="'dot-camp-' + i">
                    <button @click="currentIndex = i"
                            :class="currentIndex === i ? 'w-8 bg-[#A8895F]' : 'w-2 bg-[#29241F]/30 hover:bg-[#29241F]/60'"
                            class="h-2 rounded-full transition-all duration-300"></button>
                </template>
            </div>
        </template>

    </div>
</section>
@endif

<!-- ================================================================= -->
<!-- SECTION 10: FINAL CTA -->
<!-- ================================================================= -->
<section class="py-24 relative overflow-hidden bg-gradient-to-r from-[#241E19] via-[#1A1613] to-[#0C0A09] text-[#F8F5EF]">
    <div class="max-w-4xl mx-auto px-4 text-center space-y-6 relative z-10">
        <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.4em] block">{{ __('READY TO ELEVATE YOUR SCENT?') }}</span>
        <h2 class="font-serif font-bold text-5xl sm:text-6xl text-[#F8F5EF]">{{ __('WEAR YOUR SIGNATURE.') }}</h2>
        <p class="text-[#A89C8C] text-sm max-w-lg mx-auto font-medium">
            {{ __('Experience luxury perfumes delivered directly to your doorstep with instant order processing and direct WhatsApp communication.') }}
        </p>
        <div>
            <a href="{{ route('shop.index') }}"
               class="inline-block px-12 py-4 bg-[#A8895F] text-[#12100E] border-2 border-[#A8895F] font-extrabold text-xs tracking-[0.3em] uppercase polygon-btn hover:bg-[#F8F5EF] hover:text-[#12100E] shadow-2xl transition-all">
                {{ __('SHOP SOZIE NOW') }}
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function heroSlider(serverSlides) {
        {{--
            The storefront ships no photography of its own: every hero image is a
            slide design the owner uploaded through Admin -> Store Setup ->
            Website Content. Until the first one exists this object is the hero —
            the same typography, description and calls to action the markup already
            ships as its no-JavaScript default.

            `image` deliberately stays null: the frame binds to `currentSlide` and
            substitutes the brand plate rather than rendering an <img> with nothing
            to load, so an unconfigured hero is a designed page and never a broken
            or blank one. Every binding reads `currentSlide`, not
            `slides[activeSlide]`, so no expression can index a slide that is not
            there.
        --}}
        const defaultSlide = {
            image: null,
            mobile_image: null,
            eyebrow: @js(__('THE ATELIER VISUAL EXPERIENCE')),
            headline: @js(__('YOUR SCENT.')),
            highlight_text: @js(__('YOUR SIGNATURE.')),
            description: @js(__('Hero Description')),
            button_text: @js(__('EXPLORE COLLECTION')),
            button_link: @js(route('shop.index')),
            secondary_button_text: @js(__('FIND YOUR SCENT')),
            secondary_button_link: '#scent-finder'
        };

        return {
            activeSlide: 0,
            slides: Array.isArray(serverSlides) ? serverSlides : [],
            timer: null,
            get currentSlide() {
                return this.slides[this.activeSlide] || defaultSlide;
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
        {{--
            The gallery is made of the owner's own slide photography, captioned with
            the copy they typed in Admin -> Store Setup -> Website Content: the mobile
            design when they uploaded one, otherwise the desktop design, the eyebrow
            as the badge, the highlight line above the headline, and the headline and
            description on the card. Nothing is invented and nothing is bundled, which
            is also why the whole section is only rendered when at least one active
            slide carries a design.
        --}}
        return {
            currentIndex: 0,
            itemsToShow: 4,
            timer: null,
            slides: (Array.isArray(serverSlides) ? serverSlides : []).map((slide) => ({
                image: slide.mobile_image || slide.image,
                title: slide.headline,
                handle: slide.highlight_text,
                subtitle: slide.description,
                tag: slide.eyebrow
            })),
            get canPage() {
                return this.slides.length > this.itemsToShow;
            },
            initSlider() {
                this.updateItemsToShow();

                this.onResize = () => this.updateItemsToShow();
                window.addEventListener('resize', this.onResize);

                if (!this.canPage) {
                    return;
                }

                this.timer = setInterval(() => {
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
                const maxIndex = Math.max(0, this.slides.length - this.itemsToShow);
                if (this.currentIndex >= maxIndex) {
                    this.currentIndex = 0;
                } else {
                    this.currentIndex++;
                }
            },
            prevSlide() {
                const maxIndex = Math.max(0, this.slides.length - this.itemsToShow);
                if (this.currentIndex <= 0) {
                    this.currentIndex = maxIndex > 0 ? maxIndex : 0;
                } else {
                    this.currentIndex--;
                }
            },
            destroy() {
                window.removeEventListener('resize', this.onResize);
                if (this.timer) {
                    clearInterval(this.timer);
                }
            }
        }
    }
</script>
@endpush
