@extends('admin.layout')

@section('page_title', 'Edit Perfume #' . $product->id)

@section('content')

<div class="max-w-4xl mx-auto glass-panel p-8 polygon-card border border-[#8B0D1A]/40 space-y-8 bg-white shadow-sm">
    <div class="border-b border-[#0B0B0B]/20 pb-4 flex justify-between items-center">
        <div>
            <span class="text-xs font-extrabold text-[#8B0D1A] uppercase tracking-[0.25em] block">ADMIN PRODUCT MANAGEMENT</span>
            <h3 class="font-serif font-bold text-3xl text-[#0B0B0B]">EDIT PRODUCT: {{ $product->name }}</h3>
        </div>
        <a href="{{ route('shop.show', $product->slug) }}" target="_blank" class="px-4 py-2 bg-[#F5F2ED] text-[#0B0B0B] font-extrabold text-xs uppercase polygon-btn hover:bg-[#8B0D1A] hover:text-white border border-[#8B0D1A]/30">
            View Live Product &rarr;
        </a>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 text-xs font-bold">
        @csrf
        @method('PUT')

        <!-- SECTION 1: BASIC INFORMATION -->
        <div class="p-5 bg-[#F5F2ED] border border-[#8B0D1A]/30 polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#0B0B0B] flex items-center gap-2 border-b border-[#8B0D1A]/20 pb-2">
                <i data-lucide="info" class="w-4 h-4 text-[#8B0D1A]"></i> 1. Basic Information
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Product Name *</label>
                    <input type="text" name="name" value="{{ $product->name }}" required
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ $product->sku }}"
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A] font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Brand</label>
                    <input type="text" name="brand" value="{{ $product->brand ?: 'Sozie Collection' }}"
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Product Type</label>
                    <select name="product_type" class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                        <option value="Eau de Parfum" {{ $product->product_type === 'Eau de Parfum' ? 'selected' : '' }}>Eau de Parfum (Perfume Spray)</option>
                        <option value="Extrait de Parfum" {{ $product->product_type === 'Extrait de Parfum' ? 'selected' : '' }}>Extrait de Parfum (Intense)</option>
                        <option value="Perfume Oil" {{ $product->product_type === 'Perfume Oil' ? 'selected' : '' }}>Concentrated Perfume Oil</option>
                        <option value="Body Mist" {{ $product->product_type === 'Body Mist' ? 'selected' : '' }}>Body Mist</option>
                        <option value="Gift Set" {{ $product->product_type === 'Gift Set' ? 'selected' : '' }}>Luxury Gift Box / Discovery Set</option>
                    </select>
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Category *</label>
                    <select name="category_id" required class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                        @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ $product->category_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Gender *</label>
                    <select name="gender" class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                        <option value="unisex" {{ $product->gender === 'unisex' ? 'selected' : '' }}>Unisex</option>
                        <option value="women" {{ $product->gender === 'women' ? 'selected' : '' }}>Women</option>
                        <option value="men" {{ $product->gender === 'men' ? 'selected' : '' }}>Men</option>
                    </select>
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Concentration</label>
                    <input type="text" name="concentration" value="{{ $product->concentration }}"
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>
            </div>

            <div>
                <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Description *</label>
                <textarea name="description" rows="3" required class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2 focus:outline-none focus:border-[#8B0D1A] font-medium">{{ $product->description }}</textarea>
            </div>
        </div>

        <!-- SECTION 2: PRICING -->
        <div class="p-5 bg-[#F5F2ED] border border-[#8B0D1A]/30 polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#0B0B0B] flex items-center gap-2 border-b border-[#8B0D1A]/20 pb-2">
                <i data-lucide="tag" class="w-4 h-4 text-[#8B0D1A]"></i> 2. Pricing
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Regular Price (TZS) *</label>
                    <input type="number" name="price" value="{{ $product->price }}" required
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Sale / Discount Price (TZS)</label>
                    <input type="number" name="discount_price" value="{{ $product->discount_price }}"
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Currency</label>
                    <input type="text" value="TZS (Tanzanian Shilling)" readonly
                           class="w-full bg-[#0B0B0B]/10 border border-[#8B0D1A]/30 text-[#0B0B0B] px-3 py-2.5 font-bold cursor-not-allowed">
                </div>
            </div>
        </div>

        @php
            $existingVariants = $product->variants->keyBy('size');
            $v10 = $existingVariants->get('10ml');
            $v30 = $existingVariants->get('30ml');
            $v50 = $existingVariants->get('50ml');
            $v100 = $existingVariants->get('100ml');

            $standardNames = ['10ml', '30ml', '50ml', '100ml'];
            $customVariant = $product->variants->first(fn($var) => !in_array($var->size, $standardNames));
        @endphp

        <!-- SECTION 3: SIZES & VARIANTS (CLEAN ML VARIANTS) -->
        <div class="p-5 bg-[#F5F2ED] border border-[#8B0D1A]/40 polygon-card space-y-3">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-1 border-b border-[#8B0D1A]/20 pb-2">
                <h4 class="font-serif font-bold text-lg text-[#0B0B0B] flex items-center gap-2">
                    <i data-lucide="layers" class="w-4 h-4 text-[#8B0D1A]"></i> 3. Sizes & Variants
                </h4>
                <span class="text-[10px] text-[#0B0B0B]/80 font-semibold">* Weka bei kwa saizi ZINAZOPATIKANA TU. Saizi yenye bei ndiyo pekee itakayoonekana dukani.</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <!-- 10ml -->
                <div class="p-3 border border-[#8B0D1A]/30 bg-white polygon-card space-y-1">
                    <label class="flex items-center gap-2 font-extrabold text-[#0B0B0B] cursor-pointer">
                        <input type="checkbox" name="variants[0][enabled]" value="1" {{ $v10 ? 'checked' : '' }} class="accent-[#8B0D1A]"
                               onchange="if(this.checked){ document.getElementById('price_10ml').focus(); } else { document.getElementById('price_10ml').value = ''; }">
                        <input type="text" name="variants[0][size]" value="10ml" class="bg-transparent font-extrabold text-[#0B0B0B] text-xs border-b border-[#8B0D1A]/30 focus:outline-none w-20">
                    </label>
                    <input type="number" id="price_10ml" name="variants[0][price]" value="{{ $v10 ? $v10->price : '' }}" placeholder="Bei ya 10ml (TZS)"
                           class="w-full bg-[#F5F2ED] border border-[#8B0D1A]/30 text-[#0B0B0B] px-2 py-1.5 focus:outline-none text-xs font-bold"
                           oninput="document.querySelector('input[name=\'variants[0][enabled]\']').checked = this.value > 0;">
                </div>

                <!-- 30ml -->
                <div class="p-3 border border-[#8B0D1A]/30 bg-white polygon-card space-y-1">
                    <label class="flex items-center gap-2 font-extrabold text-[#0B0B0B] cursor-pointer">
                        <input type="checkbox" name="variants[1][enabled]" value="1" {{ $v30 ? 'checked' : '' }} class="accent-[#8B0D1A]"
                               onchange="if(this.checked){ document.getElementById('price_30ml').focus(); } else { document.getElementById('price_30ml').value = ''; }">
                        <input type="text" name="variants[1][size]" value="30ml" class="bg-transparent font-extrabold text-[#0B0B0B] text-xs border-b border-[#8B0D1A]/30 focus:outline-none w-20">
                    </label>
                    <input type="number" id="price_30ml" name="variants[1][price]" value="{{ $v30 ? $v30->price : '' }}" placeholder="Bei ya 30ml (TZS)"
                           class="w-full bg-[#F5F2ED] border border-[#8B0D1A]/30 text-[#0B0B0B] px-2 py-1.5 focus:outline-none text-xs font-bold"
                           oninput="document.querySelector('input[name=\'variants[1][enabled]\']').checked = this.value > 0;">
                </div>

                <!-- 50ml -->
                <div class="p-3 border border-[#8B0D1A]/30 bg-white polygon-card space-y-1">
                    <label class="flex items-center gap-2 font-extrabold text-[#0B0B0B] cursor-pointer">
                        <input type="checkbox" name="variants[2][enabled]" value="1" {{ $v50 ? 'checked' : '' }} class="accent-[#8B0D1A]"
                               onchange="if(this.checked){ document.getElementById('price_50ml').focus(); } else { document.getElementById('price_50ml').value = ''; }">
                        <input type="text" name="variants[2][size]" value="50ml" class="bg-transparent font-extrabold text-[#0B0B0B] text-xs border-b border-[#8B0D1A]/30 focus:outline-none w-20">
                    </label>
                    <input type="number" id="price_50ml" name="variants[2][price]" value="{{ $v50 ? $v50->price : '' }}" placeholder="Bei ya 50ml (TZS)"
                           class="w-full bg-[#F5F2ED] border border-[#8B0D1A]/30 text-[#0B0B0B] px-2 py-1.5 focus:outline-none text-xs font-bold"
                           oninput="document.querySelector('input[name=\'variants[2][enabled]\']').checked = this.value > 0;">
                </div>

                <!-- 100ml -->
                <div class="p-3 border border-[#8B0D1A]/30 bg-white polygon-card space-y-1">
                    <label class="flex items-center gap-2 font-extrabold text-[#0B0B0B] cursor-pointer">
                        <input type="checkbox" name="variants[3][enabled]" value="1" {{ $v100 ? 'checked' : '' }} class="accent-[#8B0D1A]"
                               onchange="if(this.checked){ document.getElementById('price_100ml').focus(); } else { document.getElementById('price_100ml').value = ''; }">
                        <input type="text" name="variants[3][size]" value="100ml" class="bg-transparent font-extrabold text-[#0B0B0B] text-xs border-b border-[#8B0D1A]/30 focus:outline-none w-20">
                    </label>
                    <input type="number" id="price_100ml" name="variants[3][price]" value="{{ $v100 ? $v100->price : '' }}" placeholder="Bei ya 100ml (TZS)"
                           class="w-full bg-[#F5F2ED] border border-[#8B0D1A]/30 text-[#0B0B0B] px-2 py-1.5 focus:outline-none text-xs font-bold"
                           oninput="document.querySelector('input[name=\'variants[3][enabled]\']').checked = this.value > 0;">
                </div>
            </div>

            <!-- Custom Size Input -->
            <div class="pt-3 border-t border-[#8B0D1A]/30 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-extrabold text-[#8B0D1A] uppercase">Custom Size Label (e.g. 6ml, 250ml)</label>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="variants[4][enabled]" value="1" {{ $customVariant ? 'checked' : '' }} class="accent-[#8B0D1A]">
                        <input type="text" name="variants[4][size]" value="{{ $customVariant ? $customVariant->size : '' }}" placeholder="e.g. 250ml or 6ml Roll-on"
                               class="w-full bg-white border border-[#8B0D1A]/30 text-[#0B0B0B] px-2 py-1.5 focus:outline-none text-xs font-bold">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-[#8B0D1A] uppercase">Custom Size Price (TZS)</label>
                    <input type="number" name="variants[4][price]" value="{{ $customVariant ? $customVariant->price : '' }}" placeholder="Bei ya Custom Size (TZS)"
                           class="w-full bg-white border border-[#8B0D1A]/30 text-[#0B0B0B] px-2 py-1.5 focus:outline-none text-xs font-bold"
                           oninput="document.querySelector('input[name=\'variants[4][enabled]\']').checked = this.value > 0;">
                </div>
            </div>
        </div>

        <!-- SECTION 4: FRAGRANCE PROFILE -->
        <div class="p-5 bg-[#F5F2ED] border border-[#8B0D1A]/30 polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#0B0B0B] flex items-center gap-2 border-b border-[#8B0D1A]/20 pb-2">
                <i data-lucide="flower" class="w-4 h-4 text-[#8B0D1A]"></i> 4. Fragrance Profile
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Fragrance Family *</label>
                    <input type="text" name="fragrance_family" value="{{ $product->fragrance_family }}" required
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Scent Note Character / Type *</label>
                    <input type="text" name="scent_type" value="{{ $product->scent_type }}" required
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Top Notes *</label>
                    <input type="text" name="top_notes" value="{{ $product->top_notes }}" required
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Heart Notes *</label>
                    <input type="text" name="heart_notes" value="{{ $product->heart_notes }}" required
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Base Notes *</label>
                    <input type="text" name="base_notes" value="{{ $product->base_notes }}" required
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>
            </div>
        </div>

        <!-- SECTION 5: PERFORMANCE -->
        <div class="p-5 bg-[#F5F2ED] border border-[#8B0D1A]/30 polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#0B0B0B] flex items-center gap-2 border-b border-[#8B0D1A]/20 pb-2">
                <i data-lucide="zap" class="w-4 h-4 text-[#8B0D1A]"></i> 5. Performance Metrics
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Longevity</label>
                    <input type="text" name="longevity" value="{{ $product->longevity }}"
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Sillage</label>
                    <input type="text" name="sillage" value="{{ $product->sillage }}"
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Intensity</label>
                    <input type="text" name="intensity" value="{{ $product->intensity }}"
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>
            </div>
        </div>

        <!-- SECTION 6: IMAGES & VIDEO -->
        <div class="p-5 bg-[#F5F2ED] border border-[#8B0D1A]/30 polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#0B0B0B] flex items-center gap-2 border-b border-[#8B0D1A]/20 pb-2">
                <i data-lucide="image" class="w-4 h-4 text-[#8B0D1A]"></i> 6. Images & Campaign Video
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Upload New Photos (Multiple Files)</label>
                    <input type="file" name="image_files[]" multiple accept="image/*"
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2 focus:outline-none focus:border-[#8B0D1A]">
                    <span class="text-[10px] text-[#0B0B0B]/80 block mt-1">Upload files to add to existing gallery</span>
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Or Add Image Link / URL</label>
                    <input type="url" name="image_url" placeholder="https://example.com/perfume-bottle.jpg"
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>
            </div>

            <div>
                <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Perfume Video Link (YouTube / Vimeo / MP4)</label>
                <input type="text" name="video_url" value="{{ $product->video_url }}" placeholder="e.g. https://youtu.be/WhKJl9W_1Fw"
                       class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
            </div>
        </div>

        <!-- SECTION 7: ADVERTISING & SOCIAL MEDIA CAPTIONS -->
        <div class="p-5 bg-[#F5F2ED] border border-[#8B0D1A]/30 polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#0B0B0B] flex items-center gap-2 border-b border-[#8B0D1A]/20 pb-2">
                <i data-lucide="share-2" class="w-4 h-4 text-[#8B0D1A]"></i> 7. Advertising & Social Media Captions
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Ad Headline</label>
                    <input type="text" name="ad_headline" value="{{ $product->ad_headline }}" placeholder="e.g. Discover Your New Signature Scent!"
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Ad Call To Action (CTA)</label>
                    <select name="ad_cta" class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                        <option value="Shop Now" {{ $product->ad_cta === 'Shop Now' ? 'selected' : '' }}>Shop Now</option>
                        <option value="Discover" {{ $product->ad_cta === 'Discover' ? 'selected' : '' }}>Discover</option>
                        <option value="Buy Now" {{ $product->ad_cta === 'Buy Now' ? 'selected' : '' }}>Buy Now</option>
                        <option value="Explore" {{ $product->ad_cta === 'Explore' ? 'selected' : '' }}>Explore</option>
                        <option value="Order Now" {{ $product->ad_cta === 'Order Now' ? 'selected' : '' }}>Order Now</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Ad Copy / Promo Text</label>
                <textarea name="ad_copy" rows="2" class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2 focus:outline-none focus:border-[#8B0D1A] font-medium">{{ $product->ad_copy }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">📸 Instagram Caption</label>
                    <textarea name="instagram_caption" rows="3" class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2 focus:outline-none focus:border-[#8B0D1A] font-medium">{{ $product->instagram_caption }}</textarea>
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">📘 Facebook Caption</label>
                    <textarea name="facebook_caption" rows="3" class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2 focus:outline-none focus:border-[#8B0D1A] font-medium">{{ $product->facebook_caption }}</textarea>
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">🎵 TikTok Caption</label>
                    <textarea name="tiktok_caption" rows="3" class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2 focus:outline-none focus:border-[#8B0D1A] font-medium">{{ $product->tiktok_caption }}</textarea>
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">💬 WhatsApp Status Text</label>
                    <textarea name="whatsapp_caption" rows="3" class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2 focus:outline-none focus:border-[#8B0D1A] font-medium">{{ $product->whatsapp_caption }}</textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 8: INVENTORY & HOMEPAGE PLACEMENT -->
        <div class="p-5 bg-[#F5F2ED] border border-[#8B0D1A]/30 polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#0B0B0B] flex items-center gap-2 border-b border-[#8B0D1A]/20 pb-2">
                <i data-lucide="package-check" class="w-4 h-4 text-[#8B0D1A]"></i> 8. Inventory & Homepage Badges
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="{{ $product->stock_quantity }}" min="0"
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Availability Status</label>
                    <select name="is_available" class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                        <option value="1" {{ $product->is_available ? 'selected' : '' }}>In Stock & Ready for Order</option>
                        <option value="0" {{ !$product->is_available ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
            </div>

            <!-- Homepage Placement Flags -->
            <div>
                <label class="block font-extrabold text-[#8B0D1A] uppercase mb-2">Homepage Placement & Badges</label>
                <div class="flex flex-wrap gap-6 p-3 bg-white border border-[#8B0D1A]/30 polygon-card">
                    <label class="flex items-center gap-2 cursor-pointer font-extrabold text-[#0B0B0B]">
                        <input type="checkbox" name="is_best_seller" value="1" {{ $product->is_best_seller ? 'checked' : '' }} class="accent-[#8B0D1A]">
                        <span>Best Seller</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer font-extrabold text-[#0B0B0B]">
                        <input type="checkbox" name="is_new_arrival" value="1" {{ $product->is_new_arrival ? 'checked' : '' }} class="accent-[#8B0D1A]">
                        <span>New Arrival</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer font-extrabold text-[#0B0B0B]">
                        <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="accent-[#8B0D1A]">
                        <span>Featured Showcase</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer font-extrabold text-[#0B0B0B]">
                        <input type="checkbox" name="is_limited_edition" value="1" {{ $product->is_limited_edition ? 'checked' : '' }} class="accent-[#8B0D1A]">
                        <span>Limited Edition</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- SECTION 9: SEO OPTIMIZATION -->
        <div class="p-5 bg-[#F5F2ED] border border-[#8B0D1A]/30 polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#0B0B0B] flex items-center gap-2 border-b border-[#8B0D1A]/20 pb-2">
                <i data-lucide="globe" class="w-4 h-4 text-[#8B0D1A]"></i> 9. SEO & Search Engine Optimization
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">SEO Meta Title</label>
                    <input type="text" name="seo_title" value="{{ $product->seo_title }}"
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">URL Slug</label>
                    <input type="text" name="slug" value="{{ $product->slug }}"
                           class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A] font-mono">
                </div>
            </div>

            <div>
                <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Meta Description</label>
                <textarea name="meta_description" rows="2" class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2 focus:outline-none focus:border-[#8B0D1A] font-medium">{{ $product->meta_description }}</textarea>
            </div>

            <div>
                <label class="block font-extrabold text-[#8B0D1A] uppercase mb-1">Image Alt Text</label>
                <input type="text" name="image_alt" value="{{ $product->image_alt }}"
                       class="w-full bg-white border border-[#8B0D1A]/40 text-[#0B0B0B] px-3 py-2.5 focus:outline-none focus:border-[#8B0D1A]">
            </div>
        </div>

        <button type="submit" class="w-full py-4 bg-[#8B0D1A] text-white font-extrabold text-sm uppercase tracking-[0.25em] polygon-btn hover:bg-[#0B0B0B] shadow-xl shadow-[#8B0D1A]/25">
            UPDATE PRODUCT DETAILS
        </button>
    </form>
</div>

@endsection
