@extends('admin.layout')

@section('page_title', 'Orders Management')

@section('content')

<div class="glass-panel p-6 polygon-card border border-[#D8C9B8] bg-[#F8F5EF] shadow-xs">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="text-[#29241F] border-b border-[#D8C9B8] uppercase tracking-wider font-extrabold">
                <tr>
                    <th class="pb-3">Order Number</th>
                    <th class="pb-3">Date</th>
                    <th class="pb-3">Customer</th>
                    <th class="pb-3">Phone</th>
                    <th class="pb-3">Address</th>
                    <th class="pb-3">Total Amount</th>
                    <th class="pb-3">Payment</th>
                    <th class="pb-3">Status</th>
                    <th class="pb-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#D8C9B8]/60">
                @foreach($orders as $o)
                <tr>
                    <td class="py-3 font-mono text-[#A8895F] font-extrabold">{{ $o->order_number }}</td>
                    <td class="py-3 text-[#29241F]/80 font-semibold">{{ $o->created_at->format('d M Y, H:i') }}</td>
                    <td class="py-3 text-[#29241F] font-bold">{{ $o->customer_name }}</td>
                    <td class="py-3 text-[#29241F] font-semibold">{{ $o->customer_phone }}</td>
                    <td class="py-3 text-[#29241F] font-semibold">{{ $o->city }} - {{ $o->shipping_address }}</td>
                    <td class="py-3 font-extrabold text-[#29241F]">TZS {{ number_format($o->total_amount, 0) }}</td>
                    <td class="py-3 text-[#29241F] uppercase font-bold">{{ str_replace('_', ' ', $o->payment_method) }}</td>
                    <td class="py-3">
                        <form action="{{ route('admin.orders.status', $o->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                    class="bg-white border border-[#D8C9B8] text-[10px] text-[#29241F] font-extrabold px-2 py-1 rounded focus:outline-none focus:border-[#A8895F]">
                                <option value="new" {{ $o->status === 'new' ? 'selected' : '' }}>NEW</option>
                                <option value="confirmed" {{ $o->status === 'confirmed' ? 'selected' : '' }}>CONFIRMED</option>
                                <option value="processing" {{ $o->status === 'processing' ? 'selected' : '' }}>PROCESSING</option>
                                <option value="shipped" {{ $o->status === 'shipped' ? 'selected' : '' }}>SHIPPED</option>
                                <option value="delivered" {{ $o->status === 'delivered' ? 'selected' : '' }}>DELIVERED</option>
                                <option value="cancelled" {{ $o->status === 'cancelled' ? 'selected' : '' }}>CANCELLED</option>
                            </select>
                        </form>
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

    <div class="pt-6">
        {{ $orders->links() }}
    </div>
</div>

@endsection
