@extends('admin.layout')

@section('page_title', 'Analytics & Store Overview')

@section('content')

<!-- Metric Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

    <div class="glass-panel p-6 polygon-card border border-[#D8C9B8] bg-[#F8F5EF] shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs text-[#29241F] font-extrabold uppercase tracking-wider">Total Sales Revenue</span>
            <i data-lucide="dollar-sign" class="w-5 h-5 text-[#A8895F]"></i>
        </div>
        <span class="font-serif font-bold text-3xl text-[#29241F] mt-2 block">TZS {{ number_format($totalRevenue, 0) }}</span>
    </div>

    <div class="glass-panel p-6 polygon-card border border-[#D8C9B8] bg-[#F8F5EF] shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs text-[#29241F] font-extrabold uppercase tracking-wider">Total Orders</span>
            <i data-lucide="shopping-bag" class="w-5 h-5 text-[#A8895F]"></i>
        </div>
        <span class="font-serif font-bold text-3xl text-[#29241F] mt-2 block">{{ $totalOrders }}</span>
        <span class="text-[10px] text-[#A8895F] font-extrabold">{{ $newOrdersCount }} pending new orders</span>
    </div>

    <div class="glass-panel p-6 polygon-card border border-[#D8C9B8] bg-[#F8F5EF] shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs text-[#29241F] font-extrabold uppercase tracking-wider">Total Perfumes</span>
            <i data-lucide="package" class="w-5 h-5 text-[#A8895F]"></i>
        </div>
        <span class="font-serif font-bold text-3xl text-[#29241F] mt-2 block">{{ $totalProducts }}</span>
    </div>

    <div class="glass-panel p-6 polygon-card border border-[#D8C9B8] bg-[#F8F5EF] shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs text-[#29241F] font-extrabold uppercase tracking-wider">Best Sellers Active</span>
            <i data-lucide="award" class="w-5 h-5 text-[#A8895F]"></i>
        </div>
        <span class="font-serif font-bold text-3xl text-[#29241F] mt-2 block">{{ $bestSellingProducts->count() }}</span>
    </div>

</div>

<!-- Recent Orders Table -->
<div class="glass-panel p-6 polygon-card border border-[#D8C9B8] space-y-4 bg-[#F8F5EF] shadow-xs">
    <div class="flex justify-between items-center pb-3 border-b border-[#D8C9B8]">
        <h3 class="font-serif font-bold text-xl text-[#29241F]">RECENT ORDERS</h3>
        <a href="{{ route('admin.orders') }}" class="text-xs font-extrabold text-[#A8895F] uppercase tracking-wider hover:underline">View All Orders &rarr;</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="text-[#29241F] border-b border-[#D8C9B8] uppercase tracking-wider font-extrabold">
                <tr>
                    <th class="pb-3">Order Number</th>
                    <th class="pb-3">Customer</th>
                    <th class="pb-3">Phone</th>
                    <th class="pb-3">City</th>
                    <th class="pb-3">Total</th>
                    <th class="pb-3">Status</th>
                    <th class="pb-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#D8C9B8]/60">
                @foreach($recentOrders as $o)
                <tr>
                    <td class="py-3 font-mono text-[#A8895F] font-extrabold">{{ $o->order_number }}</td>
                    <td class="py-3 text-[#29241F] font-bold">{{ $o->customer_name }}</td>
                    <td class="py-3 text-[#29241F] font-semibold">{{ $o->customer_phone }}</td>
                    <td class="py-3 text-[#29241F] font-semibold">{{ $o->city }}</td>
                    <td class="py-3 text-[#29241F] font-extrabold">TZS {{ number_format($o->total_amount, 0) }}</td>
                    <td class="py-3">
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $o->status === 'new' ? 'bg-[#A8895F] text-white' : 'bg-emerald-800 text-white' }}">
                            {{ $o->status }}
                        </span>
                    </td>
                    <td class="py-3">
                        <a href="{{ route('orders.show', $o->order_number) }}" target="_blank" class="text-xs text-[#A8895F] hover:underline font-extrabold">
                            View Receipt
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
