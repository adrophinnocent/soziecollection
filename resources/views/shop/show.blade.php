@extends('layouts.app')

@section('title', $product->name . ' | ' . __('Sozie Collection'))

@section('content')

@php
    $displayVariants = $product->variants->filter(fn($v) => $v->price > 0 && $v->is_available);
@endphp

<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="productDetail({{ $product->id }}, {{ json_encode($displayVariants) }}, {{ $product->effective_price }})">

    <!-- Breadcrumb -->
    <nav class="flex text-xs text-[#B5A897] mb-8 uppercase tracking-widest gap-2 font-bold">
        <a href="{{ route('home') }}" class="hover:text-[#A8895F]">{{ __('Home') }}</a>
        <span>/</span>
        <a href="{{ route('shop.index') }}" class="hover:text-[#A8895F]">{{ __('Shop') }}</a>
        <span>/</span>
        <span class="text-[#A8895F] font-extrabold">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

        <!-- LEFT: GALLERY & VIDEO -->
        <div class="lg:col-span-6 space-y-6">

            <!-- Main Featured Image in Polygonal Frame: Spacious & Unclipped -->
            <div class="relative w-full h-[480px] sm:h-[540px] glass-panel p-4 polygon-card border border-[#A8895F]/40 gold-glow bg-[#17130F] flex items-center justify-center">
                <img :src="activeImage" data-sozie-fallback alt="{{ $product->name }}" class="w-full h-full object-contain filter drop-shadow-2xl polygon-card">
            </div>

            <!-- Larger, Clearer Gallery Thumbnails -->
            <div class="flex gap-3 overflow-x-auto pb-2">
                @if(is_array($product->images))
                    @foreach($product->images as $img)
                    <button @click="activeImage = '{{ $img }}'"
                            :class="activeImage === '{{ $img }}' ? 'border-[#A8895F]' : 'border-[#322B23]'"
                            class="w-24 h-28 sm:w-28 sm:h-32 polygon-card border-2 flex-shrink-0 overflow-hidden bg-[#17130F] p-2 flex items-center justify-center hover:border-[#A8895F] transition-all">
                        <img src="{{ $img }}" data-sozie-fallback loading="lazy" decoding="async" class="w-full h-full object-contain filter drop-shadow-md">
                    </button>
                    @endforeach
                @endif
            </div>

            <!-- EMBEDDED CAMPAIGN VIDEO -->
            @if($product->embed_video_url)
            <div class="glass-panel p-4 polygon-card border border-[#A8895F]/40 space-y-3 bg-[#17130F]">
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.25em] flex items-center gap-2">
                    <i data-lucide="play-circle" class="w-4 h-4 text-[#A8895F]"></i>
                    {{ __('PERFUME CAMPAIGN VIDEO') }}
                </span>
                <div class="aspect-video w-full polygon-card overflow-hidden border border-[#322B23] shadow-lg bg-black">
                    <iframe src="{{ $product->embed_video_url }}"
                            title="{{ __(':name Campaign Video', ['name' => $product->name]) }}"
                            class="w-full h-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            </div>
            @endif

        </div>

        <!-- RIGHT: PRODUCT DETAILS -->
        <div class="lg:col-span-6 space-y-6">

            <div>
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">{{ $product->brand ?: 'SOZIE COLLECTION' }}</span>
                <h1 class="font-serif font-bold text-4xl sm:text-5xl text-[#EDE5D8]">{{ $product->name }}</h1>
                <p class="text-xs font-extrabold text-[#B5A897] uppercase tracking-widest mt-2">
                    {{ $product->concentration }} • {{ $product->fragrance_family ?: $product->scent_type }} • {{ $product->gender }}
                </p>
            </div>

            <!-- Rating Summary & Badges -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex text-[#A8895F] gap-1 items-center">
                    @for($i=1; $i<=5; $i++)
                    <i data-lucide="star" class="w-4 h-4 {{ $i <= round($product->average_rating) ? 'fill-[#A8895F] text-[#A8895F]' : 'text-[#322B23]' }}"></i>
                    @endfor
                    <span class="text-xs font-bold text-[#EDE5D8] ml-1">{{ $product->average_rating }}</span>
                </div>
                <span class="text-xs text-[#EDE5D8] font-bold">({{ $product->reviews_count }} {{ __('Client Reviews') }})</span>

                <div class="flex flex-wrap gap-1.5 ml-auto">
                    @if($product->is_best_seller)
                    <span class="px-2 py-0.5 bg-[#A8895F] text-[#12100E] text-[9px] font-extrabold uppercase polygon-badge">{{ __('Best Seller') }}</span>
                    @endif
                    @if($product->is_new_arrival)
                    <span class="px-2 py-0.5 bg-emerald-900 text-emerald-200 text-[9px] font-extrabold uppercase polygon-badge">{{ __('New Arrival') }}</span>
                    @endif
                    @if($product->is_featured)
                    <span class="px-2 py-0.5 bg-purple-900 text-purple-200 text-[9px] font-extrabold uppercase polygon-badge">{{ __('Featured') }}</span>
                    @endif
                    @if($product->is_limited_edition)
                    <span class="px-2 py-0.5 bg-rose-900 text-rose-200 text-[9px] font-extrabold uppercase polygon-badge">{{ __('Limited Edition') }}</span>
                    @endif
                </div>
            </div>

            <!-- Price & Stock Display -->
            <div class="glass-panel p-4 polygon-card border border-[#A8895F]/40 flex items-center justify-between bg-[#17130F]">
                <div>
                    <span class="text-xs text-[#B5A897] block uppercase tracking-wider font-extrabold">{{ __('Price') }}</span>
                    <span class="font-serif font-bold text-3xl text-[#A8895F]" x-text="'TZS ' + Number(currentPrice).toLocaleString()"></span>
                </div>
                <span class="px-3 py-1 text-[10px] font-extrabold uppercase polygon-badge
                    {{ $product->computed_stock_status === 'In Stock' ? 'bg-emerald-800 text-white' : ($product->computed_stock_status === 'Low Stock' ? 'bg-amber-800 text-white' : 'bg-rose-900 text-white') }}">
                    {{ __($product->computed_stock_status) }}
                </span>
            </div>

            <!-- Size / Variant Selector -->
            @if($displayVariants->count() > 0)
            <div>
                <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-3">{{ __('Select Bottle Size') }}</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach($displayVariants as $variant)
                    <button @click="selectVariant('{{ $variant->size }}', {{ $variant->effective_price }})"
                            :class="selectedSize === '{{ $variant->size }}' ? 'bg-[#A8895F] text-[#12100E] border-[#A8895F] shadow-md font-extrabold' : 'bg-[#17130F] text-[#EDE5D8] border-[#322B23] hover:border-[#A8895F] font-bold'"
                            class="py-3 px-3 border text-center polygon-btn transition-all">
                        <span class="block text-xs font-bold uppercase">{{ $variant->size }}</span>
                        <span class="block text-xs sm:text-[10px] font-bold opacity-90">{{ $variant->formatted_effective_price }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Quantity & Actions -->
            <div class="space-y-3 pt-2">
                <div class="flex items-center gap-4">
                    <div class="flex items-center border border-[#322B23] bg-[#17130F] polygon-card">
                        <button @click="quantity > 1 ? quantity-- : null" class="px-3 py-3 text-[#EDE5D8] hover:text-[#A8895F] font-extrabold">-</button>
                        <span class="px-4 text-xs font-extrabold text-[#EDE5D8]" x-text="quantity"></span>
                        <button @click="quantity++" class="px-3 py-3 text-[#EDE5D8] hover:text-[#A8895F] font-extrabold">+</button>
                    </div>

                    <button @click="addToCart({{ $product->id }}, selectedSize, quantity)"
                            class="flex-grow py-4 bg-[#A8895F] text-[#12100E] font-extrabold text-xs uppercase tracking-[0.2em] polygon-btn shadow-xl shadow-[#A8895F]/30 hover:bg-[#12100E] hover:text-[#F8F5EF]">
                        {{ __('ADD TO CART') }}
                    </button>
                </div>

                <!-- WhatsApp Direct Order Button -->
                <a :href="whatsappUrl()"
                   target="_blank"
                   class="w-full py-3 bg-emerald-800 text-white font-extrabold text-xs uppercase tracking-[0.2em] polygon-btn text-center block hover:bg-emerald-900">
                    <i data-lucide="message-circle" class="w-4 h-4 inline-block mr-2 text-white"></i> {{ __('ORDER DIRECTLY VIA WHATSAPP') }}
                </a>

                <!-- Share Campaign Link Button -->
                <button onclick="copyProductLink('/product/{{ $product->slug }}')"
                        class="w-full py-2.5 bg-[#17130F] border border-[#A8895F]/40 text-[#EDE5D8] font-extrabold text-xs uppercase tracking-wider polygon-btn text-center block hover:bg-[#221D19] transition-all">
                    <i data-lucide="share-2" class="w-4 h-4 inline-block mr-2 text-[#A8895F]"></i> {{ __('COPY CAMPAIGN LINK') }}
                </button>
            </div>

            <!-- Fragrance Characteristics Badges -->
            <div class="grid grid-cols-3 gap-3 pt-4 border-t border-[#322B23] text-center">
                <div class="navy-card p-3 polygon-card bg-[#17130F] border border-[#322B23]">
                    <span class="text-[9px] text-[#B5A897] uppercase block font-extrabold">{{ __('Longevity') }}</span>
                    <span class="text-xs font-extrabold text-[#A8895F]">{{ $product->longevity ?: '8 - 12 Hours' }}</span>
                </div>
                <div class="navy-card p-3 polygon-card bg-[#17130F] border border-[#322B23]">
                    <span class="text-[9px] text-[#B5A897] uppercase block font-extrabold">{{ __('Sillage') }}</span>
                    <span class="text-xs font-extrabold text-[#A8895F]">{{ $product->sillage ?: 'Strong' }}</span>
                </div>
                <div class="navy-card p-3 polygon-card bg-[#17130F] border border-[#322B23]">
                    <span class="text-[9px] text-[#B5A897] uppercase block font-extrabold">{{ __('Intensity') }}</span>
                    <span class="text-xs font-extrabold text-[#A8895F]">{{ $product->intensity ?: 'Intense' }}</span>
                </div>
            </div>

        </div>

    </div>

    <!-- ABOUT PERFUME & STORY -->
    <div class="mt-16 space-y-8">
        @if($product->fragrance_story)
        <div class="glass-panel p-8 sm:p-12 polygon-card border border-[#A8895F]/40 bg-[#17130F] text-center relative overflow-hidden">
            <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.4em] block mb-2">{{ __('FRAGRANCE STORY') }}</span>
            <blockquote class="font-serif italic text-xl sm:text-2xl text-[#EDE5D8] max-w-2xl mx-auto leading-relaxed">
                "{{ $product->fragrance_story }}"
            </blockquote>
        </div>
        @endif

        <div class="glass-panel p-8 sm:p-12 polygon-card border border-[#322B23] bg-[#17130F]">
            <div class="max-w-3xl mx-auto space-y-6 text-[#B5A897] text-sm leading-relaxed font-semibold">
                <h3 class="font-serif font-bold text-3xl text-[#EDE5D8]">{{ __('ABOUT :name', ['name' => strtoupper($product->name)]) }}</h3>
                <p>{{ $product->description }}</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 text-center">
                    <div class="navy-card p-4 polygon-card border border-[#322B23] bg-[#17130F]">
                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block mb-1">{{ __('Top Notes') }}</span>
                        <span class="text-xs font-bold text-[#EDE5D8]">{{ $product->top_notes }}</span>
                    </div>
                    <div class="navy-card p-4 polygon-card border border-[#322B23] bg-[#17130F]">
                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block mb-1">{{ __('Heart Notes') }}</span>
                        <span class="text-xs font-bold text-[#EDE5D8]">{{ $product->heart_notes }}</span>
                    </div>
                    <div class="navy-card p-4 polygon-card border border-[#322B23] bg-[#17130F]">
                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block mb-1">{{ __('Base Notes') }}</span>
                        <span class="text-xs font-bold text-[#EDE5D8]">{{ $product->base_notes }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- REVIEWS & SUBMIT REVIEW -->
    <div class="mt-16 grid grid-cols-1 lg:grid-cols-12 gap-8">

        <div class="lg:col-span-7 space-y-6">
            <h3 class="font-serif font-bold text-2xl text-[#EDE5D8]">{{ __('CLIENT REVIEWS') }}</h3>

            @php
                $approvedReviews = $product->approvedReviews;
            @endphp

            @if($approvedReviews->isEmpty())
            <p class="text-xs text-[#B5A897] font-semibold">{{ __('No reviews yet for this fragrance. Be the first to share your impression!') }}</p>
            @else
            <div class="space-y-4">
                @foreach($approvedReviews as $rev)
                <div class="navy-card p-4 polygon-card border border-[#322B23] bg-[#17130F]">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center gap-2">
                            <span class="font-serif font-bold text-sm text-[#EDE5D8]">{{ $rev->customer_name }}</span>
                            @if($rev->is_verified)
                            <span class="text-[9px] text-emerald-400 font-extrabold uppercase border border-emerald-800 px-1.5 py-0.5 rounded">{{ __('Verified Purchase') }}</span>
                            @endif
                        </div>
                        <div class="flex text-[#A8895F] text-xs">
                            @for($i=0; $i<$rev->rating; $i++)★@endfor
                        </div>
                    </div>
                    <p class="text-xs text-[#B5A897] font-medium">{{ $rev->comment }}</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="lg:col-span-5">
            <form action="{{ route('product.review', $product->id) }}" method="POST" class="glass-panel p-6 polygon-card border border-[#322B23] space-y-4 bg-[#17130F]">
                @csrf
                <h4 class="font-serif font-bold text-lg text-[#EDE5D8]">{{ __('WRITE A REVIEW') }}</h4>

                <div>
                    <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">{{ __('Your Name') }}</label>
                    <input type="text" name="customer_name" required class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-bold">
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">{{ __('Rating') }}</label>
                    <select name="rating" class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-bold">
                        <option value="5">{{ __('★★★★★ (5/5) Exceptional') }}</option>
                        <option value="4">{{ __('★★★★☆ (4/5) Very Good') }}</option>
                        <option value="3">{{ __('★★★☆☆ (3/5) Average') }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">{{ __('Your Experience') }}</label>
                    <textarea name="comment" rows="3" required class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-medium"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-[#A8895F] text-[#12100E] font-extrabold text-xs uppercase tracking-widest polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF]">
                    {{ __('SUBMIT REVIEW') }}
                </button>
            </form>
        </div>

    </div>

    <!-- RELATED & RECOMMENDED PERFUMES (YOU MAY ALSO LIKE): Spacious & Tall Vertical Cards -->
    @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
    <div class="mt-20 pt-12 border-t border-[#322B23]">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 gap-4">
            <div>
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">{{ __('CURATED RECOMMENDATIONS') }}</span>
                <h3 class="font-serif font-bold text-2xl sm:text-3xl text-[#EDE5D8]">{{ __('YOU MAY ALSO LIKE') }}</h3>
            </div>
            <a href="{{ route('shop.index') }}" class="text-xs font-extrabold text-[#A8895F] uppercase tracking-widest hover:text-[#F8F5EF]">
                {{ __('EXPLORE FULL CATALOG') }} &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $rel)
            <div class="navy-card p-3 sm:p-4 polygon-card border border-[#322B23] group hover:border-[#A8895F] transition-all duration-300 flex flex-col h-full relative shadow-md bg-[#17130F]">

                @if($rel->discount_percentage)
                <span class="absolute top-5 left-5 z-20 bg-[#221D19] text-white text-[9px] font-extrabold uppercase px-2 py-0.5 polygon-badge shadow-md border border-[#322B23]">
                    -{{ $rel->discount_percentage }}%
                </span>
                @endif

                <!-- Wishlist Heart Button: the reactive :class binding must stay on this button. -->
                <button @click="toggleWishlist({ id: {{ $rel->id }}, name: '{{ addslashes($rel->name) }}', price: '{{ $rel->formatted_price }}', image: '{{ $rel->primary_image }}' })"
                        :class="isInWishlist({{ $rel->id }}) ? '[&_svg]:fill-rose-400 [&_svg]:text-rose-400' : ''"
                        aria-label="{{ __('Save :name to wishlist', ['name' => $rel->name]) }}"
                        class="absolute top-4 right-4 z-20 w-9 h-9 flex items-center justify-center bg-[#17130F]/90 rounded-full text-[#EDE5D8] hover:text-rose-400 transition-colors shadow-sm border border-[#322B23]">
                    <i data-lucide="heart" class="w-4 h-4"></i>
                </button>

                <!-- 1. Image frame -->
                <div class="relative w-full aspect-square bg-[#100E0C] polygon-card overflow-hidden border border-[#322B23] group/img flex items-center justify-center p-2 sm:p-3">
                    <a href="{{ route('shop.show', $rel->slug) }}" class="absolute inset-0 z-10" aria-label="{{ $rel->name }}"></a>

                    <img src="{{ $rel->primary_image }}" data-sozie-fallback loading="lazy" decoding="async"
                         alt="{{ $rel->name }}"
                         class="w-full h-full object-cover object-center filter drop-shadow-xl group-hover/img:scale-105 transition-transform duration-500">

                    <div class="absolute inset-0 z-20 bg-[#12100E]/70 opacity-0 group-hover/img:opacity-100 group-focus-within/img:opacity-100 transition-opacity flex items-center justify-center p-3 backdrop-blur-xs">
                        <button @click="$dispatch('open-quickview', { id: {{ $rel->id }} })"
                                class="px-4 py-2 bg-[#A8895F] border border-[#A8895F] text-[#12100E] font-extrabold text-xs uppercase tracking-wider polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF] transition-colors">
                            {{ __('QUICK VIEW') }}
                        </button>
                    </div>
                </div>

                <!-- 2. Category tag, 3. name, 4. notes -->
                <div class="mt-3 space-y-1">
                    <span class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-widest truncate">
                        {{ $rel->gender }} • {{ $rel->category ? $rel->category->name : __('Signature') }}
                    </span>

                    <a href="{{ route('shop.show', $rel->slug) }}" class="block">
                        <h4 class="font-serif font-bold text-base sm:text-lg leading-snug text-[#F8F5EF] group-hover:text-[#A8895F] transition-colors line-clamp-2">
                            {{ $rel->name }}
                        </h4>
                    </a>

                    <p class="text-[11px] sm:text-xs leading-snug text-[#B5A897] font-medium line-clamp-1">{{ $rel->top_notes }}</p>
                </div>

                <!-- 5. Divider, 6. price row (price on the right), 7. full-width add to cart -->
                <div class="mt-auto pt-3 border-t border-[#322B23]">
                    <div class="flex flex-wrap items-baseline justify-end gap-x-1.5 gap-y-0.5" data-card-price-row>
                        @if($rel->discount_price)
                        <span class="text-[11px] sm:text-xs text-[#A89C8C] line-through font-semibold">{{ $rel->formatted_original_price }}</span>
                        @endif
                        <span class="text-base sm:text-xl font-black text-[#A8895F] tracking-tight whitespace-nowrap" data-card-price>{{ $rel->formatted_price }}</span>
                    </div>

                    <button @click="addToCart({{ $rel->id }}, '{{ $rel->default_size }}')"
                            aria-label="{{ __('Add :name to cart', ['name' => $rel->name]) }}"
                            class="mt-2.5 w-full min-h-11 py-2.5 bg-[#A8895F] border border-[#A8895F] text-[#12100E] font-extrabold text-[11px] sm:text-xs uppercase tracking-wider polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF] transition-colors shadow-md flex items-center justify-center gap-1.5 sm:gap-2">
                        <i data-lucide="shopping-bag" class="w-4 h-4 shrink-0"></i>
                        <span>{{ __('ADD TO CART') }}</span>
                    </button>
                </div>

            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@endsection

@push('scripts')
<script>
    function productDetail(productId, variants, basePrice) {
        const firstVariant = variants && variants.length > 0 ? variants[0] : null;
        return {
            activeImage: {{ \Illuminate\Support\Js::from($product->primary_image) }},
            productName: {{ \Illuminate\Support\Js::from($product->name) }},
            productImage: {{ \Illuminate\Support\Js::from($product->primary_image) }},
            productUrl: {{ \Illuminate\Support\Js::from(route('shop.show', $product->slug)) }},
            whatsappPhone: {{ \Illuminate\Support\Js::from(config('payment.whatsapp.phone_number', '255691980178')) }},
            selectedSize: firstVariant ? firstVariant.size : {{ \Illuminate\Support\Js::from($product->default_size) }},
            currentPrice: firstVariant ? (firstVariant.discount_price || firstVariant.price) : basePrice,
            quantity: 1,

            selectVariant(size, price) {
                this.selectedSize = size;
                this.currentPrice = price;
            },

            whatsappUrl() {
                const message = [
                    '{{ __('Jambo Sozie Collection! Naomba kujionyesha bidhaa hii:') }}',
                    '',
                    '{{ __('*Bidhaa:*') }} ' + this.productName,
                    '{{ __('Size:') }} ' + this.selectedSize,
                    '{{ __('Quantity:') }} ' + this.quantity,
                    '{{ __('Bei: TZS') }} ' + Number(this.currentPrice).toLocaleString(),
                    this.productImage ? '{{ __('📸 Picha:') }} ' + this.productImage : '',
                    '{{ __('🔗 Bidhaa:') }} ' + this.productUrl,
                ].filter(Boolean).join('\n');

                return 'https://wa.me/' + this.whatsappPhone + '?text=' + encodeURIComponent(message);
            }
        }
    }

    function copyProductLink(path) {
        const fullUrl = path.startsWith('http') ? path : (window.location.origin + (path.startsWith('/') ? path : '/' + path));
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(fullUrl).then(() => {
                alert('Copied product campaign link:\n' + fullUrl);
            }).catch(() => {
                fallbackCopyTextToClipboard(fullUrl);
            });
        } else {
            fallbackCopyTextToClipboard(fullUrl);
        }
    }

    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            alert('Copied product campaign link:\n' + text);
        } catch (err) {
            alert('Link: ' + text);
        }
        document.body.removeChild(textArea);
    }
</script>
@endpush
