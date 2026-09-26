<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal Login | Sozie Collection</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@1.48.0/dist/umd/lucide.min.js" defer></script>
</head>
<body class="bg-[#EDE5D8] text-[#29241F] font-sans min-h-screen flex items-center justify-center relative overflow-hidden">

    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden opacity-50">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-[#D8C9B8]/50 blur-[130px] rounded-full animate-pulse-glow"></div>
        <div class="absolute top-1/3 -right-40 w-[500px] h-[500px] bg-[#A8895F]/20 blur-[160px] rounded-full"></div>
        <div class="absolute bottom-10 left-1/4 w-80 h-80 bg-[#D8C9B8]/30 blur-[120px] rounded-full"></div>
    </div>

    <div class="relative z-10 w-full max-w-5xl px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">

        <div class="hidden lg:flex flex-col justify-between p-10 polygon-card border border-[#A8895F]/40 glass-panel-gold bg-gradient-to-br from-[#29241F] via-[#3D352C] to-[#29241F] text-[#F8F5EF] shadow-2xl">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-10 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#A8895F] via-[#D8C9B8] to-[#8cc63f] polygon-card flex items-center justify-center p-[1px] shadow-md group-hover:scale-105 transition-transform duration-300">
                        <div class="w-full h-full bg-[#29241F] polygon-card flex items-center justify-center">
                            <span class="font-serif font-bold text-2xl text-[#A8895F]">S</span>
                        </div>
                    </div>
                    <div>
                        <span class="font-serif font-bold text-3xl tracking-[0.2em] text-[#F8F5EF] block">SOZIE</span>
                        <span class="block text-[10px] tracking-[0.35em] text-[#A8895F] uppercase -mt-1 font-extrabold">COLLECTION</span>
                    </div>
                </a>

                <span class="text-[11px] font-extrabold text-[#A8895F] uppercase tracking-[0.35em] block mb-4">RESTRICTED ACCESS</span>
                <h1 class="font-serif font-bold text-4xl text-[#F8F5EF] leading-tight mb-6">
                    Atelier Management<br>
                    <span class="text-[#A8895F]">Control Center</span>
                </h1>
                <p class="text-sm text-[#D8C9B8] leading-relaxed font-medium max-w-sm">
                    Secure portal for Sozie Collection operations, product catalog, order fulfillment, customer management, and reporting.
                </p>
            </div>

            <div class="space-y-4 pt-8">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#A8895F]/20 border border-[#A8895F]/40 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="shield-check" class="w-4 h-4 text-[#A8895F]"></i>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-[#F8F5EF] block">Role-Based Permissions</span>
                        <span class="text-xs text-[#D8C9B8]">Granular access control across all modules</span>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#A8895F]/20 border border-[#A8895F]/40 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="package" class="w-4 h-4 text-[#A8895F]"></i>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-[#F8F5EF] block">Inventory & Orders</span>
                        <span class="text-xs text-[#D8C9B8]">Manage products, stock, and fulfill orders</span>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#A8895F]/20 border border-[#A8895F]/40 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 text-[#A8895F]"></i>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-[#F8F5EF] block">Analytics & Reports</span>
                        <span class="text-xs text-[#D8C9B8]">Sales insights and performance dashboards</span>
                    </div>
                </div>
            </div>

            <div class="pt-6 mt-6 border-t border-white/10 flex items-center justify-between text-xs text-[#D8C9B8]/70 font-bold">
                <a href="{{ route('home') }}" class="flex items-center gap-2 hover:text-[#A8895F] transition-colors">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Return to Storefront</span>
                </a>
                <span>&copy; {{ date('Y') }} Sozie Collection</span>
            </div>
        </div>

        <div class="glass-panel p-8 sm:p-10 polygon-card border border-[#D8C9B8] bg-[#F8F5EF] shadow-2xl flex flex-col justify-center">

            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-gradient-to-br from-[#A8895F] via-[#D8C9B8] to-[#29241F] polygon-card flex items-center justify-center p-[1px]">
                    <div class="w-full h-full bg-[#29241F] polygon-card flex items-center justify-center">
                        <span class="font-serif font-bold text-lg text-[#A8895F]">S</span>
                    </div>
                </div>
                <div>
                    <span class="font-serif font-bold text-2xl tracking-[0.2em] text-[#29241F] block">SOZIE ADMIN</span>
                    <span class="block text-[9px] text-[#A8895F] tracking-[0.3em] uppercase -mt-1 font-extrabold">PORTAL LOGIN</span>
                </div>
            </div>

            <div class="mb-8 lg:mb-10">
                <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.35em] block mb-2">WELCOME BACK</span>
                <h2 class="font-serif font-bold text-3xl sm:text-4xl text-[#29241F] mb-2">Sign In to Atelier</h2>
                <p class="text-sm text-gray-600 font-medium leading-relaxed">
                    Enter your administrative credentials to access the management dashboard.
                </p>
            </div>

            @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold polygon-card flex items-start gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 flex-shrink-0 mt-0.5"></i>
                <div class="space-y-1">
                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                        Admin Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-4 h-4 text-[#A8895F]/60"></i>
                        </div>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="admin@soziecollection.com"
                            class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="lock-keyhole" class="w-4 h-4 text-[#A8895F]/60"></i>
                        </div>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your secure password"
                            class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label for="remember" class="flex items-center gap-2 cursor-pointer group">
                        <input
                            id="remember"
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 accent-[#A8895F] border-[#D8C9B8] rounded">
                        <span class="text-[11px] text-gray-600 font-bold group-hover:text-[#29241F] transition-colors">
                            Remember this device
                        </span>
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full py-3.5 bg-[#A8895F] text-white font-extrabold text-xs uppercase tracking-[0.25em] polygon-btn text-center block shadow-lg shadow-[#A8895F]/25 hover:bg-[#29241F] hover:shadow-[#29241F]/30 active:scale-[0.99] transition-all mt-2">
                    <span class="inline-flex items-center justify-center gap-2">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        Access Admin Panel
                    </span>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-[#D8C9B8]">
                <div class="p-4 bg-[#EDE5D8]/60 border border-[#A8895F]/30 polygon-card text-[11px] space-y-2">
                    <span class="font-extrabold text-[#A8895F] uppercase tracking-[0.2em] block">Test Credentials</span>
                    <div class="grid grid-cols-1 gap-1 font-bold text-[#29241F]/80">
                        <p><span class="text-[#A8895F]">Super Admin:</span> admin@soziecollection.com / password123</p>
                        <p><span class="text-[#A8895F]">Manager:</span> manager@soziecollection.com / password123</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
