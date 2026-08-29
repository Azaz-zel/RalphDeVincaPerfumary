<section class="py-24">

    <div class="mx-auto max-w-6xl px-8">

        <div class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm dark:border-stone-700 dark:bg-[#242424]">

            <div class="grid items-center gap-10 p-10 lg:grid-cols-[1.5fr_0.8fr] lg:p-12">

                {{-- Left --}}
                <div>

                    <p class="uppercase tracking-[0.35em] text-[#B08D57]">
                        Begin Exploring
                    </p>

                    <h2 class="mt-5 font-serif text-5xl leading-tight">
                        Discover the World
                        <br>
                        of Fragrance
                    </h2>

                    <p class="mt-7 max-w-2xl leading-8 text-stone-600 dark:text-stone-400">
                        Whether you are discovering fragrance for the first time
                        or looking to understand your collection more deeply,
                        there is always something new to explore.
                    </p>

                </div>


                {{-- Right --}}
                <div class="flex lg:justify-end">

                    <a
                        href="{{ route('explore') }}"
                        class="inline-flex items-center gap-3 rounded-full bg-[#B08D57] px-8 py-3.5 text-white transition hover:opacity-90"
                    >

                        Explore Fragrances

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M9 5l7 7-7 7"
                            />

                        </svg>

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>