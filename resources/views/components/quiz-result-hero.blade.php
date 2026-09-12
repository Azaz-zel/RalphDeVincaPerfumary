@props(['summary' => [], 'topMatch' => null])

<section class="border-b border-stone-200 bg-stone-50 py-20 dark:border-stone-800 dark:bg-[#1B1A17]">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        {{-- Breadcrumb --}}
        <p class="text-sm text-stone-500">

            <a href="{{ route('home') }}" class="transition hover:text-[#B08D57]">
                Home
            </a>

            <span class="mx-2">/</span>

            <a href="{{ route('quiz') }}" class="transition hover:text-[#B08D57]">
                Find Your Scent
            </a>

            <span class="mx-2">/</span>

            <span class="text-[#B08D57]">
                Your Matches
            </span>

        </p>

        <h1 class="mt-6 font-serif text-5xl font-semibold lg:text-6xl">

            Your Scent
            <span class="text-[#B08D57]">
                Matches
            </span>

        </h1>

        @if(count($summary))

            <p class="mt-6 max-w-3xl text-lg leading-8 text-stone-600 dark:text-stone-400">

                You told us you lean toward
                <span class="text-stone-900 dark:text-stone-100">
                    {{ collect($summary)->join(', ', ' and ') }}.
                </span>
                Here is what fits.

            </p>

        @endif

    </div>

</section>

{{-- The single best match, given room to breathe --}}
@if($topMatch)

    <section class="py-20">

        <div class="mx-auto max-w-7xl px-8 lg:px-16">

            <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">

                <div class="overflow-hidden rounded-3xl border border-stone-200 shadow-sm dark:border-stone-700">

                    <img loading="lazy" decoding="async"
                        src="{{ $topMatch->image ? asset($topMatch->image) : route('placeholder.perfume', $topMatch->slug) }}"
                        onerror="this.onerror=null;this.src='{{ route('placeholder.perfume', $topMatch->slug) }}';"
                        alt="{{ $topMatch->name }}"
                        class="aspect-[4/5] w-full object-cover">

                </div>

                <div>

                    <p class="text-xs uppercase tracking-[0.35em] text-[#B08D57]">
                        Your Best Match &bull; {{ $topMatch->match_score }}% fit
                    </p>

                    <h2 class="mt-5 font-serif text-4xl font-semibold lg:text-5xl">
                        {{ $topMatch->name }}
                    </h2>

                    <a href="{{ route('brand.detail', $topMatch->brand->slug) }}"
                        class="mt-3 inline-block text-lg text-stone-500 transition hover:text-[#B08D57]">
                        {{ $topMatch->brand->name }}
                    </a>

                    <div class="mt-6 flex flex-wrap gap-2">

                        @if($topMatch->fragranceFamily)
                            <span class="rounded-full bg-stone-100 px-4 py-1.5 text-sm dark:bg-stone-800">
                                {{ $topMatch->fragranceFamily->name }}
                            </span>
                        @endif

                        <span class="rounded-full bg-stone-100 px-4 py-1.5 text-sm capitalize dark:bg-stone-800">
                            {{ $topMatch->gender }}
                        </span>

                        @if($topMatch->concentration)
                            <span class="rounded-full bg-stone-100 px-4 py-1.5 text-sm dark:bg-stone-800">
                                {{ $topMatch->concentration }}
                            </span>
                        @endif

                    </div>

                    @if(count($topMatch->match_reasons))

                        <div class="mt-8">

                            <p class="text-sm uppercase tracking-[0.2em] text-stone-500">
                                Why this one
                            </p>

                            <ul class="mt-4 space-y-2.5">

                                @foreach($topMatch->match_reasons as $reason)

                                    <li class="flex items-start gap-3 leading-7 text-stone-600 dark:text-stone-400">

                                        <span class="mt-2.5 h-1.5 w-1.5 flex-none rounded-full bg-[#B08D57]"></span>

                                        Suits your taste for {{ $reason }}

                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif

                    <a href="{{ route('perfume.detail', $topMatch->slug) }}"
                        class="mt-10 inline-block rounded-full bg-[#B08D57] px-8 py-4 font-medium text-white transition hover:opacity-90">
                        View {{ $topMatch->name }}
                    </a>

                </div>

            </div>

        </div>

    </section>

@endif
