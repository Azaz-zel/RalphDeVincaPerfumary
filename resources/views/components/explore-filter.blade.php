@props(['search' => ''])

<section class="py-10">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        {{-- Search --}}
        <form
            action="{{ route('explore') }}"
            method="GET"
            class="flex flex-col gap-4 md:flex-row">

            <div class="relative flex-1">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-5 top-1/2 h-5 w-5 -translate-y-1/2 text-stone-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-4.3-4.3m1.3-5.2a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>

                </svg>

                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Search perfumes..."
                    class="w-full rounded-2xl border border-stone-300 bg-white py-4 pl-14 pr-5 outline-none transition focus:border-[#B08D57] dark:border-stone-700 dark:bg-[#232323]">

            </div>

            <div class="flex gap-3">

                <button
                    type="submit"
                    class="rounded-2xl bg-stone-900 px-7 py-4 text-sm font-medium text-white transition hover:bg-[#B08D57] dark:bg-stone-100 dark:text-stone-900">
                    Search
                </button>

                @if (!empty($search))
                    <a
                        id="clear-search-link"
                        href="{{ route('explore') }}"
                        class="rounded-2xl border border-stone-300 px-7 py-4 text-sm font-medium text-stone-700 transition hover:border-[#B08D57] hover:text-[#B08D57] dark:border-stone-700 dark:text-stone-200">
                        Clear
                    </a>
                @endif

            </div>

        </form>

        {{-- Quick Filter --}}
            <div class="mt-6 flex flex-wrap items-center gap-3">

        {{-- Trending --}}
        <button
            type="button"
            class="filter-chip rounded-full border border-stone-300 px-5 py-2 text-sm transition dark:border-stone-700"
            data-filter="trending"
            data-value="1">

            Trending

        </button>


        {{-- Designer --}}
        <button
            type="button"
            class="filter-chip rounded-full border border-stone-300 px-5 py-2 text-sm transition dark:border-stone-700"
            data-filter="type"
            data-value="Designer">

            Designer

        </button>


        {{-- Niche --}}
        <button
            type="button"
            class="filter-chip rounded-full border border-stone-300 px-5 py-2 text-sm transition dark:border-stone-700"
            data-filter="type"
            data-value="Niche">

            Niche

        </button>

                {{-- Family --}}
                <x-filter-dropdown
                    id="family"
                    title="Fragrance Family"
                    :multiple="true"
                    :items="[
                        'Citrus',
                        'Floral',
                        'Woody',
                        'Amber',
                        'Vanilla',
                        'Fresh',
                        'Aquatic',
                        'Musky',
                        'Gourmand'
                    ]"/>

                {{-- Season --}}
                <x-filter-dropdown
                    id="season"
                    title="Season"
                    :items="[
                        'Spring',
                        'Summer',
                        'Autumn',
                        'Winter',
                        'All Season'
                    ]"/>

                {{-- Gender --}}
                <x-filter-dropdown
                    id="gender"
                    title="Gender"
                    :items="[
                        'Men',
                        'Women',
                        'Unisex'
                    ]"/>

                {{-- Sort --}}
                <x-filter-dropdown
                    id="sort"
                    title="Sort By"
                    :items="[
                        'Most Popular',
                        'Newest',
                        'Highest Rated',
                        'A-Z',
                        'Z-A'
                    ]"/>


                {{-- Clear Filters --}}
                <button
                    id="clear-filters"
                    type="button"
                    class="rounded-full border border-stone-300 bg-white px-5 py-2 text-sm text-stone-600 transition hover:border-[#B08D57] dark:border-stone-700 dark:text-stone-300">
                    Clear Filters
                </button>

            </div>

        </div>

    </div>

</section>