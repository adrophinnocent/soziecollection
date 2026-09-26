<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-2 mb-8">
        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.4em]">{{ __('MY ACCOUNT') }}</span>
        <h1 class="font-serif font-bold text-4xl text-[#29241F]">@yield('account_title', __('Dashboard'))</h1>
        <p class="text-sm text-gray-600 font-medium max-w-2xl">
            @yield('account_subtitle', __('Manage your Sozie Collection orders, saved wishlist, delivery addresses, and personal details from one elegant atelier dashboard.'))
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start">

        <aside class="sticky top-28 self-start">
            <div class="glass-panel-gold border-2 border-[#A8895F]/30 polygon-card bg-[#F8F5EF] p-5 shadow-xl">
                <div class="flex items-center gap-3 p-3 mb-4 bg-[#EDE5D8]/70 polygon-card border border-[#A8895F]/25">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#A8895F] via-[#D8C9B8] to-[#29241F] p-[1.5px]">
                        <div class="w-full h-full rounded-full bg-[#F8F5EF] flex items-center justify-center">
                            <span class="font-serif font-bold text-xl text-[#29241F]">
                                {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-[#29241F] truncate leading-tight">{{ $user->name }}</p>
                        <p class="text-[10px] text-gray-500 font-bold truncate">{{ $user->email }}</p>
                        <span class="inline-flex items-center gap-1 mt-1 text-[9px] uppercase tracking-[0.2em] font-extrabold text-[#A8895F]">
                            <i data-lucide="gem" class="w-3 h-3"></i>
                            {{ $user->roleLabel }} {{ __('Member') }}
                        </span>
                    </div>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('account.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-[0.2em] transition-colors polygon-card
                       {{ request()->routeIs('account.dashboard') ? 'bg-[#A8895F] text-white shadow-md' : 'text-[#29241F] hover:bg-[#EDE5D8]' }}">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                    <a href="{{ route('account.orders') }}"
                       class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-[0.2em] transition-colors polygon-card
                       {{ request()->routeIs('account.orders*') ? 'bg-[#A8895F] text-white shadow-md' : 'text-[#29241F] hover:bg-[#EDE5D8]' }}">
                        <i data-lucide="package-search" class="w-4 h-4"></i>
                        <span>{{ __('My Orders') }}</span>
                    </a>
                    <a href="{{ route('account.wishlist') }}"
                       class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-[0.2em] transition-colors polygon-card
                       {{ request()->routeIs('account.wishlist*') ? 'bg-[#A8895F] text-white shadow-md' : 'text-[#29241F] hover:bg-[#EDE5D8]' }}">
                        <i data-lucide="heart" class="w-4 h-4"></i>
                        <span>{{ __('Wishlist') }}</span>
                    </a>
                    <a href="{{ route('account.addresses') }}"
                       class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-[0.2em] transition-colors polygon-card
                       {{ request()->routeIs('account.addresses*') ? 'bg-[#A8895F] text-white shadow-md' : 'text-[#29241F] hover:bg-[#EDE5D8]' }}">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        <span>{{ __('Saved Addresses') }}</span>
                    </a>
                    <a href="{{ route('account.profile') }}"
                       class="flex items-center gap-3 px-3 py-2.5 text-[11px] font-bold uppercase tracking-[0.2em] transition-colors polygon-card
                       {{ request()->routeIs('account.profile*') ? 'bg-[#A8895F] text-white shadow-md' : 'text-[#29241F] hover:bg-[#EDE5D8]' }}">
                        <i data-lucide="user-cog" class="w-4 h-4"></i>
                        <span>{{ __('Profile') }}</span>
                    </a>
                </nav>

                <div class="mt-5 pt-5 border-t border-[#D8C9B8] space-y-2">
                    <a href="{{ route('orders.track') }}"
                       class="flex items-center gap-2.5 px-3 py-2 text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#A8895F] hover:text-[#29241F] transition-colors">
                        <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                        {{ __('Track Order') }}
                    </a>
                    <a href="{{ route('shop.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#A8895F] hover:text-[#29241F] transition-colors">
                        <i data-lucide="store" class="w-3.5 h-3.5"></i>
                        {{ __('Browse Perfumes') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="block w-full">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-2.5 w-full px-3 py-2 text-[10px] font-extrabold uppercase tracking-[0.2em] text-rose-700 hover:bg-rose-50 transition-colors rounded text-left">
                            <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                            {{ __('Sign Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="min-h-[60vh] space-y-6">
            @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold polygon-card flex items-start gap-2">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-700 mt-0.5 flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold polygon-card flex items-start gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 mt-0.5 flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold polygon-card space-y-1">
                @foreach($errors->all() as $err)
                <p class="flex items-start gap-1.5">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 mt-0.5 flex-shrink-0"></i>
                    <span>{{ $err }}</span>
                </p>
                @endforeach
            </div>
            @endif

            @yield('account_content')
        </main>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', function () { sozieIcons(); });</script>
