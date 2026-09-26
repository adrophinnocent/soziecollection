@extends('layouts.app')

@section('title', __('My Account | Sozie Collection'))

@section('content')
@include('account._sidebar_layout', [
    'account_title' => __('Dashboard'),
    'account_subtitle' => __('Welcome back to your Sozie Collection atelier. Here is a quick overview of your fragrance journey with us.')
])
@endsection

@section('account_content')
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="glass-panel-gold border-2 border-[#A8895F]/25 polygon-card bg-[#F8F5EF] p-5 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center">
                <i data-lucide="shopping-bag" class="w-5 h-5 text-[#A8895F]"></i>
            </div>
            <span class="text-[10px] font-extrabold text-gray-500 uppercase tracking-[0.2em]">{{ __('LIFETIME') }}</span>
        </div>
        <span class="text-3xl font-serif font-bold text-[#29241F] block leading-none">{{ $totalOrders }}</span>
        <span class="text-[11px] text-gray-600 font-bold uppercase tracking-wider mt-1 block">{{ __('Total Orders Placed') }}</span>
    </div>

    <div class="glass-panel-gold border-2 border-[#A8895F]/25 polygon-card bg-[#F8F5EF] p-5 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center">
                <i data-lucide="heart" class="w-5 h-5 text-[#A8895F]"></i>
            </div>
            <span class="text-[10px] font-extrabold text-gray-500 uppercase tracking-[0.2em]">{{ __('SAVED') }}</span>
        </div>
        <span class="text-3xl font-serif font-bold text-[#29241F] block leading-none">{{ $wishlistCount }}</span>
        <span class="text-[11px] text-gray-600 font-bold uppercase tracking-wider mt-1 block">{{ __('Scents in Wishlist') }}</span>
    </div>

    <div class="glass-panel-gold border-2 border-[#A8895F]/25 polygon-card bg-[#F8F5EF] p-5 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center">
                <i data-lucide="coins" class="w-5 h-5 text-[#A8895F]"></i>
            </div>
            <span class="text-[10px] font-extrabold text-gray-500 uppercase tracking-[0.2em]">{{ __('SPENT') }}</span>
        </div>
        <span class="text-3xl font-serif font-bold text-[#29241F] block leading-none">TZS {{ number_format($totalSpent, 0) }}</span>
        <span class="text-[11px] text-gray-600 font-bold uppercase tracking-wider mt-1 block">{{ __('Total with Sozie') }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-6">
    <div class="glass-panel border border-[#D8C9B8] polygon-card bg-[#F8F5EF] p-6 shadow-md">
        <div class="flex items-center justify-between mb-5">
            <div>
                <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">{{ __('Recent Orders') }}</span>
                <h3 class="font-serif font-bold text-xl text-[#29241F]">{{ __('Latest Deliveries') }}</h3>
            </div>
            <a href="{{ route('account.orders') }}"
               class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#A8895F] hover:text-[#29241F] transition-colors flex items-center gap-1.5">
                {{ __('View All Orders') }} <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        @if($recentOrders->isEmpty())
        <div class="py-16 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#A8895F]/10 border border-[#A8895F]/30 flex items-center justify-center">
                <i data-lucide="package-open" class="w-8 h-8 text-[#A8895F]/60"></i>
            </div>
            <p class="font-serif font-bold text-lg text-[#29241F] mb-1">{{ __('No orders placed yet') }}</p>
            <p class="text-xs text-gray-500 font-bold mb-5 max-w-sm mx-auto">{{ __('Your first Sozie Collection fragrance awaits. Start exploring our signature atelier collection today.') }}</p>
            <a href="{{ route('shop.index') }}"
               class="inline-block px-5 py-2.5 bg-[#A8895F] text-white text-[10px] font-extrabold uppercase tracking-[0.25em] polygon-btn hover:bg-[#29241F] shadow-md">
                {{ __('Browse Perfume Collection') }}
            </a>
        </div>
        @else
        <div class="overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.2em] text-left border-b border-[#D8C9B8]">
                        <th class="pb-3 pr-2">{{ __('Order Number') }}</th>
                        <th class="pb-3 pr-2">{{ __('Date') }}</th>
                        <th class="pb-3 pr-2">{{ __('Items') }}</th>
                        <th class="pb-3 pr-2">{{ __('Total') }}</th>
                        <th class="pb-3 text-right">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="text-xs font-bold">
                    @foreach($recentOrders as $order)
                    <tr class="border-b border-[#D8C9B8]/60 last:border-0 hover:bg-[#EDE5D8]/50 transition-colors">
                        <td class="py-4 pr-2">
                            <a href="{{ route('account.orders.show', $order->order_number) }}"
                               class="font-mono font-extrabold text-[#29241F] hover:text-[#A8895F] tracking-wider">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="py-4 pr-2 text-gray-600">
                            {{ $order->created_at?->format('M d, Y') ?? '—' }}
                        </td>
                        <td class="py-4 pr-2 text-gray-700">
                            {{ $order->items_count ?? ($order->items->count() ?? 0) }} {{ __('items') }}
                        </td>
                        <td class="py-4 pr-2 text-[#29241F] font-extrabold">
                            TZS {{ number_format($order->total_amount, 0) }}
                        </td>
                        <td class="py-4 text-right">
                            <span class="inline-block px-2.5 py-1 rounded text-[9px] uppercase tracking-[0.18em] font-extrabold {{ $order->statusBadgeClass }}">
                                {{ $order->statusLabel }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <div class="space-y-5">
        <div class="glass-panel-gold border-2 border-[#A8895F]/25 polygon-card bg-gradient-to-br from-[#29241F] to-[#1d1814] p-5 shadow-lg text-[#F8F5EF] overflow-hidden relative">
            <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-[#A8895F]/20 blur-2xl"></div>
            <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-2 relative">{{ __('VIP REWARDS') }}</span>
            <h4 class="font-serif font-bold text-lg mb-2 relative">{{ __('Refer a Friend & Earn') }}</h4>
            <p class="text-[11px] text-[#D8C9B8] font-medium leading-relaxed mb-4 relative">
                {{ __('Share your love of Sozie Collection. Invite friends and unlock exclusive member rewards and early access drops.') }}
            </p>
            <button class="relative w-full px-4 py-2.5 bg-[#A8895F] text-white text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn hover:bg-[#D8C9B8] hover:text-[#29241F] transition-all">
                {{ __('Get My Referral Link') }}
            </button>
        </div>

        <div class="glass-panel border border-[#D8C9B8] polygon-card bg-[#F8F5EF] p-5 shadow-md">
            <div class="flex items-center gap-2.5 mb-4">
                <div class="w-8 h-8 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center">
                    <i data-lucide="sparkles" class="w-4 h-4 text-[#A8895F]"></i>
                </div>
                <h4 class="font-serif font-bold text-base text-[#29241F]">{{ __('Need Help Finding Your Scent?') }}</h4>
            </div>
            <p class="text-[11px] text-gray-600 font-medium leading-relaxed mb-4">
                {{ __('Our Fragrance Finder matches your personality and mood to a signature Sozie scent in under 60 seconds.') }}
            </p>
            <a href="{{ route('home') }}#scent-finder"
               class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-[#A8895F]/50 text-[#29241F] text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn hover:bg-[#EDE5D8] transition-all">
                <i data-lucide="wand-2" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                {{ __('Take Fragrance Quiz') }}
            </a>
        </div>
    </div>
</div>
@endsection
