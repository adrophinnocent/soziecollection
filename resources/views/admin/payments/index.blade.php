@extends('admin.layout')

@section('page_title', 'Payment Methods & Delivery Setup')

@section('content')
<div class="space-y-6">

    <!-- Active Gateways -->
    <div class="bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm p-6 space-y-4">
        <div class="border-b border-[#D8C9B8] pb-3 flex justify-between items-center">
            <div>
                <h4 class="font-serif font-bold text-lg text-[#29241F]">Supported Payment Gateways</h4>
                <p class="text-xs text-gray-600">Active payment options during customer checkout.</p>
            </div>
            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-900 border border-emerald-300 font-extrabold text-[10px] uppercase rounded">Configured</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-serif font-bold text-base text-[#29241F]">WhatsApp Direct Order</span>
                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-900 font-extrabold text-[9px] uppercase rounded">Active</span>
                </div>
                <p class="text-xs text-gray-600 font-medium">Allows instant 1-click checkout directly to WhatsApp Concierge.</p>
            </div>

            <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-serif font-bold text-base text-[#29241F]">M-Pesa & Tigo Pesa</span>
                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-900 font-extrabold text-[9px] uppercase rounded">Active</span>
                </div>
                <p class="text-xs text-gray-600 font-medium">Tanzania local mobile money payment instructions displayed upon order placement.</p>
            </div>

            <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-serif font-bold text-base text-[#29241F]">Cash on Delivery</span>
                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-900 font-extrabold text-[9px] uppercase rounded">Active</span>
                </div>
                <p class="text-xs text-gray-600 font-medium">Pay upon doorstep package arrival across Dar es Salaam and Tanzania cities.</p>
            </div>
        </div>
    </div>

    <!-- Delivery Fee Thresholds -->
    <div class="bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm p-6 space-y-4">
        <h4 class="font-serif font-bold text-lg text-[#29241F] border-b border-[#D8C9B8] pb-3">Delivery Rates & Free Shipping Rule</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-1">
                <span class="text-[10px] font-extrabold uppercase text-[#A8895F]">Standard Delivery Fee</span>
                <span class="font-serif font-bold text-xl text-[#29241F] block">TZS {{ number_format($config['delivery_fee'] ?? 5000) }}</span>
            </div>

            <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-1">
                <span class="text-[10px] font-extrabold uppercase text-[#A8895F]">Free Delivery Order Threshold</span>
                <span class="font-serif font-bold text-xl text-emerald-800 block">TZS {{ number_format($config['free_delivery_threshold'] ?? 100000) }}+</span>
            </div>
        </div>
    </div>

</div>
@endsection
