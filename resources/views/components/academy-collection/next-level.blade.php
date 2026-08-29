{{-- Final CTA --}}
<section class="py-28 bg-stone-50 dark:bg-[#1C1C1C]">

    <div class="mx-auto max-w-5xl px-8 lg:px-12">

        <div class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white p-10 dark:bg-[#242424]">

            <div class="px-8 py-16 text-center lg:px-16 lg:py-20">

                {{-- Label --}}
                <p class="uppercase tracking-[0.35em] text-[#B08D57]">
                    Academy Complete
                </p>


                {{-- Heading --}}
                <h2 class="mx-auto mt-5 max-w-3xl font-serif text-5xl leading-tight">
                    Your Collection Should
                    <br>
                    Tell Your Story
                </h2>


                {{-- Description --}}
                <p class="mx-auto mt-8 max-w-2xl leading-8 text-stone-600 dark:text-stone-400">
                    You now understand how to choose fragrances based on
                    composition, performance, season, occasion, and personal
                    preference. The next step is putting that knowledge into
                    practice and discovering what truly works for you.
                </p>


                {{-- Buttons --}}
                <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">

                    <a
                        href="{{ route('explore') }}"
                        class="inline-flex items-center gap-3 rounded-full bg-[#B08D57] px-8 py-3.5 text-white transition hover:opacity-90">

                        Explore Fragrances

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M9 5l7 7-7 7"/>

                        </svg>

                    </a>


                    <a
                        href="{{ route('academy') }}"
                        class="inline-flex items-center gap-3 rounded-full border border-stone-300 bg-white px-8 py-3.5 text-stone-700 transition hover:bg-stone-50 dark:border-stone-600 dark:bg-transparent dark:text-stone-200 dark:hover:bg-stone-800">

                        Back to Academy

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>