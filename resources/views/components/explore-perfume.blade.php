@props([
    'image',
    'brand',
    'brandSlug' => null,
    'name',
    'slug' => null,
    'family',
    'gender' => 'Unisex',
    'season' => 'All Season',
    'rating' => '4.8'
])

<div
    class="group relative block overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl dark:border-stone-700 dark:bg-[#232323]">

    {{-- Whole-card link (sits beneath the brand link so both stay clickable) --}}
    <a
        href="{{ $slug ? route('perfume.detail', ['slug' => $slug]) : '#' }}"
        class="absolute inset-0 z-10"
        aria-label="{{ $name }}"></a>

    {{-- Image --}}
    <div class="overflow-hidden">

        <img loading="lazy" decoding="async"
            src="{{ asset($image) }}"
            alt="{{ $name }}"
            onerror="this.onerror=null;this.src='{{ $slug ? route('placeholder.perfume', $slug) : asset('images/placeholder.svg') }}';"
            class="aspect-[4/5] w-full object-cover transition duration-700 group-hover:scale-105">

    </div>

    {{-- Content --}}
    <div class="p-6">

        @if($brandSlug)

            <a
                href="{{ route('brand.detail', $brandSlug) }}"
                class="relative z-20 text-xs uppercase tracking-[0.25em] text-stone-500 transition hover:text-[#B08D57]">
                {{ $brand }}
            </a>

        @else

            <p class="text-xs uppercase tracking-[0.25em] text-stone-500">
                {{ $brand }}
            </p>

        @endif

        <h3 class="mt-2 font-serif text-2xl">
            {{ $name }}
        </h3>

        <span
            class="mt-4 inline-block rounded-full bg-stone-100 px-3 py-1 text-xs dark:bg-stone-700">

            {{ $family }}

        </span>

        <div class="mt-5 flex items-center justify-between text-sm text-stone-500">

            <span>
                ⭐ {{ $rating }}
            </span>

            <span>
                {{ $gender }} • {{ $season }}
            </span>

        </div>

        <span
            class="mt-6 inline-flex items-center gap-2 font-medium text-[#B08D57] transition group-hover:gap-3">

            View Details →

        </span>

    </div>

</div>
