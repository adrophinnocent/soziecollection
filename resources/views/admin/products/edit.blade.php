@extends('admin.layout')

@section('page_title', 'Edit Perfume: ' . $product->name)

@section('content')

@php
    $existingVariants = $product->variants->map(function ($var) {
        return [
            'id' => $var->id,
            'size' => $var->size,
            'price' => $var->price,
            'discount_price' => $var->discount_price,
            'sku' => $var->sku,
            'stock_quantity' => $var->stock_quantity,
            'is_available' => (bool)$var->is_available,
        ];
    })->values()->toArray();

    if (empty($existingVariants)) {
        $existingVariants = [
            ['size' => '10ml', 'price' => $product->price, 'discount_price' => $product->discount_price, 'sku' => $product->sku . '-10ML', 'stock_quantity' => $product->stock_quantity, 'is_available' => true],
            ['size' => '30ml', 'price' => '', 'discount_price' => '', 'sku' => '', 'stock_quantity' => $product->stock_quantity, 'is_available' => true],
            ['size' => '50ml', 'price' => $product->price, 'discount_price' => $product->discount_price, 'sku' => $product->sku . '-50ML', 'stock_quantity' => $product->stock_quantity, 'is_available' => true],
            ['size' => '100ml', 'price' => '', 'discount_price' => '', 'sku' => '', 'stock_quantity' => $product->stock_quantity, 'is_available' => true],
        ];
    }
@endphp

