@extends('layouts.app')

@section('title', (isset($address) ? 'Edit Address' : 'Add New Address').' | Sozie Collection')

@section('content')
@php
    $isEdit = isset($address);
    $pageTitle = $isEdit ? 'Edit Saved Address' : 'Add New Shipping Address';
    $subtitle = $isEdit
        ? 'Update details for this saved delivery location. Changes apply immediately at your next Sozie Collection checkout.'
        : 'Add a new delivery location to your Sozie Collection address book for ultra-fast 1-click checkout on future orders.';
@endphp
@include('account._sidebar_layout', [
    'account_title' => $pageTitle,
    'account_subtitle' => $subtitle
])
@endsection

@section('account_content')
<div class="glass-panel-gold border-2 border-[#A8895F]/30 polygon-card bg-[#F8F5EF] p-6 sm:p-8 shadow-xl max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-8 pb-5 border-b border-[#D8C9B8]">
        <a href="{{ route('account.addresses') }}"
           class="w-10 h-10 rounded-full bg-white border border-[#D8C9B8] flex items-center justify-center text-[#29241F] hover:text-[#A8895F] hover:border-[#A8895F] transition-colors flex-shrink-0">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div>
            <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-0.5">
                {{ $isEdit ? 'Update Delivery Location' : 'New Delivery Location' }}
            </span>
            <h3 class="font-serif font-bold text-2xl text-[#29241F]">{{ $address->label ?? 'Address Details' }}</h3>
        </div>
    </div>

    <form method="POST"
          action="{{ $isEdit ? route('account.addresses.update', $address->id) : route('account.addresses.store') }}"
          class="space-y-5">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label for="label" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                    Address Label <span class="text-gray-400 font-normal normal-case">(e.g. Home, Office, Mum\'s House)</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="bookmark" class="w-4 h-4 text-[#A8895F]/60"></i>
                    </div>
                    <input id="label" name="label" type="text"
                           value="{{ old('label', $address?->label) }}"
                           required
                           placeholder="e.g. Dar Home"
                           maxlength="50"
                           class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                </div>
            </div>

            <div class="sm:col-span-2">
                <label for="full_name" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                    Recipient Full Name
                </label>
                <input id="full_name" name="full_name" type="text"
                       value="{{ old('full_name', $address?->full_name ?? $user->name) }}"
                       required
                       placeholder="Full name of who will receive the package"
                       maxlength="255"
                       class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
            </div>

            <div class="sm:col-span-1">
                <label for="phone" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                    Contact Phone Number
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="phone" class="w-4 h-4 text-[#A8895F]/60"></i>
                    </div>
                    <input id="phone" name="phone" type="tel"
                           value="{{ old('phone', $address?->phone ?? $user->phone) }}"
                           required
                           placeholder="e.g. 0712345678"
                           maxlength="50"
                           class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                </div>
            </div>

            <div class="sm:col-span-1">
                <label for="city" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                    City / Region
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="building-2" class="w-4 h-4 text-[#A8895F]/60"></i>
                    </div>
                    <input id="city" name="city" type="text"
                           value="{{ old('city', $address?->city) }}"
                           required
                           placeholder="e.g. Dar es Salaam, Mwanza, Arusha"
                           maxlength="100"
                           class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] pl-10 pr-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold">
                </div>
            </div>

            <div class="sm:col-span-2">
                <label for="street_address" class="block text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.25em] mb-2">
                    Full Street & Delivery Details
                </label>
                <textarea id="street_address" name="street_address" rows="4"
                          required
                          placeholder="Street, Plot / House number, Area, Landmarks, Floor, Apartment... Include every detail the delivery rider will need!"
                          maxlength="500"
                          class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-3 focus:outline-none focus:border-[#A8895F] focus:ring-2 focus:ring-[#A8895F]/20 font-bold transition-all placeholder:text-gray-400 placeholder:font-bold leading-relaxed">{{ old('street_address', $address?->street_address) }}</textarea>
                <p class="mt-1.5 text-[10px] text-gray-500 font-bold uppercase tracking-wider flex items-center gap-1">
                    <i data-lucide="info" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                    {{ 500 - strlen(old('street_address', $address?->street_address ?? '')) }} characters remaining
                </p>
            </div>

            <div class="sm:col-span-2">
                <label class="flex items-start gap-3 cursor-pointer group select-none">
                    <div class="relative pt-0.5">
                        <input id="is_default" name="is_default" type="checkbox" value="1"
                               {{ old('is_default', $address?->is_default ?? false) ? 'checked' : '' }}
                               class="peer sr-only">
                        <span class="block w-5 h-5 rounded border-2 border-[#D8C9B8] bg-white peer-checked:bg-[#A8895F] peer-checked:border-[#A8895F] transition-colors"></span>
                        <i data-lucide="check" class="w-3.5 h-3.5 absolute top-1 left-1 text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                    </div>
                    <div class="flex-1">
                        <span class="text-[11px] font-extrabold text-[#29241F] uppercase tracking-[0.2em] block mb-0.5">
                            Use as my Default Delivery Address
                        </span>
                        <span class="text-[10px] text-gray-500 font-medium leading-relaxed">
                            This location will be auto-selected at checkout for all future Sozie Collection fragrance orders.
                        </span>
                    </div>
                </label>
            </div>
        </div>

        <div class="pt-6 mt-2 border-t border-[#D8C9B8] flex flex-wrap items-center gap-3 justify-end">
            <a href="{{ route('account.addresses') }}"
               class="px-5 py-2.5 bg-white border border-[#D8C9B8] text-[#29241F] text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn hover:bg-[#EDE5D8] transition-all">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-[#A8895F] text-white text-[10px] font-extrabold uppercase tracking-[0.25em] polygon-btn hover:bg-[#29241F] shadow-lg inline-flex items-center gap-2">
                <i data-lucide="{{ $isEdit ? 'save' : 'map-pin-plus' }}" class="w-4 h-4"></i>
                {{ $isEdit ? 'Update Address' : 'Save Address' }}
            </button>
        </div>
    </form>
</div>
@endsection
