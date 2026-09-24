@extends('admin.layout')

@section('page_title', 'Marketing & Campaign Hub')

@section('content')
<div class="space-y-8">

    <!-- Overview Banner -->
    <div class="bg-gradient-to-br from-[#29241F] via-[#3D352C] to-[#29241F] text-[#F8F5EF] p-6 polygon-card border border-[#A8895F]/40 shadow-xl flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="space-y-2 max-w-xl">
            <span class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-[#A8895F]">ATELIER CAMPAIGN ENGINE</span>
            <h3 class="font-serif font-bold text-2xl text-[#F8F5EF]">Sozie Collection Promotional Campaigns</h3>
            <p class="text-xs text-[#D8C9B8] leading-relaxed">Manage promotional coupons, campaign banners, social copy generator, and VIP launch offers.</p>
        </div>
        <a href="{{ route('home') }}#scent-finder" target="_blank"
           class="px-5 py-3 bg-[#A8895F] text-white font-extrabold text-xs uppercase tracking-wider polygon-btn hover:bg-white hover:text-[#29241F] transition-all shadow-md flex-shrink-0">
            Preview Active Campaign
        </a>
    </div>

    <!-- Active Banners List -->
    <div class="bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm p-6 space-y-4">
        <h4 class="font-serif font-bold text-lg text-[#29241F] border-b border-[#D8C9B8] pb-3">Active Promotional Banners</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($banners as $banner)
            <div class="border border-[#D8C9B8] p-4 bg-white polygon-card flex gap-4">
                <img src="{{ $banner->image }}" class="w-24 h-24 object-cover polygon-card border border-[#D8C9B8] flex-shrink-0">
                <div class="space-y-1 min-w-0 flex-1">
                    <span class="text-[9px] font-extrabold uppercase text-[#A8895F] tracking-wider block">Banner #{{ $banner->sort_order }}</span>
                    <h5 class="font-serif font-bold text-base text-[#29241F] truncate">{{ $banner->title }}</h5>
                    <p class="text-xs text-gray-600 line-clamp-1 font-medium">{{ $banner->subtitle }}</p>
                    <div class="pt-2 flex items-center justify-between">
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-900 border border-emerald-300 text-[9px] font-extrabold uppercase rounded">Active</span>
                        <a href="{{ $banner->button_link }}" target="_blank" class="text-[10px] font-bold text-[#A8895F] uppercase hover:underline">View Target Link →</a>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-xs text-gray-500 font-medium">No campaign banners found.</p>
            @endforelse
        </div>
    </div>

    <!-- Coupons & Discount Codes -->
    <div class="bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm p-6 space-y-4">
        <div class="flex justify-between items-center border-b border-[#D8C9B8] pb-3">
            <h4 class="font-serif font-bold text-lg text-[#29241F]">Active Promotional Coupons</h4>
            <span class="text-xs font-bold text-[#A8895F] uppercase">VIP Discount Codes</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#EDE5D8] text-[#29241F] font-extrabold uppercase text-[10px] tracking-wider border-b border-[#D8C9B8]">
                    <tr>
                        <th class="p-3">Coupon Code</th>
                        <th class="p-3">Discount Type</th>
                        <th class="p-3">Amount</th>
                        <th class="p-3">Usage Limit</th>
                        <th class="p-3 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8C9B8]/60">
                    @forelse($coupons as $coupon)
                    <tr>
                        <td class="p-3 font-mono font-bold text-[#29241F]">{{ $coupon->code }}</td>
                        <td class="p-3 font-medium text-gray-700 capitalize">{{ $coupon->type }}</td>
                        <td class="p-3 font-bold text-[#A8895F]">
                            {{ $coupon->type === 'percentage' ? $coupon->amount.'%' : 'TZS '.number_format($coupon->amount) }}
                        </td>
                        <td class="p-3 font-medium text-gray-600">{{ $coupon->used_count ?? 0 }} / {{ $coupon->max_uses ?? 'Unlimited' }}</td>
                        <td class="p-3 text-right">
                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-900 border border-emerald-300 text-[9px] font-extrabold uppercase rounded">Active</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500 font-medium">No active coupons found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
