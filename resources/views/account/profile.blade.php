@extends('layouts.app')

@section('title', 'My Profile | Sozie Collection')

@section('content')
@include('account._sidebar_layout', [
    'account_title' => 'Account Profile',
    'account_subtitle' => 'Keep your Sozie Collection member details current. Update contact info and manage your secure account password from here.'
])
@endsection

@section('account_content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="glass-panel-gold border-2 border-[#A8895F]/30 polygon-card bg-[#F8F5EF] p-6 shadow-xl">
        <div class="flex items-center gap-4 mb-7 pb-5 border-b border-[#D8C9B8]">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-[#A8895F] via-[#D8C9B8] to-[#29241F] p-[2px] shadow-md">
                <div class="w-full h-full rounded-full bg-[#F8F5EF] flex items-center justify-center">
                    <span class="font-serif font-bold text-3xl text-[#29241F]">
                        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                    </span>
                </div>
            </div>
            <div class="min-w-0 flex-1">
                <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-0.5">
                    {{ __($user->roleLabel) }} Since
                </span>
                <h3 class="font-serif font-bold text-xl text-[#29241F] leading-tight truncate">{{ $user->name }}</h3>
                <span class="text-[11px] text-gray-600 font-bold">
                    Joined {{ $user->created_at?->format('F Y') ?? 'Recently' }}
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <h4 class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] mb-2">Personal Details</h4>

            <div>
                <label for="name" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                    Full Display Name
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="user-round" class="w-4 h-4 text-[#A8895F]/60"></i>
                    </div>
                    <input id="name" name="name" type="text"
                           value="{{ old('name', $user->name) }}"
                           required
                           maxlength="255"
                           class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                </div>
            </div>

            <div>
                <label for="email" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                    Email Address
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="w-4 h-4 text-[#A8895F]/60"></i>
                    </div>
                    <input id="email" name="email" type="email"
                           value="{{ old('email', $user->email) }}"
                           required
                           maxlength="255"
                           class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                </div>
            </div>

            <div>
                <label for="phone" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                    Phone Number <span class="text-gray-400 font-normal normal-case">(For SMS delivery updates)</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="smartphone" class="w-4 h-4 text-[#A8895F]/60"></i>
                    </div>
                    <input id="phone" name="phone" type="tel"
                           value="{{ old('phone', $user->phone) }}"
                           maxlength="50"
                           placeholder="e.g. 0712345678"
                           class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                </div>
            </div>

            <div class="pt-4 border-t border-[#D8C9B8]/60 flex justify-end">
                <button type="submit"
                        class="px-6 py-2.5 bg-[#A8895F] text-white text-[10px] font-extrabold uppercase tracking-[0.25em] polygon-btn hover:bg-[#29241F] shadow-md inline-flex items-center gap-2">
                    <i data-lucide="user-round-check" class="w-4 h-4"></i>
                    Save Profile Changes
                </button>
            </div>
        </form>
    </div>

    <div class="space-y-6">
        <div class="glass-panel border border-[#D8C9B8] polygon-card bg-[#F8F5EF] p-6 shadow-md">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#D8C9B8]">
                <div class="w-11 h-11 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center">
                    <i data-lucide="lock-keyhole" class="w-5 h-5 text-[#A8895F]"></i>
                </div>
                <div>
                    <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-0.5">Account Security</span>
                    <h4 class="font-serif font-bold text-xl text-[#29241F]">Change Password</h4>
                </div>
            </div>

            <form method="POST" action="{{ route('account.profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                        Current Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="shield-alert" class="w-4 h-4 text-[#A8895F]/60"></i>
                        </div>
                        <input id="current_password" name="current_password" type="password" required
                               placeholder="Enter current password"
                               autocomplete="current-password"
                               class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                        New Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="key-round" class="w-4 h-4 text-[#A8895F]/60"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                               placeholder="Minimum 8 characters, mix letters & numbers"
                               autocomplete="new-password"
                               class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                    </div>
                </div>

                <div>
                    <label for="password_confirm" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                        Confirm New Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="shield-check" class="w-4 h-4 text-[#A8895F]/60"></i>
                        </div>
                        <input id="password_confirm" name="password_confirmation" type="password" required
                               placeholder="Re-enter new password exactly"
                               autocomplete="new-password"
                               class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                    </div>
                </div>

                <div class="pt-3 border-t border-[#D8C9B8]/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <p class="text-[10px] text-gray-500 font-medium leading-relaxed max-w-xs">
                        Use a unique password you don't reuse anywhere else. For security, you will not be signed out after this change.
                    </p>
                    <button type="submit"
                            class="px-6 py-2.5 bg-[#29241F] text-white text-[10px] font-extrabold uppercase tracking-[0.25em] polygon-btn hover:bg-[#A8895F] shadow-md inline-flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        <div class="glass-panel-gold border-2 border-[#A8895F]/25 polygon-card bg-gradient-to-br from-[#29241F] to-[#1d1814] p-6 shadow-xl text-[#F8F5EF] relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-40 h-40 rounded-full bg-[#A8895F]/20 blur-3xl"></div>
            <div class="relative space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-[#A8895F]/20 border border-[#A8895F]/40 flex items-center justify-center">
                        <i data-lucide="triangle-alert" class="w-5 h-5 text-[#A8895F]"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-0.5">Danger Zone</span>
                        <h4 class="font-serif font-bold text-xl text-[#F8F5EF]">Need to Delete Account?</h4>
                    </div>
                </div>
                <p class="text-[11px] text-[#D8C9B8] font-medium leading-relaxed relative">
                    Permanently deleting your account will erase your wishlist, address book, and remove future member access to order tracking. Your historical order data remains on record for accounting purposes.
                </p>
                <a href="https://wa.me/255700000000?text=Jambo%20Sozie%20Collection%2C%20ninaomba%20kufuta%20account%20yangu"
                   target="_blank"
                   class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600/90 hover:bg-rose-700 text-white text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn transition-all border border-rose-500/30">
                    <i data-lucide="user-minus" class="w-4 h-4"></i>
                    Contact Support to Delete Account
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
