@extends('admin.layout')

@section('page_title', 'Registered Customers')

@section('content')
<div class="space-y-6">

    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#F8F5EF] border border-[#D8C9B8] p-5 polygon-card shadow-sm">
            <span class="text-[10px] font-extrabold uppercase text-[#A8895F] tracking-widest block">Total Customer Accounts</span>
            <span class="font-serif font-bold text-3xl text-[#29241F] block mt-1">{{ $customers->total() }}</span>
        </div>
        <div class="bg-[#F8F5EF] border border-[#D8C9B8] p-5 polygon-card shadow-sm">
            <span class="text-[10px] font-extrabold uppercase text-[#A8895F] tracking-widest block">Active VIP Shoppers</span>
            <span class="font-serif font-bold text-3xl text-[#29241F] block mt-1">{{ $customers->where('orders_count', '>', 0)->count() }}</span>
        </div>
        <div class="bg-[#F8F5EF] border border-[#D8C9B8] p-5 polygon-card shadow-sm">
            <span class="text-[10px] font-extrabold uppercase text-[#A8895F] tracking-widest block">Checkout Guest Support</span>
            <span class="font-serif font-bold text-3xl text-emerald-800 block mt-1">Enabled</span>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm overflow-hidden">
        <div class="p-5 border-b border-[#D8C9B8] flex justify-between items-center bg-[#EDE5D8]/50">
            <div>
                <h3 class="font-serif font-bold text-lg text-[#29241F]">Customer Directory</h3>
                <p class="text-xs text-gray-600">Registered client accounts and order activity.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#EDE5D8] text-[#29241F] font-extrabold uppercase text-[10px] tracking-wider border-b border-[#D8C9B8]">
                    <tr>
                        <th class="p-4">Customer Name</th>
                        <th class="p-4">Email Address</th>
                        <th class="p-4">Phone Number</th>
                        <th class="p-4">Total Orders</th>
                        <th class="p-4">Joined Date</th>
                        <th class="p-4 text-right">Account Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8C9B8]/60">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-white/60 transition-colors">
                        <td class="p-4 font-bold text-[#29241F]">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#A8895F] to-[#29241F] text-white flex items-center justify-center font-serif font-bold text-xs">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <span>{{ $customer->name }}</span>
                            </div>
                        </td>
                        <td class="p-4 font-medium text-gray-700">{{ $customer->email }}</td>
                        <td class="p-4 font-medium text-gray-700">{{ $customer->phone ?? 'N/A' }}</td>
                        <td class="p-4 font-bold">
                            <span class="px-2.5 py-1 bg-[#A8895F]/10 text-[#A8895F] border border-[#A8895F]/30 rounded polygon-badge font-extrabold text-[10px]">
                                {{ $customer->orders_count }} {{ Str::plural('Order', $customer->orders_count) }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-600 font-medium">{{ $customer->created_at->format('M d, Y') }}</td>
                        <td class="p-4 text-right">
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-900 border border-emerald-300 font-extrabold text-[9px] uppercase tracking-wider rounded">
                                Customer
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500 font-medium">
                            No registered customers found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
        <div class="p-4 border-t border-[#D8C9B8]">
            {{ $customers->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
