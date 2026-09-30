@php
    $previewId = $banner?->id ?? 'new';
@endphp

<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="title-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#7C5A2B] mb-1">Internal Slide Name *</label>
            <input id="title-{{ $previewId }}" type="text" name="title" required
                   value="{{ old('title', $banner?->title) }}"
                   placeholder="e.g. Summer Launch 2026"
                   class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
            @error('title')
            <p class="mt-1 text-[10px] font-bold text-rose-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="eyebrow-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#7C5A2B] mb-1">Small Label</label>
            <input id="eyebrow-{{ $previewId }}" type="text" name="eyebrow"
                   value="{{ old('eyebrow', $banner?->eyebrow) }}"
                   placeholder="e.g. SIGNATURE RELEASE"
                   class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
            @error('eyebrow')
            <p class="mt-1 text-[10px] font-bold text-rose-700">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="headline-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#7C5A2B] mb-1">Main Headline *</label>
            <input id="headline-{{ $previewId }}" type="text" name="headline" required
                   value="{{ old('headline', $banner?->headline) }}"
                   placeholder="e.g. YOUR SCENT."
                   class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
            @error('headline')
            <p class="mt-1 text-[10px] font-bold text-rose-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="highlight_text-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#7C5A2B] mb-1">Gold Highlight Line</label>
            <input id="highlight_text-{{ $previewId }}" type="text" name="highlight_text"
                   value="{{ old('highlight_text', $banner?->highlight_text) }}"
                   placeholder="e.g. YOUR SIGNATURE."
                   class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
            @error('highlight_text')
            <p class="mt-1 text-[10px] font-bold text-rose-700">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="subtitle-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#7C5A2B] mb-1">Supporting Description</label>
        <textarea id="subtitle-{{ $previewId }}" name="subtitle" rows="2"
                  placeholder="Short description shown below the headline."
                  class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">{{ old('subtitle', $banner?->subtitle) }}</textarea>
        @error('subtitle')
        <p class="mt-1 text-[10px] font-bold text-rose-700">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-4 bg-[#EDE5D8] border border-[#D8C9B8] polygon-card space-y-3">
            <div class="flex items-center justify-between gap-3">
                <label for="image-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#7C5A2B]">Desktop Design Image *</label>
                <span class="text-[9px] font-bold text-emerald-800">✓ Upload file au weka URL</span>
            </div>

            @if($banner?->image_url)
            <img src="{{ $banner->image_url }}" alt="Current desktop slide design"
                 data-image-preview="{{ $previewId }}"
                 class="w-full h-32 object-cover border border-[#D8C9B8] bg-white">
            @else
            <img alt="Desktop slide design preview" data-image-preview="{{ $previewId }}"
                 class="hidden w-full h-32 object-cover border border-[#D8C9B8] bg-white">
            @endif

            <input id="image-{{ $previewId }}" type="file" name="image" accept="image/*" data-preview-id="{{ $previewId }}"
                   class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F]">

            <div class="pt-1">
                <label class="block text-[9px] font-extrabold uppercase text-[#7C5A2B] mb-0.5">Au Image URL Link</label>
                <input type="text" name="image_url" value="{{ old('image_url', Str::startsWith($banner?->image ?? '', 'http') ? $banner?->image : '') }}" placeholder="https://cdn.example.com/slide.jpg"
                       class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-2.5 py-1.5 text-xs focus:outline-none focus:border-[#A8895F]">
            </div>

            @error('image')
            <p class="text-[10px] font-bold text-rose-700">{{ $message }}</p>
            @enderror
        </div>

        <div class="p-4 bg-[#EDE5D8] border border-[#D8C9B8] polygon-card space-y-3">
            <label for="mobile_image-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#7C5A2B]">Mobile Design <span class="normal-case font-semibold">(optional)</span></label>

            @if($banner?->mobile_image_url)
            <img src="{{ $banner->mobile_image_url }}" alt="Current mobile slide design"
                 data-mobile-preview="{{ $previewId }}"
                 class="w-full h-32 object-cover border border-[#D8C9B8] bg-white">
            @else
            <img alt="Mobile slide design preview" data-mobile-preview="{{ $previewId }}"
                 class="hidden w-full h-32 object-cover border border-[#D8C9B8] bg-white">
            @endif

            <input id="mobile_image-{{ $previewId }}" type="file" name="mobile_image" accept="image/*" data-mobile-preview-id="{{ $previewId }}"
                   class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F]">

            <div class="pt-1">
                <label class="block text-[9px] font-extrabold uppercase text-[#7C5A2B] mb-0.5">Au Mobile Image URL Link</label>
                <input type="text" name="mobile_image_url" value="{{ old('mobile_image_url', Str::startsWith($banner?->mobile_image ?? '', 'http') ? $banner?->mobile_image : '') }}" placeholder="https://cdn.example.com/mobile-slide.jpg"
                       class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-2.5 py-1.5 text-xs focus:outline-none focus:border-[#A8895F]">
            </div>

            @error('mobile_image')
            <p class="text-[10px] font-bold text-rose-700">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-4 border border-[#D8C9B8] bg-white polygon-card space-y-3">
            <span class="text-[10px] font-extrabold uppercase text-[#7C5A2B]">Primary Button</span>
            <input type="text" name="button_text" value="{{ old('button_text', $banner?->button_text) }}"
                   placeholder="EXPLORE COLLECTION"
                   class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
            <input type="text" name="button_link" value="{{ old('button_link', $banner?->button_link) }}"
                   placeholder="/shop or https://example.com"
                   class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
            @error('button_link')
            <p class="text-[10px] font-bold text-rose-700">{{ $message }}</p>
            @enderror
        </div>

        <div class="p-4 border border-[#D8C9B8] bg-white polygon-card space-y-3">
            <span class="text-[10px] font-extrabold uppercase text-[#7C5A2B]">Secondary Button <span class="normal-case font-semibold">(optional)</span></span>
            <input type="text" name="secondary_button_text" value="{{ old('secondary_button_text', $banner?->secondary_button_text) }}"
                   placeholder="FIND YOUR SCENT"
                   class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
            <input type="text" name="secondary_button_link" value="{{ old('secondary_button_link', $banner?->secondary_button_link) }}"
                   placeholder="/#scent-finder"
                   class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
            @error('secondary_button_link')
            <p class="text-[10px] font-bold text-rose-700">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
        <div>
            <label for="sort_order-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#7C5A2B] mb-1">Slide Order</label>
            <input id="sort_order-{{ $previewId }}" type="number" name="sort_order" min="0" max="999" required
                   value="{{ old('sort_order', $banner?->sort_order ?? $defaultSortOrder ?? 0) }}"
                   class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
            @error('sort_order')
            <p class="mt-1 text-[10px] font-bold text-rose-700">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-3 p-3 bg-[#EDE5D8] border border-[#D8C9B8] polygon-card cursor-pointer">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner?->is_active ?? true))
                   class="w-4 h-4 accent-[#A8895F]">
            <span class="text-xs font-extrabold text-[#29241F]">Status: Active (Visible on site)</span>
        </label>
    </div>

    <!-- Display Placements -->
    <div class="p-4 bg-[#F8F5EF] border border-[#D8C9B8] polygon-card space-y-2">
        <span class="block text-[10px] font-extrabold uppercase text-[#7C5A2B]">Where should this visual appear?</span>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <label class="flex items-center gap-2.5 p-3 bg-white border border-[#D8C9B8] polygon-card cursor-pointer hover:border-[#A8895F]">
                <input type="hidden" name="show_in_hero" value="0">
                <input type="checkbox" name="show_in_hero" value="1" @checked(old('show_in_hero', $banner?->show_in_hero ?? true))
                       class="w-4 h-4 accent-[#A8895F]">
                <div>
                    <span class="text-xs font-extrabold text-[#29241F] block">🎯 Hero Slider (Top of Homepage)</span>
                    <span class="text-[10px] text-gray-600">Main promotional hero banner section at the top.</span>
                </div>
            </label>

            <label class="flex items-center gap-2.5 p-3 bg-white border border-[#D8C9B8] polygon-card cursor-pointer hover:border-[#A8895F]">
                <input type="hidden" name="show_in_gallery" value="0">
                <input type="checkbox" name="show_in_gallery" value="1" @checked(old('show_in_gallery', $banner?->show_in_gallery ?? true))
                       class="w-4 h-4 accent-[#A8895F]">
                <div>
                    <span class="text-xs font-extrabold text-[#29241F] block">📸 Instagram & Campaign Visuals Gallery</span>
                    <span class="text-[10px] text-gray-600">Interactive #SOZIECOLLECTION gallery carousel further down.</span>
                </div>
            </label>
        </div>
    </div>
</div>
