@extends('admin.layout')

@section('page_title', 'Website Content & Media')

@section('content')
<div class="space-y-6">

    <!-- Store Announcement Settings -->
    <div class="bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm p-6 space-y-4">
        <div class="border-b border-[#D8C9B8] pb-3 flex justify-between items-center">
            <div>
                <h4 class="font-serif font-bold text-lg text-[#29241F]">Store Announcement & Language Bar</h4>
                <p class="text-xs text-gray-600">Controls the top announcement bar displayed across the entire store.</p>
            </div>
            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-900 border border-emerald-300 font-extrabold text-[10px] uppercase rounded">Live</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-2">
                <span class="text-[10px] font-extrabold uppercase text-[#A8895F]">English Banner Text</span>
                <p class="text-xs text-[#29241F] font-bold">SOZIE COLLECTION</p>
            </div>
            <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-2">
                <span class="text-[10px] font-extrabold uppercase text-[#A8895F]">Bilingual EN / SW Toggle</span>
                <p class="text-xs text-emerald-800 font-extrabold">Active (Dynamic Switcher Enabled)</p>
            </div>
        </div>
    </div>

    <!-- Hero Banners -->
    <div class="bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm p-6 space-y-4">
        <h4 class="font-serif font-bold text-lg text-[#29241F] border-b border-[#D8C9B8] pb-3">Homepage Campaign Banners</h4>
        <div class="space-y-3">
            @foreach($banners as $banner)
            <div class="p-4 bg-white border border-[#D8C9B8] polygon-card flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="flex items-center gap-4 min-w-0">
                    <img src="{{ $banner->image }}" class="w-16 h-16 object-cover polygon-card border border-[#D8C9B8]">
                    <div class="min-w-0">
                        <h5 class="font-serif font-bold text-base text-[#29241F]">{{ $banner->title }}</h5>
                        <p class="text-xs text-gray-600 font-medium">{{ $banner->subtitle }}</p>
                    </div>
                </div>
                <a href="{{ route('home') }}" target="_blank" class="px-4 py-2 bg-[#29241F] text-white text-xs font-bold uppercase polygon-btn hover:bg-[#A8895F]">
                    Preview On Front Store
                </a>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
