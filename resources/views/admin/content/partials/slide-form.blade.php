@php
    $previewId = $banner?->id ?? 'new';
@endphp

<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="title-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#A8895F] mb-1">Internal Slide Name *</label>
            <input id="title-{{ $previewId }}" type="text" name="title" required
                   value="{{ old('title', $banner?->title) }}"
                   placeholder="e.g. Summer Launch 2026"
                   class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
            @error('title')
            <p class="mt-1 text-[10px] font-bold text-rose-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="eyebrow-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#A8895F] mb-1">Small Label</label>
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
            <label for="headline-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#A8895F] mb-1">Main Headline *</label>
            <input id="headline-{{ $previewId }}" type="text" name="headline" required
                   value="{{ old('headline', $banner?->headline) }}"
                   placeholder="e.g. YOUR SCENT."
                   class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F]">
            @error('headline')
            <p class="mt-1 text-[10px] font-bold text-rose-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="highlight_text-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#A8895F] mb-1">Gold Highlight Line</label>
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
        <label for="subtitle-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#A8895F] mb-1">Supporting Description</label>
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
                <label for="image-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#A8895F]">Desktop Design *</label>
                <span class="text-[9px] font-bold text-gray-600">Minimum 800 × 500px</span>
            </div>

            @if($banner?->image_url)
            <img src="{{ $banner->image_url }}" alt="Current desktop slide design"
                 data-image-preview="{{ $previewId }}"
                 class="w-full h-32 object-cover border border-[#D8C9B8] bg-white">
            @else
            <img alt="Desktop slide design preview" data-image-preview="{{ $previewId }}"
                 class="hidden w-full h-32 object-cover border border-[#D8C9B8] bg-white">
            @endif

            <input id="image-{{ $previewId }}" type="file" name="image" accept="image/jpeg,image/png,image/webp" data-preview-id="{{ $previewId }}"
                   @required(! $banner)
                   class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F]">
            @error('image')
            <p class="text-[10px] font-bold text-rose-700">{{ $message }}</p>
            @enderror
        </div>

        <div class="p-4 bg-[#EDE5D8] border border-[#D8C9B8] polygon-card space-y-3">
            <label for="mobile_image-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#A8895F]">Mobile Design <span class="normal-case font-semibold">(optional)</span></label>

            @if($banner?->mobile_image_url)
            <img src="{{ $banner->mobile_image_url }}" alt="Current mobile slide design"
                 data-mobile-preview="{{ $previewId }}"
                 class="w-full h-32 object-cover border border-[#D8C9B8] bg-white">
            @else
            <img alt="Mobile slide design preview" data-mobile-preview="{{ $previewId }}"
                 class="hidden w-full h-32 object-cover border border-[#D8C9B8] bg-white">
            @endif

            <input id="mobile_image-{{ $previewId }}" type="file" name="mobile_image" accept="image/jpeg,image/png,image/webp" data-mobile-preview-id="{{ $previewId }}"
                   class="w-full bg-white border border-[#D8C9B8] text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F]">
            @error('mobile_image')
            <p class="text-[10px] font-bold text-rose-700">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-4 border border-[#D8C9B8] bg-white polygon-card space-y-3">
            <span class="text-[10px] font-extrabold uppercase text-[#A8895F]">Primary Button</span>
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
            <span class="text-[10px] font-extrabold uppercase text-[#A8895F]">Secondary Button <span class="normal-case font-semibold">(optional)</span></span>
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
            <label for="sort_order-{{ $previewId }}" class="block text-[10px] font-extrabold uppercase text-[#A8895F] mb-1">Slide Order</label>
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
            <span class="text-xs font-extrabold text-[#29241F]">Show this slide on the homepage</span>
        </label>
    </div>
</div>
