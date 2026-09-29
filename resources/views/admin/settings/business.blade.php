@extends('admin.layout')

@section('page_title', 'Business Information & Brand Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-[#F8F5EF] p-6 border border-[#D8C9B8] polygon-card shadow-sm">
        <div>
            <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] block">SETTINGS & BRAND IDENTITY</span>
            <h3 class="font-serif font-bold text-2xl text-[#29241F]">Business Information</h3>
            <p class="text-xs text-gray-600 mt-1">Configure official brand details. Used automatically in footer, contact details, invoices, and JSON-LD Organization schema.</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.business.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- General Info -->
        <div class="bg-[#F8F5EF] border border-[#D8C9B8] p-6 polygon-card shadow-sm space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#29241F] border-b border-[#D8C9B8] pb-2">General Business Identity</h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-bold">
                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">Business Name *</label>
                    <input type="text" name="business_name" value="{{ old('business_name', $settings['business_name'] ?? 'Sozie Collection') }}" required
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">Website Base URL *</label>
                    <input type="url" name="website_url" value="{{ old('website_url', $settings['website_url'] ?? 'https://soziecollection.twinasafaris.com') }}" required
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div class="text-xs font-bold">
                <label class="block text-[#A8895F] uppercase mb-1">Business Description</label>
                <textarea name="business_description" rows="2"
                          class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">{{ old('business_description', $settings['business_description'] ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-bold">
                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">Upload Brand Logo (File Upload)</label>
                    <input type="file" name="logo_file" accept="image/*"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2 focus:outline-none focus:border-[#A8895F]">
                    <span class="text-[10px] text-gray-500 mt-1 block">Upload PNG, JPG, WebP, or SVG logo file</span>
                </div>

                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">Or Brand Logo URL Link</label>
                    <input type="text" name="logo_url" value="{{ old('logo_url', $settings['logo_url'] ?? '/images/sozie-logo.png') }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>
        </div>

        <!-- Contact & Location -->
        <div class="bg-[#F8F5EF] border border-[#D8C9B8] p-6 polygon-card shadow-sm space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#29241F] border-b border-[#D8C9B8] pb-2">Contact & Location Details</h4>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-bold">
                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">Official Email</label>
                    <input type="email" name="email" value="{{ old('email', $settings['email'] ?? 'admin@soziecollection.com') }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $settings['phone'] ?? '+255 711 000 001') }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">WhatsApp Number</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp'] ?? '255711000001') }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-bold">
                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">Country</label>
                    <input type="text" name="country" value="{{ old('country', $settings['country'] ?? 'Tanzania') }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">City / Region</label>
                    <input type="text" name="city" value="{{ old('city', $settings['city'] ?? 'Dar es Salaam') }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">Opening Hours</label>
                    <input type="text" name="opening_hours" value="{{ old('opening_hours', $settings['opening_hours'] ?? 'Mon - Sat: 9:00 AM - 8:00 PM') }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>

            <div class="text-xs font-bold">
                <label class="block text-[#A8895F] uppercase mb-1">Physical Address / Service Area</label>
                <input type="text" name="address" value="{{ old('address', $settings['address'] ?? 'Oysterbay, Toure Drive, Dar es Salaam, Tanzania') }}"
                       class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
            </div>
        </div>

        <!-- Social Channels -->
        <div class="bg-[#F8F5EF] border border-[#D8C9B8] p-6 polygon-card shadow-sm space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#29241F] border-b border-[#D8C9B8] pb-2">Official Social Media Profiles</h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-bold">
                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">Instagram Profile URL</label>
                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">Facebook Page URL</label>
                    <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">TikTok Profile URL</label>
                    <input type="url" name="tiktok_url" value="{{ old('tiktok_url', $settings['tiktok_url'] ?? '') }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">WhatsApp Direct Chat URL</label>
                    <input type="url" name="whatsapp_url" value="{{ old('whatsapp_url', $settings['whatsapp_url'] ?? '') }}"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-[#A8895F] text-white font-extrabold text-xs uppercase polygon-btn hover:bg-[#29241F]">
                Save Business Settings
            </button>
        </div>
    </form>
</div>
@endsection
