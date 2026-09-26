@extends('layouts.app')

@section('title', __('Forgot Password | Sozie Collection'))

@section('content')
<div class="py-24 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="glass-panel-gold p-8 sm:p-12 polygon-card border-2 border-[#A8895F]/40 shadow-2xl bg-[#F8F5EF] text-center">
        <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center">
            <i data-lucide="key-round" class="w-8 h-8 text-[#A8895F]"></i>
        </div>

        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.35em] block mb-3">{{ __('PASSWORD RECOVERY') }}</span>
        <h1 class="font-serif font-bold text-4xl text-[#29241F] mb-4">{{ __('Forgot Your Password?') }}</h1>
        <p class="text-sm text-gray-600 font-medium leading-relaxed mb-8 max-w-md mx-auto">
            {{ __("No worries. Enter the email address associated with your Sozie Collection account and we'll send you a secure link to reset your password.") }}
        </p>

        @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold polygon-card text-left">
            @foreach ($errors->all() as $err)
            <p class="flex items-start gap-1.5">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 mt-0.5 flex-shrink-0"></i>
                <span>{{ $err }}</span>
            </p>
            @endforeach
        </div>
        @endif

        @if (session('status'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold polygon-card flex items-center gap-2 text-left">
            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-700 flex-shrink-0"></i>
            <span>{{ session('status') }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5 max-w-md mx-auto text-left">
            @csrf

            <div>
                <label for="email" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                    {{ __('Registered Email Address') }}
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
                        placeholder="you@example.com"
                        class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                </div>
            </div>

            <button
                type="submit"
                class="w-full py-3.5 bg-[#A8895F] text-white font-extrabold text-xs uppercase tracking-[0.3em] polygon-btn text-center block shadow-xl shadow-[#A8895F]/25 hover:bg-[#29241F] active:scale-[0.99] transition-all">
                {{ __('Email Password Reset Link') }}
            </button>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-[0.25em] text-[#A8895F] hover:text-[#29241F] transition-colors">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    {{ __('Back to Sign In') }}
                </a>
                <a href="{{ route('register') }}" class="text-xs font-bold uppercase tracking-[0.25em] text-[#29241F] hover:text-[#A8895F] transition-colors text-right">
                    {{ __('New? Create Account') }}
                </a>
            </div>
        </form>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
