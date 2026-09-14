@props(['paginator', 'route'])

@if ($paginator->hasPages())

    @php
        $pageUrl = fn ($page) => route($route, array_merge(request()->query(), ['page' => $page]));

        $current = $paginator->currentPage();
        $last = $paginator->lastPage();

        // Listing every page put eleven buttons in a row, which ran off the
        // side of a phone. Only the first page, the last page and the current
        // page's immediate neighbours are kept; the gaps become ellipses.
        $window = collect(range(max(1, $current - 1), min($last, $current + 1)))
            ->prepend(1)
            ->push($last)
            ->unique()
            ->sort()
            ->values();

        // Narrow screens keep only the three pages around the current one, so
        // the row always fits: arrows plus three numbers.
        $nearCurrent = fn ($page) => abs($page - $current) <= 1;
    @endphp

    <nav
        class="flex flex-wrap items-center justify-center gap-1.5 sm:gap-2"
        aria-label="Pagination">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())

            <span class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-stone-300 dark:border-stone-700">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>

            </span>

        @else

            <a
                href="{{ $pageUrl($current - 1) }}"
                rel="prev"
                aria-label="Previous page"
                class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 text-stone-600 transition hover:border-[#B08D57] hover:text-[#B08D57] dark:border-stone-700 dark:text-stone-300">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>

            </a>

        @endif

        {{-- Page numbers --}}
        @php $previousPage = 0; @endphp

        @foreach ($window as $page)

            @if ($page - $previousPage > 1)

                <span class="hidden h-10 items-center px-1 text-sm text-stone-400 sm:flex">
                    &hellip;
                </span>

            @endif

            @if ($page == $current)

                <span
                    aria-current="page"
                    class="flex h-10 min-w-10 items-center justify-center rounded-full bg-[#B08D57] px-3 text-sm font-medium text-white">
                    {{ $page }}
                </span>

            @else

                <a
                    href="{{ $pageUrl($page) }}"
                    aria-label="Page {{ $page }}"
                    class="{{ $nearCurrent($page) ? 'flex' : 'hidden sm:flex' }} h-10 min-w-10 items-center justify-center rounded-full border border-stone-300 px-3 text-sm text-stone-600 transition hover:border-[#B08D57] hover:text-[#B08D57] dark:border-stone-700 dark:text-stone-300">
                    {{ $page }}
                </a>

            @endif

            @php $previousPage = $page; @endphp

        @endforeach

        {{-- Position, for the pages hidden on small screens --}}
        <span class="flex h-10 items-center px-2 text-sm text-stone-500 sm:hidden">
            of {{ $last }}
        </span>

        {{-- Next --}}
        @if ($paginator->hasMorePages())

            <a
                href="{{ $pageUrl($current + 1) }}"
                rel="next"
                aria-label="Next page"
                class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 text-stone-600 transition hover:border-[#B08D57] hover:text-[#B08D57] dark:border-stone-700 dark:text-stone-300">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>

            </a>

        @else

            <span class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-stone-300 dark:border-stone-700">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>

            </span>

        @endif

    </nav>

@endif
