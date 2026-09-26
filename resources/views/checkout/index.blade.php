@extends('layouts.app')

@section('title', __('Checkout & Payment | Sozie Collection'))

@section('content')

<div class="py-12 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="mb-8 border-b border-[#322B23] pb-4">
        <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">{{ __('FINAL STEP') }}</span>
        <h1 class="font-serif font-bold text-3xl sm:text-4xl text-[#EDE5D8]">{{ __('CHECKOUT & DELIVERY DETAILS') }}</h1>
    </div>

    @php
        $isLoggedIn = Auth::check() && ! (Auth::user()->isAdmin ?? false);
    @endphp

    @if(! $isLoggedIn)
    <div class="mb-7 glass-panel-gold border-2 border-[#A8895F]/30 polygon-card bg-[#17130F] p-6 shadow-md">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-[#322B23]">
            <div class="w-10 h-10 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center">
                <i data-lucide="user-round-check" class="w-5 h-5 text-[#A8895F]"></i>
            </div>
            <div>
                <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-0.5">{{ __('Step :current of :total', ['current' => 1, 'total' => 3]) }}</span>
                <h3 class="font-serif font-bold text-xl text-[#EDE5D8]">{{ __('Choose How You Want to Checkout') }}</h3>
            </div>
            <span class="ml-auto text-[10px] font-extrabold uppercase tracking-[0.25em] text-[#A89C8C] bg-[#0C0A09] border border-[#322B23] px-3 py-1 rounded">
                {{ __('No account required to order') }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="{ mode: '{{ old('checkout_mode', 'guest') }}' }">
            <input type="hidden" name="checkout_mode" form="checkout-main-form" :value="mode">

            <label @click="mode = 'guest'"
                   class="navy-card p-5 polygon-card cursor-pointer transition-all group
                          ring-2 ring-offset-2 ring-offset-[#17130F] border-2 bg-[#17130F]
                          :class="mode === 'guest' ? 'ring-[#A8895F] border-[#A8895F] shadow-xl' : 'ring-transparent border-[#322B23] hover:border-[#A8895F]/60 shadow-sm'\">
                <input type="radio" name="checkout_mode_select" value="guest" x-model="mode" class="sr-only">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-[#29241F] to-[#A8895F]/70 flex items-center justify-center shadow-md">
                        <i data-lucide="user-round-cog" class="w-5 h-5 text-white"></i>
                    </div>
                    <span
                          :class="mode === 'guest' ? 'bg-[#A8895F] border-[#A8895F] text-[#12100E]' : 'bg-[#17130F] border-[#322B23] text-[#A89C8C]'\"
                          class="inline-flex items-center justify-center w-6 h-6 rounded-full border-2 transition-colors flex-shrink-0">
                        <i data-lucide="check" class="w-3.5 h-3.5" :class="mode === 'guest' ? 'opacity-100' : 'opacity-0'\"></i>
                    </span>
                </div>
                <h4 class="font-serif font-bold text-lg text-[#EDE5D8] mb-1.5 leading-tight group-hover:text-[#A8895F] transition-colors">
                    {{ __('Continue as Guest') }}
                </h4>
                <p class="text-[11px] text-[#B5A897] font-medium leading-relaxed mb-3">
                    {{ __('Fastest option. Order instantly without creating any account. Track later via order number sent to your phone.') }}
                </p>
                <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-[0.2em] text-emerald-300 bg-[#0C2119] border border-[#065F46] px-2.5 py-1 rounded">
                    <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                    {{ __('Recommended') }}
                </span>
            </label>

            <label @click="window.location = '{{ route('login') }}?next={{ urlencode(route('checkout.index')) }}'"
                   class="navy-card p-5 polygon-card cursor-pointer transition-all group border-2 bg-[#17130F] border-[#322B23] hover:border-[#A8895F]/60 shadow-sm hover:shadow-md">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-full bg-[#221D19] flex items-center justify-center shadow-md">
                        <i data-lucide="log-in" class="w-5 h-5 text-[#A8895F]"></i>
                    </div>
                    <a href="{{ route('login') }}?next={{ urlencode(route('checkout.index')) }}"
                       class="inline-flex items-center justify-center w-6 h-6 rounded-full border-2 bg-[#17130F] border-[#322B23] text-[#A89C8C] hover:border-[#A8895F] hover:text-[#A8895F] transition-colors flex-shrink-0">
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
                <h4 class="font-serif font-bold text-lg text-[#EDE5D8] mb-1.5 leading-tight group-hover:text-[#A8895F] transition-colors">
                    {{ __('Sign In') }}
                </h4>
                <p class="text-[11px] text-[#B5A897] font-medium leading-relaxed mb-3">
                    {{ __('Returning Sozie member? Sign in for auto-filled saved addresses, wishlist access and order history.') }}
                </p>
                <span class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#A8895F] flex items-center gap-1.5">
                    {{ __('Go to Sign In') }} <i data-lucide="chevron-right" class="w-3 h-3"></i>
                </span>
            </label>

            <label @click="window.location = '{{ route('register') }}?next={{ urlencode(route('checkout.index')) }}'"
                   class="navy-card p-5 polygon-card cursor-pointer transition-all group border-2 bg-[#17130F] border-[#322B23] hover:border-[#A8895F]/60 shadow-sm hover:shadow-md">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-[#A8895F] to-[#C5A059] flex items-center justify-center shadow-md">
                        <i data-lucide="user-plus" class="w-5 h-5 text-[#12100E]"></i>
                    </div>
                    <a href="{{ route('register') }}?next={{ urlencode(route('checkout.index')) }}"
                       class="inline-flex items-center justify-center w-6 h-6 rounded-full border-2 bg-[#17130F] border-[#322B23] text-[#A89C8C] hover:border-[#A8895F] hover:text-[#A8895F] transition-colors flex-shrink-0">
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
                <h4 class="font-serif font-bold text-lg text-[#EDE5D8] mb-1.5 leading-tight group-hover:text-[#A8895F] transition-colors">
                    {{ __('Create Account') }}
                </h4>
                <p class="text-[11px] text-[#B5A897] font-medium leading-relaxed mb-3">
                    {{ __('Join the Sozie VIP Atelier. Save wishlists, unlock member-only deals and reorder in a single click.') }}
                </p>
                <span class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#A8895F] flex items-center gap-1.5">
                    {{ __('Go to Register') }} <i data-lucide="chevron-right" class="w-3 h-3"></i>
                </span>
            </label>
        </div>
    </div>
    @endif

    <form id="checkout-main-form" action="{{ route('checkout.store') }}" method="POST">
        @csrf

        @if($isLoggedIn)
        <input type="hidden" name="checkout_mode" value="logged_in">
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Left: Delivery Information -->
            <div class="lg:col-span-7 space-y-6">

                @if($isLoggedIn && $savedAddresses->isNotEmpty())
                <div class="glass-panel-gold p-6 polygon-card border-2 border-[#A8895F]/30 space-y-5 bg-[#17130F] shadow-md">
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-10 h-10 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center">
                            <i data-lucide="map-pinned" class="w-5 h-5 text-[#A8895F]"></i>
                        </div>
                        <div class="flex-1">
                            <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-0.5">{{ __('Saved Addresses') }}</span>
                            <h3 class="font-serif font-bold text-lg text-[#EDE5D8]">{{ __('Use 1-Click Saved Address or Enter New') }}</h3>
                        </div>
                        <a href="{{ route('account.addresses.create') }}" target="_blank"
                           class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#A8895F] hover:text-[#F8F5EF] transition-colors inline-flex items-center gap-1.5">
                            <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                            {{ __('Add New') }}
                        </a>
                    </div>

                    <div x-data="{ selected: {{ (int) old('saved_address_id', $selectedAddressId ?? 0) }} }">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                            @foreach($savedAddresses as $addr)
                            <label @click="selected = {{ $addr->id }}"
                                   class="cursor-pointer p-4 polygon-card border-2 bg-[#17130F] transition-all
                                          {{ $addr->is_default ? 'ring-1 ring-offset-1 ring-offset-[#17130F] ring-[#A8895F]/40' : '' }}
                                          :class="selected === {{ $addr->id }} ? 'border-[#A8895F] shadow-lg bg-[#221D19]/60' : 'border-[#322B23] hover:border-[#A8895F]/60'\">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="saved_address_id" value="{{ $addr->id }}" x-model="selected" class="sr-only">
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full border-2 transition-colors flex-shrink-0"
                                              :class="selected === {{ $addr->id }} ? 'bg-[#A8895F] border-[#A8895F]' : 'bg-[#17130F] border-[#322B23]'\">
                                            <i data-lucide="check" class="w-3 h-3 text-[#12100E]" :class="selected === {{ $addr->id }} ? 'opacity-100' : 'opacity-0'\"></i>
                                        </span>
                                        <span class="font-serif font-bold text-sm text-[#EDE5D8]">{{ $addr->label }}</span>
                                    </div>
                                    @if($addr->is_default)
                                    <span class="polygon-badge bg-[#A8895F] text-[#12100E] text-[9px] uppercase tracking-widest font-extrabold px-2 py-0.5">{{ __('DEFAULT') }}</span>
                                    @endif
                                </div>
                                <p class="text-[11px] font-bold text-[#EDE5D8] leading-tight mb-0.5">{{ $addr->full_name }} • {{ $addr->phone }}</p>
                                <p class="text-[11px] text-[#B5A897] font-semibold leading-relaxed line-clamp-2">{{ $addr->city }} — {{ $addr->street_address }}</p>
                            </label>
                            @endforeach
                            <label @click="selected = 0"
                                   class="cursor-pointer p-4 polygon-card border-2 border-dashed bg-[#100E0C]/40 transition-all flex flex-col items-center justify-center text-center min-h-[120px]
                                          :class="selected === 0 ? 'border-[#A8895F] bg-[#17130F] shadow-lg' : 'border-[#322B23] hover:border-[#A8895F]/60'\">
                                <input type="radio" name="saved_address_id" value="0" x-model="selected" class="sr-only">
                                <div class="w-10 h-10 rounded-full bg-[#17130F] border-2 border-dashed border-[#A8895F]/60 flex items-center justify-center mb-2">
                                    <i data-lucide="pencil-line" class="w-5 h-5 text-[#A8895F]"></i>
                                </div>
                                <h5 class="font-serif font-bold text-sm text-[#EDE5D8] mb-0.5">{{ __('Enter New Address') }}</h5>
                                <p class="text-[10px] text-[#A89C8C] font-semibold leading-tight max-w-[180px]">{{ __('Type in a one-off delivery location below.') }}</p>
                            </label>
                        </div>
                    </div>
                </div>
                @endif

                <div class="glass-panel p-6 polygon-card border border-[#322B23] space-y-4 bg-[#17130F]">
                    <h3 class="font-serif font-bold text-xl text-[#EDE5D8]">{{ __('1. SHIPPING & CONTACT INFORMATION') }}</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">{{ __('Full Name *') }}</label>
                            <input type="text" name="customer_name" required value="{{ $prefill['customer_name'] }}"
                                   placeholder="{{ __('e.g. Amina Khamis') }}"
                                   class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] px-3 py-2.5 focus:outline-none focus:border-[#A8895F] font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">{{ __('Phone Number (WhatsApp) *') }}</label>
                            <input type="text" name="customer_phone" required value="{{ $prefill['customer_phone'] }}"
                                   placeholder="{{ __('e.g. 0712345678') }}"
                                   class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] px-3 py-2.5 focus:outline-none focus:border-[#A8895F] font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">{{ __('Email Address (Optional)') }}</label>
                        <input type="email" name="customer_email" value="{{ $prefill['customer_email'] }}"
                               placeholder="{{ __('e.g. amina@example.com') }}"
                               class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] px-3 py-2.5 focus:outline-none focus:border-[#A8895F] font-bold">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">{{ __('City / Region *') }}</label>
                            <select name="city" class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] px-3 py-2.5 focus:outline-none focus:border-[#A8895F] font-bold">
                                @if(! empty($prefill['city']))
                                <option value="{{ $prefill['city'] }}" selected>{{ $prefill['city'] }}</option>
                                @endif
                                <option value="Dar es Salaam" {{ old('city') === 'Dar es Salaam' ? 'selected' : '' }}>{{ __('Dar es Salaam') }}</option>
                                <option value="Arusha">{{ __('Arusha') }}</option>
                                <option value="Dodoma">{{ __('Dodoma') }}</option>
                                <option value="Mwanza">{{ __('Mwanza') }}</option>
                                <option value="Zanzibar">{{ __('Zanzibar') }}</option>
                                <option value="Other">{{ __('Other Region') }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">{{ __('Detailed Street Address / Landmark *') }}</label>
                            <input type="text" name="shipping_address" required value="{{ $prefill['shipping_address'] }}"
                                   placeholder="{{ __('e.g. Masaki, Haile Selassie Rd, House 42') }}"
                                   class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] px-3 py-2.5 focus:outline-none focus:border-[#A8895F] font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">{{ __('Order Notes (Optional)') }}</label>
                        <textarea name="notes" rows="2" placeholder="{{ __('Special delivery instructions or perfume gift message...') }}"
                                  class="w-full bg-[#17130F] border border-[#322B23] text-xs text-[#EDE5D8] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-medium"></textarea>
                    </div>

                </div>

                <!-- Payment Method Configuration & Options -->
                <div class="glass-panel p-6 polygon-card border border-[#322B23] space-y-4 bg-[#17130F]" x-data="{ selectedMethod: 'whatsapp' }">
                    <h3 class="font-serif font-bold text-xl text-[#EDE5D8]">{{ __('2. PAYMENT METHOD CONFIGURATION') }}</h3>

                    <div class="space-y-3">
                        <!-- Option 1: WhatsApp Direct -->
                        <label @click="selectedMethod = 'whatsapp'"
                               class="navy-card p-4 polygon-card flex flex-col cursor-pointer border hover:border-[#A8895F] bg-[#17130F]"
                               :class="selectedMethod === 'whatsapp' ? 'border-[#A8895F]' : 'border-[#322B23]'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="whatsapp" x-model="selectedMethod" class="accent-[#A8895F]">
                                    <div>
                                        <span class="font-extrabold text-xs text-[#EDE5D8] block">{{ __('WhatsApp Direct Confirmation') }}</span>
                                        <span class="text-[10px] text-[#B5A897] font-semibold">{{ __('Instant order confirmation via WhatsApp agent') }}</span>
                                    </div>
                                </div>
                                <i data-lucide="message-circle" class="w-5 h-5 text-emerald-600"></i>
                            </div>

                            <div x-show="selectedMethod === 'whatsapp'" class="mt-3 pt-3 border-t border-[#322B23] text-[11px] text-[#B5A897] space-y-1">
                                <p>{{ __('✅ After clicking place order, you will be redirected to WhatsApp with your auto-generated itemized receipt.') }}</p>
                                <p>{{ __('📱 Business Number:') }} <strong class="text-[#A8895F]">+{{ config('payment.whatsapp.phone_number') }}</strong></p>
                            </div>
                        </label>

                        <!-- Option 2: Mobile Money Lipa Namba -->
                        <label @click="selectedMethod = 'mobile_money'"
                               class="navy-card p-4 polygon-card flex flex-col cursor-pointer border hover:border-[#A8895F] bg-[#17130F]"
                               :class="selectedMethod === 'mobile_money' ? 'border-[#A8895F]' : 'border-[#322B23]'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="mobile_money" x-model="selectedMethod" class="accent-[#A8895F]">
                                    <div>
                                        <span class="font-extrabold text-xs text-[#EDE5D8] block">{{ __('Lipa Kwa Simu (M-Pesa / Tigo Pesa / Airtel Money)') }}</span>
                                        <span class="text-[10px] text-[#B5A897] font-semibold">{{ __('Pay directly to our official Merchant Till Numbers') }}</span>
                                    </div>
                                </div>
                                <i data-lucide="smartphone" class="w-5 h-5 text-[#A8895F]"></i>
                            </div>

                            <div x-show="selectedMethod === 'mobile_money'" class="mt-3 pt-3 border-t border-[#322B23] text-[11px] text-[#B5A897] space-y-2">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                    <div class="p-2 bg-[#17130F] border border-[#322B23] rounded">
                                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block">{{ __('Vodacom M-Pesa') }}</span>
                                        <span class="font-mono text-xs font-bold text-[#EDE5D8] block">{{ __('Lipa Namba: :number', ['number' => config('payment.mobile_money.mpesa.till_number')]) }}</span>
                                        <span class="text-[9px] text-[#B5A897] block">{{ config('payment.mobile_money.mpesa.account_name') }}</span>
                                    </div>
                                    <div class="p-2 bg-[#17130F] border border-[#322B23] rounded">
                                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block">{{ __('Tigo Pesa') }}</span>
                                        <span class="font-mono text-xs font-bold text-[#EDE5D8] block">{{ __('Lipa Namba: :number', ['number' => config('payment.mobile_money.tigopesa.till_number')]) }}</span>
                                        <span class="text-[9px] text-[#B5A897] block">{{ config('payment.mobile_money.tigopesa.account_name') }}</span>
                                    </div>
                                    <div class="p-2 bg-[#17130F] border border-[#322B23] rounded">
                                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block">{{ __('Airtel Money') }}</span>
                                        <span class="font-mono text-xs font-bold text-[#EDE5D8] block">{{ __('Lipa Namba: :number', ['number' => config('payment.mobile_money.airtel.till_number')]) }}</span>
                                        <span class="text-[9px] text-[#B5A897] block">{{ config('payment.mobile_money.airtel.account_name') }}</span>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Option 3: Bank Transfer -->
                        <label @click="selectedMethod = 'bank_transfer'"
                               class="navy-card p-4 polygon-card flex flex-col cursor-pointer border hover:border-[#A8895F] bg-[#17130F]"
                               :class="selectedMethod === 'bank_transfer' ? 'border-[#A8895F]' : 'border-[#322B23]'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="bank_transfer" x-model="selectedMethod" class="accent-[#A8895F]">
                                    <div>
                                        <span class="font-extrabold text-xs text-[#EDE5D8] block">{{ __('Bank Wire Transfer (CRDB / NMB)') }}</span>
                                        <span class="text-[10px] text-[#B5A897] font-semibold">{{ __('Direct deposit or internet banking transfer') }}</span>
                                    </div>
                                </div>
                                <i data-lucide="building-2" class="w-5 h-5 text-[#A8895F]"></i>
                            </div>

                            <div x-show="selectedMethod === 'bank_transfer'" class="mt-3 pt-3 border-t border-[#322B23] text-[11px] text-[#B5A897] space-y-2">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <div class="p-2 bg-[#17130F] border border-[#322B23] rounded">
                                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block">{{ __('CRDB Bank') }}</span>
                                        <span class="font-mono text-xs font-bold text-[#EDE5D8] block">{{ __('Acc: :number', ['number' => config('payment.bank_transfer.crdb.account_number')]) }}</span>
                                        <span class="text-[9px] text-[#B5A897] block">{{ config('payment.bank_transfer.crdb.account_name') }}</span>
                                    </div>
                                    <div class="p-2 bg-[#17130F] border border-[#322B23] rounded">
                                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block">{{ __('NMB Bank') }}</span>
                                        <span class="font-mono text-xs font-bold text-[#EDE5D8] block">{{ __('Acc: :number', ['number' => config('payment.bank_transfer.nmb.account_number')]) }}</span>
                                        <span class="text-[9px] text-[#B5A897] block">{{ config('payment.bank_transfer.nmb.account_name') }}</span>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Option 4: Cash on Delivery -->
                        <label @click="selectedMethod = 'cash_on_delivery'"
                               class="navy-card p-4 polygon-card flex flex-col cursor-pointer border hover:border-[#A8895F] bg-[#17130F]"
                               :class="selectedMethod === 'cash_on_delivery' ? 'border-[#A8895F]' : 'border-[#322B23]'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="cash_on_delivery" x-model="selectedMethod" class="accent-[#A8895F]">
                                    <div>
                                        <span class="font-extrabold text-xs text-[#EDE5D8] block">{{ __('Cash / Mobile Money on Delivery') }}</span>
                                        <span class="text-[10px] text-[#B5A897] font-semibold">{{ __('Pay cash or mobile money upon receiving your package') }}</span>
                                    </div>
                                </div>
                                <i data-lucide="truck" class="w-5 h-5 text-[#A8895F]"></i>
                            </div>

                            <div x-show="selectedMethod === 'cash_on_delivery'" class="mt-3 pt-3 border-t border-[#322B23] text-[11px] text-[#B5A897] space-y-1">
                                <p>{{ __('📦 Cash on delivery is available for:') }} <strong class="text-[#A8895F]">{{ implode(', ', config('payment.cash_on_delivery.available_cities')) }}</strong>.</p>
                            </div>
                        </label>
                    </div>

                </div>

            </div>

            <!-- Right: Order Summary -->
            <div class="lg:col-span-5 space-y-6">

                <div class="glass-panel-gold p-6 polygon-card border border-[#A8895F]/40 space-y-4 bg-[#17130F]">
                    <h3 class="font-serif font-bold text-xl text-[#EDE5D8] pb-3 border-b border-[#322B23]">{{ __('YOUR SELECTION SUMMARY') }}</h3>

                    <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
                        @foreach($cart as $item)
                        <div class="flex gap-3 items-center">
                            <img src="{{ $item['image'] }}" data-sozie-fallback loading="lazy" decoding="async" class="w-12 h-12 object-cover polygon-card border border-[#322B23]">
                            <div class="flex-grow">
                                <h4 class="font-serif font-bold text-xs text-[#EDE5D8]">{{ $item['name'] }}</h4>
                                <span class="text-[10px] text-[#A8895F] font-extrabold">{{ $item['size'] }} x {{ $item['quantity'] }}</span>
                            </div>
                            <span class="text-sm sm:text-xs font-extrabold text-[#EDE5D8]">TZS {{ number_format($item['price'] * $item['quantity'], 0) }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-[#322B23] space-y-2 text-xs text-[#B5A897] font-bold">
                        <div class="flex justify-between">
                            <span>{{ __('Subtotal') }}</span>
                            <span>TZS {{ number_format($subtotal, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>{{ __('Delivery Fee') }}</span>
                            @if($shipping == 0)
                            <span class="text-emerald-400 uppercase font-extrabold">{{ __('FREE DELIVERY') }}</span>
                            @else
                            <span>TZS {{ number_format($shipping, 0) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="pt-3 border-t border-[#322B23] flex justify-between items-center">
                        <span class="font-serif font-bold text-lg text-[#EDE5D8]">{{ __('GRAND TOTAL') }}</span>
                        <span class="font-serif font-bold text-2xl text-[#A8895F]">TZS {{ number_format($total, 0) }}</span>
                    </div>

                    <button type="submit"
                            class="w-full py-4 bg-[#A8895F] text-[#12100E] font-extrabold text-xs uppercase tracking-[0.2em] polygon-btn text-center block hover:bg-[#12100E] hover:text-[#F8F5EF] shadow-xl shadow-[#A8895F]/30">
                        {{ __('CONFIRM & PLACE ORDER') }}
                    </button>
                </div>

            </div>

        </div>
    </form>

</div>

@endsection
