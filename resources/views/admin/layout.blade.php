<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <meta name="theme-color" content="#EDE5D8">
    <title>@yield('page_title', 'Dashboard') | Admin | Sozie Collection</title>

    {{-- Guarded icon renderer: defined before Alpine boots and safe to call even when
         the icon script is unavailable, so a CDN/network failure can never break a
         feature. Lucide itself is self-hosted (public/vendor/lucide.min.js). --}}
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

    <script src="{{ asset('vendor/lucide.min.js') }}" defer onerror="void 0"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ adminMenuOpen: false }" class="bg-[#EDE5D8] text-[#29241F] font-sans min-h-screen flex">

    <div x-show="adminMenuOpen" @click="adminMenuOpen = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden" style="display: none;"></div>

    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-[#F8F5EF] border-r border-[#D8C9B8] flex flex-col justify-between p-6 flex-shrink-0 shadow-sm overflow-y-auto transform transition-transform duration-300 lg:static lg:translate-x-0"
           :class="adminMenuOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="space-y-8">
            <div class="flex items-center justify-between lg:hidden">
                <span class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-[#A8895F]">Navigation</span>
                <button type="button" @click="adminMenuOpen = false" class="p-2 text-[#A8895F]" aria-label="Close navigation">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        <div class="space-y-8">

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 bg-gradient-to-br from-[#A8895F] via-[#D8C9B8] to-[#29241F] polygon-card flex items-center justify-center p-[1px] shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <div class="w-full h-full bg-[#29241F] polygon-card flex items-center justify-center">
                        <span class="font-serif font-bold text-base text-[#A8895F]">S</span>
                    </div>
                </div>
                <div>
                    <span class="font-serif font-bold text-lg tracking-wider text-[#29241F] block">SOZIE ADMIN</span>
                    <span class="text-[9px] text-[#A8895F] tracking-[0.2em] uppercase font-extrabold block -mt-1">Management Hub</span>
                </div>
            </a>

            <nav class="space-y-4 text-xs font-bold tracking-wider uppercase"
                 x-data="{
                     collapsedGroups: [],
                     init() {
                         try {
                             this.collapsedGroups = JSON.parse(localStorage.getItem('sozie-admin-menu-groups') || '[]');
                         } catch (error) {
                             this.collapsedGroups = [];
                         }
                     },
                     isCollapsed(group) {
                         return this.collapsedGroups.includes(group);
                     },
                     toggleGroup(group) {
                         this.collapsedGroups = this.isCollapsed(group)
                             ? this.collapsedGroups.filter((item) => item !== group)
                             : [...this.collapsedGroups, group];

                         localStorage.setItem('sozie-admin-menu-groups', JSON.stringify(this.collapsedGroups));
                     }
                 }">

                <div>
                    <button type="button" @click="toggleGroup('operations')" class="w-full flex items-center justify-between px-3 py-2 text-[#A8895F] border-b border-[#D8C9B8] text-[10px] font-extrabold tracking-[0.2em]">
                        <span>Daily Operations</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform" :class="isCollapsed('operations') ? '-rotate-90' : ''"></i>
                    </button>
                    <div x-show="!isCollapsed('operations')" class="space-y-2 pt-2">
                        <a href="{{ route('admin.dashboard') }}" @click="adminMenuOpen = false"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded polygon-btn transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#29241F] text-[#EDE5D8] font-extrabold shadow-sm' : 'text-[#29241F] hover:text-[#A8895F] hover:bg-[#A8895F]/10' }}">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 text-[#A8895F]"></i>
                            <span>Dashboard</span>
                        </a>

                        @can('products')
                        <a href="{{ route('admin.products') }}" @click="adminMenuOpen = false"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded polygon-btn transition-all {{ request()->routeIs('admin.products*') ? 'bg-[#29241F] text-[#EDE5D8] font-extrabold shadow-sm' : 'text-[#29241F] hover:text-[#A8895F] hover:bg-[#A8895F]/10' }}">
                            <i data-lucide="package" class="w-4 h-4 text-[#A8895F]"></i>
                            <span>Products Catalog</span>
                        </a>
                        @endcan

                        @can('orders')
                        <a href="{{ route('admin.orders') }}" @click="adminMenuOpen = false"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded polygon-btn transition-all {{ request()->routeIs('admin.orders*') ? 'bg-[#29241F] text-[#EDE5D8] font-extrabold shadow-sm' : 'text-[#29241F] hover:text-[#A8895F] hover:bg-[#A8895F]/10' }}">
                            <i data-lucide="shopping-cart" class="w-4 h-4 text-[#A8895F]"></i>
                            <span>Orders</span>
                        </a>
                        @endcan

                        @can('customers')
                        <a href="{{ route('admin.customers') }}" @click="adminMenuOpen = false"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded polygon-btn transition-all {{ request()->routeIs('admin.customers*') ? 'bg-[#29241F] text-[#EDE5D8] font-extrabold shadow-sm' : 'text-[#29241F] hover:text-[#A8895F] hover:bg-[#A8895F]/10' }}">
                            <i data-lucide="users" class="w-4 h-4 text-[#A8895F]"></i>
                            <span>Customers</span>
                        </a>
                        @endcan
                    </div>
                </div>

                <div>
                    <button type="button" @click="toggleGroup('growth')" class="w-full flex items-center justify-between px-3 py-2 text-[#A8895F] border-b border-[#D8C9B8] text-[10px] font-extrabold tracking-[0.2em]">
                        <span>Growth & Reports</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform" :class="isCollapsed('growth') ? '-rotate-90' : ''"></i>
                    </button>
                    <div x-show="!isCollapsed('growth')" class="space-y-2 pt-2">
                        @can('marketing')
                        <a href="{{ route('admin.marketing') }}" @click="adminMenuOpen = false"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded polygon-btn transition-all {{ request()->routeIs('admin.marketing*') ? 'bg-[#29241F] text-[#EDE5D8] font-extrabold shadow-sm' : 'text-[#29241F] hover:text-[#A8895F] hover:bg-[#A8895F]/10' }}">
                            <i data-lucide="megaphone" class="w-4 h-4 text-[#A8895F]"></i>
                            <span>Marketing</span>
                        </a>
                        @endcan

                        @can('reports')
                        <a href="{{ route('admin.reports') }}" @click="adminMenuOpen = false"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded polygon-btn transition-all {{ request()->routeIs('admin.reports*') ? 'bg-[#29241F] text-[#EDE5D8] font-extrabold shadow-sm' : 'text-[#29241F] hover:text-[#A8895F] hover:bg-[#A8895F]/10' }}">
                            <i data-lucide="bar-chart-3" class="w-4 h-4 text-[#A8895F]"></i>
                            <span>Reports</span>
                        </a>
                        @endcan
                    </div>
                </div>

                <div>
                    <button type="button" @click="toggleGroup('setup')" class="w-full flex items-center justify-between px-3 py-2 text-[#A8895F] border-b border-[#D8C9B8] text-[10px] font-extrabold tracking-[0.2em]">
                        <span>Store Setup</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform" :class="isCollapsed('setup') ? '-rotate-90' : ''"></i>
                    </button>
                    <div x-show="!isCollapsed('setup')" class="space-y-2 pt-2">
                        @can('homepage_content')
                        <a href="{{ route('admin.content') }}" @click="adminMenuOpen = false"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded polygon-btn transition-all {{ request()->routeIs('admin.content*') ? 'bg-[#29241F] text-[#EDE5D8] font-extrabold shadow-sm' : 'text-[#29241F] hover:text-[#A8895F] hover:bg-[#A8895F]/10' }}">
                            <i data-lucide="layout-template" class="w-4 h-4 text-[#A8895F]"></i>
                            <span>Website Content</span>
                        </a>
                        @endcan

                        @can('admin_users')
                        <a href="{{ route('admin.users') }}" @click="adminMenuOpen = false"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded polygon-btn transition-all {{ request()->routeIs('admin.users*') ? 'bg-[#29241F] text-[#EDE5D8] font-extrabold shadow-sm' : 'text-[#29241F] hover:text-[#A8895F] hover:bg-[#A8895F]/10' }}">
                            <i data-lucide="shield-user" class="w-4 h-4 text-[#A8895F]"></i>
                            <span>Admin Users</span>
                        </a>
                        @endcan

                        @can('settings')
                        <a href="{{ route('admin.payments') }}" @click="adminMenuOpen = false"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded polygon-btn transition-all {{ request()->routeIs('admin.payments*') ? 'bg-[#29241F] text-[#EDE5D8] font-extrabold shadow-sm' : 'text-[#29241F] hover:text-[#A8895F] hover:bg-[#A8895F]/10' }}">
                            <i data-lucide="credit-card" class="w-4 h-4 text-[#A8895F]"></i>
                            <span>Payment Setup</span>
                        </a>
                        @endcan
                    </div>
                </div>
            </nav>
        </div>

        <div class="pt-6 border-t border-[#D8C9B8] space-y-3">
            <div class="px-3 py-2 bg-[#EDE5D8] border border-[#A8895F]/30 polygon-card text-[10px] space-y-1">
                <span class="font-extrabold text-[#A8895F] uppercase block">Atelier Status</span>
                <span class="flex items-center gap-1.5 text-[#29241F] font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-600 animate-ping"></span>
                    Store Live & Accepting Orders
                </span>
            </div>

            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center justify-center gap-2 w-full py-2.5 bg-[#29241F] text-[#EDE5D8] border border-[#A8895F]/40 font-extrabold text-xs uppercase tracking-wider polygon-btn hover:bg-[#A8895F] hover:text-white transition-all shadow-xs">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>View Front Store</span>
            </a>
        </div>
    </aside>

    <div class="flex-grow flex flex-col min-w-0">

        <header class="bg-[#F8F5EF] border-b border-[#D8C9B8] px-4 sm:px-8 py-4 flex justify-between items-center shadow-xs sticky top-0 z-30 gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" @click="adminMenuOpen = true" class="lg:hidden p-2 text-[#A8895F] border border-[#D8C9B8] bg-white shrink-0" aria-label="Open navigation">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <div class="min-w-0">
                    <span class="text-[10px] text-[#A8895F] font-extrabold uppercase tracking-[0.25em] block truncate">SOZIE ATELIER MANAGEMENT</span>
                    <h2 class="font-serif font-bold text-xl text-[#29241F] truncate">@yield('page_title', 'Dashboard Overview')</h2>
                </div>
            </div>

            <div class="flex items-center gap-4">
                @can('products')
                <a href="{{ route('admin.products.create') }}"
                   class="hidden sm:flex items-center gap-2 px-4 py-2 bg-[#A8895F] text-white font-extrabold text-xs uppercase tracking-wider polygon-btn hover:bg-[#29241F] shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>New Perfume</span>
                </a>
                @endcan

                <div class="h-6 w-[1px] bg-[#D8C9B8]"></div>

                <div x-data="{ userMenuOpen: false }" class="relative">
                    <button @click="userMenuOpen = !userMenuOpen"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-[#EDE5D8] transition-all group">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#A8895F] to-[#29241F] flex items-center justify-center polygon-card shadow-sm">
                            <span class="font-serif font-bold text-xs text-white uppercase tracking-wider">
                                {{ substr(auth()->user()?->name ?? 'A', 0, 1) }}
                            </span>
                        </div>
                        <div class="text-right hidden sm:block">
                            <span class="font-serif font-bold text-[13px] text-[#29241F] block leading-tight">{{ auth()->user()?->name ?? 'Admin' }}</span>
                            <span class="text-[9px] font-extrabold text-[#A8895F] uppercase tracking-wider leading-tight block">
                                {{ auth()->user()?->role_label ?? 'Administrator' }}
                            </span>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-[#A8895F] transition-transform group-hover:text-[#29241F]"></i>
                    </button>

                    <div x-show="userMenuOpen"
                         @click.away="userMenuOpen = false"
                         x-transition
                         class="absolute right-0 mt-3 w-64 glass-panel bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-2xl z-50 overflow-hidden" style="display: none;">
                        <div class="p-4 bg-gradient-to-br from-[#29241F] via-[#3D352C] to-[#29241F] border-b border-[#A8895F]/30 space-y-1">
                            <span class="text-[#F8F5EF] font-serif font-bold text-sm block">{{ auth()->user()?->name }}</span>
                            <span class="text-[#A8895F] text-[10px] font-extrabold uppercase tracking-wider block">{{ auth()->user()?->email }}</span>
                            <span class="inline-block mt-1 px-2 py-0.5 bg-[#A8895F]/20 text-[#A8895F] text-[9px] font-extrabold uppercase rounded border border-[#A8895F]/40 tracking-wider">
                                {{ auth()->user()?->role_label ?? 'Role' }}
                            </span>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('home') }}" target="_blank"
                               class="flex items-center gap-2 px-3 py-2 text-xs font-bold text-[#29241F] hover:bg-[#EDE5D8] rounded-lg transition-colors">
                                <i data-lucide="store" class="w-4 h-4 text-[#A8895F]"></i>
                                Visit Storefront
                            </a>
                            <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2 px-3 py-2 text-xs font-bold text-rose-700 hover:bg-rose-50 rounded-lg transition-colors mt-1">
                                    <i data-lucide="log-out" class="w-4 h-4 text-rose-600"></i>
                                    Sign Out of Admin
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-8 flex-grow">
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-800/10 border border-emerald-800 text-emerald-900 text-xs font-bold polygon-card flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-800"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-rose-800/10 border border-rose-800 text-rose-900 text-xs font-bold polygon-card flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-rose-800"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            sozieIcons();
        });
    </script>
</body>
</html>
