@extends('admin.layout')

@section('page_title', 'SEO & Google Indexing Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-[#F8F5EF] p-6 border border-[#D8C9B8] polygon-card shadow-sm">
        <div>
            <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] block">SEARCH ENGINE OPTIMIZATION</span>
            <h3 class="font-serif font-bold text-2xl text-[#29241F]">SEO & Google Indexing Settings</h3>
            <p class="text-xs text-gray-600 mt-1">Configure global search metadata, Open Graph cards, sitemap URLs, and Google verification tools.</p>
        </div>
        <div class="flex gap-2">
            <a href="/sitemap.xml" target="_blank" class="px-3.5 py-2 bg-white border border-[#D8C9B8] text-[#29241F] font-extrabold text-xs uppercase polygon-btn hover:bg-[#29241F] hover:text-white">
                View XML Sitemap
            </a>
            <a href="/robots.txt" target="_blank" class="px-3.5 py-2 bg-white border border-[#D8C9B8] text-[#29241F] font-extrabold text-xs uppercase polygon-btn hover:bg-[#29241F] hover:text-white">
                View Robots.txt
            </a>
        </div>
    </div>

    <form action="{{ route('admin.settings.seo.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Metadata -->
        <div class="bg-[#F8F5EF] border border-[#D8C9B8] p-6 polygon-card shadow-sm space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#29241F] border-b border-[#D8C9B8] pb-2">Global Search Metadata</h4>

            <div class="text-xs font-bold">
                <label class="block text-[#A8895F] uppercase mb-1">Homepage Site Title *</label>
                <input type="text" name="site_title" value="{{ old('site_title', $settings['site_title'] ?? 'Sozie Collection | Premium Perfumes & Fragrances') }}" required
                       class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
            </div>

            <div class="text-xs font-bold">
                <label class="block text-[#A8895F] uppercase mb-1">Global Meta Description *</label>
                <textarea name="meta_description" rows="3" required
                          class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-bold">
                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">Canonical Base URL *</label>
                    <input type="url" name="canonical_url" value="{{ old('canonical_url', $settings['canonical_url'] ?? 'https://soziecollection.twinasafaris.com') }}" required
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">Search Engine Robots Setting *</label>
                    <select name="robots_setting" class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                        <option value="index, follow" @selected(($settings['robots_setting'] ?? '') === 'index, follow')>index, follow (Allow Google Crawling)</option>
                        <option value="noindex, nofollow" @selected(($settings['robots_setting'] ?? '') === 'noindex, nofollow')>noindex, nofollow (Block Search Engines)</option>
                    </select>
                </div>
            </div>

            <div class="text-xs font-bold">
                <label class="block text-[#A8895F] uppercase mb-1">Default Open Graph (OG) Image URL</label>
                <input type="url" name="default_og_image" value="{{ old('default_og_image', $settings['default_og_image'] ?? '') }}"
                       class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
            </div>

            <div class="text-xs font-bold">
                <label class="block text-[#A8895F] uppercase mb-1">Default Focus Terms / Keywords</label>
                <input type="text" name="default_keywords" value="{{ old('default_keywords', $settings['default_keywords'] ?? '') }}"
                       class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
            </div>
        </div>

        <!-- Google Tools Integration -->
        <div class="bg-[#F8F5EF] border border-[#D8C9B8] p-6 polygon-card shadow-sm space-y-4">
            <h4 class="font-serif font-bold text-lg text-[#29241F] border-b border-[#D8C9B8] pb-2">Google Verification & Analytics</h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-bold">
                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">Google Search Console Verification Code</label>
                    <input type="text" name="gsc_verification_code" value="{{ old('gsc_verification_code', $settings['gsc_verification_code'] ?? '') }}" placeholder="e.g. google-site-verification=xyz..."
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>

                <div>
                    <label class="block text-[#A8895F] uppercase mb-1">Google Analytics Tracking ID (GA4)</label>
                    <input type="text" name="google_analytics_id" value="{{ old('google_analytics_id', $settings['google_analytics_id'] ?? '') }}" placeholder="e.g. G-XXXXXXXXXX"
                           class="w-full bg-white border border-[#D8C9B8] text-[#29241F] p-2.5 focus:outline-none focus:border-[#A8895F]">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-[#A8895F] text-white font-extrabold text-xs uppercase polygon-btn hover:bg-[#29241F]">
                Save SEO Settings
            </button>
        </div>
    </form>
</div>
@endsection
