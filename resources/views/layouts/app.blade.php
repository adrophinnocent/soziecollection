<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="sozie-storefront scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="dark">
    <meta name="theme-color" content="#0C0A09">
    <title>@yield('title', __('Sozie Collection | Premium Perfume E-Commerce'))</title>

    {{--
        Guarded icon renderer. Defined as a plain script (not inside Alpine state) so it
        always exists before Alpine boots, and safe to call unconditionally: icons are
        decorative, so a missing or slow icon script must never throw and take the rest
        of the page's JavaScript (cart count, wishlist, drawers) down with it.
    --}}
    <script>
        window.sozieIcons = function () {
            try {
                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons();
                }
            } catch (e) {
                /* Icons are decorative: never let them break a feature. */
            }
        };
        window.addEventListener('load', function () { window.sozieIcons(); });
    </script>

    {{--
        Guarded image fallback. Product photography lives in the database as an
        absolute URL, so a browser on a network that cannot reach that host used
        to collapse the whole card to nothing. Every image that is allowed to be
        missing carries data-sozie-fallback, and this one delegated capture-phase
        listener swaps in a bundled local placeholder when it fails.

        `error` does not bubble, but it does travel the capture phase, so a single
        listener on `document` also covers the images Alpine injects later — no
        per-element handlers and no MutationObserver. If the placeholder itself
        fails to load, the marker is dropped instead of retried, so it cannot loop.

        Progressive enhancement: the placeholder is a nicety, not a requirement. With
        JavaScript off, every page renders exactly as it did before.
    --}}
    <script>
        window.sozieImageFallback = function () {
            if (window.sozieImageFallbackBound) {
                return;
            }
            window.sozieImageFallbackBound = true;

            var placeholder = @js(asset('images/product-placeholder.svg'));

            document.addEventListener('error', function (event) {
                var image = event.target;

                if (!image || image.nodeName !== 'IMG' || !image.hasAttribute('data-sozie-fallback')) {
                    return;
                }

                if (image.dataset.sozieFallbackDone === '1') {
                    image.removeAttribute('data-sozie-fallback');
                    return;
                }

                image.dataset.sozieFallbackDone = '1';
                image.src = placeholder;
            }, true);
        };
        window.addEventListener('DOMContentLoaded', function () { window.sozieImageFallback(); });
    </script>

    {{-- Lucide 1.48.0 is self-hosted (public/vendor/lucide.min.js) instead of a CDN:
         third-party hosts get blocked or throttled on some mobile networks, which used
         to leave every <i data-lucide> empty on some browsers. --}}
    <script src="{{ asset('vendor/lucide.min.js') }}" defer onerror="void 0"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#0C0A09] text-[#EDE5D8] font-sans selection:bg-[#A8895F] selection:text-[#12100E] min-h-screen flex flex-col relative transition-colors duration-500"
      x-data="sozieApp()"
      x-init="initApp()"
      @open-quickview.window="openQuickView($event.detail.id)">

    <!-- BACKGROUND POLYGONAL GRAPHIC OVERLAYS (#EDE5D8 Warm Sand & #A8895F Champagne Gold) -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden opacity-40">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-[#C5A059]/20 blur-[130px] rounded-full animate-pulse-glow"></div>
        <div class="absolute top-1/3 -right-40 w-[500px] h-[500px] bg-[#A8895F]/20 blur-[160px] rounded-full"></div>
        <div class="absolute bottom-10 left-1/4 w-80 h-80 bg-[#C5A059]/12 blur-[120px] rounded-full"></div>
        <!-- Geometric Grid Lines -->
        <svg class="absolute inset-0 w-full h-full opacity-15" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid-pattern" width="60" height="60" patternUnits="userSpaceOnUse">
                    <path d="M 60 0 L 0 0 0 60" fill="none" stroke="#A8895F" stroke-width="0.5" />
                    <path d="M 0 60 L 60 0" fill="none" stroke="#D8C9B8" stroke-width="0.3" stroke-dasharray="2,4" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-pattern)" />
        </svg>
    </div>

    <!-- ANNOUNCEMENT BAR & LANGUAGE SWITCHER -->
    <div class="bg-[#12100E] border-b border-[#C5A059]/30 py-2.5 px-4 text-xs tracking-[0.2em] text-[#FFF5D0] font-bold uppercase z-50 relative">
        <div class="max-w-7xl mx-auto flex items-center justify-between w-full">
            <span class="truncate flex items-center gap-2.5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37] animate-ping"></span>
                <span class="text-[#A89C8C] font-bold tracking-normal sm:tracking-widest sm:hidden">{{ __('Announcement Bar Short') }}</span>
                <span class="text-[#A89C8C] font-bold tracking-widest hidden sm:inline">{{ __('Announcement Bar') }}</span>
            </span>

            <div class="flex items-center space-x-2 pl-4 flex-shrink-0">
                <!-- Language Switcher Buttons -->
                <a href="{{ route('lang.switch', 'en') }}"
                   class="px-2.5 py-1 rounded text-[10px] font-black border transition-all {{ app()->getLocale() === 'en' ? 'bg-gradient-to-r from-[#A8895F] to-[#D4AF37] text-[#12100E] border-[#D4AF37] shadow-sm' : 'text-[#A89C8C] border-[#C5A059]/30 hover:border-[#D4AF37] hover:text-[#FFF5D0]' }}"
                   title="{{ __('Switch to English') }}" aria-label="{{ __('Switch to English') }}">
                    🇬🇧 EN
                </a>
                <a href="{{ route('lang.switch', 'sw') }}"
                   class="px-2.5 py-1 rounded text-[10px] font-black border transition-all {{ app()->getLocale() === 'sw' ? 'bg-gradient-to-r from-[#A8895F] to-[#D4AF37] text-[#12100E] border-[#D4AF37] shadow-sm' : 'text-[#A89C8C] border-[#C5A059]/30 hover:border-[#D4AF37] hover:text-[#FFF5D0]' }}"
                   title="{{ __('Switch to Kiswahili') }}" aria-label="{{ __('Switch to Kiswahili') }}">
                    🇹🇿 SW
                </a>
            </div>
        </div>
    </div>

    <!-- HEADER / NAVIGATION -->
    <header class="sticky top-0 z-40 glass-panel-dark border-b border-[#A8895F]/25 transition-all duration-300 bg-[#12100E]/95 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">

                <!-- BRAND LOGO -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#A8895F] via-[#C5A059] to-[#12100E] polygon-card flex items-center justify-center p-[1px] shadow-md group-hover:scale-105 transition-transform duration-300">
                        <div class="w-full h-full bg-[#12100E] polygon-card flex items-center justify-center">
                            <span class="font-serif font-bold text-lg text-[#A8895F]">S</span>
                        </div>
                    </div>
                    <div>
                        <span class="font-serif font-bold text-2xl tracking-[0.2em] text-[#F8F5EF] group-hover:text-[#D4AF37] transition-colors">SOZIE</span>
                        <span class="block text-[9px] tracking-[0.35em] text-[#A8895F] uppercase -mt-1 font-extrabold">COLLECTION</span>
                    </div>
                </a>

                <!-- DESKTOP NAVIGATION -->
                <nav class="hidden md:flex items-center space-x-8 text-xs font-bold tracking-widest uppercase text-[#F8F5EF]">
                    <a href="{{ route('home') }}" class="hover:text-[#A8895F] transition-colors relative py-1 {{ request()->routeIs('home') ? 'text-[#A8895F]' : '' }}">
                        {{ __('Home') }}
                        @if(request()->routeIs('home'))
                        <span class="absolute bottom-0 left-0 w-full h-[2px] bg-[#A8895F] shadow-sm"></span>
                        @endif
                    </a>
                    <a href="{{ route('shop.index') }}" class="hover:text-[#A8895F] transition-colors relative py-1 {{ request()->routeIs('shop.index') ? 'text-[#A8895F]' : '' }}">
                        {{ __('Shop Collection') }}
                    </a>
                    <a href="{{ route('shop.index', ['scent_type' => 'Floral']) }}" class="hover:text-[#A8895F] transition-colors">
                        {{ __('Moods & Scents') }}
                    </a>
                    <a href="{{ route('home') }}#scent-finder" class="hover:text-[#A8895F] transition-colors text-[#A8895F] flex items-center gap-1 font-extrabold">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-[#A8895F]"></i> {{ __('Fragrance Finder') }}
                    </a>
                    <a href="{{ route('orders.track') }}" class="hover:text-[#A8895F] transition-colors">
                        {{ __('Track Order') }}
                    </a>

                    @guest
                        <div class="flex items-center space-x-3 pl-2 ml-1 border-l border-[#A8895F]/25">
                            <a href="{{ route('login') }}"
                               class="text-xs font-bold uppercase tracking-[0.2em] text-[#F8F5EF] hover:text-[#D4AF37] transition-colors">
                                {{ __('Sign In') }}
                            </a>
                            <a href="{{ route('register') }}"
                               class="text-xs text-[#12100E] hover:text-[#12100E] font-extrabold uppercase tracking-[0.2em] bg-[#A8895F] hover:bg-[#D4AF37] px-3 py-1.5 polygon-btn transition-all shadow-md">
                                {{ __('Join') }}
                            </a>
                        </div>
                    @else
                        @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-1.5 text-xs text-[#D4AF37] hover:text-[#F3E5AB] transition-colors border border-[#A8895F]/50 px-3 py-1.5 rounded polygon-btn bg-[#A8895F]/10 font-bold">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                            {{ __('Admin Portal') }}
                        </a>
                        @endif

                        <div x-data="{ accountOpen: false }" class="relative pl-2 ml-1 border-l border-[#A8895F]/25">
                            <button @click="accountOpen = !accountOpen"
                                    class="flex items-center gap-2 group"
                                    aria-haspopup="menu">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#A8895F] via-[#C5A059] to-[#12100E] p-[1.5px] shadow-sm group-hover:scale-105 transition-transform">
                                    <div class="w-full h-full rounded-full bg-[#17130F] flex items-center justify-center">
                                        <span class="font-serif font-bold text-sm text-[#EDE5D8]">
                                            {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <span class="hidden lg:flex flex-col items-start leading-tight">
                                    <span class="text-[10px] text-[#A8895F] uppercase tracking-[0.2em] font-extrabold">{{ __('My Account') }}</span>
                                    <span class="text-[11px] text-[#F8F5EF] font-bold max-w-[140px] truncate">{{ Auth::user()->name }}</span>
                                </span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                            </button>

                            <div x-show="accountOpen"
                                 @click.away="accountOpen = false"
                                 x-transition
                                 class="absolute right-0 mt-3 w-72 glass-panel-gold border-2 border-[#A8895F]/30 shadow-2xl rounded-none polygon-card bg-[#17130F] z-50 py-2 overflow-hidden">
                                <div class="px-4 py-3 border-b border-[#322B23] bg-[#100E0C]/70">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-[#A8895F] via-[#C5A059] to-[#12100E] p-[1.5px]">
                                            <div class="w-full h-full rounded-full bg-[#17130F] flex items-center justify-center">
                                                <span class="font-serif font-bold text-base text-[#EDE5D8]">
                                                    {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-bold text-[#EDE5D8] truncate">{{ Auth::user()->name }}</p>
                                            <p class="text-[10px] text-[#A89C8C] font-bold truncate">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="py-1.5">
                                    <a href="{{ route('account.dashboard') }}"
                                       class="flex items-center gap-3 px-4 py-2.5 text-[11px] font-bold text-[#EDE5D8] hover:bg-[#A8895F]/10 transition-colors uppercase tracking-[0.2em]">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4 text-[#A8895F]"></i>
                                        {{ __('Dashboard') }}
                                    </a>
                                    <a href="{{ route('account.orders') }}"
                                       class="flex items-center gap-3 px-4 py-2.5 text-[11px] font-bold text-[#EDE5D8] hover:bg-[#A8895F]/10 transition-colors uppercase tracking-[0.2em]">
                                        <i data-lucide="package-search" class="w-4 h-4 text-[#A8895F]"></i>
                                        {{ __('My Orders') }}
                                    </a>
                                    <a href="{{ route('account.wishlist') }}"
                                       class="flex items-center gap-3 px-4 py-2.5 text-[11px] font-bold text-[#EDE5D8] hover:bg-[#A8895F]/10 transition-colors uppercase tracking-[0.2em]">
                                        <i data-lucide="heart" class="w-4 h-4 text-[#A8895F]"></i>
                                        {{ __('Saved Wishlist') }}
                                    </a>
                                    <a href="{{ route('account.addresses') }}"
                                       class="flex items-center gap-3 px-4 py-2.5 text-[11px] font-bold text-[#EDE5D8] hover:bg-[#A8895F]/10 transition-colors uppercase tracking-[0.2em]">
                                        <i data-lucide="map-pin" class="w-4 h-4 text-[#A8895F]"></i>
                                        {{ __('Addresses') }}
                                    </a>
                                    <a href="{{ route('account.profile') }}"
                                       class="flex items-center gap-3 px-4 py-2.5 text-[11px] font-bold text-[#EDE5D8] hover:bg-[#A8895F]/10 transition-colors uppercase tracking-[0.2em]">
                                        <i data-lucide="user-cog" class="w-4 h-4 text-[#A8895F]"></i>
                                        {{ __('Profile') }}
                                    </a>
                                </div>

                                <div class="border-t border-[#322B23] py-1.5">
                                    <form method="POST" action="{{ route('logout') }}" class="block w-full">
                                        @csrf
                                        <button type="submit"
                                                class="flex items-center gap-3 w-full px-4 py-2.5 text-[11px] font-bold text-rose-300 hover:bg-[#2A1215] transition-colors uppercase tracking-[0.2em] text-left">
                                            <i data-lucide="log-out" class="w-4 h-4"></i>
                                            {{ __('Sign Out') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endguest
                </nav>

                <!-- HEADER ACTIONS (Search, Wishlist, Cart, WhatsApp) -->
                <div class="flex items-center space-x-4">

                    <!-- Search Trigger -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="p-2 text-[#F8F5EF] hover:text-[#D4AF37] transition-colors">
                            <i data-lucide="search" class="w-5 h-5"></i>
                        </button>

                        <div x-show="open"
                             @click.away="open = false"
                             x-transition
                             class="absolute right-0 mt-3 w-72 sm:w-96 glass-panel p-3 polygon-card shadow-2xl z-50 bg-[#17130F] border border-[#322B23]">
                            <form action="{{ route('shop.index') }}" method="GET" class="flex items-center gap-2">
                                <input type="text" name="q" placeholder="{{ __('Search perfumes, notes, mood...') }}"
                                       class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] px-3 py-2 focus:outline-none focus:border-[#A8895F]">
                                <button type="submit" class="bg-[#A8895F] border border-[#A8895F] text-[#12100E] px-4 py-2 font-extrabold text-xs uppercase polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF]">
                                    {{ __('Search') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Wishlist Trigger Button -->
                    <button @click="wishlistOpen = true" class="relative p-2 text-[#F8F5EF] hover:text-[#D4AF37] transition-colors">
                        <i data-lucide="heart" class="w-5 h-5"></i>
                        <span x-show="wishlist.length > 0"
                              x-text="wishlist.length"
                              class="absolute -top-1 -right-1 bg-[#A8895F] text-[#12100E] text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">0</span>
                    </button>

                    <!-- Cart Trigger Button -->
                    <button @click="cartOpen = true" class="relative p-2 text-[#F8F5EF] hover:text-[#D4AF37] transition-colors flex items-center gap-2 group">
                        <div class="relative">
                            <i data-lucide="shopping-bag" class="w-5 h-5 group-hover:scale-110 transition-transform text-[#F8F5EF]"></i>
                            <span x-show="cartCount > 0"
                                  x-text="cartCount"
                                  class="absolute -top-2 -right-2 bg-[#A8895F] text-[#12100E] text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">0</span>
                        </div>
                        <span class="hidden lg:inline text-xs font-bold uppercase tracking-wider text-[#A8895F]" x-text="formattedTotal">TZS 0</span>
                    </button>

                    <!-- Direct WhatsApp Contact lives in the floating button, see below -->

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-[#F8F5EF] hover:text-[#D4AF37]">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- MOBILE MENU OVERLAY -->
        <div x-show="mobileMenuOpen"
             x-transition
             class="md:hidden glass-panel border-t border-[#322B23] px-4 py-6 space-y-5 font-bold text-sm tracking-widest uppercase text-[#EDE5D8] bg-[#17130F]">
            <a href="{{ route('home') }}" class="block hover:text-[#A8895F]">{{ __('Home') }}</a>
            <a href="{{ route('shop.index') }}" class="block hover:text-[#A8895F]">{{ __('Shop Collection') }}</a>
            <a href="{{ route('home') }}#scent-finder" class="block text-[#A8895F] hover:text-[#F8F5EF]">{{ __('Fragrance Finder') }}</a>
            <a href="{{ route('orders.track') }}" class="block hover:text-[#A8895F]">{{ __('Track Order') }}</a>

            @guest
                <div class="pt-3 border-t border-[#322B23] grid grid-cols-2 gap-3">
                    <a href="{{ route('login') }}"
                       class="block text-center text-xs font-extrabold uppercase tracking-[0.2em] text-[#EDE5D8] border border-[#A8895F]/50 px-3 py-2.5 polygon-btn">
                        {{ __('Sign In') }}
                    </a>
                    <a href="{{ route('register') }}"
                       class="block text-center text-xs font-extrabold uppercase tracking-[0.2em] text-[#12100E] bg-[#A8895F] px-3 py-2.5 polygon-btn shadow-md">
                        {{ __('Join') }}
                    </a>
                </div>
            @else
                <div class="pt-3 border-t border-[#322B23] space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#A8895F] via-[#C5A059] to-[#12100E] p-[1.5px]">
                            <div class="w-full h-full rounded-full bg-[#17130F] flex items-center justify-center">
                                <span class="font-serif font-bold text-sm text-[#EDE5D8]">
                                    {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-[#EDE5D8] truncate leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-[#A89C8C] font-bold truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <a href="{{ route('account.dashboard') }}"
                           class="flex items-center gap-1.5 px-2.5 py-2 text-[10px] font-extrabold uppercase tracking-[0.15em] text-[#EDE5D8] bg-[#0C0A09] polygon-btn">
                            <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                            {{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('account.orders') }}"
                           class="flex items-center gap-1.5 px-2.5 py-2 text-[10px] font-extrabold uppercase tracking-[0.15em] text-[#EDE5D8] bg-[#0C0A09] polygon-btn">
                            <i data-lucide="package-search" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                            {{ __('Orders') }}
                        </a>
                        <a href="{{ route('account.wishlist') }}"
                           class="flex items-center gap-1.5 px-2.5 py-2 text-[10px] font-extrabold uppercase tracking-[0.15em] text-[#EDE5D8] bg-[#0C0A09] polygon-btn">
                            <i data-lucide="heart" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                            {{ __('Wishlist') }}
                        </a>
                        <a href="{{ route('account.profile') }}"
                           class="flex items-center gap-1.5 px-2.5 py-2 text-[10px] font-extrabold uppercase tracking-[0.15em] text-[#EDE5D8] bg-[#0C0A09] polygon-btn">
                            <i data-lucide="user-cog" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                            {{ __('Profile') }}
                        </a>
                    </div>

                    @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center justify-center gap-1.5 text-[11px] text-[#A8895F] border border-[#A8895F]/50 px-3 py-2.5 polygon-btn bg-[#17130F] font-extrabold uppercase tracking-[0.2em]">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        {{ __('Admin Portal') }}
                    </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="block w-full">
                        @csrf
                        <button type="submit"
                                class="flex items-center justify-center gap-2 w-full px-3 py-2.5 text-[11px] font-extrabold uppercase tracking-[0.2em] text-white bg-rose-700 polygon-btn shadow">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            {{ __('Sign Out') }}
                        </button>
                    </form>
                </div>
            @endguest
        </div>
    </header>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed bottom-5 left-5 z-50 max-w-[calc(100vw-2.5rem)] bg-[#17130F] text-[#EDE5D8] border-2 border-[#A8895F] p-4 polygon-card shadow-2xl flex items-center gap-3 backdrop-blur-md">
        <i data-lucide="check-circle-2" class="w-5 h-5 text-[#A8895F] shrink-0"></i>
        <span class="text-xs font-bold tracking-wider">{{ session('success') }}</span>
        <button @click="show = false" class="ml-4 text-[#A89C8C] hover:text-[#F8F5EF]">&times;</button>
    </div>
    @endif

    <!-- FLOATING WHATSAPP CONCIERGE -->
    @if(config('payment.whatsapp.enabled') && config('payment.whatsapp.phone_number'))
    <a href="https://wa.me/{{ config('payment.whatsapp.phone_number') }}?text={{ rawurlencode(__('Hello Sozie Collection! I would like some help with your products.')) }}"
       target="_blank" rel="noopener"
       class="group fixed bottom-6 right-5 z-40 inline-flex items-center focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D4AF37] focus-visible:ring-offset-2 focus-visible:ring-offset-[#0C0A09] sm:right-6"
       aria-label="{{ __('Chat with us on WhatsApp') }}"
       title="{{ __('Chat with us on WhatsApp') }}">

        <span class="pointer-events-none absolute -inset-2 -z-10 rounded-full bg-[#D4AF37]/25 blur-2xl animate-pulse-glow" aria-hidden="true"></span>

        <span class="relative flex h-14 w-14 items-center justify-center overflow-hidden border border-[#C5A059]/40 bg-gradient-to-br from-[#221D19] via-[#151210] to-[#0C0A09] shadow-[0_18px_40px_-14px_rgba(18,16,14,0.7)] transition-all duration-500 group-hover:-translate-y-0.5 group-hover:border-[#D4AF37]/70 group-hover:shadow-[0_22px_55px_-14px_rgba(212,175,55,0.45)]"
              style="clip-path: polygon(14px 0, 100% 0, 100% calc(100% - 14px), calc(100% - 14px) 100%, 0 100%, 0 14px);">

            <span class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-[#F3E5AB]/20 to-transparent transition-transform duration-[900ms] ease-out group-hover:translate-x-full" aria-hidden="true"></span>

            <i data-lucide="message-circle" class="relative h-7 w-7 text-[#25D366]" aria-hidden="true"></i>
        </span>
    </a>
    @endif

    <!-- MAIN CONTENT -->
    <main class="flex-grow z-10 relative">
        @yield('content')
    </main>

    <!-- SLIDE-OVER CART DRAWER -->
    <div x-show="cartOpen" class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="cartOpen = false"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div class="w-screen max-w-md glass-panel border-l border-[#322B23] shadow-2xl flex flex-col justify-between p-6 bg-[#17130F]">

                <!-- Drawer Header -->
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-[#322B23]">
                        <div class="flex items-center gap-2">
                            <i data-lucide="shopping-bag" class="w-5 h-5 text-[#A8895F]"></i>
                            <h2 class="font-serif font-bold text-xl tracking-wider text-[#EDE5D8]">{{ __('YOUR SELECTION') }}</h2>
                        </div>
                        <button @click="cartOpen = false" class="text-[#A89C8C] hover:text-[#F8F5EF]">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <!-- Free Shipping Progress Bar -->
                    <div class="py-3 px-1 border-b border-[#322B23]">
                        <div class="flex justify-between text-[11px] font-bold mb-1">
                            <span class="text-[#B5A897]" x-text="total >= 100000 ? @js(__('🎉 Free Delivery unlocked!')) : @js(__('Add TZS :amount more for Free Delivery!')).replace(':amount', Number(100000 - total).toLocaleString())"></span>
                            <span class="text-[#A8895F]" x-text="Math.min(100, Math.round((total / 100000) * 100)) + '%'"></span>
                        </div>
                        <div class="w-full h-2 bg-[#0C0A09] rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-[#C5A059] to-[#A8895F] transition-all duration-500" :style="'width: ' + Math.min(100, (total / 100000) * 100) + '%'"></div>
                        </div>
                    </div>
                </div>

                <!-- Items List -->
                <div class="flex-grow overflow-y-auto py-4 space-y-4 pr-1">
                    <template x-if="cartItems.length === 0">
                        <div class="text-center py-16 text-[#B5A897]">
                            <i data-lucide="sparkles" class="w-12 h-12 text-[#A8895F] mx-auto mb-3"></i>
                            <p class="font-serif text-lg font-bold text-[#EDE5D8]">{{ __('Your cart is currently empty.') }}</p>
                            <a href="{{ route('shop.index') }}" @click="cartOpen = false"
                               class="inline-block mt-4 text-xs font-bold uppercase tracking-widest text-[#A8895F] underline hover:text-[#F8F5EF]">
                                {{ __('EXPLORE COLLECTION') }}
                            </a>
                        </div>
                    </template>

                    <template x-for="item in cartItems" :key="item.cart_key">
                        <div class="navy-card p-3 polygon-card flex gap-3 relative border border-[#322B23] bg-[#17130F]">
                            <img :src="item.image" :alt="item.name" data-sozie-fallback loading="lazy" decoding="async" class="w-16 h-16 object-cover polygon-card border border-[#322B23]">
                            <div class="flex-grow">
                                <h4 class="font-serif font-bold text-sm text-[#EDE5D8]" x-text="item.name"></h4>
                                <span class="text-[10px] text-[#A8895F] font-extrabold tracking-wider uppercase block" x-text="item.size"></span>
                                <span class="text-sm sm:text-xs text-[#B5A897] font-extrabold" x-text="'TZS ' + Number(item.price).toLocaleString()"></span>

                                <div class="flex items-center gap-2 mt-2">
                                    <button @click="updateQuantity(item.cart_key, item.quantity - 1)"
                                            class="w-5 h-5 bg-[#0C0A09] text-[#EDE5D8] rounded flex items-center justify-center text-xs font-bold hover:bg-[#A8895F] hover:text-[#12100E]">-</button>
                                    <span class="text-xs font-bold text-[#EDE5D8] px-1" x-text="item.quantity"></span>
                                    <button @click="updateQuantity(item.cart_key, item.quantity + 1)"
                                            class="w-5 h-5 bg-[#0C0A09] text-[#EDE5D8] rounded flex items-center justify-center text-xs font-bold hover:bg-[#A8895F] hover:text-[#12100E]">+</button>
                                </div>
                            </div>
                            <button @click="removeItem(item.cart_key)" class="text-[#A89C8C] hover:text-rose-400 p-1">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Footer Summary & Checkout CTA -->
                <div class="pt-4 border-t border-[#322B23] space-y-4">
                    <div class="flex justify-between items-center text-sm font-bold tracking-wider">
                        <span class="text-[#B5A897]">{{ __('ESTIMATED TOTAL') }}</span>
                        <span class="font-serif text-xl font-bold text-[#A8895F]" x-text="formattedTotal"></span>
                    </div>

                    <div class="space-y-2">
                        <a href="{{ route('checkout.index') }}"
                           class="w-full py-3.5 bg-[#A8895F] text-[#12100E] font-extrabold text-xs uppercase tracking-[0.2em] polygon-btn text-center block shadow-lg hover:bg-[#12100E] hover:text-[#F8F5EF]">
                            {{ __('PROCEED TO CHECKOUT') }}
                        </a>
                        <a href="{{ route('cart.index') }}"
                           class="w-full py-2.5 bg-[#17130F] border border-[#322B23] text-[#EDE5D8] font-bold text-xs uppercase tracking-wider polygon-btn text-center block hover:bg-[#221D19]">
                            {{ __('VIEW FULL CART') }}
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- WISHLIST DRAWER -->
    <div x-show="wishlistOpen" class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="wishlistOpen = false"></div>
        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div class="w-screen max-w-md glass-panel border-l border-[#322B23] shadow-2xl flex flex-col justify-between p-6 bg-[#17130F]">

                <div class="flex items-center justify-between pb-4 border-b border-[#322B23]">
                    <div class="flex items-center gap-2">
                        <i data-lucide="heart" class="w-5 h-5 text-[#A8895F]"></i>
                        <h2 class="font-serif font-bold text-xl tracking-wider text-[#EDE5D8]">{{ __('SAVED WISHLIST') }}</h2>
                    </div>
                    <button @click="wishlistOpen = false" class="text-[#A89C8C] hover:text-[#F8F5EF]">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <div class="flex-grow overflow-y-auto py-4 space-y-4">
                    <div x-show="!wishlistLoggedIn && wishlist.length > 0" class="mb-4 p-3 bg-[#241B0A] border border-[#78350F] text-amber-300 polygon-card text-[11px] leading-relaxed" style="display: none;">
                        <div class="flex items-start gap-2.5">
                            <i data-lucide="key-round" class="w-4 h-4 text-amber-400 flex-shrink-0 mt-0.5"></i>
                            <div class="flex-grow">
                                <p class="font-extrabold mb-1 uppercase tracking-[0.2em] text-[10px] text-amber-300">{{ __('Cross-Device Save Not Active') }}</p>
                                <p class="font-medium">{{ __('This wishlist is currently stored') }} <strong>{{ __('only on this browser/device') }}</strong>. {{ __('Create or sign into your Sozie Collection account to access these saved scents from phone, tablet, and desktop.') }}</p>
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <a href="{{ route('register') }}" class="inline-block px-3 py-1.5 bg-[#A8895F] text-[#12100E] text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn shadow-sm">{{ __('Create Account') }}</a>
                                    <a href="{{ route('login') }}" class="inline-block px-3 py-1.5 bg-[#17130F] border border-[#322B23] text-[#EDE5D8] text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn">{{ __('Sign In') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <template x-if="wishlist.length === 0">
                        <div class="text-center py-16 text-[#B5A897]">
                            <i data-lucide="heart" class="w-12 h-12 text-[#A8895F] mx-auto mb-3"></i>
                            <p class="font-serif text-lg font-bold text-[#EDE5D8]">{{ __('Your wishlist is empty.') }}</p>
                            <p class="text-xs text-[#A89C8C] mt-1">{{ __('Tap the heart icon on any perfume card to save items.') }}</p>
                        </div>
                    </template>

                    <template x-for="item in wishlistItems" :key="item.id">
                        <div class="navy-card p-3 polygon-card flex gap-3 relative border border-[#322B23] bg-[#17130F]">
                            <img :src="item.image" data-sozie-fallback loading="lazy" decoding="async" class="w-16 h-16 object-cover polygon-card border border-[#322B23]">
                            <div class="flex-grow">
                                <h4 class="font-serif font-bold text-sm text-[#EDE5D8]" x-text="item.name"></h4>
                                <span class="text-sm sm:text-xs text-[#A8895F] font-extrabold" x-text="item.formatted_price"></span>
                                <div class="flex gap-2 mt-2">
                                    <button @click="addToCart(item.id); toggleWishlist(item)" class="px-3 py-1 bg-[#A8895F] text-[#12100E] text-[10px] font-bold uppercase polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF]">
                                        {{ __('Move to Cart') }}
                                    </button>
                                </div>
                            </div>
                            <button @click="toggleWishlist(item)" class="text-[#A89C8C] hover:text-rose-400 p-1">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </template>
                </div>

            </div>
        </div>
    </div>

    <!-- QUICK VIEW MODAL WITH INTERACTIVE VARIANT SELECTOR -->
    <div x-show="quickViewOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-md" @click="quickViewOpen = false"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative max-w-2xl w-full glass-panel-gold p-6 sm:p-8 polygon-card border border-[#A8895F]/50 shadow-2xl z-10 bg-[#17130F]" @click.stop>
                <button @click="quickViewOpen = false" class="absolute top-4 right-4 text-[#A89C8C] hover:text-[#F8F5EF]">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>

                <template x-if="quickViewData">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <img :src="quickViewData.image" data-sozie-fallback loading="lazy" decoding="async" class="w-full h-64 object-cover polygon-card border border-[#322B23]">
                        <div class="space-y-3">
                            <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-widest" x-text="quickViewData.category + ' • ' + quickViewData.concentration"></span>
                            <h3 class="font-serif font-bold text-2xl text-[#EDE5D8]" x-text="quickViewData.name"></h3>
                            <span class="font-serif font-bold text-xl text-[#A8895F] block" x-text="quickViewSelectedPrice || quickViewData.formatted_price"></span>
                            <p class="text-xs text-[#B5A897] line-clamp-2 leading-relaxed font-medium" x-text="quickViewData.description"></p>

                            <!-- Variant Size Pills -->
                            <template x-if="quickViewData.variants && quickViewData.variants.length > 0">
                                <div>
                                    <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-wider block mb-1">{{ __('Select Size') }}</span>
                                    <div class="flex flex-wrap gap-1.5">
                                        <template x-for="v in quickViewData.variants" :key="v.size">
                                            <button @click="quickViewSize = v.size; quickViewSelectedPrice = v.formatted_price"
                                                    :class="quickViewSize === v.size ? 'bg-[#A8895F] text-[#12100E] font-bold' : 'bg-[#17130F] text-[#EDE5D8] border border-[#322B23]'"
                                                    class="px-2.5 py-1 text-[10px] polygon-btn transition-all font-bold">
                                                <span x-text="v.size"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <div class="text-[11px] text-[#B5A897] space-y-1 pt-2 border-t border-[#322B23] font-medium">
                                <div><strong class="text-[#A8895F] font-bold">{{ __('Top Notes:') }}</strong> <span x-text="quickViewData.top_notes"></span></div>
                                <div><strong class="text-[#A8895F] font-bold">{{ __('Heart Notes:') }}</strong> <span x-text="quickViewData.heart_notes"></span></div>
                                <div><strong class="text-[#A8895F] font-bold">{{ __('Base Notes:') }}</strong> <span x-text="quickViewData.base_notes"></span></div>
                            </div>

                            <div class="pt-3 flex gap-2">
                                <button @click="addToCart(quickViewData.id, quickViewSize); quickViewOpen = false" class="flex-grow py-3 bg-[#A8895F] text-[#12100E] font-extrabold text-xs uppercase polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF]">
                                    {{ __('ADD TO CART') }}
                                </button>
                                <a :href="'/product/' + quickViewData.slug" class="px-4 py-3 bg-[#0C0A09] text-[#EDE5D8] font-bold text-xs uppercase polygon-btn hover:bg-[#2C2620]">
                                    {{ __('Full Page') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- GLOBAL FOOTER -->
    <footer class="obsidian-footer-bg relative text-[#F8F5EF] z-10 overflow-hidden pt-1">
        <!-- Top Metallic Gold Shimmer Hairline -->
        <div class="h-[2px] bg-gradient-to-r from-transparent via-[#D4AF37] to-transparent w-full"></div>

        <!-- LUXURY TRUST GUARANTEE BANNER -->
        <div class="border-b border-[#C5A059]/20 bg-[#171411]/90 backdrop-blur-md py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="flex items-center gap-3.5 p-3 rounded bg-white/[0.02] border border-[#C5A059]/10">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#D4AF37]/20 to-[#A8895F]/10 border border-[#D4AF37]/30 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="gem" class="w-5 h-5 text-[#D4AF37]"></i>
                        </div>
                        <div>
                            <h5 class="text-xs font-serif font-bold tracking-wider text-[#FFF5D0] uppercase">{{ __('100% Authentic') }}</h5>
                            <p class="text-[10px] text-[#C5A059] font-medium">{{ __('Artisanal Niche Perfumery') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3.5 p-3 rounded bg-white/[0.02] border border-[#C5A059]/10">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#D4AF37]/20 to-[#A8895F]/10 border border-[#D4AF37]/30 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="truck" class="w-5 h-5 text-[#D4AF37]"></i>
                        </div>
                        <div>
                            <h5 class="text-xs font-serif font-bold tracking-wider text-[#FFF5D0] uppercase">{{ __('VIP Express Delivery') }}</h5>
                            <p class="text-[10px] text-[#C5A059] font-medium">{{ __('Fast Shipping Across Tanzania') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3.5 p-3 rounded bg-white/[0.02] border border-[#C5A059]/10">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#D4AF37]/20 to-[#A8895F]/10 border border-[#D4AF37]/30 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="gift" class="w-5 h-5 text-[#D4AF37]"></i>
                        </div>
                        <div>
                            <h5 class="text-xs font-serif font-bold tracking-wider text-[#FFF5D0] uppercase">{{ __('Complimentary Samples') }}</h5>
                            <p class="text-[10px] text-[#C5A059] font-medium">{{ __('Included With Every Order') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3.5 p-3 rounded bg-white/[0.02] border border-[#C5A059]/10">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#D4AF37]/20 to-[#A8895F]/10 border border-[#D4AF37]/30 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="shield-check" class="w-5 h-5 text-[#D4AF37]"></i>
                        </div>
                        <div>
                            <h5 class="text-xs font-serif font-bold tracking-wider text-[#FFF5D0] uppercase">{{ __('Secure Payments') }}</h5>
                            <p class="text-[10px] text-[#C5A059] font-medium">{{ __('Mobile Money & COD Available') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-14">

                <!-- Col 1: Brand Info -->
                <div class="space-y-5">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#D4AF37] via-[#A8895F] to-[#12100E] polygon-card flex items-center justify-center p-[1px] shadow-lg">
                            <div class="w-full h-full bg-[#12100E] polygon-card flex items-center justify-center">
                                <span class="font-serif font-bold text-lg text-[#D4AF37]">S</span>
                            </div>
                        </div>
                        <div>
                            <span class="font-serif font-bold text-2xl tracking-[0.2em] text-transparent bg-clip-text bg-gradient-to-r from-[#FFF5D0] via-[#D4AF37] to-[#A8895F] block">SOZIE</span>
                            <span class="block text-[9px] tracking-[0.4em] text-[#C5A059] uppercase -mt-1 font-black">PARFUMERIE DE LUXE</span>
                        </div>
                    </div>

                    <p class="text-xs text-[#A89C8C] leading-relaxed font-normal pr-2">
                        {{ __('Exclusive haute parfumerie campaign & luxury sensory e-commerce destination. Every creation is meticulously crafted to evoke timeless elegance and leave an unforgettable signature aura.') }}
                    </p>

                    <div class="flex space-x-3 pt-1">
                        <!-- Instagram -->
                        <a href="https://instagram.com" target="_blank" title="Instagram" class="w-9 h-9 rounded bg-white/[0.04] border border-[#C5A059]/30 flex items-center justify-center text-[#FFF5D0] hover:text-[#D4AF37] hover:border-[#D4AF37] hover:bg-[#D4AF37]/10 transition-all shadow-md">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>

                        <!-- Facebook -->
                        <a href="https://facebook.com" target="_blank" title="Facebook" class="w-9 h-9 rounded bg-white/[0.04] border border-[#C5A059]/30 flex items-center justify-center text-[#FFF5D0] hover:text-[#D4AF37] hover:border-[#D4AF37] hover:bg-[#D4AF37]/10 transition-all shadow-md">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>

                        <!-- TikTok -->
                        <a href="https://tiktok.com" target="_blank" title="TikTok" class="w-9 h-9 rounded bg-white/[0.04] border border-[#C5A059]/30 flex items-center justify-center text-[#FFF5D0] hover:text-[#D4AF37] hover:border-[#D4AF37] hover:bg-[#D4AF37]/10 transition-all shadow-md">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.29-2.71.74-5.43 2.78-7.14 1.41-1.18 3.23-1.8 5.06-1.8.15 0 .3 0 .45.01v4.03c-.27-.04-.54-.05-.81-.03-1.07.03-2.11.49-2.83 1.25-.82.83-1.19 2.02-1.01 3.18.19 1.25 1.05 2.3 2.22 2.65.87.27 1.82.16 2.61-.28.87-.47 1.47-1.32 1.62-2.31.07-.63.05-1.28.05-1.92V.02z"/>
                            </svg>
                        </a>

                        <!-- WhatsApp -->
                        <a href="https://wa.me/{{ config('payment.whatsapp.phone_number') }}" target="_blank" title="{{ __('WhatsApp Concierge') }}" class="w-9 h-9 rounded bg-emerald-950/80 border border-emerald-600/50 flex items-center justify-center text-emerald-300 hover:bg-emerald-900 transition-all shadow-md">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.631.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Collections -->
                <div>
                    <h4 class="font-serif font-bold text-sm tracking-[0.25em] text-[#D4AF37] uppercase mb-5 flex items-center gap-2">
                        <span>{{ __('COLLECTIONS') }}</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
                    </h4>
                    <ul class="space-y-3 text-xs text-[#A89C8C] font-medium">
                        <li><a href="{{ route('shop.index', ['gender' => 'women']) }}" class="hover:text-[#FFF5D0] transition-colors flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-[#C5A059]/40 group-hover:bg-[#D4AF37] transition-colors"></span> {{ __("Women's Perfumes") }}</a></li>
                        <li><a href="{{ route('shop.index', ['gender' => 'men']) }}" class="hover:text-[#FFF5D0] transition-colors flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-[#C5A059]/40 group-hover:bg-[#D4AF37] transition-colors"></span> {{ __("Men's Perfumes") }}</a></li>
                        <li><a href="{{ route('shop.index', ['gender' => 'unisex']) }}" class="hover:text-[#FFF5D0] transition-colors flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-[#C5A059]/40 group-hover:bg-[#D4AF37] transition-colors"></span> {{ __('Unisex Signature') }}</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'perfume-oils']) }}" class="hover:text-[#FFF5D0] transition-colors flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-[#C5A059]/40 group-hover:bg-[#D4AF37] transition-colors"></span> {{ __('Concentrated Oils') }}</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'perfume-gift-sets']) }}" class="hover:text-[#FFF5D0] transition-colors flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-[#C5A059]/40 group-hover:bg-[#D4AF37] transition-colors"></span> {{ __('Luxury Gift Boxes') }}</a></li>
                    </ul>
                </div>

                <!-- Col 3: Customer Care -->
                <div>
                    <h4 class="font-serif font-bold text-sm tracking-[0.25em] text-[#D4AF37] uppercase mb-5 flex items-center gap-2">
                        <span>{{ __('CUSTOMER CARE') }}</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
                    </h4>
                    <ul class="space-y-3 text-xs text-[#A89C8C] font-medium">
                        <li><a href="{{ route('orders.track') }}" class="hover:text-[#FFF5D0] transition-colors flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-[#C5A059]/40 group-hover:bg-[#D4AF37] transition-colors"></span> {{ __('Track Order') }}</a></li>
                        <li><a href="{{ route('home') }}#scent-finder" class="hover:text-[#FFF5D0] transition-colors flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-[#C5A059]/40 group-hover:bg-[#D4AF37] transition-colors"></span> {{ __('Fragrance Finder') }}</a></li>
                        <li><a href="https://wa.me/{{ config('payment.whatsapp.phone_number') }}" target="_blank" class="hover:text-[#FFF5D0] transition-colors flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-[#C5A059]/40 group-hover:bg-[#D4AF37] transition-colors"></span> {{ __('VIP WhatsApp Concierge') }}</a></li>
                        <li><a href="{{ route('account.dashboard') }}" class="hover:text-[#FFF5D0] transition-colors flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-[#C5A059]/40 group-hover:bg-[#D4AF37] transition-colors"></span> {{ __('My Account Dashboard') }}</a></li>
                    </ul>
                </div>

                <!-- Col 4: VIP Newsletter Box -->
                <div>
                    <div class="p-5 rounded-lg border border-[#C5A059]/30 bg-[#1A1613]/80 polygon-card shadow-2xl backdrop-blur-md">
                        <h4 class="font-serif font-bold text-sm tracking-[0.2em] text-[#D4AF37] uppercase mb-2">{{ __('THE VIP CIRCLE') }}</h4>
                        <p class="text-[11px] text-[#A89C8C] mb-4 font-normal leading-relaxed">
                            {{ __('Subscribe for exclusive private access to unreleased perfume launches and private campaign invitations.') }}
                        </p>
                        <form @submit.prevent="alert(@js(__('Thank you for joining Sozie Collection VIP Circle!')))" class="space-y-3">
                            <input type="email" placeholder="{{ __('Enter your email...') }}" required class="w-full bg-[#12100E] border border-[#C5A059]/40 text-xs text-[#FFF5D0] px-3.5 py-2.5 focus:outline-none focus:border-[#D4AF37] rounded font-medium placeholder-[#A89C8C]">
                            <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-[#A8895F] via-[#D4AF37] to-[#A8895F] hover:from-[#D4AF37] hover:to-[#A8895F] text-[#12100E] font-black text-[10px] uppercase tracking-[0.25em] polygon-btn transition-all shadow-lg">
                                {{ __('JOIN VIP CIRCLE') }}
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Footer Bottom Line -->
            <div class="gold-line-glow h-[1px] w-full mb-8"></div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-[11px] text-[#C5A059] font-medium">
                <p>&copy; {{ date('Y') }} {{ __('SOZIE COLLECTION') }}. {{ __('All rights reserved. Haute Parfumerie & Luxury E-Commerce.') }}</p>

                <div class="flex flex-wrap items-center gap-3 text-[10px]">
                    <span class="px-2 py-1 rounded bg-white/[0.03] border border-[#C5A059]/20 text-[#FFF5D0]">{{ __('Dar es Salaam, Tanzania') }}</span>
                    <span class="px-2 py-1 rounded bg-white/[0.03] border border-[#C5A059]/20 text-[#FFF5D0]">{{ __('M-Pesa & Tigo Pesa') }}</span>
                    <span class="px-2 py-1 rounded bg-white/[0.03] border border-[#C5A059]/20 text-[#FFF5D0]">{{ __('Cash On Delivery') }}</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- ALPINE JS CONTROLLER SCRIPT -->
    <script>
        function sozieApp() {
            return {
                cartOpen: false,
                wishlistOpen: false,
                mobileMenuOpen: false,
                quickViewOpen: false,
                quickViewData: null,
                quickViewSize: null,
                quickViewSelectedPrice: null,
                cartCount: 0,
                cartItems: [],
                total: 0,
                formattedTotal: 'TZS 0',
                wishlist: JSON.parse(localStorage.getItem('sozie_wishlist') || '[]'),
                wishlistItems: [],
                wishlistLoggedIn: false,

                initApp() {
                    this.fetchCart();
                    this.fetchLoggedInWishlist();
                    this.syncWishlistItems();
                    sozieIcons();
                },

                fetchCart() {
                    fetch('{{ route("api.cart") }}')
                        .then(res => res.json())
                        .then(data => {
                            this.cartItems = data.cart || [];
                            this.cartCount = data.cart_count || 0;
                            this.total = data.total || 0;
                            this.formattedTotal = data.formatted_total || 'TZS 0';
                            this.$nextTick(() => sozieIcons());
                        });
                },

                fetchLoggedInWishlist() {
                    fetch('{{ route("wishlist.index_api") }}', {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.logged_in === true) {
                                this.wishlistLoggedIn = true;
                                this.wishlist = data.items || [];
                                localStorage.setItem('sozie_wishlist', JSON.stringify(this.wishlist));
                                this.syncWishlistItems();
                                this.$nextTick(() => sozieIcons());
                            } else {
                                this.wishlistLoggedIn = false;
                            }
                        })
                        .catch(() => { this.wishlistLoggedIn = false; });
                },

                addToCart(productId, size = null, quantity = 1) {
                    fetch('{{ route("cart.add") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ product_id: productId, size: size, quantity: quantity })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.cartItems = data.cart;
                        this.cartCount = data.cart_count;
                        this.total = data.total;
                        this.formattedTotal = 'TZS ' + Number(data.total).toLocaleString();
                        this.cartOpen = true;
                        this.$nextTick(() => sozieIcons());
                    });
                },

                updateQuantity(cartKey, newQty) {
                    if (newQty < 1) {
                        this.removeItem(cartKey);
                        return;
                    }
                    fetch('{{ route("cart.update") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ cart_key: cartKey, quantity: newQty })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.cartItems = data.cart;
                        this.cartCount = data.cart_count;
                        this.total = data.total;
                        this.formattedTotal = 'TZS ' + Number(data.total).toLocaleString();
                    });
                },

                removeItem(cartKey) {
                    fetch('{{ route("cart.remove") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ cart_key: cartKey })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.cartItems = data.cart;
                        this.cartCount = data.cart_count;
                        this.total = data.total;
                        this.formattedTotal = 'TZS ' + Number(data.total).toLocaleString();
                    });
                },

                openQuickView(productId) {
                    fetch('/api/product/' + productId + '/quickview')
                        .then(res => res.json())
                        .then(data => {
                            this.quickViewData = data;
                            this.quickViewSize = data.variants && data.variants.length > 0 ? data.variants[0].size : null;
                            this.quickViewSelectedPrice = data.variants && data.variants.length > 0 ? data.variants[0].formatted_price : data.formatted_price;
                            this.quickViewOpen = true;
                            this.$nextTick(() => sozieIcons());
                        });
                },

                toggleWishlist(product) {
                    const localItem = {
                        id: product.id,
                        name: product.name,
                        formatted_price: product.formatted_price || ('TZS ' + Number(product.price || 0).toLocaleString()),
                        image: product.image || product.primary_image
                    };

                    if (! this.wishlistLoggedIn) {
                        const idx = this.wishlist.findIndex(item => Number(item.id) === Number(product.id));
                        if (idx > -1) {
                            this.wishlist.splice(idx, 1);
                        } else {
                            this.wishlist.push(localItem);
                        }
                        localStorage.setItem('sozie_wishlist', JSON.stringify(this.wishlist));
                        this.syncWishlistItems();
                        this.$nextTick(() => sozieIcons());
                        return;
                    }

                    fetch('{{ route("wishlist.toggle") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ product_id: product.id })
                    })
                        .then(async res => {
                            if (res.status === 401) {
                                this.wishlistLoggedIn = false;
                                const idx = this.wishlist.findIndex(item => Number(item.id) === Number(product.id));
                                if (idx > -1) this.wishlist.splice(idx, 1);
                                else this.wishlist.push(localItem);
                                localStorage.setItem('sozie_wishlist', JSON.stringify(this.wishlist));
                                this.syncWishlistItems();
                                this.$nextTick(() => sozieIcons());
                                return null;
                            }
                            return res.json();
                        })
                        .then(data => {
                            if (! data) return;
                            if (data.logged_in === false) {
                                this.wishlistLoggedIn = false;
                                const idx = this.wishlist.findIndex(item => Number(item.id) === Number(product.id));
                                if (idx > -1) this.wishlist.splice(idx, 1);
                                else this.wishlist.push(localItem);
                                localStorage.setItem('sozie_wishlist', JSON.stringify(this.wishlist));
                                this.syncWishlistItems();
                                this.$nextTick(() => sozieIcons());
                                return;
                            }
                            this.fetchLoggedInWishlist();
                        })
                        .catch(() => {
                            this.wishlistLoggedIn = false;
                            const idx = this.wishlist.findIndex(item => Number(item.id) === Number(product.id));
                            if (idx > -1) this.wishlist.splice(idx, 1);
                            else this.wishlist.push(localItem);
                            localStorage.setItem('sozie_wishlist', JSON.stringify(this.wishlist));
                            this.syncWishlistItems();
                            this.$nextTick(() => sozieIcons());
                        });
                },

                isInWishlist(productId) {
                    return this.wishlist.some(item => item.id === productId);
                },

                syncWishlistItems() {
                    this.wishlistItems = this.wishlist;
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
