@extends('admin.layout')

@section('page_title', 'Create New Perfume Product')

@section('content')

<div class="max-w-4xl mx-auto glass-panel p-8 polygon-card border border-[#D8C9B8] space-y-8 bg-[#F8F5EF] shadow-sm">
    <div class="border-b border-[#D8C9B8] pb-4">
        <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.25em] block">ADMIN CATALOG MANAGEMENT</span>
        <h3 class="font-serif font-bold text-3xl text-[#29241F]">ADD NEW PRODUCT</h3>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 text-xs font-bold">
        @csrf

        <!-- SECTION 1: BASIC INFORMATION -->
        <div class="p-5 bg-[#EDE5D8] border border-[#D8C9B8] polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#29241F] flex items-center gap-2 border-b border-[#A8895F]/20 pb-2">
                <i data-lucide="info" class="w-4 h-4 text-[#A8895F]"></i> 1. Basic Information
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Product Name *</label>
                    <input type="text" name="name" required placeholder="e.g. SOZIE ROYAL OUD"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">SKU (Auto-Generated if blank)</label>
                    <input type="text" name="sku" placeholder="e.g. SZ-ROYAL-OUD"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F] font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Brand</label>
                    <input type="text" name="brand" value="Sozie Collection"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Product Type</label>
                    <select name="product_type" class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                        <option value="Eau de Parfum">Eau de Parfum (Perfume Spray)</option>
                        <option value="Extrait de Parfum">Extrait de Parfum (Intense)</option>
                        <option value="Perfume Oil">Concentrated Perfume Oil</option>
                        <option value="Body Mist">Body Mist</option>
                        <option value="Gift Set">Luxury Gift Box / Discovery Set</option>
                    </select>
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Category *</label>
                    <select name="category_id" required class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                        @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Gender *</label>
                    <select name="gender" class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                        <option value="unisex">Unisex</option>
                        <option value="women">Women</option>
                        <option value="men">Men</option>
                    </select>
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Concentration</label>
                    <input type="text" name="concentration" value="Eau de Parfum" placeholder="e.g. Eau de Parfum"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div>
                <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Description *</label>
                <textarea name="description" rows="3" required placeholder="Describe the perfume character and sensory impression..."
                          class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-medium"></textarea>
            </div>
        </div>

        <!-- SECTION 2: PRICING -->
        <div class="p-5 bg-[#EDE5D8] border border-[#D8C9B8] polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#29241F] flex items-center gap-2 border-b border-[#A8895F]/20 pb-2">
                <i data-lucide="tag" class="w-4 h-4 text-[#A8895F]"></i> 2. Pricing
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Regular Price (TZS) *</label>
                    <input type="number" name="price" required placeholder="65000"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Sale / Discount Price (TZS)</label>
                    <input type="number" name="discount_price" placeholder="55000"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Currency</label>
                    <input type="text" value="TZS (Tanzanian Shilling)" readonly
                           class="w-full bg-[#D8C9B8]/40 border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 font-bold cursor-not-allowed">
                </div>
            </div>
        </div>

        <!-- SECTION 3: SIZES & VARIANTS -->
        <div class="p-5 bg-[#EDE5D8] border border-[#D8C9B8] polygon-card space-y-3">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-1 border-b border-[#A8895F]/20 pb-2">
                <h4 class="font-serif font-bold text-lg text-[#29241F] flex items-center gap-2">
                    <i data-lucide="layers" class="w-4 h-4 text-[#A8895F]"></i> 3. Sizes & Variants
                </h4>
                <span class="text-[10px] text-[#29241F]/80 font-semibold">* Weka bei kwa saizi ZINAZOPATIKANA TU. Saizi yenye bei ndiyo pekee itakayoonekana dukani.</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <!-- 10ml -->
                <div class="p-3 border border-[#D8C9B8] bg-white polygon-card space-y-1">
                    <label class="flex items-center gap-2 font-extrabold text-[#29241F] cursor-pointer">
                        <input type="checkbox" name="variants[0][enabled]" value="1" class="accent-[#A8895F]"
                               onchange="if(this.checked){ document.getElementById('price_10ml').focus(); } else { document.getElementById('price_10ml').value = ''; }">
                        <input type="text" name="variants[0][size]" value="10ml" class="bg-transparent font-extrabold text-[#29241F] text-xs border-b border-[#D8C9B8] focus:outline-none w-20">
                    </label>
                    <input type="number" id="price_10ml" name="variants[0][price]" placeholder="Bei ya 10ml (TZS)"
                           class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-2 py-1.5 focus:outline-none text-xs font-bold"
                           oninput="document.querySelector('input[name=\'variants[0][enabled]\']').checked = this.value > 0;">
                </div>

                <!-- 30ml -->
                <div class="p-3 border border-[#D8C9B8] bg-white polygon-card space-y-1">
                    <label class="flex items-center gap-2 font-extrabold text-[#29241F] cursor-pointer">
                        <input type="checkbox" name="variants[1][enabled]" value="1" class="accent-[#A8895F]"
                               onchange="if(this.checked){ document.getElementById('price_30ml').focus(); } else { document.getElementById('price_30ml').value = ''; }">
                        <input type="text" name="variants[1][size]" value="30ml" class="bg-transparent font-extrabold text-[#29241F] text-xs border-b border-[#D8C9B8] focus:outline-none w-20">
                    </label>
                    <input type="number" id="price_30ml" name="variants[1][price]" placeholder="Bei ya 30ml (TZS)"
                           class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-2 py-1.5 focus:outline-none text-xs font-bold"
                           oninput="document.querySelector('input[name=\'variants[1][enabled]\']').checked = this.value > 0;">
                </div>

                <!-- 50ml -->
                <div class="p-3 border border-[#D8C9B8] bg-white polygon-card space-y-1">
                    <label class="flex items-center gap-2 font-extrabold text-[#29241F] cursor-pointer">
                        <input type="checkbox" name="variants[2][enabled]" value="1" class="accent-[#A8895F]"
                               onchange="if(this.checked){ document.getElementById('price_50ml').focus(); } else { document.getElementById('price_50ml').value = ''; }">
                        <input type="text" name="variants[2][size]" value="50ml" class="bg-transparent font-extrabold text-[#29241F] text-xs border-b border-[#D8C9B8] focus:outline-none w-20">
                    </label>
                    <input type="number" id="price_50ml" name="variants[2][price]" placeholder="Bei ya 50ml (TZS)"
                           class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-2 py-1.5 focus:outline-none text-xs font-bold"
                           oninput="document.querySelector('input[name=\'variants[2][enabled]\']').checked = this.value > 0;">
                </div>

                <!-- 100ml -->
                <div class="p-3 border border-[#D8C9B8] bg-white polygon-card space-y-1">
                    <label class="flex items-center gap-2 font-extrabold text-[#29241F] cursor-pointer">
                        <input type="checkbox" name="variants[3][enabled]" value="1" class="accent-[#A8895F]"
                               onchange="if(this.checked){ document.getElementById('price_100ml').focus(); } else { document.getElementById('price_100ml').value = ''; }">
                        <input type="text" name="variants[3][size]" value="100ml" class="bg-transparent font-extrabold text-[#29241F] text-xs border-b border-[#D8C9B8] focus:outline-none w-20">
                    </label>
                    <input type="number" id="price_100ml" name="variants[3][price]" placeholder="Bei ya 100ml (TZS)"
                           class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-2 py-1.5 focus:outline-none text-xs font-bold"
                           oninput="document.querySelector('input[name=\'variants[3][enabled]\']').checked = this.value > 0;">
                </div>
            </div>

            <!-- Custom Size Input -->
            <div class="pt-3 border-t border-[#D8C9B8] grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-extrabold text-[#A8895F] uppercase">Custom Size Label (e.g. 6ml, 250ml)</label>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="variants[4][enabled]" value="1" class="accent-[#A8895F]">
                        <input type="text" name="variants[4][size]" placeholder="e.g. 250ml or 6ml Roll-on"
                               class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-2 py-1.5 focus:outline-none text-xs font-bold">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-[#A8895F] uppercase">Custom Size Price (TZS)</label>
                    <input type="number" name="variants[4][price]" placeholder="Bei ya Custom Size (TZS)"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-2 py-1.5 focus:outline-none text-xs font-bold"
                           oninput="document.querySelector('input[name=\'variants[4][enabled]\']').checked = this.value > 0;">
                </div>
            </div>
        </div>

        <!-- SECTION 4: FRAGRANCE PROFILE -->
        <div class="p-5 bg-[#EDE5D8] border border-[#D8C9B8] polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#29241F] flex items-center gap-2 border-b border-[#A8895F]/20 pb-2">
                <i data-lucide="flower" class="w-4 h-4 text-[#A8895F]"></i> 4. Fragrance Profile
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Fragrance Family *</label>
                    <input type="text" name="fragrance_family" required placeholder="e.g. Floral Fruity, Oriental Oud, Woody Amber"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Scent Note Character / Type *</label>
                    <input type="text" name="scent_type" required placeholder="e.g. Floral, Woody, Vanilla, Fresh, Spicy"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Top Notes *</label>
                    <input type="text" name="top_notes" required placeholder="e.g. Bergamot, Pear, Pink Pepper"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Heart Notes *</label>
                    <input type="text" name="heart_notes" required placeholder="e.g. Damask Rose, Jasmine, Peony"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Base Notes *</label>
                    <input type="text" name="base_notes" required placeholder="e.g. Madagascar Vanilla, Amber, White Musk"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>
        </div>

        <!-- SECTION 5: PERFORMANCE -->
        <div class="p-5 bg-[#EDE5D8] border border-[#D8C9B8] polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#29241F] flex items-center gap-2 border-b border-[#A8895F]/20 pb-2">
                <i data-lucide="zap" class="w-4 h-4 text-[#A8895F]"></i> 5. Performance Metrics
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Longevity</label>
                    <input type="text" name="longevity" value="10 - 12 Hours" placeholder="e.g. 12+ Hours"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Sillage</label>
                    <input type="text" name="sillage" value="Enormous" placeholder="e.g. Moderate, Strong, Enormous"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Intensity</label>
                    <input type="text" name="intensity" value="Strong" placeholder="e.g. Medium, Strong, Intense"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>
        </div>

        <!-- SECTION 6: IMAGES & VIDEO -->
        <div class="p-5 bg-[#EDE5D8] border border-[#D8C9B8] polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#29241F] flex items-center gap-2 border-b border-[#A8895F]/20 pb-2">
                <i data-lucide="image" class="w-4 h-4 text-[#A8895F]"></i> 6. Images & Campaign Video
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Upload Product Photos (Multiple Files)</label>
                    <input type="file" name="image_files[]" multiple accept="image/*"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F]">
                    <span class="text-[10px] text-[#29241F]/80 block mt-1">Select one or multiple photos (JPG, PNG, WebP)</span>
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Or Image Link / URL</label>
                    <input type="url" name="image_url" placeholder="https://images.unsplash.com/..."
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div>
                <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Perfume Video Link (YouTube / Vimeo / MP4)</label>
                <input type="text" name="video_url" placeholder="e.g. https://youtu.be/WhKJl9W_1Fw"
                       class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                <span class="text-[10px] text-[#29241F]/80 block mt-1">Video is embedded directly on product page.</span>
            </div>
        </div>

        <!-- SECTION 7: ADVERTISING & SOCIAL MEDIA CAPTIONS -->
        <div class="p-5 bg-[#EDE5D8] border border-[#D8C9B8] polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#29241F] flex items-center gap-2 border-b border-[#A8895F]/20 pb-2">
                <i data-lucide="share-2" class="w-4 h-4 text-[#A8895F]"></i> 7. Advertising & Social Media Captions
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Ad Headline</label>
                    <input type="text" name="ad_headline" placeholder="e.g. Discover Your New Signature Scent - 24hr Longevity!"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Ad Call To Action (CTA)</label>
                    <select name="ad_cta" class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                        <option value="Shop Now">Shop Now</option>
                        <option value="Discover">Discover</option>
                        <option value="Buy Now">Buy Now</option>
                        <option value="Explore">Explore</option>
                        <option value="Order Now">Order Now</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Ad Copy / Promo Text</label>
                <textarea name="ad_copy" rows="2" placeholder="Write catchy advertisement copy for Facebook & Google ads..."
                          class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-medium"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1 flex items-center gap-1.5">
                        📸 Instagram Caption
                    </label>
                    <textarea name="instagram_caption" rows="3" placeholder="✨ Unveil luxury in every drop. Tap link in bio to shop! #SozieCollection #LuxuryPerfume"
                              class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-medium"></textarea>
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1 flex items-center gap-1.5">
                        📘 Facebook Caption
                    </label>
                    <textarea name="facebook_caption" rows="3" placeholder="✨ Elevate your presence with Sozie Collection. Order today with doorstep delivery!"
                              class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-medium"></textarea>
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1 flex items-center gap-1.5">
                        🎵 TikTok Caption
                    </label>
                    <textarea name="tiktok_caption" rows="3" placeholder="This scent will get you compliments everywhere you go 👑🔥 Link in bio! #perfumetok #sozie"
                              class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-medium"></textarea>
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1 flex items-center gap-1.5">
                        💬 WhatsApp Status Text
                    </label>
                    <textarea name="whatsapp_caption" rows="3" placeholder="Jambo! Fragrance yetu mpya ya SOZIE sasa ipo tayari. Agiza sasa WhatsApp 0700000000 🛍️✨"
                              class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-medium"></textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 8: INVENTORY & HOMEPAGE PLACEMENT -->
        <div class="p-5 bg-[#EDE5D8] border border-[#D8C9B8] polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#29241F] flex items-center gap-2 border-b border-[#A8895F]/20 pb-2">
                <i data-lucide="package-check" class="w-4 h-4 text-[#A8895F]"></i> 8. Inventory & Homepage Badges
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="50" min="0"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Availability Status</label>
                    <select name="is_available" class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                        <option value="1">In Stock & Ready for Order</option>
                        <option value="0">Out of Stock</option>
                    </select>
                </div>
            </div>

            <!-- Homepage Placement Flags -->
            <div>
                <label class="block font-extrabold text-[#A8895F] uppercase mb-2">Homepage Placement & Badges</label>
                <div class="flex flex-wrap gap-6 p-3 bg-white border border-[#D8C9B8] polygon-card">
                    <label class="flex items-center gap-2 cursor-pointer font-extrabold text-[#29241F]">
                        <input type="checkbox" name="is_best_seller" value="1" class="accent-[#A8895F]">
                        <span>Best Seller</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer font-extrabold text-[#29241F]">
                        <input type="checkbox" name="is_new_arrival" value="1" checked class="accent-[#A8895F]">
                        <span>New Arrival</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer font-extrabold text-[#29241F]">
                        <input type="checkbox" name="is_featured" value="1" class="accent-[#A8895F]">
                        <span>Featured Showcase</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer font-extrabold text-[#29241F]">
                        <input type="checkbox" name="is_limited_edition" value="1" class="accent-[#A8895F]">
                        <span>Limited Edition</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- SECTION 9: SEO OPTIMIZATION -->
        <div class="p-5 bg-[#EDE5D8] border border-[#D8C9B8] polygon-card space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#29241F] flex items-center gap-2 border-b border-[#A8895F]/20 pb-2">
                <i data-lucide="globe" class="w-4 h-4 text-[#A8895F]"></i> 9. SEO & Search Engine Optimization
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">SEO Meta Title</label>
                    <input type="text" name="seo_title" placeholder="e.g. SOZIE ROYAL OUD | Luxury Eau de Parfum Tanzania"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">URL Slug</label>
                    <input type="text" name="slug" placeholder="e.g. sozie-royal-oud"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F] font-mono">
                </div>
            </div>

            <div>
                <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Meta Description</label>
                <textarea name="meta_description" rows="2" placeholder="Write a search engine snippet summary..."
                          class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-medium"></textarea>
            </div>

            <div>
                <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Image Alt Text</label>
                <input type="text" name="image_alt" placeholder="e.g. Sozie Royal Oud luxury perfume bottle in gold casing"
                       class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
            </div>
        </div>

        <button type="submit" class="w-full py-4 bg-[#A8895F] text-white font-extrabold text-sm uppercase tracking-[0.25em] polygon-btn hover:bg-[#29241F] shadow-xl shadow-[#A8895F]/20">
            SAVE & PUBLISH PRODUCT
        </button>
    </form>
</div>

@endsection
