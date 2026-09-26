@extends('admin.layout')

@section('page_title', 'Products Management')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h3 class="font-serif font-bold text-2xl text-[#29241F]">ALL PERFUME PRODUCTS</h3>
    <a href="{{ route('admin.products.create') }}" class="px-6 py-2.5 bg-[#A8895F] text-white font-extrabold text-xs uppercase tracking-wider polygon-btn hover:bg-[#29241F]">
        + ADD NEW PERFUME
    </a>
</div>

<div class="glass-panel p-6 polygon-card border border-[#D8C9B8] bg-[#F8F5EF] shadow-xs">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="text-[#29241F] border-b border-[#D8C9B8] uppercase tracking-wider font-extrabold">
                <tr>
                    <th class="pb-3">Image</th>
                    <th class="pb-3">Name</th>
                    <th class="pb-3">Category</th>
                    <th class="pb-3">Gender</th>
                    <th class="pb-3">Price</th>
                    <th class="pb-3">Flags</th>
                    <th class="pb-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#D8C9B8]/60">
                @foreach($products as $p)
                <tr>
                    <td class="py-3">
                        <img src="{{ $p->primary_image }}" loading="lazy" decoding="async" class="w-12 h-12 object-cover polygon-card border border-[#D8C9B8]">
                    </td>
                    <td class="py-3">
                        <span class="font-serif font-bold text-sm text-[#29241F] block">{{ $p->name }}</span>
                        <span class="text-[10px] text-[#29241F]/70 font-mono font-bold">{{ $p->sku }}</span>
                    </td>
                    <td class="py-3 text-[#29241F] font-semibold">{{ $p->category ? $p->category->name : 'N/A' }}</td>
                    <td class="py-3 text-[#29241F] font-bold uppercase">{{ $p->gender }}</td>
                    <td class="py-3 font-extrabold text-[#A8895F]">{{ $p->formatted_price }}</td>
                    <td class="py-3 space-x-1">
                        @if($p->is_best_seller)
                        <span class="px-1.5 py-0.5 bg-[#A8895F] text-white text-[9px] font-extrabold rounded">BEST SELLER</span>
                        @endif
                        @if($p->is_new_arrival)
                        <span class="px-1.5 py-0.5 bg-[#29241F] text-white text-[9px] font-extrabold rounded">NEW</span>
                        @endif
                    </td>
                    <td class="py-3 space-x-2">
                        <a href="{{ route('admin.products.edit', $p->id) }}" class="text-xs text-[#A8895F] hover:underline font-extrabold">Edit</a>
                        <form action="{{ route('admin.products.delete', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-rose-600 hover:underline font-extrabold">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pt-6">
        {{ $products->links() }}
    </div>
</div>

@endsection
