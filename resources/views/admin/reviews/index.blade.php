@extends('admin.layout')

@section('page_title', 'Customer Reviews & Ratings')

@section('content')

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-[#F8F5EF] p-6 border border-[#D8C9B8] polygon-card shadow-sm">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-[#7C5A2B]">CUSTOMER FEEDBACK MODERATION</span>
            <h3 class="font-serif font-bold text-2xl text-[#29241F]">Customer Reviews & Ratings</h3>
            <p class="text-xs text-gray-600 mt-1">Approve, hide, or feature genuine customer reviews. Star ratings and average scores are computed automatically from approved reviews.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 bg-[#29241F] text-white text-xs font-extrabold uppercase polygon-badge">
                {{ $reviews->count() }} Total Reviews
            </span>
        </div>
    </div>

    <div class="bg-white border border-[#D8C9B8] polygon-card shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F8F5EF] border-b border-[#D8C9B8] text-[10px] font-extrabold uppercase text-[#7C5A2B] tracking-wider">
                    <tr>
                        <th class="p-4">Customer</th>
                        <th class="p-4">Product</th>
                        <th class="p-4">Rating & Review</th>
                        <th class="p-4">Verified</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8C9B8]">
                    @forelse($reviews as $rev)
                    <tr class="hover:bg-[#F8F5EF]/50 transition-colors">
                        <td class="p-4 font-bold text-[#29241F]">
                            {{ $rev->customer_name }}
                            <span class="block text-[10px] text-gray-600 font-normal">{{ $rev->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="p-4 font-extrabold text-[#29241F]">
                            {{ $rev->product ? $rev->product->name : 'General Perfume' }}
                        </td>
                        <td class="p-4 max-w-xs">
                            <div class="flex items-center gap-1 text-amber-500 mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                <i data-lucide="star" class="w-3.5 h-3.5 {{ $i <= $rev->rating ? 'fill-amber-400 text-amber-500' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="text-xs font-bold text-[#29241F] ml-1">{{ $rev->rating }}.0</span>
                            </div>
                            <p class="text-xs text-gray-700 italic font-medium line-clamp-2">"{{ $rev->comment }}"</p>
                        </td>
                        <td class="p-4">
                            @if($rev->is_verified)
                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-900 border border-emerald-300 text-[9px] font-extrabold uppercase rounded">
                                Verified Purchase
                            </span>
                            @else
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 border border-gray-300 text-[9px] font-extrabold uppercase rounded">
                                General
                            </span>
                            @endif
                        </td>
                        <td class="p-4">
                            @if($rev->status === 'approved')
                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-900 border border-emerald-300 text-[9px] font-extrabold uppercase rounded">Approved</span>
                            @elseif($rev->status === 'pending')
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-900 border border-amber-300 text-[9px] font-extrabold uppercase rounded">Pending</span>
                            @else
                            <span class="px-2 py-0.5 bg-rose-100 text-rose-900 border border-rose-300 text-[9px] font-extrabold uppercase rounded">Hidden</span>
                            @endif

                            @if($rev->is_featured)
                            <span class="ml-1 px-2 py-0.5 bg-purple-100 text-purple-900 border border-purple-300 text-[9px] font-extrabold uppercase rounded">Featured</span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($rev->status !== 'approved')
                                <form action="{{ route('admin.reviews.status', $rev) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="px-2.5 py-1 bg-emerald-700 text-white text-[10px] font-extrabold uppercase polygon-btn hover:bg-emerald-800">
                                        Approve
                                    </button>
                                </form>
                                @endif

                                @if($rev->status !== 'hidden')
                                <form action="{{ route('admin.reviews.status', $rev) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="hidden">
                                    <button type="submit" class="px-2.5 py-1 bg-amber-700 text-white text-[10px] font-extrabold uppercase polygon-btn hover:bg-amber-800">
                                        Hide
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.reviews.delete', $rev) }}" method="POST" onsubmit="return confirm('Delete this review permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 bg-rose-800 text-white text-[10px] font-extrabold uppercase polygon-btn hover:bg-black">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-600 font-medium">
                            No customer reviews yet. Real customer reviews submitted on product pages will appear here for moderation.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