<div class="max-w-5xl mx-auto space-y-6"
     x-data="{
         activeTab: 'basic',
         variants: {{ \Illuminate\Support\Js::from($existingVariants) }},
         addVariant() {
             this.variants.push({ size: '', price: '', discount_price: '', sku: '', stock_quantity: '{{ $product->stock_quantity }}', is_available: true });
         },
         removeVariant(index) {
             this.variants.splice(index, 1);
         }
     }">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-[#F8F5EF] p-6 border border-[#D8C9B8] polygon-card shadow-sm">
        <div>
            <span class="text-[10px] font-extrabold text-[#7C5A2B] uppercase tracking-[0.25em] block">ADMIN CATALOG MANAGEMENT</span>
            <h3 class="font-serif font-bold text-2xl text-[#29241F]">Edit Product: {{ $product->name }}</h3>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('shop.show', $product->slug) }}" target="_blank" class="px-4 py-2 bg-[#29241F] text-white font-extrabold text-xs uppercase polygon-btn hover:bg-[#7C5A2B]">
                View Live Page →
            </a>
            <a href="{{ route('admin.products') }}" class="px-4 py-2 bg-white border border-[#D8C9B8] text-[#29241F] font-extrabold text-xs uppercase polygon-btn hover:bg-[#29241F] hover:text-white">
                Back to List
            </a>
        </div>
    </div>

    <!-- TABBED NAVIGATION BAR -->
    <div class="flex flex-wrap gap-2 border-b border-[#D8C9B8] bg-[#F8F5EF] p-2 polygon-card shadow-xs">
        <button type="button" @click="activeTab = 'basic'"
                :class="activeTab === 'basic' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="info" class="w-4 h-4 text-[#7C5A2B]"></i>
            1. Basic Info
        </button>

        <button type="button" @click="activeTab = 'sizes'"
                :class="activeTab === 'sizes' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="layers" class="w-4 h-4 text-[#7C5A2B]"></i>
            2. Sizes & Pricing
        </button>

        <button type="button" @click="activeTab = 'profile'"
                :class="activeTab === 'profile' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="flower" class="w-4 h-4 text-[#7C5A2B]"></i>
            3. Fragrance Profile
        </button>

        <button type="button" @click="activeTab = 'performance'"
                :class="activeTab === 'performance' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="zap" class="w-4 h-4 text-[#7C5A2B]"></i>
            4. Performance
        </button>

        <button type="button" @click="activeTab = 'story'"
                :class="activeTab === 'story' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="book-open" class="w-4 h-4 text-[#7C5A2B]"></i>
            5. Product Story
        </button>

        <button type="button" @click="activeTab = 'media'"
                :class="activeTab === 'media' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="image" class="w-4 h-4 text-[#7C5A2B]"></i>
            6. Media & Video
        </button>

        <button type="button" @click="activeTab = 'inventory'"
                :class="activeTab === 'inventory' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="package-check" class="w-4 h-4 text-[#7C5A2B]"></i>
            7. Inventory & Badges
        </button>

        <button type="button" @click="activeTab = 'seo'"
                :class="activeTab === 'seo' ? 'bg-[#29241F] text-[#EDE5D8] border-[#A8895F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F]'"
                class="px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider border polygon-btn transition-all flex items-center gap-2">
            <i data-lucide="globe" class="w-4 h-4 text-[#7C5A2B]"></i>
            8. SEO (Optional)
        </button>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs font-bold">
        @csrf
        @method('PUT')

        <!-- TAB 1: BASIC INFORMATION -->
        <div x-show="activeTab === 'basic'" class="p-6 bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm space-y-5">
            <div class="border-b border-[#D8C9B8] pb-3 flex justify-between items-center">
                <h4 class="font-serif font-bold text-lg text-[#29241F]">1. Basic Information</h4>
                <span class="text-[10px] text-gray-600 font-extrabold uppercase">Step 1 of 8</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Product Name *</label>
                    <input type="text" name="name" value="{{ $product->name }}" required
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ $product->sku }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F] font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Brand</label>
                    <input type="text" name="brand" value="{{ $product->brand ?: 'Sozie Collection' }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Product Type *</label>
                    <select name="product_type" class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                        <option value="Eau de Parfum" {{ $product->product_type === 'Eau de Parfum' ? 'selected' : '' }}>Eau de Parfum (Perfume Spray)</option>
                        <option value="Extrait de Parfum" {{ $product->product_type === 'Extrait de Parfum' ? 'selected' : '' }}>Extrait de Parfum (Intense)</option>
                        <option value="Concentrated Perfume Oil" {{ $product->product_type === 'Concentrated Perfume Oil' ? 'selected' : '' }}>Concentrated Perfume Oil</option>
                        <option value="Body Mist" {{ $product->product_type === 'Body Mist' ? 'selected' : '' }}>Body Mist</option>
                        <option value="Luxury Gift Box / Discovery Set" {{ $product->product_type === 'Luxury Gift Box / Discovery Set' ? 'selected' : '' }}>Luxury Gift Box / Discovery Set</option>
                    </select>
                </div>

                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Category *</label>
                    <select name="category_id" required class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                        @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ $product->category_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Gender *</label>
                    <select name="gender" class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                        <option value="unisex" {{ $product->gender === 'unisex' ? 'selected' : '' }}>Unisex</option>
                        <option value="women" {{ $product->gender === 'women' ? 'selected' : '' }}>Women</option>
                        <option value="men" {{ $product->gender === 'men' ? 'selected' : '' }}>Men</option>
                    </select>
                </div>

                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Concentration</label>
                    <input type="text" name="concentration" value="{{ $product->concentration }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div>
                <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Description *</label>
                <textarea name="description" rows="5" required class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-3 focus:outline-none focus:border-[#A8895F] font-medium leading-relaxed">{{ $product->description }}</textarea>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="button" @click="activeTab = 'sizes'" class="px-5 py-2.5 bg-[#7C5A2B] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#29241F]">
                    Next: Sizes & Pricing →
                </button>
            </div>
        </div>

        <!-- TAB 2: SIZES & PRICING -->
        <div x-show="activeTab === 'sizes'" class="p-6 bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm space-y-5" style="display: none;">
            <div class="border-b border-[#D8C9B8] pb-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                <div>
                    <h4 class="font-serif font-bold text-lg text-[#29241F]">2. Sizes & Pricing (Dynamic Variants)</h4>
                    <p class="text-xs text-gray-600 mt-0.5">Manage price and stock per size variant. Sizes without a price will not be displayed on the storefront.</p>
                </div>
                <button type="button" @click="addVariant()" class="px-4 py-2 bg-[#29241F] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#A8895F] hover:text-[#12100E] flex items-center gap-1.5">
                    <i data-lucide="plus" class="w-4 h-4"></i> + Add Size / Variant
                </button>
            </div>

            <!-- Base / Fallback Default Price -->
            <div class="p-4 bg-[#EDE5D8] border border-[#D8C9B8] polygon-card grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Base Regular Price (TZS) *</label>
                    <input type="number" name="price" value="{{ $product->price }}" required
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Base Sale Price (TZS, Optional)</label>
                    <input type="number" name="discount_price" value="{{ $product->discount_price }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div class="space-y-3 pt-2">
                <template x-for="(v, index) in variants" :key="index">
                    <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-3 relative">
                        <div class="flex items-center justify-between border-b border-[#D8C9B8] pb-2">
                            <span class="text-xs font-extrabold text-[#7C5A2B] uppercase">Variant #<span x-text="index + 1"></span></span>
                            <button type="button" @click="removeVariant(index)" class="text-rose-700 hover:text-rose-900 text-xs font-extrabold uppercase flex items-center gap-1">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Remove
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-6 gap-3">
                            <div>
                                <label class="block text-[10px] font-extrabold text-[#7C5A2B] uppercase mb-1">Size Label *</label>
                                <input type="text" :name="'variants['+index+'][size]'" x-model="v.size" placeholder="e.g. 50ml"
                                       class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-2 py-1.5 text-xs font-bold focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-[10px] font-extrabold text-[#7C5A2B] uppercase mb-1">Regular Price *</label>
                                <input type="number" :name="'variants['+index+'][price]'" x-model="v.price" placeholder="TZS 65,000"
                                       class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-2 py-1.5 text-xs font-bold focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-[10px] font-extrabold text-[#7C5A2B] uppercase mb-1">Sale Price</label>
                                <input type="number" :name="'variants['+index+'][discount_price]'" x-model="v.discount_price" placeholder="TZS 55,000"
                                       class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-2 py-1.5 text-xs font-bold focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-[10px] font-extrabold text-[#7C5A2B] uppercase mb-1">Variant SKU</label>
                                <input type="text" :name="'variants['+index+'][sku]'" x-model="v.sku" placeholder="SZ-50ML"
                                       class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-2 py-1.5 text-xs font-bold focus:outline-none font-mono">
                            </div>

                            <div>
                                <label class="block text-[10px] font-extrabold text-[#7C5A2B] uppercase mb-1">Stock</label>
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
                <button type="button" @click="activeTab = 'profile'" class="px-5 py-2.5 bg-[#7C5A2B] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#29241F]">
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
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Fragrance Family *</label>
                    <input type="text" name="fragrance_family" value="{{ $product->fragrance_family }}" required list="families-list"
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
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Scent Character / Type *</label>
                    <input type="text" name="scent_type" value="{{ $product->scent_type }}" required list="scent-types-list"
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
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Top Notes *</label>
                    <input type="text" name="top_notes" value="{{ $product->top_notes }}" required
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Heart Notes *</label>
                    <input type="text" name="heart_notes" value="{{ $product->heart_notes }}" required
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Base Notes *</label>
                    <input type="text" name="base_notes" value="{{ $product->base_notes }}" required
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div class="pt-4 flex justify-between">
                <button type="button" @click="activeTab = 'sizes'" class="px-4 py-2 bg-white border border-[#D8C9B8] text-[#29241F] text-xs font-extrabold uppercase polygon-btn">
                    ← Back
                </button>
                <button type="button" @click="activeTab = 'performance'" class="px-5 py-2.5 bg-[#7C5A2B] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#29241F]">
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
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Longevity</label>
                    <input type="text" name="longevity" value="{{ $product->longevity }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Sillage</label>
                    <input type="text" name="sillage" value="{{ $product->sillage }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Intensity</label>
                    <input type="text" name="intensity" value="{{ $product->intensity }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div class="pt-4 flex justify-between">
                <button type="button" @click="activeTab = 'profile'" class="px-4 py-2 bg-white border border-[#D8C9B8] text-[#29241F] text-xs font-extrabold uppercase polygon-btn">
                    ← Back
                </button>
                <button type="button" @click="activeTab = 'story'" class="px-5 py-2.5 bg-[#7C5A2B] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#29241F]">
                    Next: Product Story →
                </button>
            </div>
        </div>

        <!-- TAB 5: PRODUCT STORY -->
        <div x-show="activeTab === 'story'" class="p-6 bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm space-y-5" style="display: none;">
            <div class="border-b border-[#D8C9B8] pb-3">
                <h4 class="font-serif font-bold text-lg text-[#29241F]">5. Fragrance Story (Brand Storytelling)</h4>
            </div>

            <div>
                <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Fragrance Story (Optional)</label>
                <textarea name="fragrance_story" rows="4" class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-3 focus:outline-none focus:border-[#A8895F] font-medium leading-relaxed">{{ $product->fragrance_story }}</textarea>
            </div>

            <div class="pt-4 flex justify-between">
                <button type="button" @click="activeTab = 'performance'" class="px-4 py-2 bg-white border border-[#D8C9B8] text-[#29241F] text-xs font-extrabold uppercase polygon-btn">
                    ← Back
                </button>
                <button type="button" @click="activeTab = 'media'" class="px-5 py-2.5 bg-[#7C5A2B] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#29241F]">
                    Next: Media & Video →
                </button>
            </div>
        </div>

        <!-- TAB 6: MEDIA & VIDEO -->
        <div x-show="activeTab === 'media'" class="p-6 bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm space-y-5" style="display: none;">
            <div class="border-b border-[#D8C9B8] pb-3">
                <h4 class="font-serif font-bold text-lg text-[#29241F]">6. Media & Campaign Video</h4>
                <p class="text-xs text-gray-600 mt-0.5">Manage primary product photo, additional gallery photos, and individual photo deletion.</p>
            </div>

            <!-- Current Gallery with Interactive Remove (Futa) Buttons -->
            @if(!empty($product->images))
            <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-3">
                <div class="flex items-center justify-between border-b border-[#D8C9B8] pb-2">
                    <span class="block text-xs font-extrabold uppercase text-[#7C5A2B]">Current Product Gallery (<span id="gallery_count">{{ count($product->images) }}</span> Picha)</span>
                    <span class="text-[10px] text-gray-600 font-bold">* Bofya kitufe chekundu cha "Futa Picha" ili kuiondoa picha usiyoitaka</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 pt-2">
                    @foreach($product->images as $index => $img)
                    <div class="relative bg-[#100E0C] border border-[#D8C9B8] polygon-card overflow-hidden p-2 flex flex-col justify-between shadow-sm transition-all duration-300">
                        <div class="w-full h-32 bg-[#0C0A09] polygon-card overflow-hidden flex items-center justify-center relative">
                            <img src="{{ $img }}" class="w-full h-full object-contain p-1">

                            @if($product->campaign_image == $img)
                            <span class="absolute top-1 left-1 bg-[#A8895F] text-[#12100E] text-[9px] font-extrabold px-2 py-0.5 uppercase polygon-badge shadow-md">
                                Picha Kuu
                            </span>
                            @endif
                        </div>

                        <input type="checkbox" name="keep_images[]" value="{{ $img }}" checked id="keep_img_{{ $index }}" class="hidden">

                        <button type="button"
                                onclick="removeGalleryImage(this, 'keep_img_{{ $index }}')"
                                class="mt-2 w-full py-1.5 bg-rose-800 text-white text-[10px] font-extrabold uppercase polygon-btn hover:bg-rose-600 transition-colors shadow-md flex items-center justify-center gap-1 cursor-pointer">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Futa Picha
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Main / Primary Photo Upload Section -->
            <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-3">
                <span class="block text-xs font-extrabold uppercase text-[#7C5A2B]">1. Main / Primary Product Photo (Picha Kuu)</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase text-gray-600 mb-1">Primary Image File Upload</label>
                        <input type="file" name="primary_image_file" accept="image/*"
                               class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-3 py-2 text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase text-gray-600 mb-1">Or Primary Image URL Link</label>
                        <input type="url" name="primary_image_url" placeholder="https://example.com/primary-perfume.jpg"
                               class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                    </div>
                </div>
            </div>

            <!-- Additional Gallery Upload Section -->
            <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-3">
                <span class="block text-xs font-extrabold uppercase text-[#7C5A2B]">2. Additional Gallery Photos (Picha za Ziada)</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase text-gray-600 mb-1">Upload Multiple Gallery Files</label>
                        <input type="file" name="gallery_files[]" multiple accept="image/*"
                               class="w-full bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] px-3 py-2 text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase text-gray-600 mb-1">Or Additional Gallery URL Link</label>
                        <input type="url" name="gallery_url" placeholder="https://example.com/gallery-photo.jpg"
                               class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Image Alt Text</label>
                    <input type="text" name="image_alt" value="{{ $product->image_alt }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Video URL (YouTube / Vimeo / MP4)</label>
                    <input type="text" name="video_url" value="{{ $product->video_url }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div class="pt-4 flex justify-between">
                <button type="button" @click="activeTab = 'story'" class="px-4 py-2 bg-white border border-[#D8C9B8] text-[#29241F] text-xs font-extrabold uppercase polygon-btn">
                    ← Back
                </button>
                <button type="button" @click="activeTab = 'inventory'" class="px-5 py-2.5 bg-[#7C5A2B] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#29241F]">
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
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Total Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="{{ $product->stock_quantity }}" min="0"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Low Stock Threshold</label>
                    <input type="number" name="low_stock_threshold" value="{{ $product->low_stock_threshold ?: 5 }}" min="0"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Availability Status</label>
                    <select name="availability_status" class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                        <option value="in_stock" {{ $product->availability_status === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="low_stock" {{ $product->availability_status === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ $product->availability_status === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="coming_soon" {{ $product->availability_status === 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                        <option value="discontinued" {{ $product->availability_status === 'discontinued' ? 'selected' : '' }}>Discontinued</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-extrabold text-[#7C5A2B] uppercase mb-2">Homepage Placement & Badges</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-white border border-[#D8C9B8] polygon-card">
                    <label class="flex items-center gap-2 cursor-pointer font-extrabold text-[#29241F]">
                        <input type="checkbox" name="is_best_seller" value="1" {{ $product->is_best_seller ? 'checked' : '' }} class="accent-[#A8895F]">
                        <span>Best Seller</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer font-extrabold text-[#29241F]">
                        <input type="checkbox" name="is_new_arrival" value="1" {{ $product->is_new_arrival ? 'checked' : '' }} class="accent-[#A8895F]">
                        <span>New Arrival</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer font-extrabold text-[#29241F]">
                        <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="accent-[#A8895F]">
                        <span>Featured Showcase</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer font-extrabold text-[#29241F]">
                        <input type="checkbox" name="is_limited_edition" value="1" {{ $product->is_limited_edition ? 'checked' : '' }} class="accent-[#A8895F]">
                        <span>Limited Edition</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 flex justify-between">
                <button type="button" @click="activeTab = 'media'" class="px-4 py-2 bg-white border border-[#D8C9B8] text-[#29241F] text-xs font-extrabold uppercase polygon-btn">
                    ← Back
                </button>
                <button type="button" @click="activeTab = 'seo'" class="px-5 py-2.5 bg-[#7C5A2B] text-white text-xs font-extrabold uppercase polygon-btn hover:bg-[#29241F]">
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
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">SEO Title</label>
                    <input type="text" name="seo_title" value="{{ $product->seo_title }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">URL Slug</label>
                    <input type="text" name="slug" value="{{ $product->slug }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F] font-mono">
                </div>

                <div>
                    <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Focus Keyword</label>
                    <input type="text" name="focus_keyword" value="{{ $product->focus_keyword }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div>
                <label class="block font-extrabold text-[#7C5A2B] uppercase mb-1">Meta Description</label>
                <textarea name="meta_description" rows="2" class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-medium">{{ $product->meta_description }}</textarea>
            </div>

            <div class="pt-4 flex justify-between">
                <button type="button" @click="activeTab = 'inventory'" class="px-4 py-2 bg-white border border-[#D8C9B8] text-[#29241F] text-xs font-extrabold uppercase polygon-btn">
                    ← Back
                </button>
            </div>
        </div>

        <div class="pt-4 border-t border-[#D8C9B8]">
            <button type="submit" class="w-full py-4 bg-[#7C5A2B] text-white font-extrabold text-sm uppercase tracking-[0.25em] polygon-btn hover:bg-[#29241F] shadow-xl shadow-[#A8895F]/20">
                UPDATE PERFUME PRODUCT DETAILS
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function removeGalleryImage(btn, inputId) {
        if (confirm('Je, una uhakika unataka kufuta picha hii kwenye gallery?')) {
            const input = document.getElementById(inputId);
            if (input) {
                input.checked = false;
            }
            const card = btn.closest('.relative');
            if (card) {
                card.style.opacity = '0.35';
                card.style.filter = 'grayscale(100%)';
                btn.innerHTML = 'Imeondolewa';
                btn.disabled = true;
                btn.className = 'absolute top-2 right-2 px-2 py-1 bg-gray-700 text-gray-300 text-[10px] font-bold rounded cursor-not-allowed';
            }
        }
    }
</script>
@endpush

@endsection
