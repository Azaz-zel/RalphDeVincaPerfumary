@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')

<section class="flex min-h-[70vh] items-center py-24">

    <div class="mx-auto max-w-7xl px-8 text-center lg:px-16">

        <p class="text-sm uppercase tracking-[0.4em] text-[#B08D57]">
            Error 404
        </p>

        <h1 class="mt-5 font-serif text-4xl font-semibold sm:text-5xl lg:text-6xl">
            This Scent Has
            <span class="text-[#B08D57]">Faded</span>
        </h1>

        <p class="mx-auto mt-6 max-w-xl text-base leading-8 text-stone-600 dark:text-stone-400 lg:text-lg">
            The page you're looking for doesn't exist, or may have been moved.
            Let's get you back to something fragrant.
        </p>

        <div class="mt-10 flex flex-wrap justify-center gap-4">

            <a href="{{ route('home') }}"
                class="rounded-full bg-[#B08D57] px-8 py-4 font-medium text-white transition hover:opacity-90">
                Back to Home
            </a>

            <a href="{{ route('explore') }}"
                class="rounded-full border border-stone-300 px-8 py-4 font-medium transition hover:border-[#B08D57] hover:text-[#B08D57] dark:border-stone-600">
                Explore Perfumes
            </a>

        </div>

        {{-- Quick links so the page is a useful dead end, not a wall --}}
        <div class="mx-auto mt-16 max-w-md border-t border-stone-200 pt-10 dark:border-stone-700">

            <p class="text-xs uppercase tracking-[0.3em] text-stone-500">
                Or browse
            </p>

            <div class="mt-5 flex flex-wrap justify-center gap-3">

                <a href="{{ route('brands.index') }}"
                    class="rounded-full bg-stone-100 px-5 py-2 text-sm transition hover:bg-[#B08D57] hover:text-white dark:bg-stone-800">
                    Fragrance Houses
                </a>

                <a href="{{ route('notes.index') }}"
                    class="rounded-full bg-stone-100 px-5 py-2 text-sm transition hover:bg-[#B08D57] hover:text-white dark:bg-stone-800">
                    Fragrance Notes
                </a>

                <a href="{{ route('academy') }}"
                    class="rounded-full bg-stone-100 px-5 py-2 text-sm transition hover:bg-[#B08D57] hover:text-white dark:bg-stone-800">
                    Academy
                </a>

            </div>

        </div>

    </div>

</section>

@endsection
