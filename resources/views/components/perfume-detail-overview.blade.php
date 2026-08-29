@props(['perfume'])

<section class="py-20">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        <div class="mb-12 text-center">

            <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">
                Fragrance Overview
            </p>

            <h2 class="mt-3 font-serif text-4xl">
                Everything You Need to Know
            </h2>

            <p class="mx-auto mt-4 max-w-2xl text-stone-600 dark:text-stone-400">
                A quick overview of the fragrance, including its origin,
                concentration, performance, and wearing recommendations.
            </p>

        </div>

        <div class="grid gap-6 lg:grid-cols-2">

            {{-- Quick Information --}}
            <div class="rounded-3xl border border-stone-200 bg-white p-8 shadow-sm dark:border-stone-700 dark:bg-[#232323]">

                <h3 class="mb-6 font-serif text-2xl">
                    Quick Information
                </h3>

                <div class="space-y-5">

                    <div class="flex justify-between border-b border-stone-100 pb-3 dark:border-stone-700">
                        <span class="text-stone-500">
                            Release Year
                        </span>

                        <span class="font-medium">
                            {{ $perfume->release_year ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b border-stone-100 pb-3 dark:border-stone-700">
                        <span class="text-stone-500">
                            Perfumer
                        </span>

                        <span class="font-medium">
                            {{ $perfume->perfumer ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b border-stone-100 pb-3 dark:border-stone-700">
                        <span class="text-stone-500">
                            Concentration
                        </span>

                        <span class="font-medium">
                            {{ $perfume->concentration ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b border-stone-100 pb-3 dark:border-stone-700">
                        <span class="text-stone-500">
                            Fragrance Family
                        </span>

                        <span class="font-medium">
                            {{ $perfume->fragranceFamily?->name ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b border-stone-100 pb-3 dark:border-stone-700">
                        <span class="text-stone-500">
                            Gender
                        </span>

                        <span class="font-medium">
                            {{ $perfume->gender ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-stone-500">
                            Origin
                        </span>

                        <span class="font-medium">
                            {{ $perfume->origin ?? '-' }}
                        </span>
                    </div>

                </div>

            </div>

            {{-- Bottle & Availability --}}
            <div class="rounded-3xl border border-stone-200 bg-white p-8 shadow-sm dark:border-stone-700 dark:bg-[#232323]">

                <h3 class="mb-6 font-serif text-2xl">
                    Bottle Information
                </h3>

                <div class="space-y-5">

                    <div class="flex justify-between border-b border-stone-100 pb-3 dark:border-stone-700">
                        <span class="text-stone-500">
                            Bottle Sizes
                        </span>

                        <span class="font-medium">
                            {{ $perfume->bottle_sizes ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b border-stone-100 pb-3 dark:border-stone-700">
                        <span class="text-stone-500">
                            Status
                        </span>

                        <span class="rounded-full bg-green-100 px-3 py-1 text-sm text-green-700">
                            {{ $perfume->status ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b border-stone-100 pb-3 dark:border-stone-700">
                        <span class="text-stone-500">
                            Availability
                        </span>

                        <span class="font-medium">
                            {{ $perfume->availability ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-stone-500">
                            Retail Price
                        </span>

                        <span class="font-medium">
                            {{ $perfume->retail_price ? 'Rp ' . number_format($perfume->retail_price, 0, ',', '.') : '-' }}
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>