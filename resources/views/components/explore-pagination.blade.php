@props(['perfumes'])

@if ($perfumes->hasPages())

<section class="pb-20">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        <div class="flex items-center justify-center gap-2">

            {{-- Previous --}}
            @if ($perfumes->onFirstPage())

                <span
                    class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-stone-300 dark:border-stone-700">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"/>

                    </svg>

                </span>

            @else

                @php
                    $previousUrl = route('explore', array_merge(
                        request()->query(),
                        ['page' => $perfumes->currentPage() - 1]
                    ));
                @endphp

                <a
                    href="{{ $previousUrl }}"
                    class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 text-stone-600 transition hover:border-[#B08D57] hover:text-[#B08D57] dark:border-stone-700 dark:text-stone-300">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"/>

                    </svg>

                </a>

            @endif


            {{-- Page Numbers --}}
            @foreach ($perfumes->getUrlRange(1, $perfumes->lastPage()) as $page => $url)

                @if ($page == $perfumes->currentPage())

                    <span
                        class="flex h-10 min-w-10 items-center justify-center rounded-full bg-[#B08D57] px-3 text-sm font-medium text-white">

                        {{ $page }}

                    </span>

                @else

                    @php
                        $pageUrl = route('explore', array_merge(
                            request()->query(),
                            ['page' => $page]
                        ));
                    @endphp

                    <a
                        href="{{ $pageUrl }}"
                        class="flex h-10 min-w-10 items-center justify-center rounded-full border border-stone-300 px-3 text-sm text-stone-600 transition hover:border-[#B08D57] hover:text-[#B08D57] dark:border-stone-700 dark:text-stone-300">

                        {{ $page }}

                    </a>

                @endif

            @endforeach


            {{-- Next --}}
            @if ($perfumes->hasMorePages())

                @php
                    $nextUrl = route('explore', array_merge(
                        request()->query(),
                        ['page' => $perfumes->currentPage() + 1]
                    ));
                @endphp

                <a
                    href="{{ $nextUrl }}"
                    class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 text-stone-600 transition hover:border-[#B08D57] hover:text-[#B08D57] dark:border-stone-700 dark:text-stone-300">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"/>

                    </svg>

                </a>

            @else

                <span
                    class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-stone-300 dark:border-stone-700">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"/>

                    </svg>

                </span>

            @endif

        </div>

    </div>

</section>

@endif