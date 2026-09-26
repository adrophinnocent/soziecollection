@extends('admin.layout')

@section('page_title', 'Website Content & Media')

@section('content')
<div class="space-y-6">

    @if($errors->any())
    <div class="p-4 bg-rose-800/10 border border-rose-800 text-rose-900 text-xs font-bold polygon-card flex items-start gap-2">
        <i data-lucide="alert-circle" class="w-5 h-5 text-rose-800 flex-shrink-0"></i>
        <div>
            <p class="font-extrabold uppercase tracking-wider mb-1">Please fix the highlighted fields.</p>
            <p>{{ $errors->first() }}</p>
        </div>
    </div>
    @endif

    <!-- Store Announcement Settings -->
    <div class="bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm p-6 space-y-4">
        <div class="border-b border-[#D8C9B8] pb-3 flex justify-between items-center">
            <div>
                <h4 class="font-serif font-bold text-lg text-[#29241F]">Store Announcement & Language Bar</h4>
                <p class="text-xs text-gray-600">Controls the top announcement bar displayed across the entire store.</p>
            </div>
            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-900 border border-emerald-300 font-extrabold text-[10px] uppercase rounded">Live</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-2">
                <span class="text-[10px] font-extrabold uppercase text-[#A8895F]">English Banner Text</span>
                <p class="text-xs text-[#29241F] font-bold">SOZIE COLLECTION</p>
            </div>
            <div class="p-4 bg-white border border-[#D8C9B8] polygon-card space-y-2">
                <span class="text-[10px] font-extrabold uppercase text-[#A8895F]">Bilingual EN / SW Toggle</span>
                <p class="text-xs text-emerald-800 font-extrabold">Active (Dynamic Switcher Enabled)</p>
            </div>
        </div>
    </div>

    <!-- Homepage Hero Slides -->
    <div class="bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm p-6 space-y-5">
        <div class="border-b border-[#D8C9B8] pb-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h4 class="font-serif font-bold text-lg text-[#29241F]">Homepage Hero Slides</h4>
                <p class="text-xs text-gray-600 mt-1">Upload your own campaign design, control the text, and decide which slide appears first.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-2.5 py-1 bg-[#EDE5D8] border border-[#D8C9B8] text-[10px] font-extrabold uppercase rounded">
                    {{ $banners->count() }} {{ \Illuminate\Support\Str::plural('slide', $banners->count()) }}
                </span>
                <a href="{{ route('home') }}" target="_blank"
                   class="px-4 py-2 bg-[#29241F] text-white text-[10px] font-extrabold uppercase polygon-btn hover:bg-[#A8895F]">
                    Preview Homepage
                </a>
            </div>
        </div>

        <details class="border border-[#A8895F]/40 bg-white polygon-card">
            <summary class="cursor-pointer list-none px-5 py-4 flex items-center justify-between gap-3 text-xs font-extrabold uppercase tracking-wider text-[#A8895F]">
                <span class="flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add New Homepage Slide
                </span>
                <span class="text-[10px] text-gray-500 normal-case font-semibold">Upload desktop design; mobile design is optional</span>
            </summary>

            <form action="{{ route('admin.slides.store') }}" method="POST" enctype="multipart/form-data" class="border-t border-[#D8C9B8] p-5">
                @csrf
                @include('admin.content.partials.slide-form', [
                    'banner' => null,
                    'defaultSortOrder' => $banners->count() + 1,
                ])

                <div class="pt-4 mt-4 border-t border-[#D8C9B8] flex justify-end">
                    <button type="submit" class="px-5 py-3 bg-[#A8895F] text-white text-xs font-extrabold uppercase tracking-wider polygon-btn hover:bg-[#29241F]">
                        Create Homepage Slide
                    </button>
                </div>
            </form>
        </details>

        <div class="space-y-4">
            @forelse($banners as $banner)
            <article class="border border-[#D8C9B8] bg-white polygon-card overflow-hidden">
                <div class="p-4 flex flex-col md:flex-row gap-4 md:items-center">
                    <div class="w-full md:w-48 aspect-video md:aspect-square shrink-0 bg-[#EDE5D8] border border-[#D8C9B8] overflow-hidden">
                        <img src="{{ $banner->image_url }}" loading="lazy" decoding="async" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                    </div>

                    <div class="flex-grow min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="text-[9px] font-extrabold uppercase tracking-widest text-[#A8895F]">Slide #{{ $banner->sort_order }}</span>
                            <span class="px-2 py-0.5 border text-[9px] font-extrabold uppercase rounded {{ $banner->is_active ? 'bg-emerald-100 text-emerald-900 border-emerald-300' : 'bg-gray-100 text-gray-700 border-gray-300' }}">
                                {{ $banner->is_active ? 'Live' : 'Hidden' }}
                            </span>
                        </div>
                        <h5 class="font-serif font-bold text-lg text-[#29241F]">{{ $banner->title }}</h5>
                        <p class="text-xs font-extrabold text-[#A8895F]">{{ $banner->headline ?: 'Headline not set' }} {{ $banner->highlight_text }}</p>
                        <p class="text-xs text-gray-600 font-medium mt-1 line-clamp-2">{{ $banner->subtitle }}</p>
                    </div>
                </div>

                <div class="border-t border-[#D8C9B8] bg-[#EDE5D8]/50 px-4 py-3 flex flex-wrap items-center justify-between gap-3">
                    <details class="flex-1">
                        <summary class="cursor-pointer list-none text-[10px] font-extrabold uppercase tracking-wider text-[#A8895F]">Edit Slide</summary>

                        <form action="{{ route('admin.slides.update', $banner) }}" method="POST" enctype="multipart/form-data" class="pt-4 space-y-4">
                            @csrf
                            @method('PUT')
                            @include('admin.content.partials.slide-form', ['banner' => $banner])

                            <div class="pt-4 border-t border-[#D8C9B8] flex justify-end">
                                <button type="submit" class="px-5 py-3 bg-[#A8895F] text-white text-xs font-extrabold uppercase tracking-wider polygon-btn hover:bg-[#29241F]">
                                    Save Slide Changes
                                </button>
                            </div>
                        </form>
                    </details>

                    <form action="{{ route('admin.slides.destroy', $banner) }}" method="POST" data-confirm="Delete this homepage slide and its uploaded designs?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2.5 bg-white border border-rose-300 text-rose-800 text-[10px] font-extrabold uppercase polygon-btn hover:bg-rose-800 hover:text-white">
                            Delete Slide
                        </button>
                    </form>
                </div>
            </article>
            @empty
            <div class="p-8 text-center bg-[#EDE5D8] border border-dashed border-[#A8895F]/50 polygon-card">
                <i data-lucide="images" class="w-10 h-10 text-[#A8895F] mx-auto mb-3"></i>
                <p class="font-serif font-bold text-lg text-[#29241F]">No homepage slides yet</p>
                <p class="text-xs text-gray-600 mt-1">Use “Add New Homepage Slide” to upload your first design. The default hero remains active until you create one.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

<script>
    document.querySelectorAll('[data-preview-id]').forEach((input) => {
        input.addEventListener('change', () => {
            const file = input.files?.[0];
            const preview = document.querySelector(`[data-image-preview="${input.dataset.previewId}"]`);

            if (file && preview) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            }
        });
    });

    document.querySelectorAll('[data-mobile-preview-id]').forEach((input) => {
        input.addEventListener('change', () => {
            const file = input.files?.[0];
            const preview = document.querySelector(`[data-mobile-preview="${input.dataset.mobilePreviewId}"]`);

            if (file && preview) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            }
        });
    });

    document.querySelectorAll('[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });
</script>
@endsection
