@extends('layouts.app')

@section('title', 'Checkout & Payment | Sozie Collection')

@section('content')

<div class="py-12 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="mb-8 border-b border-[#D8C9B8] pb-4">
        <span class="text-xs font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-1">FINAL STEP</span>
        <h1 class="font-serif font-bold text-3xl sm:text-4xl text-[#29241F]">CHECKOUT & DELIVERY DETAILS</h1>
    </div>

    @php
        $isLoggedIn = Auth::check() && ! (Auth::user()->isAdmin ?? false);
    @endphp

    @if(! $isLoggedIn)
    <div class="mb-7 glass-panel-gold border-2 border-[#A8895F]/30 polygon-card bg-[#F8F5EF] p-6 shadow-md">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-[#D8C9B8]">
            <div class="w-10 h-10 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center">
                <i data-lucide="user-round-check" class="w-5 h-5 text-[#A8895F]"></i>
            </div>
            <div>
                <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-0.5">Step 1 of 3</span>
                <h3 class="font-serif font-bold text-xl text-[#29241F]">Choose How You Want to Checkout</h3>
            </div>
            <span class="ml-auto text-[10px] font-extrabold uppercase tracking-[0.25em] text-gray-500 bg-[#EDE5D8] border border-[#D8C9B8] px-3 py-1 rounded">
                No account required to order
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="{ mode: '{{ old('checkout_mode', 'guest') }}' }">
            <input type="hidden" name="checkout_mode" form="checkout-main-form" :value="mode">

            <label @click="mode = 'guest'"
                   class="navy-card p-5 polygon-card cursor-pointer transition-all group
                          ring-2 ring-offset-2 ring-offset-[#F8F5EF] border-2 bg-white
                          :class=\"mode === 'guest' ? 'ring-[#A8895F] border-[#A8895F] shadow-xl' : 'ring-transparent border-[#D8C9B8] hover:border-[#A8895F]/60 shadow-sm'\">
                <input type="radio" name="checkout_mode_select" value="guest" x-model="mode" class="sr-only">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-[#29241F] to-[#A8895F]/70 flex items-center justify-center shadow-md">
                        <i data-lucide="user-round-cog" class="w-5 h-5 text-white"></i>
                    </div>
                    <span
                          :class=\"mode === 'guest' ? 'bg-[#A8895F] border-[#A8895F] text-white' : 'bg-white border-[#D8C9B8] text-gray-500'\"
                          class="inline-flex items-center justify-center w-6 h-6 rounded-full border-2 transition-colors flex-shrink-0">
                        <i data-lucide="check" class="w-3.5 h-3.5" :class=\"mode === 'guest' ? 'opacity-100' : 'opacity-0'\"></i>
                    </span>
                </div>
                <h4 class="font-serif font-bold text-lg text-[#29241F] mb-1.5 leading-tight group-hover:text-[#A8895F] transition-colors">
                    Continue as Guest
                </h4>
                <p class="text-[11px] text-gray-600 font-medium leading-relaxed mb-3">
                    Fastest option. Order instantly without creating any account. Track later via order number sent to your phone.
                </p>
                <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-[0.2em] text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded">
                    <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                    Recommended
                </span>
            </label>

            <label @click="window.location = '{{ route('login') }}?next={{ urlencode(route('checkout.index')) }}'"
                   class="navy-card p-5 polygon-card cursor-pointer transition-all group border-2 bg-white border-[#D8C9B8] hover:border-[#A8895F]/60 shadow-sm hover:shadow-md">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-full bg-[#29241F] flex items-center justify-center shadow-md">
                        <i data-lucide="log-in" class="w-5 h-5 text-[#A8895F]"></i>
                    </div>
                    <a href="{{ route('login') }}?next={{ urlencode(route('checkout.index')) }}"
                       class="inline-flex items-center justify-center w-6 h-6 rounded-full border-2 bg-white border-[#D8C9B8] text-gray-400 hover:border-[#A8895F] hover:text-[#A8895F] transition-colors flex-shrink-0">
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
                <h4 class="font-serif font-bold text-lg text-[#29241F] mb-1.5 leading-tight group-hover:text-[#A8895F] transition-colors">
                    Sign In
                </h4>
                <p class="text-[11px] text-gray-600 font-medium leading-relaxed mb-3">
                    Returning Sozie member? Sign in for auto-filled saved addresses, wishlist access and order history.
                </p>
                <span class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#A8895F] flex items-center gap-1.5">
                    Go to Sign In <i data-lucide="chevron-right" class="w-3 h-3"></i>
                </span>
            </label>

            <label @click="window.location = '{{ route('register') }}?next={{ urlencode(route('checkout.index')) }}'"
                   class="navy-card p-5 polygon-card cursor-pointer transition-all group border-2 bg-white border-[#D8C9B8] hover:border-[#A8895F]/60 shadow-sm hover:shadow-md">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-[#A8895F] to-[#D8C9B8] flex items-center justify-center shadow-md">
                        <i data-lucide="user-plus" class="w-5 h-5 text-[#29241F]"></i>
                    </div>
                    <a href="{{ route('register') }}?next={{ urlencode(route('checkout.index')) }}"
                       class="inline-flex items-center justify-center w-6 h-6 rounded-full border-2 bg-white border-[#D8C9B8] text-gray-400 hover:border-[#A8895F] hover:text-[#A8895F] transition-colors flex-shrink-0">
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
                <h4 class="font-serif font-bold text-lg text-[#29241F] mb-1.5 leading-tight group-hover:text-[#A8895F] transition-colors">
                    Create Account
                </h4>
                <p class="text-[11px] text-gray-600 font-medium leading-relaxed mb-3">
                    Join the Sozie VIP Atelier. Save wishlists, unlock member-only deals and reorder in a single click.
                </p>
                <span class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#A8895F] flex items-center gap-1.5">
                    Go to Register <i data-lucide="chevron-right" class="w-3 h-3"></i>
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
                <div class="glass-panel-gold p-6 polygon-card border-2 border-[#A8895F]/30 space-y-5 bg-[#F8F5EF] shadow-md">
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-10 h-10 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center">
                            <i data-lucide="map-pinned" class="w-5 h-5 text-[#A8895F]"></i>
                        </div>
                        <div class="flex-1">
                            <span class="text-[10px] font-extrabold text-[#A8895F] uppercase tracking-[0.3em] block mb-0.5">Saved Addresses</span>
                            <h3 class="font-serif font-bold text-lg text-[#29241F]">Use 1-Click Saved Address or Enter New</h3>
                        </div>
                        <a href="{{ route('account.addresses.create') }}" target="_blank"
                           class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#A8895F] hover:text-[#29241F] transition-colors inline-flex items-center gap-1.5">
                            <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                            Add New
                        </a>
                    </div>

                    <div x-data="{ selected: {{ (int) old('saved_address_id', $selectedAddressId ?? 0) }} }">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                            @foreach($savedAddresses as $addr)
                            <label @click="selected = {{ $addr->id }}"
                                   class="cursor-pointer p-4 polygon-card border-2 bg-white transition-all
                                          {{ $addr->is_default ? 'ring-1 ring-offset-1 ring-offset-[#F8F5EF] ring-[#A8895F]/40' : '' }}
                                          :class=\"selected === {{ $addr->id }} ? 'border-[#A8895F] shadow-lg bg-[#EDE5D8]/50' : 'border-[#D8C9B8] hover:border-[#A8895F]/60'\">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="saved_address_id" value="{{ $addr->id }}" x-model="selected" class="sr-only">
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full border-2 transition-colors flex-shrink-0"
                                              :class=\"selected === {{ $addr->id }} ? 'bg-[#A8895F] border-[#A8895F]' : 'bg-white border-[#D8C9B8]'\">
                                            <i data-lucide="check" class="w-3 h-3 text-white" :class=\"selected === {{ $addr->id }} ? 'opacity-100' : 'opacity-0'\"></i>
                                        </span>
                                        <span class="font-serif font-bold text-sm text-[#29241F]">{{ $addr->label }}</span>
                                    </div>
                                    @if($addr->is_default)
                                    <span class="polygon-badge bg-[#A8895F] text-white text-[9px] uppercase tracking-widest font-extrabold px-2 py-0.5">DEFAULT</span>
                                    @endif
                                </div>
                                <p class="text-[11px] font-bold text-[#29241F] leading-tight mb-0.5">{{ $addr->full_name }} • {{ $addr->phone }}</p>
                                <p class="text-[11px] text-gray-600 font-semibold leading-relaxed line-clamp-2">{{ $addr->city }} — {{ $addr->street_address }}</p>
                            </label>
                            @endforeach
                            <label @click="selected = 0"
                                   class="cursor-pointer p-4 polygon-card border-2 border-dashed bg-[#EDE5D8]/40 transition-all flex flex-col items-center justify-center text-center min-h-[120px]
                                          :class=\"selected === 0 ? 'border-[#29241F] bg-white shadow-lg' : 'border-[#D8C9B8] hover:border-[#A8895F]/60'\">
                                <input type="radio" name="saved_address_id" value="0" x-model="selected" class="sr-only">
                                <div class="w-10 h-10 rounded-full bg-white border-2 border-dashed border-[#A8895F]/60 flex items-center justify-center mb-2">
                                    <i data-lucide="pencil-line" class="w-5 h-5 text-[#A8895F]"></i>
                                </div>
                                <h5 class="font-serif font-bold text-sm text-[#29241F] mb-0.5">Enter New Address</h5>
                                <p class="text-[10px] text-gray-500 font-semibold leading-tight max-w-[180px]">Type in a one-off delivery location below.</p>
                            </label>
                        </div>
                    </div>
                </div>
                @endif

                <div class="glass-panel p-6 polygon-card border border-[#D8C9B8] space-y-4 bg-[#F8F5EF]">
                    <h3 class="font-serif font-bold text-xl text-[#29241F]">1. SHIPPING & CONTACT INFORMATION</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">Full Name *</label>
                            <input type="text" name="customer_name" required value="{{ $prefill['customer_name'] }}"
                                   placeholder="e.g. Amina Khamis"
                                   class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F] font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">Phone Number (WhatsApp) *</label>
                            <input type="text" name="customer_phone" required value="{{ $prefill['customer_phone'] }}"
                                   placeholder="e.g. 0712345678"
                                   class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F] font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">Email Address (Optional)</label>
                        <input type="email" name="customer_email" value="{{ $prefill['customer_email'] }}"
                               placeholder="e.g. amina@example.com"
                               class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F] font-bold">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">City / Region *</label>
                            <select name="city" class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F] font-bold">
                                @if(! empty($prefill['city']))
                                <option value="{{ $prefill['city'] }}" selected>{{ $prefill['city'] }}</option>
                                @endif
                                <option value="Dar es Salaam" {{ old('city') === 'Dar es Salaam' ? 'selected' : '' }}>Dar es Salaam</option>
                                <option value="Arusha">Arusha</option>
                                <option value="Dodoma">Dodoma</option>
                                <option value="Mwanza">Mwanza</option>
                                <option value="Zanzibar">Zanzibar</option>
                                <option value="Other">Other Region</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">Detailed Street Address / Landmark *</label>
                            <input type="text" name="shipping_address" required value="{{ $prefill['shipping_address'] }}"
                                   placeholder="e.g. Masaki, Haile Selassie Rd, House 42"
                                   class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-2.5 focus:outline-none focus:border-[#A8895F] font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-[#A8895F] uppercase tracking-widest mb-1">Order Notes (Optional)</label>
                        <textarea name="notes" rows="2" placeholder="Special delivery instructions or perfume gift message..."
                                  class="w-full bg-white border border-[#D8C9B8] text-xs text-[#29241F] px-3 py-2 focus:outline-none focus:border-[#A8895F] font-medium"></textarea>
                    </div>

                </div>

                <!-- Payment Method Configuration & Options -->
                <div class="glass-panel p-6 polygon-card border border-[#D8C9B8] space-y-4 bg-[#F8F5EF]" x-data="{ selectedMethod: 'whatsapp' }">
                    <h3 class="font-serif font-bold text-xl text-[#29241F]">2. PAYMENT METHOD CONFIGURATION</h3>

                    <div class="space-y-3">
                        <!-- Option 1: WhatsApp Direct -->
                        <label @click="selectedMethod = 'whatsapp'"
                               class="navy-card p-4 polygon-card flex flex-col cursor-pointer border hover:border-[#A8895F] bg-white"
                               :class="selectedMethod === 'whatsapp' ? 'border-[#A8895F]' : 'border-[#D8C9B8]'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="whatsapp" x-model="selectedMethod" class="accent-[#A8895F]">
                                    <div>
                                        <span class="font-extrabold text-xs text-[#29241F] block">WhatsApp Direct Confirmation</span>
                                        <span class="text-[10px] text-gray-600 font-semibold">Instant order confirmation via WhatsApp agent</span>
                                    </div>
                                </div>
                                <i data-lucide="message-circle" class="w-5 h-5 text-emerald-600"></i>
                            </div>

                            <div x-show="selectedMethod === 'whatsapp'" class="mt-3 pt-3 border-t border-[#D8C9B8] text-[11px] text-gray-700 space-y-1">
                                <p>✅ After clicking place order, you will be redirected to WhatsApp with your auto-generated itemized receipt.</p>
                                <p>📱 Business Number: <strong class="text-[#A8895F]">+{{ config('payment.whatsapp.phone_number') }}</strong></p>
                            </div>
                        </label>

                        <!-- Option 2: Mobile Money Lipa Namba -->
                        <label @click="selectedMethod = 'mobile_money'"
                               class="navy-card p-4 polygon-card flex flex-col cursor-pointer border hover:border-[#A8895F] bg-white"
                               :class="selectedMethod === 'mobile_money' ? 'border-[#A8895F]' : 'border-[#D8C9B8]'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="mobile_money" x-model="selectedMethod" class="accent-[#A8895F]">
                                    <div>
                                        <span class="font-extrabold text-xs text-[#29241F] block">Lipa Kwa Simu (M-Pesa / Tigo Pesa / Airtel Money)</span>
                                        <span class="text-[10px] text-gray-600 font-semibold">Pay directly to our official Merchant Till Numbers</span>
                                    </div>
                                </div>
                                <i data-lucide="smartphone" class="w-5 h-5 text-[#A8895F]"></i>
                            </div>

                            <div x-show="selectedMethod === 'mobile_money'" class="mt-3 pt-3 border-t border-[#D8C9B8] text-[11px] text-gray-700 space-y-2">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                    <div class="p-2 bg-[#F8F5EF] border border-[#D8C9B8] rounded">
                                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block">Vodacom M-Pesa</span>
                                        <span class="font-mono text-xs font-bold text-[#29241F] block">Lipa Namba: {{ config('payment.mobile_money.mpesa.till_number') }}</span>
                                        <span class="text-[9px] text-gray-600 block">{{ config('payment.mobile_money.mpesa.account_name') }}</span>
                                    </div>
                                    <div class="p-2 bg-[#F8F5EF] border border-[#D8C9B8] rounded">
                                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block">Tigo Pesa</span>
                                        <span class="font-mono text-xs font-bold text-[#29241F] block">Lipa Namba: {{ config('payment.mobile_money.tigopesa.till_number') }}</span>
                                        <span class="text-[9px] text-gray-600 block">{{ config('payment.mobile_money.tigopesa.account_name') }}</span>
                                    </div>
                                    <div class="p-2 bg-[#F8F5EF] border border-[#D8C9B8] rounded">
                                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block">Airtel Money</span>
                                        <span class="font-mono text-xs font-bold text-[#29241F] block">Lipa Namba: {{ config('payment.mobile_money.airtel.till_number') }}</span>
                                        <span class="text-[9px] text-gray-600 block">{{ config('payment.mobile_money.airtel.account_name') }}</span>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Option 3: Bank Transfer -->
                        <label @click="selectedMethod = 'bank_transfer'"
                               class="navy-card p-4 polygon-card flex flex-col cursor-pointer border hover:border-[#A8895F] bg-white"
                               :class="selectedMethod === 'bank_transfer' ? 'border-[#A8895F]' : 'border-[#D8C9B8]'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="bank_transfer" x-model="selectedMethod" class="accent-[#A8895F]">
                                    <div>
                                        <span class="font-extrabold text-xs text-[#29241F] block">Bank Wire Transfer (CRDB / NMB)</span>
                                        <span class="text-[10px] text-gray-600 font-semibold">Direct deposit or internet banking transfer</span>
                                    </div>
                                </div>
                                <i data-lucide="building-2" class="w-5 h-5 text-[#A8895F]"></i>
                            </div>

                            <div x-show="selectedMethod === 'bank_transfer'" class="mt-3 pt-3 border-t border-[#D8C9B8] text-[11px] text-gray-700 space-y-2">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <div class="p-2 bg-[#F8F5EF] border border-[#D8C9B8] rounded">
                                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block">CRDB Bank</span>
                                        <span class="font-mono text-xs font-bold text-[#29241F] block">Acc: {{ config('payment.bank_transfer.crdb.account_number') }}</span>
                                        <span class="text-[9px] text-gray-600 block">{{ config('payment.bank_transfer.crdb.account_name') }}</span>
                                    </div>
                                    <div class="p-2 bg-[#F8F5EF] border border-[#D8C9B8] rounded">
                                        <span class="text-[10px] font-extrabold text-[#A8895F] uppercase block">NMB Bank</span>
                                        <span class="font-mono text-xs font-bold text-[#29241F] block">Acc: {{ config('payment.bank_transfer.nmb.account_number') }}</span>
                                        <span class="text-[9px] text-gray-600 block">{{ config('payment.bank_transfer.nmb.account_name') }}</span>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Option 4: Cash on Delivery -->
                        <label @click="selectedMethod = 'cash_on_delivery'"
                               class="navy-card p-4 polygon-card flex flex-col cursor-pointer border hover:border-[#A8895F] bg-white"
                               :class="selectedMethod === 'cash_on_delivery' ? 'border-[#A8895F]' : 'border-[#D8C9B8]'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="cash_on_delivery" x-model="selectedMethod" class="accent-[#A8895F]">
                                    <div>
                                        <span class="font-extrabold text-xs text-[#29241F] block">Cash / Mobile Money on Delivery</span>
                                        <span class="text-[10px] text-gray-600 font-semibold">Pay cash or mobile money upon receiving your package</span>
                                    </div>
                                </div>
                                <i data-lucide="truck" class="w-5 h-5 text-[#A8895F]"></i>
                            </div>

                            <div x-show="selectedMethod === 'cash_on_delivery'" class="mt-3 pt-3 border-t border-[#D8C9B8] text-[11px] text-gray-700 space-y-1">
                                <p>📦 Cash on delivery is available for: <strong class="text-[#A8895F]">{{ implode(', ', config('payment.cash_on_delivery.available_cities')) }}</strong>.</p>
                            </div>
                        </label>
                    </div>

                </div>

            </div>

            <!-- Right: Order Summary -->
            <div class="lg:col-span-5 space-y-6">

                <div class="glass-panel-gold p-6 polygon-card border border-[#A8895F]/40 space-y-4 bg-[#F8F5EF]">
                    <h3 class="font-serif font-bold text-xl text-[#29241F] pb-3 border-b border-[#D8C9B8]">YOUR SELECTION SUMMARY</h3>

                    <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
                        @foreach($cart as $item)
                        <div class="flex gap-3 items-center">
                            <img src="{{ $item['image'] }}" class="w-12 h-12 object-cover polygon-card border border-[#D8C9B8]">
                            <div class="flex-grow">
                                <h4 class="font-serif font-bold text-xs text-[#29241F]">{{ $item['name'] }}</h4>
                                <span class="text-[10px] text-[#A8895F] font-extrabold">{{ $item['size'] }} x {{ $item['quantity'] }}</span>
                            </div>
                            <span class="text-xs font-bold text-[#29241F]">TZS {{ number_format($item['price'] * $item['quantity'], 0) }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-[#D8C9B8] space-y-2 text-xs text-gray-700 font-bold">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>TZS {{ number_format($subtotal, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Delivery Fee</span>
                            @if($shipping == 0)
                            <span class="text-emerald-700 uppercase font-extrabold">FREE DELIVERY</span>
                            @else
                            <span>TZS {{ number_format($shipping, 0) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="pt-3 border-t border-[#D8C9B8] flex justify-between items-center">
                        <span class="font-serif font-bold text-lg text-[#29241F]">GRAND TOTAL</span>
                        <span class="font-serif font-bold text-2xl text-[#A8895F]">TZS {{ number_format($total, 0) }}</span>
                    </div>

                    <button type="submit"
                            class="w-full py-4 bg-[#A8895F] text-white font-extrabold text-xs uppercase tracking-[0.2em] polygon-btn text-center block hover:bg-[#29241F] shadow-xl shadow-[#A8895F]/30">
                        CONFIRM & PLACE ORDER
                    </button>
                </div>

            </div>

        </div>
    </form>

</div>

@endsection
