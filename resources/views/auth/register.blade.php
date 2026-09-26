@extends('layouts.app')

@section('title', __('Create Account | Sozie Collection'))

@section('content')
<div class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
        <div class="hidden lg:flex flex-col space-y-8">
            <div class="mb-4">
                <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.4em] block mb-3">{{ __('JOIN THE ATELIER') }}</span>
                <h1 class="font-serif font-bold text-5xl leading-tight text-[#EDE5D8]">
                    {{ __('Create Your') }}<br>
                    <span class="text-[#A8895F]">{{ __('Sozie Account') }}</span>
                </h1>
            </div>

            <p class="text-sm text-[#B5A897] leading-relaxed font-medium max-w-md">
                {{ __('Join Sozie Collection for early access to limited editions, scent sample drops, personalized recommendations, and seamless checkout every time you return.') }}
            </p>

            <div class="space-y-4 max-w-md">
                <div class="flex items-start gap-4 p-4 glass-panel border border-[#322B23] polygon-card bg-[#17130F]">
                    <span class="w-8 h-8 rounded-full bg-[#A8895F] text-[#12100E] text-xs font-extrabold flex items-center justify-center flex-shrink-0">01</span>
                    <div>
                        <span class="text-sm font-bold text-[#EDE5D8] block">{{ __('Track Orders Like A Pro') }}</span>
                        <span class="text-xs text-[#B5A897] font-medium">{{ __('See every delivery status from fragrance atelier to your doorstep.') }}</span>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 glass-panel border border-[#322B23] polygon-card bg-[#17130F]">
                    <span class="w-8 h-8 rounded-full bg-[#A8895F] text-[#12100E] text-xs font-extrabold flex items-center justify-center flex-shrink-0">02</span>
                    <div>
                        <span class="text-sm font-bold text-[#EDE5D8] block">{{ __('Wishlist Your Dream Scents') }}</span>
                        <span class="text-xs text-[#B5A897] font-medium">{{ __('Curate and save your perfect collection across every device.') }}</span>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 glass-panel border border-[#322B23] polygon-card bg-[#17130F]">
                    <span class="w-8 h-8 rounded-full bg-[#A8895F] text-[#12100E] text-xs font-extrabold flex items-center justify-center flex-shrink-0">03</span>
                    <div>
                        <span class="text-sm font-bold text-[#EDE5D8] block">{{ __('VIP Early Access Drops') }}</span>
                        <span class="text-xs text-[#B5A897] font-medium">{{ __('Members get exclusive 48-hour early access to limited collections.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="glass-panel-gold p-8 sm:p-10 polygon-card border-2 border-[#A8895F]/40 shadow-2xl bg-[#17130F]">
                <div class="mb-8">
                    <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.35em] block mb-2">{{ __('NEW MEMBER REGISTRATION') }}</span>
                    <h2 class="font-serif font-bold text-3xl text-[#EDE5D8]">{{ __('Create Your Account') }}</h2>
                    <p class="text-sm text-[#B5A897] font-medium mt-2">
                        {{ __('Already a member?') }}
                        <a href="{{ route('login') }}" class="text-[#A8895F] font-bold underline hover:text-[#F8F5EF] transition-colors ml-1">{{ __('Sign in here') }} →</a>
                    </p>
                </div>

                @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-[#7F1D3A] text-rose-300 text-xs font-bold polygon-card">
                    <div class="flex flex-col gap-1.5">
                        @foreach ($errors->all() as $err)
                        <p class="flex items-start gap-1.5">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400 mt-0.5 flex-shrink-0"></i>
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
                            {{ __('Full Name') }}
                        </label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="e.g. Amina Khamis"
                            autocomplete="name"
                            class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] px-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/50 font-bold transition-all placeholder:text-[#A89C8C] placeholder:font-bold">
                    </div>

                    <div>
                        <label for="email" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                            {{ __('Email Address') }}
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="you@example.com"
                            autocomplete="email"
                            class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] px-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/50 font-bold transition-all placeholder:text-[#A89C8C] placeholder:font-bold">
                    </div>

                    <div>
                        <label for="phone" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                            {{ __('Phone Number') }} <span class="text-[#A89C8C] font-normal normal-case">{{ __('(Recommended for order updates)') }}</span>
                        </label>
                        <input
                            id="phone"
                            type="tel"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="e.g. 0712345678"
                            autocomplete="tel"
                            class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] px-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/50 font-bold transition-all placeholder:text-[#A89C8C] placeholder:font-bold">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                                {{ __('Password') }}
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="{{ __('Minimum 8 characters') }}"
                                class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] px-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/50 font-bold transition-all placeholder:text-[#A89C8C] placeholder:font-bold">
                        </div>
                        <div>
                            <label for="password-confirm" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                                {{ __('Confirm Password') }}
                            </label>
                            <input
                                id="password-confirm"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="{{ __('Re-enter password') }}"
                                class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] px-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/50 font-bold transition-all placeholder:text-[#A89C8C] placeholder:font-bold">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full py-3.5 bg-[#A8895F] text-[#12100E] font-extrabold text-xs uppercase tracking-[0.3em] polygon-btn text-center block shadow-xl shadow-[#A8895F]/25 hover:bg-[#12100E] hover:text-[#F8F5EF] active:scale-[0.99] transition-all">
                            {{ __('Create Sozie Collection Account') }}
                        </button>
                    </div>

                    <p class="pt-1 text-[10px] text-[#A89C8C] font-semibold leading-relaxed text-center">
                        {{ __('By creating an account, you agree to Sozie Collection Terms of Service and Privacy Policy. Your data is protected and never shared.') }}
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>document.addEventListener('DOMContentLoaded', function () { sozieIcons(); });</script>
@endsection
