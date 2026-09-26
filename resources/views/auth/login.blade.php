@extends('layouts.app')

@section('title', __('Sign In | Sozie Collection'))

@section('content')

<div class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

        <div class="hidden lg:flex flex-col space-y-8">
            <div class="mb-4">
                <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.4em] block mb-3">{{ __('WELCOME BACK') }}</span>
                <h1 class="font-serif font-bold text-5xl leading-tight text-[#29241F]">
                    {{ __('Return to Your') }}<br>
                    <span class="text-[#A8895F]">Sozie Collection</span>
                </h1>
            </div>

            <p class="text-sm text-gray-600 leading-relaxed font-medium max-w-md">
                {{ __('Sign in to access your saved wishlist, saved shipping addresses, order history, and get exclusive early access to limited-edition fragrance drops.') }}
            </p>

            <div class="space-y-5 max-w-md">
                <div class="flex items-start gap-4 p-4 glass-panel border border-[#D8C9B8] polygon-card bg-[#F8F5EF]">
                    <div class="w-10 h-10 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="package-search" class="w-5 h-5 text-[#A8895F]"></i>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-[#29241F] block">{{ __('Order Tracking At A Glance') }}</span>
                        <span class="text-xs text-gray-600 font-medium">{{ __('All your orders with real-time status updates in one place.') }}</span>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-4 glass-panel border border-[#D8C9B8] polygon-card bg-[#F8F5EF]">
                    <div class="w-10 h-10 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="heart" class="w-5 h-5 text-[#A8895F]"></i>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-[#29241F] block">{{ __('Saved Wishlist Everywhere') }}</span>
                        <span class="text-xs text-gray-600 font-medium">{{ __('Access your saved scents across phone, desktop, and tablet.') }}</span>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-4 glass-panel border border-[#D8C9B8] polygon-card bg-[#F8F5EF]">
                    <div class="w-10 h-10 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="map-pin" class="w-5 h-5 text-[#A8895F]"></i>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-[#29241F] block">{{ __('Fast 1-Click Checkout') }}</span>
                        <span class="text-xs text-gray-600 font-medium">{{ __('Save multiple delivery addresses for instant reorders.') }}</span>
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.25em] text-[#A8895F] hover:text-[#29241F] transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    {{ __('Continue Browsing Perfumes') }}
                </a>
            </div>
        </div>

        <div>
            <div class="glass-panel-gold p-8 sm:p-10 polygon-card border-2 border-[#A8895F]/40 shadow-2xl bg-[#F8F5EF]">
                <div class="mb-8">
                    <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.35em] block mb-2">{{ __('ACCOUNT ACCESS') }}</span>
                    <h2 class="font-serif font-bold text-3xl text-[#29241F]">{{ __('Sign In to Your Account') }}</h2>
                    <p class="text-sm text-gray-600 font-medium mt-2">
                        {{ __('New to Sozie Collection?') }}
                        <a href="{{ route('register') }}" class="text-[#A8895F] font-bold underline hover:text-[#29241F] transition-colors ml-1">{{ __('Create an account') }} →</a>
                    </p>
                </div>

                @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold polygon-card">
                    <div class="flex flex-wrap gap-1">
                        @foreach ($errors->all() as $err)
                        <p class="flex items-start gap-1.5">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 mt-0.5 flex-shrink-0"></i>
                            <span>{{ $err }}</span>
                        </p>
                        @endforeach
                    </div>
                </div>
                @endif

                @if (session('status'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold polygon-card flex items-center gap-2">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-700"></i>
                    <span>{{ session('status') }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                            {{ __('Email Address') }}
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
                                placeholder="you@example.com"
                                class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                            {{ __('Password') }}
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
                                placeholder="••••••••"
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
                                {{ __('Keep me signed in') }}
                            </span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-[#A8895F] hover:text-[#29241F] transition-colors underline underline-offset-2">
                            {{ __('Forgot password?') }}
                        </a>
                    </div>

                    <button
                        type="submit"
                        class="w-full py-3.5 bg-[#A8895F] text-white font-extrabold text-xs uppercase tracking-[0.3em] polygon-btn text-center block shadow-xl shadow-[#A8895F]/25 hover:bg-[#29241F] active:scale-[0.99] transition-all">
                        {{ __('Sign In to Account') }}
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-[#D8C9B8]">
                    <div class="p-4 bg-[#EDE5D8]/70 border border-[#A8895F]/30 polygon-card text-[11px]">
                        <span class="font-extrabold text-[#A8895F] uppercase tracking-[0.2em] block mb-1">{{ __('Just Browsing?') }}</span>
                        <p class="text-gray-700 font-bold leading-relaxed">
                            {{ __('No account needed to order. You can') }} <a href="{{ route('shop.index') }}" class="underline text-[#29241F]">{{ __('continue shopping as guest') }}</a> {{ __('and create your account later at checkout.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>document.addEventListener('DOMContentLoaded', function () { sozieIcons(); });</script>
@endsection
