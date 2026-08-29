<section class="py-20 bg-stone-50 dark:bg-[#1B1A17]">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        {{-- Header --}}
        <div class="text-center">

            <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">
                Scent Profile
            </p>

            <h2 class="mt-3 font-serif text-4xl">
                Character of the Fragrance
            </h2>

            <p class="mx-auto mt-4 max-w-2xl text-stone-600 dark:text-stone-400">
                A visual representation of this fragrance's dominant characteristics.
            </p>

        </div>

        {{-- Scent Profile Data --}}
        @php
            $profiles = $perfume->scentProfile();
        @endphp

        {{-- Profile Bars --}}
        <div class="mt-16 grid gap-x-12 gap-y-8 md:grid-cols-2">

            @foreach($profiles as [$name, $value])

                <div>

                    {{-- Label --}}
                    <div class="mb-2 flex items-center justify-between">

                        <span class="font-medium text-stone-800 dark:text-stone-200">
                            {{ $name }}
                        </span>

                        <span class="text-sm text-stone-500 dark:text-stone-400">
                            {{ $value }}%
                        </span>

                    </div>

                    {{-- Progress Bar --}}
                    <div
                        class="h-3 overflow-hidden rounded-full bg-stone-200 dark:bg-stone-700">

                        <div
                            class="h-full rounded-full bg-[#B08D57] transition-all duration-700"
                            style="width: {{ $value }}%;">
                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>