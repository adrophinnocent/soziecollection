@extends('layouts.app')

@section('title', 'Saved Addresses | Sozie Collection')

@section('content')
@include('account._sidebar_layout', [
    'account_title' => 'Saved Shipping Addresses',
    'account_subtitle' => 'Manage your delivery locations. Set a default address for super-fast checkout next time you order your favorite Sozie fragrances.'
])
@endsection

@section('account_content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-5">
    <div class="flex items-center gap-3 text-[11px] font-bold text-gray-600">
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#A8895F]/15 text-[#A8895F] rounded-full border border-[#A8895F]/30 uppercase tracking-[0.2em] text-[9px] font-extrabold">
            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
            {{ $addresses->count() }} {{ Str::plural('Address', $addresses->count()) }} Saved
        </span>
    </div>
    <a href="{{ route('account.addresses.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#A8895F] text-white text-[10px] font-extrabold uppercase tracking-[0.25em] polygon-btn hover:bg-[#29241F] shadow-md">
        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
        Add New Address
    </a>
</div>

<div class="glass-panel border border-[#D8C9B8] polygon-card bg-[#F8F5EF] p-6 shadow-md">
    @if($addresses->isEmpty())
    <div class="py-20 text-center">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-[#A8895F]/10 border border-[#A8895F]/30 flex items-center justify-center">
            <i data-lucide="map-pinned" class="w-10 h-10 text-[#A8895F]/60"></i>
        </div>
        <h3 class="font-serif font-bold text-2xl text-[#29241F] mb-2">No saved addresses yet</h3>
        <p class="text-sm text-gray-500 font-medium max-w-md mx-auto mb-7">
            Store your most-used delivery locations for 1-click checkout on every future Sozie Collection fragrance order.
        </p>
        <a href="{{ route('account.addresses.create') }}"
           class="inline-block px-7 py-3 bg-[#A8895F] text-white text-xs font-extrabold uppercase tracking-[0.3em] polygon-btn hover:bg-[#29241F] shadow-lg inline-flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add First Address
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($addresses as $address)
        <div class="navy-card p-5 polygon-card bg-white border border-[#D8C9B8] relative overflow-hidden hover:shadow-md transition-shadow
            {{ $address->is_default ? 'ring-2 ring-[#A8895F] ring-offset-2 ring-offset-[#F8F5EF]' : '' }}">
            @if($address->is_default)
            <span class="absolute top-4 right-4 inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-[#A8895F] text-white text-[9px] uppercase tracking-[0.2em] font-extrabold shadow">
                <i data-lucide="badge-check" class="w-3 h-3"></i>
                Default
            </span>
            @endif

            <div class="flex items-start gap-3 mb-3">
                <div class="w-10 h-10 flex-shrink-0 rounded-full bg-[#A8895F]/15 border border-[#A8895F]/40 flex items-center justify-center">
                    <i data-lucide="home" class="w-4 h-4 text-[#A8895F]"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="font-serif font-bold text-lg text-[#29241F] leading-tight mb-1 pr-16">{{ $address->label }}</h4>
                    <p class="text-xs font-extrabold text-[#29241F] leading-snug mb-0.5">{{ $address->full_name }}</p>
                    <p class="text-[11px] text-gray-600 font-bold flex items-center gap-1.5">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                        {{ $address->phone }}
                    </p>
                </div>
            </div>

            <address class="not-italic text-xs text-gray-700 font-medium leading-relaxed bg-[#EDE5D8]/60 border border-[#D8C9B8] p-3 polygon-card mb-4 min-h-[60px]">
                <p class="font-bold">{{ $address->city }}</p>
                <p>{{ $address->street_address }}</p>
            </address>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('account.addresses.edit', $address->id) }}"
                   class="px-3 py-1.5 bg-white border border-[#A8895F]/50 text-[#29241F] text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn hover:bg-[#EDE5D8] transition-all inline-flex items-center gap-1.5">
                    <i data-lucide="pencil" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                    Edit
                </a>

                @if(! $address->is_default)
                <form action="{{ route('account.addresses.default', $address->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="px-3 py-1.5 bg-[#EDE5D8] border border-[#D8C9B8] text-[#29241F] text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn hover:bg-[#D8C9B8] transition-all inline-flex items-center gap-1.5">
                        <i data-lucide="star" class="w-3.5 h-3.5 text-[#A8895F]"></i>
                        Default
                    </button>
                </form>
                @endif

                <form action="{{ route('account.addresses.destroy', $address->id) }}" method="POST" class="inline-block ml-auto"
                      onsubmit="return confirm('Delete this saved address? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-3 py-1.5 bg-white border border-rose-200 text-rose-700 text-[10px] font-extrabold uppercase tracking-[0.2em] polygon-btn hover:bg-rose-50 transition-all inline-flex items-center gap-1.5">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        Remove
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
