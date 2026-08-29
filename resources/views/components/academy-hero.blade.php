<section class="py-18">

    <div class="mx-auto flex max-w-screen-2xl flex-col items-center gap-20 px-8 lg:flex-row lg:px-12">

        {{-- Left --}}
        <div class="w-full lg:w-1/2">

            <p
                class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">

                Ralph de Vinca Academy

            </p>

            <h1
                class="mt-6 font-serif text-5xl leading-tight lg:text-6xl font-semibold">

                Master the
                <span class="text-[#B08D57]">

                    Art of Fragrance

                </span>

            </h1>

            <p
                class="mt-8 max-w-xl leading-8 text-stone-600 dark:text-stone-400">

                Welcome to the Ralph de Vinca Academy, where fragrance becomes
                more than just scent. Learn the foundations of perfumery,
                understand fragrance notes and families, and build the confidence
                to choose perfumes with purpose.

            </p>

            {{-- CTA --}}
            <div class="mt-10 flex flex-wrap gap-4">

                <a
                    href="#learning-paths"
                    class="rounded-full bg-[#B08D57] px-8 py-3 text-white transition hover:opacity-90">

                    Start Learning

                </a>

                <a
                    href="{{ route('explore') }}"
                    class="rounded-full border border-stone-300 px-8 py-3 transition hover:border-[#B08D57] hover:text-[#B08D57]">

                    Explore Perfumes

                </a>

            </div>

            {{-- Highlights --}}
            <div class="mt-16 grid gap-8 md:grid-cols-3">

                <div class="border-t border-stone-200 pt-6 dark:border-stone-700">

                    <h3 class="mt-4 font-serif text-2xl">

                        Learn the Basics

                    </h3>

                    <p class="mt-7 leading-7 text-stone-600 dark:text-stone-400">

                        Discover the fundamentals of perfumery, fragrance structure, and how scents evolve over time.

                    </p>

                </div>

                <div class="border-t border-stone-200 pt-6 dark:border-stone-700">

                    <h3 class="font-serif text-2xl">

                        Understand the Notes

                    </h3>

                    <p class="mt-3 leading-7 text-stone-600 dark:text-stone-400">

                        Explore fragrance notes, scent families, and ingredients that define every perfume.

                    </p>

                </div>

                <div class="border-t border-stone-200 pt-6 dark:border-stone-700">

                    <h3 class="font-serif text-2xl">

                        Choose with Confidence

                    </h3>

                    <p class="mt-3 leading-7 text-stone-600 dark:text-stone-400">

                        Learn how to select fragrances based on your style, season, occasion, and personality.

                    </p>

                </div>

            </div>

        </div>

        {{-- Right --}}
        <div class="flex w-full justify-center lg:w-1/2">

            <img
                src="{{ asset('images/academy/hero.jpg') }}"
                alt="Fragrance Academy"
                class="w-full max-w-lg rounded-[2rem] object-cover shadow-2xl">

        </div>

    </div>

</section>