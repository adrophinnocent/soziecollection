@extends('admin.layout')

@section('page_title', 'Analytics & Reports')

@section('content')
<div class="space-y-6">

    <!-- Overview Financial Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-[#F8F5EF] border border-[#D8C9B8] p-5 polygon-card shadow-sm space-y-1">
            <span class="text-[10px] font-extrabold uppercase text-[#A8895F] tracking-widest block">Gross Store Revenue</span>
            <span class="font-serif font-bold text-2xl text-[#29241F] block">TZS {{ number_format($totalRevenue) }}</span>
        </div>
        <div class="bg-[#F8F5EF] border border-[#D8C9B8] p-5 polygon-card shadow-sm space-y-1">
            <span class="text-[10px] font-extrabold uppercase text-[#A8895F] tracking-widest block">New Orders Placed</span>
            <span class="font-serif font-bold text-2xl text-blue-900 block">{{ $placedOrders }}</span>
        </div>
        <div class="bg-[#F8F5EF] border border-[#D8C9B8] p-5 polygon-card shadow-sm space-y-1">
            <span class="text-[10px] font-extrabold uppercase text-[#A8895F] tracking-widest block">Fulfilled / Delivered</span>
            <span class="font-serif font-bold text-2xl text-emerald-800 block">{{ $deliveredOrders }}</span>
        </div>
        <div class="bg-[#F8F5EF] border border-[#D8C9B8] p-5 polygon-card shadow-sm space-y-1">
            <span class="text-[10px] font-extrabold uppercase text-[#A8895F] tracking-widest block">Cancelled Orders</span>
            <span class="font-serif font-bold text-2xl text-rose-800 block">{{ $cancelledOrders }}</span>
        </div>
    </div>

    <!-- Top Selling Products Report -->
    <div class="bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm p-6 space-y-4">
        <h4 class="font-serif font-bold text-lg text-[#29241F] border-b border-[#D8C9B8] pb-3">Top Performing Signature Perfumes</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($topSelling as $product)
            <div class="border border-[#D8C9B8] p-4 bg-white polygon-card flex gap-4">
                <img src="{{ $product->primary_image }}" class="w-16 h-16 object-cover polygon-card border border-[#D8C9B8]">
                <div class="space-y-1 min-w-0 flex-1">
                    <h5 class="font-serif font-bold text-sm text-[#29241F] truncate">{{ $product->name }}</h5>
                    <span class="text-[10px] text-[#A8895F] font-bold block uppercase">{{ $product->fragrance_family }}</span>
                    <span class="text-xs font-serif font-bold text-[#29241F] block">{{ $product->formatted_price }}</span>
                </div>
            </div>
            @empty
            <p class="text-xs text-gray-500 font-medium">No sales reports available yet.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
