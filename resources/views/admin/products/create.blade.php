@extends('admin.layout')

@section('page_title', 'Add New Perfume Product')

@section('content')

<div class="max-w-5xl mx-auto space-y-6"
     x-data="{
         activeTab: 'basic',
         variants: [
             { size: '10ml', price: '', discount_price: '', sku: '', stock_quantity: '50', is_available: true },
             { size: '30ml', price: '', discount_price: '', sku: '', stock_quantity: '50', is_available: true },
             { size: '50ml', price: '', discount_price: '', sku: '', stock_quantity: '50', is_available: true },
             { size: '100ml', price: '', discount_price: '', sku: '', stock_quantity: '50', is_available: true }
         ],
         addVariant() {
             this.variants.push({ size: '', price: '', discount_price: '', sku: '', stock_quantity: '50', is_available: true });
         },
         removeVariant(index) {
             this.variants.splice(index, 1);
         }
     }">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-[#F8F5EF] p-6 border border-[#D8C9B8] polygon-card shadow-sm">
        <div>
            <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] block">ADMIN CATALOG MANAGEMENT</span>
            <h3 class="font-serif font-bold text-2xl text-[#29241F]">Add New Perfume Product</h3>
        </div>
        <a href="{{ route('admin.products') }}" class="px-4 py-2 bg-white border border-[#D8C9B8] text-[#29241F] font-extrabold text-xs uppercase polygon-btn hover:bg-[#29241F] hover:text-white">
            ← Back to Products List
        </a>
    </div>

    <!-- TABBED NAVIGATION BAR -->
    <div class="flex flex-wrap gap-2 border-b border-[#D8C9B8] bg-[#F8F5EF] p-2 polygon-card shadow-xs">
        <button type="button" @click="activeTab = 'basic'"
                :class="activeTab === 'basic' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="info" class="w-4 h-4 text-[#A8895F]"></i>
            1. Basic Info
        </button>

        <button type="button" @click="activeTab = 'sizes'"
                :class="activeTab === 'sizes' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="layers" class="w-4 h-4 text-[#A8895F]"></i>
            2. Sizes & Pricing
        </button>

        <button type="button" @click="activeTab = 'profile'"
                :class="activeTab === 'profile' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="flower" class="w-4 h-4 text-[#A8895F]"></i>
            3. Fragrance Profile
        </button>

        <button type="button" @click="activeTab = 'performance'"
                :class="activeTab === 'performance' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="zap" class="w-4 h-4 text-[#A8895F]"></i>
            4. Performance
        </button>

        <button type="button" @click="activeTab = 'story'"
                :class="activeTab === 'story' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="book-open" class="w-4 h-4 text-[#A8895F]"></i>
            5. Product Story
        </button>

        <button type="button" @click="activeTab = 'media'"
                :class="activeTab === 'media' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="image" class="w-4 h-4 text-[#A8895F]"></i>
            6. Media & Video
        </button>

        <button type="button" @click="activeTab = 'inventory'"
                :class="activeTab === 'inventory' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="package-check" class="w-4 h-4 text-[#A8895F]"></i>
            7. Inventory & Badges
        </button>

        <button type="button" @click="activeTab = 'seo'"
                :class="activeTab === 'seo' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="globe" class="w-4 h-4 text-[#A8895F]"></i>
            8. SEO (Optional)
        </button>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs font-bold">
        @csrf

        <!-- TAB 1: BASIC INFORMATION -->
        <div x-show="activeTab === 'basic'" class="p-6 bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm space-y-5">
            <div class="border-b border-[#D8C9B8] pb-3 flex justify-between items-center">
                <h4 class="font-serif font-bold text-lg text-[#29241F]">1. Basic Information</h4>
                <span class="text-[10px] text-gray-500 font-extrabold uppercase">Step 1 of 8</span>
            </div>

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
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Product Type *</label>
                    <select name="product_type" class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                        <option value="Eau de Parfum">Eau de Parfum (Perfume Spray)</option>
                        <option value="Extrait de Parfum">Extrait de Parfum (Intense)</option>
                        <option value="Concentrated Perfume Oil">Concentrated Perfume Oil</option>
                        <option value="Body Mist">Body Mist</option>
                        <option value="Luxury Gift Box / Discovery Set">Luxury Gift Box / Discovery Set</option>
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
                <textarea name="description" rows="5" required placeholder="Describe the perfume character, sensory impression, and elegance..."
                          class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-3 focus:outline-none focus:border-[#A8895F] font-medium leading-relaxed"></textarea>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="button" @click="activeTab = 'sizes'" class="px-5 py-2.5 bg-[#A8895F] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#29241F]">
                    Next: Sizes & Pricing →
                </button>
            </div>
        </div>

        <!-- TAB 2: SIZES & PRICING (DYNAMIC VARIANTS) -->
        <div x-show="activeTab === 'sizes'" class="p-6 bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm space-y-5" style="display: none;">
            <div class="border-b border-[#D8C9B8] pb-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                <div>
                    <h4 class="font-serif font-bold text-lg text-[#29241F]">2. Sizes & Pricing (Dynamic Variants)</h4>
                    <p class="text-xs text-gray-600 mt-0.5">Manage price and stock per size variant. Sizes without a price will not be displayed on the storefront.</p>
                </div>
                <button type="button" @click="addVariant()" class="px-4 py-2 bg-[#29241F] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#A8895F] flex items-center gap-1.5">
                    <i data-lucide="plus" class="w-4 h-4"></i> + Add Size / Variant
                </button>
            </div>

            <!-- Base / Fallback Default Price -->
            <div class="p-4 bg-[#EDE5D8] border border-[#D8C9B8] polygon-card grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Base Regular Price (TZS) *</label>
                    <input type="number" name="price" required placeholder="65000"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Base Sale Price (TZS, Optional)</label>
                    <input type="number" name="discount_price" placeholder="55000"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div class="space-y-3 pt-2">
                <template x-for="(v, index) in variants" :key="index">
                    <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-3 relative">
                        <div class="flex items-center justify-between border-b border-[#D8C9B8] pb-2">
                            <span class="text-xs font-extrabold text-[#A8895F] uppercase">Variant #<span x-text="index + 1"></span></span>
                            <button type="button" @click="removeVariant(index)" class="text-rose-700 hover:text-rose-900 text-xs font-extrabold uppercase flex items-center gap-1">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Remove
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-6 gap-3">
                            <div>
                                <label class="block text-[10px] font-extrabold text-[#A8895F] uppercase mb-1">Size Label *</label>
                                <input type="text" :name="'variants['+index+'][size]'" x-model="v.size" placeholder="e.g. 50ml"
                                       class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-2 py-1.5 text-xs font-bold focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-[10px] font-extrabold text-[#A8895F] uppercase mb-1">Regular Price *</label>
                                <input type="number" :name="'variants['+index+'][price]'" x-model="v.price" placeholder="TZS 65,000"
                                       class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-2 py-1.5 text-xs font-bold focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-[10px] font-extrabold text-[#A8895F] uppercase mb-1">Sale Price</label>
                                <input type="number" :name="'variants['+index+'][discount_price]'" x-model="v.discount_price" placeholder="TZS 55,000"
                                       class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-2 py-1.5 text-xs font-bold focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-[10px] font-extrabold text-[#A8895F] uppercase mb-1">Variant SKU</label>
                                <input type="text" :name="'variants['+index+'][sku]'" x-model="v.sku" placeholder="SZ-50ML"
                                       class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-2 py-1.5 text-xs font-bold focus:outline-none font-mono">
                            </div>

                            <div>
                                <label class="block text-[10px] font-extrabold text-[#A8895F] uppercase mb-1">Stock</label>
                                <input type="number" :name="'variants['+index+'][stock_quantity]'" x-model="v.stock_quantity" placeholder="10"
                                       class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-2 py-1.5 text-xs font-bold focus:outline-none">
                            </div>

                            <div class="flex items-center pt-4">
                                <label class="flex items-center gap-1.5 cursor-pointer">
                                    <input type="hidden" :name="'variants['+index+'][enabled]'" value="1">
                                    <input type="checkbox" :name="'variants['+index+'][is_available]'" value="1" x-model="v.is_available" class="accent-[#A8895F]">
                                    <span class="text-[11px] font-bold text-[#29241F]">Available</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="pt-4 flex justify-between">
                <button type="button" @click="activeTab = 'basic'" class="px-4 py-2 bg-white border border-[#D8C9B8] text-[#29241F] text-xs font-extrabold uppercase polygon-btn">
                    ← Back
                </button>
                <button type="button" @click="activeTab = 'profile'" class="px-5 py-2.5 bg-[#A8895F] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#29241F]">
                    Next: Fragrance Profile →
                </button>
            </div>
        </div>

        <!-- TAB 3: FRAGRANCE PROFILE -->
        <div x-show="activeTab === 'profile'" class="p-6 bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm space-y-5" style="display: none;">
            <div class="border-b border-[#D8C9B8] pb-3">
                <h4 class="font-serif font-bold text-lg text-[#29241F]">3. Fragrance Profile</h4>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Fragrance Family *</label>
                    <input type="text" name="fragrance_family" required list="families-list" placeholder="e.g. Floral, Woody, Amber, Oriental"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                    <datalist id="families-list">
                        <option value="Floral"></option>
                        <option value="Woody"></option>
                        <option value="Fresh"></option>
                        <option value="Oriental"></option>
                        <option value="Citrus"></option>
                        <option value="Gourmand"></option>
                        <option value="Amber"></option>
                        <option value="Aromatic"></option>
                        <option value="Chypre"></option>
                        <option value="Leather"></option>
                        <option value="Musky"></option>
                    </datalist>
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Scent Character / Type *</label>
                    <input type="text" name="scent_type" required list="scent-types-list" placeholder="e.g. Sweet, Spicy, Vanilla, Warm"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                    <datalist id="scent-types-list">
                        <option value="Sweet"></option>
                        <option value="Fresh"></option>
                        <option value="Spicy"></option>
                        <option value="Vanilla"></option>
                        <option value="Fruity"></option>
                        <option value="Musky"></option>
                        <option value="Powdery"></option>
                        <option value="Smoky"></option>
                        <option value="Creamy"></option>
                        <option value="Clean"></option>
                        <option value="Warm"></option>
                        <option value="Earthy"></option>
                    </datalist>
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

            <div class="pt-4 flex justify-between">
                <button type="button" @click="activeTab = 'sizes'" class="px-4 py-2 bg-white border border-[#D8C9B8] text-[#29241F] text-xs font-extrabold uppercase polygon-btn">
                    ← Back
                </button>
                <button type="button" @click="activeTab = 'performance'" class="px-5 py-2.5 bg-[#A8895F] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#29241F]">
                    Next: Performance →
                </button>
            </div>
        </div>

        <!-- TAB 4: PERFORMANCE -->
        <div x-show="activeTab === 'performance'" class="p-6 bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm space-y-5" style="display: none;">
            <div class="border-b border-[#D8C9B8] pb-3">
                <h4 class="font-serif font-bold text-lg text-[#29241F]">4. Performance Metrics</h4>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Longevity</label>
                    <input type="text" name="longevity" value="8 – 12 Hours" placeholder="e.g. 6–8 Hours, 10–12 Hours"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Sillage</label>
                    <input type="text" name="sillage" value="Strong" placeholder="e.g. Moderate, Strong, Enormous"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Intensity</label>
                    <input type="text" name="intensity" value="Intense" placeholder="e.g. Soft, Medium, Strong, Intense"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div class="pt-4 flex justify-between">
                <button type="button" @click="activeTab = 'profile'" class="px-4 py-2 bg-white border border-[#D8C9B8] text-[#29241F] text-xs font-extrabold uppercase polygon-btn">
                    ← Back
                </button>
                <button type="button" @click="activeTab = 'story'" class="px-5 py-2.5 bg-[#A8895F] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#29241F]">
                    Next: Product Story →
                </button>
            </div>
        </div>

        <!-- TAB 5: PRODUCT STORY -->
        <div x-show="activeTab === 'story'" class="p-6 bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm space-y-5" style="display: none;">
            <div class="border-b border-[#D8C9B8] pb-3">
                <h4 class="font-serif font-bold text-lg text-[#29241F]">5. Fragrance Story (Brand Storytelling)</h4>
                <p class="text-xs text-gray-600 mt-1">Optional storytelling text for premium luxury branding displayed in a dedicated story section on the product page.</p>
            </div>

            <div>
                <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Fragrance Story (Optional)</label>
                <textarea name="fragrance_story" rows="4" placeholder="e.g. This warm and sophisticated fragrance creates a memorable presence with a smooth, sensual finish..."
                          class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-3 focus:outline-none focus:border-[#A8895F] font-medium leading-relaxed"></textarea>
            </div>

            <div class="pt-4 flex justify-between">
                <button type="button" @click="activeTab = 'performance'" class="px-4 py-2 bg-white border border-[#D8C9B8] text-[#29241F] text-xs font-extrabold uppercase polygon-btn">
                    ← Back
                </button>
                <button type="button" @click="activeTab = 'media'" class="px-5 py-2.5 bg-[#A8895F] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#29241F]">
                    Next: Media & Video →
                </button>
            </div>
        </div>

        <!-- TAB 6: MEDIA & VIDEO -->
        <div x-show="activeTab === 'media'" class="p-6 bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm space-y-5" style="display: none;">
            <div class="border-b border-[#D8C9B8] pb-3">
                <h4 class="font-serif font-bold text-lg text-[#29241F]">6. Media & Campaign Video</h4>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-2">
                    <label class="block font-extrabold text-[#A8895F] uppercase">Upload Photos (Multiple Files)</label>
                    <input type="file" name="image_files[]" multiple accept="image/*"
                           class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-3 py-2 focus:outline-none">
                    <span class="text-[10px] text-gray-600 block">First image becomes the Main/Featured Image. Supports JPG, PNG, WebP.</span>
                </div>

                <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-2">
                    <label class="block font-extrabold text-[#A8895F] uppercase">Or Image URL</label>
                    <input type="url" name="image_url" placeholder="https://example.com/perfume-bottle.jpg"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Image Alt Text</label>
                    <input type="text" name="image_alt" placeholder="e.g. Sozie Royal Oud luxury perfume bottle"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Product/Campaign Video URL (YouTube / Vimeo / MP4)</label>
                    <input type="text" name="video_url" placeholder="e.g. https://youtu.be/WhKJl9W_1Fw"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div class="pt-4 flex justify-between">
                <button type="button" @click="activeTab = 'story'" class="px-4 py-2 bg-white border border-[#D8C9B8] text-[#29241F] text-xs font-extrabold uppercase polygon-btn">
                    ← Back
                </button>
                <button type="button" @click="activeTab = 'inventory'" class="px-5 py-2.5 bg-[#A8895F] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#29241F]">
                    Next: Inventory & Badges →
                </button>
            </div>
        </div>

        <!-- TAB 7: INVENTORY & BADGES -->
        <div x-show="activeTab === 'inventory'" class="p-6 bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm space-y-5" style="display: none;">
            <div class="border-b border-[#D8C9B8] pb-3">
                <h4 class="font-serif font-bold text-lg text-[#29241F]">7. Inventory & Homepage Badges</h4>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Total Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="50" min="0"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Low Stock Threshold</label>
                    <input type="number" name="low_stock_threshold" value="5" min="0"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Availability Status</label>
                    <select name="availability_status" class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                        <option value="in_stock">In Stock</option>
                        <option value="low_stock">Low Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="coming_soon">Coming Soon</option>
                        <option value="discontinued">Discontinued</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-extrabold text-[#A8895F] uppercase mb-2">Homepage Placement & Badges</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-white border border-[#D8C9B8] polygon-card">
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

            <div class="pt-4 flex justify-between">
                <button type="button" @click="activeTab = 'media'" class="px-4 py-2 bg-white border border-[#D8C9B8] text-[#29241F] text-xs font-extrabold uppercase polygon-btn">
                    ← Back
                </button>
                <button type="button" @click="activeTab = 'seo'" class="px-5 py-2.5 bg-[#A8895F] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#29241F]">
                    Next: SEO (Optional) →
                </button>
            </div>
        </div>

        <!-- TAB 8: SEO & MARKETING (OPTIONAL) -->
        <div x-show="activeTab === 'seo'" class="p-6 bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm space-y-5" style="display: none;">
            <div class="border-b border-[#D8C9B8] pb-3">
                <h4 class="font-serif font-bold text-lg text-[#29241F]">8. SEO & Marketing Captions (Optional)</h4>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">SEO Title</label>
                    <input type="text" name="seo_title" placeholder="e.g. SOZIE ROYAL OUD | Luxury Eau de Parfum"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">URL Slug</label>
                    <input type="text" name="slug" placeholder="e.g. sozie-royal-oud"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F] font-mono">
                </div>

                <div>
                    <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Focus Keyword</label>
                    <input type="text" name="focus_keyword" placeholder="e.g. Oud perfume Tanzania"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div>
                <label class="block font-extrabold text-[#A8895F] uppercase mb-1">Meta Description</label>
                <textarea name="meta_description" rows="2" placeholder="Search engine description summary..."
                          class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-medium"></textarea>
            </div>

            <div class="pt-4 flex justify-between">
                <button type="button" @click="activeTab = 'inventory'" class="px-4 py-2 bg-white border border-[#D8C9B8] text-[#29241F] text-xs font-extrabold uppercase polygon-btn">
                    ← Back
                </button>
            </div>
        </div>

        <div class="pt-4 border-t border-[#D8C9B8]">
            <button type="submit" class="w-full py-4 bg-[#A8895F] text-white font-extrabold text-sm uppercase tracking-[0.25em] polygon-btn hover:bg-[#29241F] shadow-xl shadow-[#A8895F]/20">
                SAVE & PUBLISH PERFUME PRODUCT
            </button>
        </div>
    </form>
</div>

@endsection
