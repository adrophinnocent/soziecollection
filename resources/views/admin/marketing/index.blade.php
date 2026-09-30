@extends('admin.layout')

@section('page_title', 'Marketing & Campaign Hub')

@section('content')
<div class="space-y-8">

    <!-- Overview Banner -->
    <div class="bg-gradient-to-br from-[#29241F] via-[#3D352C] to-[#29241F] text-[#F8F5EF] p-6 polygon-card border border-[#A8895F]/40 shadow-xl flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="space-y-2 max-w-xl">
            {{-- #D4AF37, not the admin's #7C5A2B: this sits on the dark banner
                 gradient, whose lightest point is the #3D352C stop (2.9:1 there). --}}
            <span class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-[#D4AF37]">ATELIER CAMPAIGN ENGINE</span>
            <h3 class="font-serif font-bold text-2xl text-[#F8F5EF]">Sozie Collection Promotional Campaigns</h3>
            <p class="text-xs text-[#D8C9B8] leading-relaxed">Manage promotional coupons, campaign banners, per-product share links, and VIP launch offers.</p>
        </div>
        <a href="{{ route('home') }}#scent-finder" target="_blank" rel="noopener"
           class="px-5 py-3 bg-[#7C5A2B] text-white font-extrabold text-xs uppercase tracking-wider polygon-btn hover:bg-white hover:text-[#29241F] transition-all shadow-md flex-shrink-0">
            Preview Active Campaign
        </a>
    </div>

    @php
        $tabs = [
            'banners' => __('Campaign Banners'),
            'coupons' => __('Promotional Coupons'),
            'campaign-links' => __('Campaign Links'),
        ];

        // Client-side filter payload. The rows themselves stay server rendered so
        // the links and descriptions are real HTML, not something JavaScript has
        // to build before the owner can read them.
        $campaignFilter = $campaignLinks
            ->map(fn (array $row) => [
                'key' => $row['id'],
                'name' => $row['name'],
                'availability' => $row['availability'],
                'link' => $row['link'],
            ])
            ->values();
    @endphp

    <!-- Three sections, one at a time, so the page never becomes an endless scroll -->
    <nav aria-label="{{ __('Marketing sections') }}" class="flex flex-wrap gap-2 border-b border-[#D8C9B8] pb-px">
        @foreach($tabs as $tabKey => $tabLabel)
        <a href="{{ route('admin.marketing', ['tab' => $tabKey]) }}"
           @if($tab === $tabKey) aria-current="page" @endif
           class="px-4 py-2.5 min-h-11 flex items-center text-xs font-extrabold uppercase tracking-wider polygon-btn border transition-colors {{ $tab === $tabKey ? 'bg-[#29241F] text-[#EDE5D8] border-[#29241F]' : 'bg-white text-[#29241F] border-[#D8C9B8] hover:border-[#A8895F] hover:text-[#7C5A2B]' }}">
            {{ $tabLabel }}
        </a>
        @endforeach
    </nav>

    @if($tab === 'banners')
    <!-- Active Banners List -->
    <div class="bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm p-6 space-y-4">
        <h4 class="font-serif font-bold text-lg text-[#29241F] border-b border-[#D8C9B8] pb-3">Active Promotional Banners</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($banners as $banner)
            <div class="border border-[#D8C9B8] p-4 bg-white polygon-card flex gap-4">
                <img src="{{ $banner->image_url }}" loading="lazy" decoding="async" class="w-24 h-24 object-cover polygon-card border border-[#D8C9B8] flex-shrink-0">
                <div class="space-y-1 min-w-0 flex-1">
                    <span class="text-[9px] font-extrabold uppercase text-[#7C5A2B] tracking-wider block">Banner #{{ $banner->sort_order }}</span>
                    <h5 class="font-serif font-bold text-base text-[#29241F] truncate">{{ $banner->title }}</h5>
                    <p class="text-xs text-gray-600 line-clamp-1 font-medium">{{ $banner->subtitle }}</p>
                    <div class="pt-2 flex items-center justify-between">
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-900 border border-emerald-300 text-[9px] font-extrabold uppercase rounded">Active</span>
                        <a href="{{ $banner->button_link }}" target="_blank" rel="noopener" class="text-[10px] font-bold text-[#7C5A2B] uppercase hover:underline">View Target Link →</a>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-xs text-gray-600 font-medium">No campaign banners found.</p>
            @endforelse
        </div>
    </div>
    @elseif($tab === 'coupons')
    <!-- Coupons & Discount Codes -->
    <div class="bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm p-6 space-y-4">
        <div class="flex justify-between items-center border-b border-[#D8C9B8] pb-3">
            <h4 class="font-serif font-bold text-lg text-[#29241F]">Active Promotional Coupons</h4>
            <span class="text-xs font-bold text-[#7C5A2B] uppercase">VIP Discount Codes</span>
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
                        <td class="p-3 font-bold text-[#7C5A2B]">
                            {{ $coupon->type === 'percentage' ? $coupon->amount.'%' : 'TZS '.number_format($coupon->amount) }}
                        </td>
                        <td class="p-3 font-medium text-gray-600">{{ $coupon->used_count ?? 0 }} / {{ $coupon->max_uses ?? 'Unlimited' }}</td>
                        <td class="p-3 text-right">
                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-900 border border-emerald-300 text-[9px] font-extrabold uppercase rounded">Active</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-600 font-medium">No active coupons found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    <!-- ================================================================= -->
    <!-- CAMPAIGN LINKS: one shareable link + one description per product -->
    <!-- ================================================================= -->
    <div class="bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm p-6 space-y-5"
         x-data="{
             rows: {{ Illuminate\Support\Js::from($campaignFilter) }},
             search: '',
             availability: 'all',
             copiedKey: null,
             copyTimer: null,

             get visibleKeys() {
                 const needle = this.search.trim().toLowerCase();

                 return this.rows
                     .filter((row) => {
                         const nameMatches = needle === '' || row.name.toLowerCase().indexOf(needle) !== -1;
                         const stockMatches = this.availability === 'all' || row.availability === this.availability;

                         return nameMatches && stockMatches;
                     })
                     .map((row) => row.key);
             },

             isVisible(key) {
                 return this.visibleKeys.indexOf(key) !== -1;
             },

             copyAll() {
                 const links = this.rows
                     .filter((row) => this.visibleKeys.indexOf(row.key) !== -1)
                     .map((row) => row.name + ' - ' + row.link);

                 if (links.length > 0) this.copy('all', links.join('\n'));
             },

             /**
              * navigator.clipboard only exists on a secure origin, so the
              * hidden-textarea plus execCommand path is the real fallback for
              * plain http and for some Android browsers. The textarea is
              * deliberately not readonly: iOS Safari refuses to copy out of a
              * readonly one, which is the browser this path exists for. The
              * confirmation is shown either way: from here the two paths look
              * identical.
              */
             copy(key, text) {
                 const fallback = () => {
                     const area = document.createElement('textarea');

                     area.value = text;
                     area.setAttribute('aria-hidden', 'true');
                     area.style.position = 'fixed';
                     area.style.top = '0';
                     area.style.left = '0';
                     area.style.width = '2px';
                     area.style.height = '2px';
                     area.style.padding = '0';
                     area.style.border = 'none';
                     area.style.outline = 'none';
                     area.style.boxShadow = 'none';
                     area.style.background = 'transparent';
                     area.style.opacity = '0';
                     document.body.appendChild(area);
                     area.focus();
                     area.select();
                     area.setSelectionRange(0, area.value.length);

                     let copied = false;

                     try {
                         copied = document.execCommand('copy');
                     } catch (error) {
                         copied = false;
                     }

                     document.body.removeChild(area);

                     return copied;
                 };

                 const confirm = () => {
                     this.copiedKey = key;
                     clearTimeout(this.copyTimer);
                     this.copyTimer = setTimeout(() => { this.copiedKey = null; }, 2000);
                 };

                 if (window.isSecureContext && navigator.clipboard && navigator.clipboard.writeText) {
                     navigator.clipboard.writeText(text).then(confirm).catch(() => {
                         if (fallback()) confirm();
                     });

                     return;
                 }

                 if (fallback()) confirm();
             },
         }">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-[#D8C9B8] pb-4">
            <div>
                <h4 class="font-serif font-bold text-lg text-[#29241F]">{{ __('Campaign Links') }}</h4>
                <p class="text-xs text-gray-600 font-medium mt-1 max-w-2xl leading-relaxed">
                    {{ __('One shareable link and one ready-to-send description per product. Copy either one, or open WhatsApp with both already written for you.') }}
                </p>
            </div>
            <button type="button" @click="copyAll()"
                    class="flex-shrink-0 px-4 py-2.5 min-h-11 w-full md:w-auto flex items-center justify-center gap-2 border border-[#29241F] bg-[#29241F] text-[#EDE5D8] font-extrabold text-xs uppercase tracking-wider polygon-btn hover:bg-[#A8895F] hover:border-[#A8895F] hover:text-[#12100E] transition-colors">
                <i data-lucide="clipboard-copy" class="w-4 h-4"></i>
                <span>{{ __('Copy all') }}</span>
            </button>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <label class="flex-1">
                <span class="sr-only">{{ __('Search products') }}</span>
                <input type="search" x-model.debounce.200ms="search" placeholder="{{ __('Search products by name...') }}"
                       class="w-full text-xs font-bold">
            </label>
            <label class="sm:w-56">
                <span class="sr-only">{{ __('Filter by availability') }}</span>
                <select x-model="availability" class="w-full text-xs font-bold">
                    <option value="all">{{ __('All availability') }}</option>
                    <option value="in">{{ __('In stock') }}</option>
                    <option value="out">{{ __('Out of stock') }}</option>
                </select>
            </label>
        </div>

        @if($campaignLinks->isNotEmpty())
        <p x-show="visibleKeys.length === 0" style="display: none;" class="text-xs text-gray-600 font-medium">
            {{ __('No products match your filters.') }}
        </p>
        @endif

        <div class="space-y-3">
            @forelse($campaignLinks as $row)
            <div data-campaign-row class="border border-[#D8C9B8] bg-white polygon-card p-4 flex flex-col xl:flex-row gap-4"
                 x-show="isVisible('{{ $row['id'] }}')">

                <div class="flex gap-3 xl:w-60 flex-shrink-0">
                    <img src="{{ $row['image'] }}" alt="{{ $row['name'] }}" loading="lazy" decoding="async"
                         class="w-20 h-20 object-cover polygon-card border border-[#D8C9B8] flex-shrink-0">
                    <div class="min-w-0 space-y-1">
                        <h5 class="font-serif font-bold text-sm text-[#29241F] leading-tight">{{ $row['name'] }}</h5>
                        {{-- #7C5A2B, not the storefront's #A8895F gold: the admin
                             panel is light, and #A8895F on white is only 3.28:1. --}}
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-[#7C5A2B]">{{ $row['category'] }}</p>
                        <p class="text-xs font-bold text-[#29241F]">{{ $row['price'] }}</p>
                        <span class="inline-block px-2 py-0.5 border text-[9px] font-extrabold uppercase rounded {{ $row['in_stock'] ? 'bg-emerald-100 text-emerald-900 border-emerald-300' : 'bg-gray-100 text-gray-700 border-gray-300' }}">
                            {{ $row['in_stock'] ? __('In stock') : __('Out of stock') }}
                        </span>
                    </div>
                </div>

                <div class="flex-grow min-w-0 space-y-3">
                    <div>
                        <span class="text-[9px] font-extrabold uppercase tracking-wider text-gray-600 block mb-1">{{ __('Shareable link') }}</span>
                        <a href="{{ $row['link'] }}" target="_blank" rel="noopener"
                           class="block font-mono text-xs text-[#29241F] bg-[#EDE5D8] border border-[#D8C9B8] px-3 py-2 break-all hover:border-[#A8895F] hover:text-[#7C5A2B] transition-colors">{{ $row['link'] }}</a>
                    </div>

                    <div>
                        <span class="text-[9px] font-extrabold uppercase tracking-wider text-gray-600 block mb-1">{{ __('Description') }}</span>
                        <p class="text-xs text-gray-700 leading-relaxed font-medium border border-[#D8C9B8] bg-[#F8F5EF] px-3 py-2">{{ $row['description'] }}</p>
                    </div>
                </div>

                <div class="flex xl:flex-col flex-wrap gap-2 xl:w-52 flex-shrink-0">
                    <button type="button" @click="copy('{{ $row['id'] }}-link', @js($row['link']))"
                            class="flex-1 xl:w-full px-3 py-2 min-h-11 flex items-center justify-center gap-1.5 border border-[#D8C9B8] bg-[#F8F5EF] text-[#29241F] font-extrabold text-[10px] uppercase tracking-wider polygon-btn hover:border-[#A8895F] hover:bg-[#7C5A2B] hover:text-white transition-colors">
                        <i data-lucide="link" class="w-3.5 h-3.5"></i>
                        <span>{{ __('Copy link') }}</span>
                    </button>

                    <button type="button" @click="copy('{{ $row['id'] }}-text', @js($row['message']))"
                            class="flex-1 xl:w-full px-3 py-2 min-h-11 flex items-center justify-center gap-1.5 border border-[#D8C9B8] bg-[#F8F5EF] text-[#29241F] font-extrabold text-[10px] uppercase tracking-wider polygon-btn hover:border-[#A8895F] hover:bg-[#7C5A2B] hover:text-white transition-colors">
                        <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                        <span>{{ __('Copy description') }}</span>
                    </button>

                    <a href="{{ $row['whatsapp_url'] }}" target="_blank" rel="noopener"
                       class="flex-1 xl:w-full px-3 py-2 min-h-11 flex items-center justify-center gap-1.5 border border-emerald-700 bg-emerald-700 text-white font-extrabold text-[10px] uppercase tracking-wider polygon-btn hover:bg-emerald-900 hover:border-emerald-900 transition-colors">
                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                        <span>{{ __('Share on WhatsApp') }}</span>
                    </a>

                    <span x-show="copiedKey === '{{ $row['id'] }}-link' || copiedKey === '{{ $row['id'] }}-text' || copiedKey === 'all'"
                          style="display: none;"
                          role="status"
                          class="w-full text-center text-[10px] font-extrabold uppercase tracking-wider text-emerald-800">
                        {{ __('Copied to clipboard') }}
                    </span>
                </div>
            </div>
            @empty
            <p class="text-xs text-gray-600 font-medium">{{ __('No products in the catalogue yet.') }}</p>
            @endforelse
        </div>
    </div>
    @endif

</div>
@endsection
