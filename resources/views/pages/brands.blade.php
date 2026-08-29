@extends('layouts.app')

@section('title', 'Brands')

@section('content')

{{-- Hero --}}
<section class="border-b border-stone-200 bg-stone-50 py-20 dark:border-stone-800 dark:bg-[#1B1A17]">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        <p class="text-sm text-stone-500">

            <a href="{{ route('home') }}" class="transition hover:text-[#B08D57]">Home</a>

            <span class="mx-2">/</span>

            <span class="text-[#B08D57]">Brands</span>

        </p>

        <h1 class="mt-6 font-serif text-4xl font-semibold sm:text-5xl lg:text-6xl">

            Fragrance
            <span class="text-[#B08D57]">Houses</span>

        </h1>

        <p class="mt-6 max-w-2xl text-base leading-8 text-stone-600 dark:text-stone-400 lg:text-lg">

            From centuries-old maisons to independent niche perfumers —
            explore the houses behind the fragrances.

        </p>

    </div>

</section>

{{-- Filters --}}
<section class="py-10" data-filter-root data-endpoint="{{ route('brands.index') }}">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        <form data-filter-form class="flex flex-col gap-4 md:flex-row">

            <div class="relative flex-1">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-5 top-1/2 h-5 w-5 -translate-y-1/2 text-stone-400"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.3-4.3m1.3-5.2a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search brands..."
                    class="w-full rounded-2xl border border-stone-300 bg-white py-4 pl-14 pr-5 outline-none transition focus:border-[#B08D57] dark:border-stone-700 dark:bg-[#232323]">

            </div>

            <button
                type="submit"
                class="rounded-2xl bg-stone-900 px-7 py-4 text-sm font-medium text-white transition hover:bg-[#B08D57] dark:bg-stone-100 dark:text-stone-900">
                Search
            </button>

        </form>

        {{-- Type filter + clear --}}
        <div class="mt-6 flex flex-wrap items-center gap-3">

            <button type="button" data-filter="type" data-value=""
                class="rounded-full border px-5 py-2 text-sm transition {{ !$type ? 'border-[#B08D57] bg-[#B08D57] text-white' : 'border-stone-300 hover:border-[#B08D57] dark:border-stone-700' }}">
                All Houses ({{ $designerCount + $nicheCount }})
            </button>

            <button type="button" data-filter="type" data-value="designer"
                class="rounded-full border px-5 py-2 text-sm transition {{ $type === 'designer' ? 'border-[#B08D57] bg-[#B08D57] text-white' : 'border-stone-300 hover:border-[#B08D57] dark:border-stone-700' }}">
                Designer ({{ $designerCount }})
            </button>

            <button type="button" data-filter="type" data-value="niche"
                class="rounded-full border px-5 py-2 text-sm transition {{ $type === 'niche' ? 'border-[#B08D57] bg-[#B08D57] text-white' : 'border-stone-300 hover:border-[#B08D57] dark:border-stone-700' }}">
                Niche ({{ $nicheCount }})
            </button>

            <button type="button" data-clear-filters
                class="rounded-full border border-stone-300 px-5 py-2 text-sm text-stone-600 transition hover:border-[#B08D57] hover:text-[#B08D57] dark:border-stone-700 dark:text-stone-300 {{ ($search || $type) ? '' : 'hidden' }}">
                Clear Filters
            </button>

        </div>

    </div>

</section>

{{-- Grid --}}
<section class="pb-24">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        <div data-filter-results>
            <x-brands-grid :brands="$brands" />
        </div>

    </div>

</section>

@endsection
