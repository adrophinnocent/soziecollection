@extends('layouts.app')

@section('title', 'Create Account | Sozie Collection')

@section('content')
<div class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
        <div class="hidden lg:flex flex-col space-y-8">
            <div class="mb-4">
                <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.4em] block mb-3">JOIN THE ATELIER</span>
                <h1 class="font-serif font-bold text-5xl leading-tight text-[#29241F]">
                    Create Your<br>
                    <span class="text-[#A8895F]">Sozie Account</span>
                </h1>
            </div>

            <p class="text-sm text-gray-600 leading-relaxed font-medium max-w-md">
                Join Sozie Collection for early access to limited editions, scent sample drops, personalized recommendations, and seamless checkout every time you return.
            </p>

            <div class="space-y-4 max-w-md">
                <div class="flex items-start gap-4 p-4 glass-panel border border-[#D8C9B8] polygon-card bg-[#F8F5EF]">
                    <span class="w-8 h-8 rounded-full bg-[#A8895F] text-white text-xs font-extrabold flex items-center justify-center flex-shrink-0">01</span>
                    <div>
                        <span class="text-sm font-bold text-[#29241F] block">Track Orders Like A Pro</span>
                        <span class="text-xs text-gray-600 font-medium">See every delivery status from fragrance atelier to your doorstep.</span>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 glass-panel border border-[#D8C9B8] polygon-card bg-[#F8F5EF]">
                    <span class="w-8 h-8 rounded-full bg-[#A8895F] text-white text-xs font-extrabold flex items-center justify-center flex-shrink-0">02</span>
                    <div>
                        <span class="text-sm font-bold text-[#29241F] block">Wishlist Your Dream Scents</span>
                        <span class="text-xs text-gray-600 font-medium">Curate and save your perfect collection across every device.</span>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 glass-panel border border-[#D8C9B8] polygon-card bg-[#F8F5EF]">
                    <span class="w-8 h-8 rounded-full bg-[#A8895F] text-white text-xs font-extrabold flex items-center justify-center flex-shrink-0">03</span>
                    <div>
                        <span class="text-sm font-bold text-[#29241F] block">VIP Early Access Drops</span>
                        <span class="text-xs text-gray-600 font-medium">Members get exclusive 48-hour early access to limited collections.</span>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="glass-panel-gold p-8 sm:p-10 polygon-card border-2 border-[#A8895F]/40 shadow-2xl bg-[#F8F5EF]">
                <div class="mb-8">
                    <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.35em] block mb-2">NEW MEMBER REGISTRATION</span>
                    <h2 class="font-serif font-bold text-3xl text-[#29241F]">Create Your Account</h2>
                    <p class="text-sm text-gray-600 font-medium mt-2">
                        Already a member?
                        <a href="{{ route('login') }}" class="text-[#A8895F] font-bold underline hover:text-[#29241F] transition-colors ml-1">Sign in here →</a>
                    </p>
                </div>

                @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold polygon-card">
                    <div class="flex flex-col gap-1.5">
                        @foreach ($errors->all() as $err)
                        <p class="flex items-start gap-1.5">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 mt-0.5 flex-shrink-0"></i>
                            <span>{{ $err }}</span>
                        </p>
                        @endforeach
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                            Full Name
                        </label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="e.g. Amina Khamis"
                            autocomplete="name"
                            class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                    </div>

                    <div>
                        <label for="email" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                            Email Address
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="you@example.com"
                            autocomplete="email"
                            class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                    </div>

                    <div>
                        <label for="phone" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                            Phone Number <span class="text-gray-400 font-normal normal-case">(Recommended for order updates)</span>
                        </label>
                        <input
                            id="phone"
                            type="tel"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="e.g. 0712345678"
                            autocomplete="tel"
                            class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                                Password
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Minimum 8 characters"
                                class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                        </div>
                        <div>
                            <label for="password-confirm" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                                Confirm Password
                            </label>
                            <input
                                id="password-confirm"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Re-enter password"
                                class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full py-3.5 bg-[#A8895F] text-white font-extrabold text-xs uppercase tracking-[0.3em] polygon-btn text-center block shadow-xl shadow-[#A8895F]/25 hover:bg-[#29241F] active:scale-[0.99] transition-all">
                            Create Sozie Collection Account
                        </button>
                    </div>

                    <p class="pt-1 text-[10px] text-gray-500 font-semibold leading-relaxed text-center">
                        By creating an account, you agree to Sozie Collection Terms of Service and Privacy Policy. Your data is protected and never shared.
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
