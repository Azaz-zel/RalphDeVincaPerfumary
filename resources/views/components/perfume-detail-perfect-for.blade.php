@props(['perfume'])

<section class="py-20">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        {{-- Heading --}}
        <div class="text-center">

            <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">
                Wearing Guide
            </p>

            <h2 class="mt-3 font-serif text-4xl">
                Perfect For
            </h2>

            <p class="mx-auto mt-4 max-w-2xl text-stone-600 dark:text-stone-400">
                Discover when, where, and for whom this fragrance performs at its best.
            </p>

        </div>

        <div class="mt-16 grid items-start gap-6 lg:grid-cols-2">

            {{-- Climate --}}
            <div class="rounded-3xl border border-stone-200 bg-white p-8 shadow-sm dark:border-stone-700 dark:bg-[#232323]">

                <h3 class="mb-8 font-serif text-2xl">
                    🌡 Climate Compatibility
                </h3>

                @foreach($perfume->climateCompatibility() as [$title, $score])

                    <div class="mb-6">

                        <div class="mb-2 flex justify-between">

                            <span>{{ $title }}</span>

                            <span class="font-medium">
                                {{ $score }}%
                            </span>

                        </div>

                        <div class="h-2 rounded-full bg-stone-200 dark:bg-stone-700">

                            <div
                                class="h-full rounded-full bg-[#B08D57]"
                                style="width: {{ $score }}%;">
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

            {{-- Recommendation --}}
            <div class="rounded-3xl border border-stone-200 bg-white p-8 shadow-sm dark:border-stone-700 dark:bg-[#232323]">

                <h3 class="mb-8 font-serif text-2xl">
                    ⭐ Best Recommendation
                </h3>

                {{-- Season --}}
                <div class="mb-8">

                    <h4 class="mb-3 text-sm uppercase tracking-widest text-stone-500">
                        Best Season
                    </h4>

                    <div class="flex flex-wrap gap-2">

                        @forelse($perfume->seasons as $season)

                            <span
                                class="rounded-full bg-[#B08D57] px-4 py-2 text-sm text-white">

                                {{ $season->name }}

                            </span>

                        @empty

                            <span
                                class="rounded-full bg-stone-100 px-4 py-2 text-sm dark:bg-stone-800">

                                No season information

                            </span>

                        @endforelse

                    </div>

                </div>

                {{-- Time --}}
                <div class="mb-8">

                    <h4 class="mb-3 text-sm uppercase tracking-widest text-stone-500">
                        Best Time
                    </h4>

                    <div class="flex flex-wrap gap-2">

                        @php $bestTimes = $perfume->bestTimes(); @endphp

                        @foreach(['Day', 'Evening', 'Night'] as $time)

                            <span
                                class="rounded-full px-4 py-2 text-sm {{ in_array($time, $bestTimes) ? 'bg-[#B08D57] text-white' : 'bg-stone-100 dark:bg-stone-800' }}">

                                {{ $time }}

                            </span>

                        @endforeach

                    </div>

                </div>

                {{-- Occasion --}}
                <div class="mb-8">

                    <h4 class="mb-3 text-sm uppercase tracking-widest text-stone-500">
                        Best Occasion
                    </h4>

                    <div class="flex flex-wrap gap-2">

                        @forelse($perfume->occasions as $occasion)

                            <span
                                class="rounded-full bg-[#B08D57] px-4 py-2 text-sm text-white">

                                {{ $occasion->name }}

                            </span>

                        @empty

                            <span
                                class="rounded-full bg-stone-100 px-4 py-2 text-sm dark:bg-stone-800">

                                No occasion information

                            </span>

                        @endforelse

                    </div>

                </div>

                {{-- Ideal Wearer --}}
                <div>

                    <h4 class="mb-3 text-sm uppercase tracking-widest text-stone-500">
                        Ideal Wearer
                    </h4>

                    <div class="flex flex-wrap gap-2">

                        @if($perfume->gender)

                            <span
                                class="rounded-full bg-[#B08D57] px-4 py-2 text-sm text-white">

                                ⭐ {{ $perfume->gender }}

                            </span>

                        @endif

                        <span
                            class="rounded-full bg-stone-100 px-4 py-2 text-sm dark:bg-stone-800">

                            Signature Scent Lovers

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>