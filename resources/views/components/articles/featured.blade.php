<section class="py-24">

    <div class="mx-auto max-w-screen-2xl px-8 lg:px-12">

        {{-- Heading --}}
        <div class="flex items-end justify-between gap-8">

            <div>

                <p class="uppercase tracking-[0.35em] text-[#B08D57]">
                    Featured
                </p>

                <h2 class="mt-4 font-serif text-5xl">
                    Editor's Selection
                </h2>

            </div>

            <p class="hidden max-w-md text-right leading-7 text-stone-500 lg:block">
                A selection of stories worth slowing down for.
            </p>

        </div>


        {{-- Featured Layout --}}
        <div class="mt-14 grid gap-8 lg:grid-cols-[1.5fr_1fr]">

            {{-- Main Article --}}
            <article class="group overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm dark:border-stone-700 dark:bg-[#242424]">

                <div class="overflow-hidden">

                    <img loading="lazy" decoding="async"
                        src="{{ asset('images/academy/concentration-scale.jpg') }}"
                        alt="Perfume bottles and fragrance ingredients"
                        class="h-[460px] w-full object-cover transition duration-700 group-hover:scale-105"
                    >

                </div>


                <div class="p-9 lg:p-10">

                    <div class="flex items-center gap-4 text-xs uppercase tracking-[0.25em]">

                        <span class="text-[#B08D57]">
                            Fragrance Basics
                        </span>

                        <span class="text-stone-400">
                            6 min read
                        </span>

                    </div>


                    <h3 class="mt-5 font-serif text-4xl leading-tight">
                        Why Does the Same Perfume
                        Smell Different on Everyone?
                    </h3>


                    <p class="mt-5 max-w-2xl leading-8 text-stone-600 dark:text-stone-400">
                        Explore how skin chemistry, temperature, moisture,
                        and individual body characteristics can influence
                        the way a fragrance develops throughout the day.
                    </p>


                    <a
                        href="#"
                        class="mt-7 inline-flex items-center gap-2 text-sm uppercase tracking-[0.2em] text-[#B08D57] transition hover:gap-4"
                    >

                        Read Article

                        <span>
                            →
                        </span>

                    </a>

                </div>

            </article>


            {{-- Side Articles --}}
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-1">

                {{-- Article 02 --}}
                <article class="group overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm dark:border-stone-700 dark:bg-[#242424]">

                    <div class="grid h-full sm:grid-cols-2 lg:grid-cols-[0.9fr_1.1fr]">

                        <div class="overflow-hidden">

                            <img loading="lazy" decoding="async"
                                src="{{ asset('images/articles/featured-notes.jpg') }}"
                                alt="Perfume ingredients and fragrance notes"
                                class="h-full min-h-[220px] w-full object-cover transition duration-700 group-hover:scale-105"
                            >

                        </div>


                        <div class="p-7">

                            <p class="text-xs uppercase tracking-[0.25em] text-[#B08D57]">
                                Ingredients
                            </p>

                            <h3 class="mt-4 font-serif text-2xl leading-tight">
                                What Does Oud
                                Actually Smell Like?
                            </h3>

                            <p class="mt-4 text-sm leading-6 text-stone-600 dark:text-stone-400">
                                Understanding one of perfumery's most
                                fascinating and complex materials.
                            </p>

                            <a
                                href="#"
                                class="mt-5 inline-flex text-sm text-[#B08D57]"
                            >
                                Read →
                            </a>

                        </div>

                    </div>

                </article>


                {{-- Article 03 --}}
                <article class="group overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm dark:border-stone-700 dark:bg-[#242424]">

                    <div class="grid h-full sm:grid-cols-2 lg:grid-cols-[0.9fr_1.1fr]">

                        <div class="overflow-hidden">

                            <img loading="lazy" decoding="async"
                                src="{{ asset('images/articles/featured-performance.jpg') }}"
                                alt="Perfume bottle for fragrance performance article"
                                class="h-full min-h-[220px] w-full object-cover transition duration-700 group-hover:scale-105"
                            >

                        </div>


                        <div class="p-7">

                            <p class="text-xs uppercase tracking-[0.25em] text-[#B08D57]">
                                Performance
                            </p>

                            <h3 class="mt-4 font-serif text-2xl leading-tight">
                                Why Does Your Perfume
                                Disappear So Quickly?
                            </h3>

                            <p class="mt-4 text-sm leading-6 text-stone-600 dark:text-stone-400">
                                The factors that influence how long a
                                fragrance stays noticeable on skin.
                            </p>

                            <a
                                href="#"
                                class="mt-5 inline-flex text-sm text-[#B08D57]"
                            >
                                Read →
                            </a>

                        </div>

                    </div>

                </article>

            </div>

        </div>

    </div>

</section>