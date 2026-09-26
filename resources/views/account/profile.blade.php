@extends('layouts.app')

@section('title', __('My Profile | Sozie Collection'))

@section('content')
@include('account._sidebar_layout', [
    'account_title' => __('Account Profile'),
    'account_subtitle' => __('Keep your Sozie Collection member details current. Update contact info and manage your secure account password from here.')
])
@endsection

@section('account_content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="glass-panel-gold border-2 border-[#A8895F]/30 polygon-card bg-[#17130F] p-6 shadow-xl">
        <div class="flex items-center gap-4 mb-7 pb-5 border-b border-[#322B23]">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-[#A8895F] via-[#C5A059] to-[#12100E] p-[2px] shadow-md">
                <div class="w-full h-full rounded-full bg-[#17130F] flex items-center justify-center">
                    <span class="font-serif font-bold text-3xl text-[#EDE5D8]">
                        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                    </span>
                </div>
            </div>
            <div class="min-w-0 flex-1">
                <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-0.5">
                    {{ $user->roleLabel }} {{ __('Since') }}
                </span>
                <h3 class="font-serif font-bold text-xl text-[#EDE5D8] leading-tight truncate">{{ $user->name }}</h3>
                <span class="text-[11px] text-[#B5A897] font-bold">
                    {{ __('Joined :date', ['date' => $user->created_at?->format('F Y') ?? __('Recently')]) }}
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <h4 class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] mb-2">{{ __('Personal Details') }}</h4>

            <div>
                <label for="name" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                    {{ __('Full Display Name') }}
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="user-round" class="w-4 h-4 text-[#A8895F]"></i>
                    </div>
                    <input id="name" name="name" type="text"
                           value="{{ old('name', $user->name) }}"
                           required
                           maxlength="255"
                           class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/50 font-bold transition-all placeholder:text-[#A89C8C] placeholder:font-bold">
                </div>
            </div>

            <div>
                <label for="email" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                    {{ __('Email Address') }}
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="w-4 h-4 text-[#A8895F]"></i>
                    </div>
                    <input id="email" name="email" type="email"
                           value="{{ old('email', $user->email) }}"
                           required
                           maxlength="255"
                           class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/50 font-bold transition-all placeholder:text-[#A89C8C] placeholder:font-bold">
                </div>
            </div>

            <div>
                <label for="phone" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                    {{ __('Phone Number') }} <span class="text-[#A89C8C] font-normal normal-case">{{ __('(For SMS delivery updates)') }}</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="smartphone" class="w-4 h-4 text-[#A8895F]"></i>
                    </div>
                    <input id="phone" name="phone" type="tel"
                           value="{{ old('phone', $user->phone) }}"
                           maxlength="50"
                           placeholder="{{ __('e.g. 0712345678') }}"
                           class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/50 font-bold transition-all placeholder:text-[#A89C8C] placeholder:font-bold">
                </div>
            </div>

            <div class="pt-4 border-t border-[#322B23]/60 flex justify-end">
                <button type="submit"
                        class="px-6 py-2.5 bg-[#A8895F] text-[#12100E] text-[10px] font-extrabold uppercase tracking-[0.25em] polygon-btn hover:bg-[#12100E] hover:text-[#F8F5EF] shadow-md inline-flex items-center gap-2">
                    <i data-lucide="user-round-check" class="w-4 h-4"></i>
                    {{ __('Save Profile Changes') }}
                </button>
            </div>
        </form>
    </div>

    <div class="space-y-6">
        <div class="glass-panel border border-[#322B23] polygon-card bg-[#17130F] p-6 shadow-md">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#322B23]">
                <div class="w-11 h-11 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center">
                    <i data-lucide="lock-keyhole" class="w-5 h-5 text-[#A8895F]"></i>
                </div>
                <div>
                    <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-0.5">{{ __('Account Security') }}</span>
                    <h4 class="font-serif font-bold text-xl text-[#EDE5D8]">{{ __('Change Password') }}</h4>
                </div>
            </div>

            <form method="POST" action="{{ route('account.profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                        {{ __('Current Password') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="shield-alert" class="w-4 h-4 text-[#A8895F]"></i>
                        </div>
                        <input id="current_password" name="current_password" type="password" required
                               placeholder="{{ __('Enter current password') }}"
                               autocomplete="current-password"
                               class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/50 font-bold transition-all placeholder:text-[#A89C8C] placeholder:font-bold">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                        {{ __('New Password') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="key-round" class="w-4 h-4 text-[#A8895F]"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                               placeholder="{{ __('Minimum 8 characters, mix letters & numbers') }}"
                               autocomplete="new-password"
                               class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/50 font-bold transition-all placeholder:text-[#A89C8C] placeholder:font-bold">
                    </div>
                </div>

                <div>
                    <label for="password_confirm" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                        {{ __('Confirm New Password') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="shield-check" class="w-4 h-4 text-[#A8895F]"></i>
                        </div>
                        <input id="password_confirm" name="password_confirmation" type="password" required
                               placeholder="{{ __('Re-enter new password exactly') }}"
                               autocomplete="new-password"
                               class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/50 font-bold transition-all placeholder:text-[#A89C8C] placeholder:font-bold">
                    </div>
                </div>

                <div class="pt-3 border-t border-[#322B23]/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <p class="text-[10px] text-[#A89C8C] font-medium leading-relaxed max-w-xs">
                        {{ __('Use a unique password you don\'t reuse anywhere else. For security, you will not be signed out after this change.') }}
                    </p>
                    <button type="submit"
                            class="px-6 py-2.5 bg-[#221D19] text-white text-[10px] font-extrabold uppercase tracking-[0.25em] polygon-btn hover:bg-[#A8895F] hover:text-[#12100E] shadow-md inline-flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        {{ __('Update Password') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="glass-panel-gold border-2 border-[#A8895F]/25 polygon-card bg-gradient-to-br from-[#241E19] to-[#141110] p-6 shadow-xl text-[#F8F5EF] relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-40 h-40 rounded-full bg-[#A8895F]/20 blur-3xl"></div>
            <div class="relative space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-[#A8895F]/20 border border-[#A8895F]/40 flex items-center justify-center">
                        <i data-lucide="triangle-alert" class="w-5 h-5 text-[#A8895F]"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-0.5">{{ __('Danger Zone') }}</span>
                        <h4 class="font-serif font-bold text-xl text-[#F8F5EF]">{{ __('Need to Delete Account?') }}</h4>
                    </div>
                </div>
                <p class="text-[11px] text-[#A89C8C] font-medium leading-relaxed relative">
                    {{ __('Permanently deleting your account will erase your wishlist, address book, and remove future member access to order tracking. Your historical order data remains on record for accounting purposes.') }}
                </p>
                <a href="https://wa.me/{{ config('payment.whatsapp.phone_number') }}?text=Jambo%20Sozie%20Collection%2C%20ninaomba%20kufuta%20account%20yangu"
                   target="_blank"
                   class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600/90 hover:bg-rose-700 text-white text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn transition-all border border-rose-500/30">
                    <i data-lucide="user-minus" class="w-4 h-4"></i>
                    {{ __('Contact Support to Delete Account') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
