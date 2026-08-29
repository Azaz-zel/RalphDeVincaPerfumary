@props(['paginator', 'route'])

@if ($paginator->hasPages())

    @php
        $pageUrl = fn ($page) => route($route, array_merge(request()->query(), ['page' => $page]));
    @endphp

    <div class="flex flex-wrap items-center justify-center gap-2">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())

            <span class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-stone-300 dark:border-stone-700">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>

            </span>

        @else

            <a
                href="{{ $pageUrl($paginator->currentPage() - 1) }}"
                class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 text-stone-600 transition hover:border-[#B08D57] hover:text-[#B08D57] dark:border-stone-700 dark:text-stone-300">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>

            </a>

        @endif

        {{-- Page Numbers --}}
        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)

            @if ($page == $paginator->currentPage())

                <span class="flex h-10 min-w-10 items-center justify-center rounded-full bg-[#B08D57] px-3 text-sm font-medium text-white">
                    {{ $page }}
                </span>

            @else

                <a
                    href="{{ $pageUrl($page) }}"
                    class="flex h-10 min-w-10 items-center justify-center rounded-full border border-stone-300 px-3 text-sm text-stone-600 transition hover:border-[#B08D57] hover:text-[#B08D57] dark:border-stone-700 dark:text-stone-300">
                    {{ $page }}
                </a>

            @endif

        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())

            <a
                href="{{ $pageUrl($paginator->currentPage() + 1) }}"
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

    </div>

@endif
