@extends('layouts.app')

@section('title', __('Reset Password | Sozie Collection'))

@section('content')
<div class="py-24 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="glass-panel-gold p-8 sm:p-12 polygon-card border-2 border-[#A8895F]/40 shadow-2xl bg-[#F8F5EF] text-center">
        <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center">
            <i data-lucide="lock-keyhole" class="w-8 h-8 text-[#A8895F]"></i>
        </div>

        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.35em] block mb-3">{{ __('SET NEW PASSWORD') }}</span>
        <h1 class="font-serif font-bold text-4xl text-[#29241F] mb-4">{{ __('Reset Your Password') }}</h1>
        <p class="text-sm text-gray-600 font-medium leading-relaxed mb-8 max-w-md mx-auto">
            {{ __('Enter a strong new password below to regain access to your Sozie Collection account.') }}
        </p>

        @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold polygon-card text-left">
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

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5 max-w-md mx-auto text-left">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                    {{ __('Account Email') }}
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="w-4 h-4 text-[#A8895F]/60"></i>
                    </div>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $email ?? '') }}"
                        required
                        placeholder="you@example.com"
                        class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                        {{ __('New Password') }}
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="{{ __('Min 8 characters') }}"
                        class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
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
                        class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                </div>
            </div>

            <button
                type="submit"
                class="w-full py-3.5 bg-[#A8895F] text-white font-extrabold text-xs uppercase tracking-[0.3em] polygon-btn text-center block shadow-xl shadow-[#A8895F]/25 hover:bg-[#29241F] active:scale-[0.99] transition-all">
                {{ __('Reset Password & Sign In') }}
            </button>

            <p class="pt-1 text-[10px] text-gray-500 font-semibold leading-relaxed text-center">
                {{ __('For security, your password must be at least 8 characters with a mix of letters, numbers, and symbols.') }}
            </p>
        </form>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', function () { sozieIcons(); });</script>
@endsection
