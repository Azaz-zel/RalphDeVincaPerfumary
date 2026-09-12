@props(['heroPerfume' => null, 'families' => collect(), 'stats' => []])

<section class="relative overflow-hidden">

    <div class="mx-auto flex min-h-[85vh] max-w-7xl items-center px-8 py-16 lg:px-16">

        {{-- LEFT --}}
        <div class="w-full lg:w-1/2">

            <span class="inline-block rounded-full bg-stone-100 px-4 py-2 text-sm text-stone-700 dark:bg-stone-800 dark:text-stone-300">
                Indonesia's Fragrance Encyclopedia
            </span>

            <h1 class="mt-8 font-serif text-4xl font-semibold leading-tight sm:text-5xl lg:text-6xl">

                Discover the
                <span class="text-[#B08D57]">
                    Art of Fragrance
                </span>

            </h1>

            <p class="mt-8 max-w-xl text-base leading-8 text-stone-600 transition-colors duration-300 dark:text-stone-400 lg:text-lg">

                Learn perfume notes, fragrance families,
                discover iconic perfumes, and find the scent
                that perfectly matches your personality.

            </p>

            {{-- Search --}}
            <form action="{{ route('explore') }}" method="GET" class="mt-10 max-w-xl">

                <div class="relative">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="absolute left-5 top-1/2 h-5 w-5 -translate-y-1/2 text-stone-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.3-4.3m1.3-5.2a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                    </svg>

                    <input
                        type="text"
                        name="search"
                        placeholder="Search perfumes, brands, or notes..."
                        class="w-full rounded-full border border-stone-300 bg-white py-4 pl-14 pr-32 outline-none transition focus:border-[#B08D57] dark:border-stone-700 dark:bg-[#232323]">

                    <button
                        type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-[#B08D57] px-6 py-2.5 text-sm font-medium text-white transition hover:opacity-90">
                        Search
                    </button>

                </div>

            </form>

            <div class="mt-6 flex flex-wrap gap-4">

                <a
                    href="{{ route('explore') }}"
                    class="rounded-full bg-stone-900 px-8 py-4 font-medium text-white transition hover:bg-[#B08D57] dark:bg-stone-100 dark:text-stone-900">

                    Explore Perfumes

                </a>

                <a
                    href="{{ route('academy') }}"
                    class="rounded-full border border-stone-300 px-8 py-4 font-medium transition hover:border-[#B08D57] hover:text-[#B08D57] dark:border-stone-600">

                    Learn Academy

                </a>

            </div>

            {{-- Popular fragrance families, linked into Explore --}}
            @if($families->isNotEmpty())

                <div class="mt-12">

                    <p class="mb-4 text-sm uppercase tracking-[0.3em] text-stone-500 transition-colors duration-300 dark:text-stone-400">

                        Popular Families

                    </p>

                    <div class="flex flex-wrap gap-3">

                        @foreach($families as $family)

                            <a
                                href="{{ route('explore', ['family' => [$family->name]]) }}"
                                class="rounded-full bg-stone-100 px-4 py-2 text-sm text-stone-700 transition hover:bg-[#B08D57] hover:text-white dark:bg-stone-800 dark:text-stone-300">

                                {{ $family->name }}

                            </a>

                        @endforeach

                    </div>

                </div>

            @endif

        </div>

        {{-- RIGHT --}}
        <div class="hidden w-1/2 justify-center lg:flex">

            @if($heroPerfume)

                <a href="{{ route('perfume.detail', $heroPerfume->slug) }}" class="group block text-center">

                    <img
                        src="{{ $heroPerfume->photo_url }}"
                        onerror="this.onerror=null;this.src='{{ route('placeholder.perfume', $heroPerfume->slug) }}';"
                        class="w-[420px] rounded-3xl drop-shadow-2xl transition duration-700 group-hover:scale-105"
                        alt="{{ $heroPerfume->name }}">

                    <p class="mt-6 text-xs uppercase tracking-[0.3em] text-stone-500">
                        Trending Now
                    </p>

                    <p class="mt-2 font-serif text-2xl transition group-hover:text-[#B08D57]">
                        {{ $heroPerfume->name }}
                    </p>

                    <p class="mt-1 text-sm text-stone-500">
                        {{ $heroPerfume->brand?->name }}
                    </p>

                </a>

            @endif

        </div>

    </div>

</section>
