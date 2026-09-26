@extends('layouts.app')

@section('title', $product->name . ' | Sozie Collection')

@section('content')

<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="productDetail({{ $product->id }}, {{ json_encode($product->variants) }}, {{ $product->effective_price }})">

    <!-- Breadcrumb -->
    <nav class="flex text-xs text-gray-600 mb-8 uppercase tracking-widest gap-2 font-bold">
        <a href="{{ route('home') }}" class="hover:text-[#A8895F]">Home</a>
        <span>/</span>
        <a href="{{ route('shop.index') }}" class="hover:text-[#A8895F]">Shop</a>
        <span>/</span>
        <span class="text-[#A8895F] font-extrabold">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

        <!-- LEFT: GALLERY & VIDEO -->
        <div class="lg:col-span-6 space-y-6">

            <!-- Main Featured Image in Polygonal Frame -->
            <div class="relative w-full h-[480px] glass-panel p-2 polygon-card border border-[#A8895F]/40 gold-glow bg-[#F8F5EF]">
                <img :src="activeImage" alt="{{ $product->name }}" class="w-full h-full object-cover polygon-card border border-[#D8C9B8]">
            </div>

            <!-- Thumbnails -->
            <div class="flex gap-3 overflow-x-auto pb-2">
                @if(is_array($product->images))
                    @foreach($product->images as $img)
                    <button @click="activeImage = '{{ $img }}'"
                            :class="activeImage === '{{ $img }}' ? 'border-[#A8895F]' : 'border-[#D8C9B8]'"
                            class="w-20 h-20 polygon-card border-2 flex-shrink-0 overflow-hidden bg-white">
                        <img src="{{ $img }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                @endif
            </div>

            <!-- EMBEDDED CAMPAIGN VIDEO -->
            @if($product->embed_video_url)
            <div class="glass-panel p-4 polygon-card border border-[#A8895F]/40 space-y-3 bg-[#F8F5EF]">
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.25em] flex items-center gap-2">
                    <i data-lucide="play-circle" class="w-4 h-4 text-[#A8895F]"></i>
                    PERFUME CAMPAIGN VIDEO
                </span>
                <div class="aspect-video w-full polygon-card overflow-hidden border border-[#D8C9B8] shadow-lg bg-black">
                    <iframe src="{{ $product->embed_video_url }}"
                            title="{{ $product->name }} Campaign Video"
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
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">SOZIE COLLECTION</span>
                <h1 class="font-serif font-bold text-4xl sm:text-5xl text-[#29241F]">{{ $product->name }}</h1>
                <p class="text-xs font-extrabold text-gray-600 uppercase tracking-widest mt-2">
                    {{ $product->concentration }} • {{ $product->scent_type }} • {{ $product->gender }}
                </p>
            </div>

            <!-- Rating Summary -->
            <div class="flex items-center gap-3">
                <div class="flex text-[#A8895F] gap-1">
                    @for($i=0; $i<5; $i++)
                    <i data-lucide="star" class="w-4 h-4 fill-[#A8895F]"></i>
                    @endfor
                </div>
                <span class="text-xs text-[#29241F] font-bold">({{ $product->reviews->count() }} Client Reviews)</span>
            </div>

            <!-- Price Display (Dynamic based on selected size) -->
            <div class="glass-panel p-4 polygon-card border border-[#A8895F]/40 flex items-center justify-between bg-[#F8F5EF]">
                <div>
                    <span class="text-xs text-gray-600 block uppercase tracking-wider font-extrabold">Price</span>
                    <span class="font-serif font-bold text-3xl text-[#A8895F]" x-text="'TZS ' + Number(currentPrice).toLocaleString()"></span>
                </div>
                <span class="bg-emerald-800 text-white text-[10px] font-extrabold uppercase px-3 py-1 polygon-badge">
                    In Stock
                </span>
            </div>

            <!-- Size / Variant Selector -->
            @if($product->variants->count() > 0)
            <div>
                <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-3">Select Bottle Size</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach($product->variants as $variant)
                    <button @click="selectVariant('{{ $variant->size }}', {{ $variant->price }})"
                            :class="selectedSize === '{{ $variant->size }}' ? 'bg-[#A8895F] text-white border-[#A8895F] shadow-md font-extrabold' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F] font-bold'"
                            class="py-3 px-3 border text-center polygon-btn transition-all">
                        <span class="block text-xs font-bold uppercase">{{ $variant->size }}</span>
                        <span class="block text-xs sm:text-[10px] font-bold opacity-90">TZS {{ number_format($variant->price, 0) }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Quantity & Actions -->
            <div class="space-y-3 pt-2">
                <div class="flex items-center gap-4">
                    <div class="flex items-center border border-[#D8C9B8] bg-white polygon-card">
                        <button @click="quantity > 1 ? quantity-- : null" class="px-3 py-3 text-[#29241F] hover:text-[#A8895F] font-extrabold">-</button>
                        <span class="px-4 text-xs font-extrabold text-[#29241F]" x-text="quantity"></span>
                        <button @click="quantity++" class="px-3 py-3 text-[#29241F] hover:text-[#A8895F] font-extrabold">+</button>
                    </div>

                    <button @click="addToCart({{ $product->id }}, selectedSize, quantity)"
                            class="flex-grow py-4 bg-[#A8895F] text-white font-extrabold text-xs uppercase tracking-[0.2em] polygon-btn shadow-xl shadow-[#A8895F]/30 hover:bg-[#29241F]">
                        ADD TO CART
                    </button>
                </div>

                <!-- WhatsApp Direct Order Button -->
                <a :href="whatsappUrl()"
                   target="_blank"
                   class="w-full py-3 bg-emerald-800 text-white font-extrabold text-xs uppercase tracking-[0.2em] polygon-btn text-center block hover:bg-emerald-900">
                    <i data-lucide="message-circle" class="w-4 h-4 inline-block mr-2 text-white"></i> ORDER DIRECTLY VIA WHATSAPP
                </a>
            </div>

            <!-- Fragrance Characteristics Badges -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-[#D8C9B8] text-center">
                <div class="navy-card p-3 polygon-card bg-[#F8F5EF] border border-[#D8C9B8]">
                    <span class="text-[9px] text-gray-600 uppercase block font-extrabold">Longevity</span>
                    <span class="text-xs font-extrabold text-[#A8895F]">{{ $product->longevity }}</span>
                </div>
                <div class="navy-card p-3 polygon-card bg-[#F8F5EF] border border-[#D8C9B8]">
                    <span class="text-[9px] text-gray-600 uppercase block font-extrabold">Sillage</span>
                    <span class="text-xs font-extrabold text-[#A8895F]">{{ $product->sillage }}</span>
                </div>
                <div class="navy-card p-3 polygon-card bg-[#F8F5EF] border border-[#D8C9B8]">
                    <span class="text-[9px] text-gray-600 uppercase block font-extrabold">Intensity</span>
                    <span class="text-xs font-extrabold text-[#A8895F]">{{ $product->intensity }}</span>
                </div>
                <div class="navy-card p-3 polygon-card bg-[#F8F5EF] border border-[#D8C9B8]">
                    <span class="text-[9px] text-gray-600 uppercase block font-extrabold">Occasion</span>
                    <span class="text-xs font-extrabold text-[#A8895F]">{{ $product->occasion }}</span>
                </div>
            </div>

        </div>

    </div>

    <!-- ABOUT PERFUME & STORY -->
    <div class="mt-20 glass-panel p-8 sm:p-12 polygon-card border border-[#A8895F]/40 bg-[#F8F5EF]">
        <div class="max-w-3xl mx-auto space-y-6 text-gray-700 text-sm leading-relaxed font-semibold">
            <h3 class="font-serif font-bold text-3xl text-[#29241F]">ABOUT {{ strtoupper($product->name) }}</h3>
            <p>{{ $product->description }}</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 text-center">
                <div class="navy-card p-4 polygon-card border border-[#D8C9B8] bg-white">
                    <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block mb-1">Top Notes</span>
                    <span class="text-xs font-bold text-[#29241F]">{{ $product->top_notes }}</span>
                </div>
                <div class="navy-card p-4 polygon-card border border-[#D8C9B8] bg-white">
                    <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block mb-1">Heart Notes</span>
                    <span class="text-xs font-bold text-[#29241F]">{{ $product->heart_notes }}</span>
                </div>
                <div class="navy-card p-4 polygon-card border border-[#D8C9B8] bg-white">
                    <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block mb-1">Base Notes</span>
                    <span class="text-xs font-bold text-[#29241F]">{{ $product->base_notes }}</span>
                </div>
            </div>

            @if(is_array($product->why_you_will_love_it))
            <div class="pt-4">
                <h4 class="font-serif font-bold text-lg text-[#A8895F] mb-3">WHY YOU'LL LOVE IT</h4>
                <ul class="space-y-2 text-xs text-[#29241F] font-bold">
                    @foreach($product->why_you_will_love_it as $point)
                    <li class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-[#A8895F]"></i>
                        <span>{{ $point }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>

    <!-- REVIEWS & SUBMIT REVIEW -->
    <div class="mt-16 grid grid-cols-1 lg:grid-cols-12 gap-8">

        <div class="lg:col-span-7 space-y-6">
            <h3 class="font-serif font-bold text-2xl text-[#29241F]">CLIENT REVIEWS</h3>

            @if($product->reviews->isEmpty())
            <p class="text-xs text-gray-600 font-semibold">No reviews yet for this fragrance. Be the first to share your impression!</p>
            @else
            <div class="space-y-4">
                @foreach($product->reviews as $rev)
                <div class="navy-card p-4 polygon-card border border-[#D8C9B8] bg-[#F8F5EF]">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-serif font-bold text-sm text-[#29241F]">{{ $rev->customer_name }}</span>
                        <div class="flex text-[#A8895F] text-xs">
                            @for($i=0; $i<$rev->rating; $i++)★@endfor
                        </div>
                    </div>
                    <p class="text-xs text-gray-700 font-medium">{{ $rev->comment }}</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="lg:col-span-5">
            <form action="{{ route('product.review', $product->id) }}" method="POST" class="glass-panel p-6 polygon-card border border-[#D8C9B8] space-y-4 bg-[#F8F5EF]">
                @csrf
                <h4 class="font-serif font-bold text-lg text-[#29241F]">WRITE A REVIEW</h4>

                <div>
                    <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">Your Name</label>
                    <input type="text" name="customer_name" required class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-bold">
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">Rating</label>
                    <select name="rating" class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-bold">
                        <option value="5">★★★★★ (5/5) Exceptional</option>
                        <option value="4">★★★★☆ (4/5) Very Good</option>
                        <option value="3">★★★☆☆ (3/3) Average</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">Your Experience</label>
                    <textarea name="comment" rows="3" required class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-medium"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-[#A8895F] text-white font-extrabold text-xs uppercase tracking-widest polygon-btn hover:bg-[#29241F]">
                    SUBMIT REVIEW
                </button>
            </form>
        </div>

    </div>

    <!-- RELATED & RECOMMENDED PERFUMES -->
    @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
    <div class="mt-20 pt-12 border-t border-[#D8C9B8]">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 gap-4">
            <div>
                <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">CURATED RECOMMENDATIONS</span>
                <h3 class="font-serif font-bold text-2xl sm:text-3xl text-[#29241F]">YOU MAY ALSO LIKE</h3>
            </div>
            <a href="{{ route('shop.index') }}" class="text-xs font-extrabold text-[#A8895F] uppercase tracking-widest hover:text-[#29241F]">
                EXPLORE FULL CATALOG &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $rel)
            <div class="navy-card p-3.5 polygon-card border border-[#D8C9B8] group hover:border-[#A8895F] transition-all duration-300 flex flex-col justify-between relative shadow-xs bg-[#F8F5EF]">

                @if($rel->discount_percentage)
                <span class="absolute top-5 left-5 z-20 bg-[#29241F] text-white text-[9px] font-extrabold uppercase px-2 py-0.5 polygon-badge shadow-md">
                    -{{ $rel->discount_percentage }}%
                </span>
                @endif

                <!-- Wishlist Heart Button -->
                <button @click="toggleWishlist({ id: {{ $rel->id }}, name: '{{ addslashes($rel->name) }}', price: '{{ $rel->formatted_price }}', image: '{{ $rel->primary_image }}' })"
                        class="absolute top-5 right-5 z-20 p-1.5 bg-white/80 rounded-full text-[#29241F] hover:text-[#A8895F] transition-colors shadow-xs">
                    <i data-lucide="heart" class="w-3.5 h-3.5" :class="isInWishlist({{ $rel->id }}) ? 'fill-[#A8895F] text-[#A8895F]' : ''"></i>
                </button>

                <div>
                    <!-- Image Frame -->
                    <div class="w-full h-48 bg-white polygon-card overflow-hidden mb-3 relative border border-[#D8C9B8]">
                        <img src="{{ $rel->primary_image }}" loading="lazy" decoding="async"
                             alt="{{ $rel->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                        <div class="absolute inset-0 bg-[#29241F]/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 p-2 backdrop-blur-xs">
                            <button @click="$dispatch('open-quickview', { id: {{ $rel->id }} })"
                                    class="px-3 py-1.5 bg-[#A8895F] border border-[#A8895F] text-white font-extrabold text-[10px] uppercase tracking-wider polygon-btn hover:bg-[#29241F]">
                                QUICK VIEW
                            </button>
                        </div>
                    </div>

                    <span class="text-[9px] font-extrabold text-[#A8895F] uppercase tracking-widest block mb-0.5">
                        {{ $rel->gender }} • {{ $rel->category ? $rel->category->name : 'Signature' }}
                    </span>

                    <a href="{{ route('shop.show', $rel->slug) }}">
                        <h4 class="font-serif font-bold text-base text-[#29241F] group-hover:text-[#A8895F] transition-colors leading-tight">
                            {{ $rel->name }}
                        </h4>
                    </a>

                    <p class="text-[11px] text-gray-600 font-medium mt-1 line-clamp-1">Notes: {{ $rel->top_notes }}</p>
                </div>

                <div class="pt-3 mt-3 border-t border-[#D8C9B8] flex items-center justify-between">
                    <div>
                        <span class="text-sm sm:text-xs font-extrabold text-[#A8895F] block">{{ $rel->formatted_price }}</span>
                        @if($rel->discount_price)
                        <span class="text-[9px] text-gray-500 line-through font-semibold">{{ $rel->formatted_original_price }}</span>
                        @endif
                    </div>

                    <button @click="addToCart({{ $rel->id }}, '{{ $rel->default_size }}')"
                            class="p-2 bg-[#A8895F] border border-[#A8895F] text-white polygon-btn hover:bg-[#29241F] transition-colors shadow-xs">
                        <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i>
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
            currentPrice: firstVariant ? firstVariant.price : basePrice,
            quantity: 1,

            selectVariant(size, price) {
                this.selectedSize = size;
                this.currentPrice = price;
            },

            whatsappUrl() {
                const message = [
                    'Jambo Sozie Collection! Naomba kujionyesha bidhaa hii:',
                    '',
                    '*Bidhaa:* ' + this.productName,
                    'Size: ' + this.selectedSize,
                    'Quantity: ' + this.quantity,
                    'Bei: TZS ' + Number(this.currentPrice).toLocaleString(),
                    this.productImage ? '📸 Picha: ' + this.productImage : '',
                    '🔗 Bidhaa: ' + this.productUrl,
                ].filter(Boolean).join('\n');

                return 'https://wa.me/' + this.whatsappPhone + '?text=' + encodeURIComponent(message);
            }
        }
    }
</script>
@endpush
