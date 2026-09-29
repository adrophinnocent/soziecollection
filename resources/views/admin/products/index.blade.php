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
                    <th class="pb-3">Campaign Link & Actions</th>
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
                    <td class="py-3">
                        <div class="flex items-center gap-2">
                            <!-- Live Product Page Link -->
                            <a href="/product/{{ $p->slug }}" target="_blank"
                               title="View product page on live store"
                               class="px-2.5 py-1 bg-emerald-950/10 text-emerald-800 border border-emerald-600/30 rounded hover:bg-emerald-800 hover:text-white transition-all text-[10px] font-extrabold uppercase flex items-center gap-1">
                                <i data-lucide="external-link" class="w-3 h-3"></i> View
                            </a>

                            <!-- Copy Direct Campaign Link Button -->
                            <button onclick="copyProductLink('/product/{{ $p->slug }}')"
                                    title="Copy direct campaign link for WhatsApp / Social Media"
                                    class="px-2.5 py-1 bg-[#A8895F]/15 text-[#A8895F] border border-[#A8895F]/40 rounded hover:bg-[#A8895F] hover:text-white transition-all text-[10px] font-extrabold uppercase flex items-center gap-1 cursor-pointer">
                                <i data-lucide="copy" class="w-3 h-3"></i> Copy Link
                            </button>

                            <a href="{{ route('admin.products.edit', $p->id) }}" class="text-xs text-[#A8895F] hover:underline font-extrabold ml-1">Edit</a>

                            <form action="{{ route('admin.products.delete', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-rose-600 hover:underline font-extrabold">Delete</button>
                            </form>
                        </div>
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

@push('scripts')
<script>
    function copyProductLink(path) {
        const fullUrl = path.startsWith('http') ? path : (window.location.origin + (path.startsWith('/') ? path : '/' + path));
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(fullUrl).then(() => {
                alert('Copied product campaign link:\n' + fullUrl);
            }).catch(() => {
                fallbackCopyTextToClipboard(fullUrl);
            });
        } else {
            fallbackCopyTextToClipboard(fullUrl);
        }
    }

    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            alert('Copied product campaign link:\n' + text);
        } catch (err) {
            alert('Product Link:\n' + text);
        }
        document.body.removeChild(textArea);
    }
</script>
@endpush

@endsection
