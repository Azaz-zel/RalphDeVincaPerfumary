<section class="py-24 bg-stone-50 dark:bg-[#1B1A17]">

    <div class="mx-auto max-w-screen-2xl px-8 lg:px-12">

        {{-- Heading --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

            <div>

                <p class="uppercase tracking-[0.35em] text-[#B08D57]">
                    Editor's Picks
                </p>

                <h2 class="mt-4 font-serif text-5xl">
                    Worth Reading
                </h2>

            </div>

            <p class="max-w-lg leading-7 text-stone-500 lg:text-right">
                A few stories selected for readers who want to look
                a little deeper into the world of fragrance.
            </p>

        </div>


        {{-- Picks --}}
        <div class="mt-14 grid gap-8 lg:grid-cols-2">


            {{-- Pick 01 --}}
            <article class="group overflow-hidden rounded-[2rem] border border-stone-200 bg-white dark:border-stone-700 dark:bg-[#242424]">

                <div class="grid md:grid-cols-2">

                    {{-- Image --}}
                    <div class="overflow-hidden">

                        <img loading="lazy" decoding="async"
                            src="{{ asset('images/articles/editor-pick-01.jpg') }}"
                            alt="Perfume collection and fragrance bottles"
                            class="h-full min-h-[320px] w-full object-cover transition duration-700 group-hover:scale-105"
                        >

                    </div>


                    {{-- Content --}}
                    <div class="flex flex-col justify-center p-8 lg:p-10">

                        <p class="text-xs uppercase tracking-[0.3em] text-[#B08D57]">
                            Fragrance Culture
                        </p>

                        <h3 class="mt-5 font-serif text-3xl leading-tight">
                            Why Do Certain Scents
                            Remind Us of Memories?
                        </h3>

                        <p class="mt-5 leading-7 text-stone-600 dark:text-stone-400">
                            Discover the fascinating relationship between
                            fragrance, memory, emotion, and personal experience.
                        </p>

                        <div class="mt-7 flex items-center justify-between">

                            <span class="text-xs uppercase tracking-[0.2em] text-stone-400">
                                6 min read
                            </span>

                            <a
                                href="#"
                                class="text-sm uppercase tracking-[0.2em] text-[#B08D57] transition hover:translate-x-1"
                            >
                                Read →
                            </a>

                        </div>

                    </div>

                </div>

            </article>


            {{-- Pick 02 --}}
            <article class="group overflow-hidden rounded-[2rem] border border-stone-200 bg-white dark:border-stone-700 dark:bg-[#242424]">

                <div class="grid md:grid-cols-2">

                    {{-- Image --}}
                    <div class="overflow-hidden">

                        <img loading="lazy" decoding="async"
                            src="{{ asset('images/articles/editor-pick-02.jpg') }}"
                            alt="Perfume bottle in an elegant setting"
                            class="h-full min-h-[320px] w-full object-cover transition duration-700 group-hover:scale-105"
                        >

                    </div>


                    {{-- Content --}}
                    <div class="flex flex-col justify-center p-8 lg:p-10">

                        <p class="text-xs uppercase tracking-[0.3em] text-[#B08D57]">
                            Buying Guide
                        </p>

                        <h3 class="mt-5 font-serif text-3xl leading-tight">
                            Do You Really Need
                            an Expensive Perfume?
                        </h3>

                        <p class="mt-5 leading-7 text-stone-600 dark:text-stone-400">
                            Price can tell part of a fragrance's story, but it
                            does not automatically determine whether a perfume
                            is right for you.
                        </p>

                        <div class="mt-7 flex items-center justify-between">

                            <span class="text-xs uppercase tracking-[0.2em] text-stone-400">
                                7 min read
                            </span>

                            <a
                                href="#"
                                class="text-sm uppercase tracking-[0.2em] text-[#B08D57] transition hover:translate-x-1"
                            >
                                Read →
                            </a>

                        </div>

                    </div>

                </div>

            </article>


        </div>

    </div>

</section>