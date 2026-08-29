<section class="py-24">

    {{-- Background --}}
    <div class="absolute inset-0 -z-20">

        <div class="absolute inset-0 bg-gradient-to-r from-[#F8F5F0]/95 via-[#F8F5F0]/85 to-[#F8F5F0]/30 dark:from-[#1B1A17]/95 dark:via-[#1B1A17]/90 dark:to-[#1B1A17]/40"></div>

    </div>

    <div class="mx-auto max-w-screen-2xl px-8 lg:px-12">

        <div class="grid items-center gap-20 lg:grid-cols-2">

            {{-- Left --}}
            <div>

                {{-- Breadcrumb --}}
                <nav class="mb-8 flex items-center gap-3 text-sm text-stone-500 dark:text-stone-400">

                    <a
                        href="{{ route('academy') }}"
                        class="transition hover:text-[#B08D57]">

                        Academy

                    </a>

                    <span>/</span>

                    <span class="text-[#B08D57]">

                        Perfume Concentration

                    </span>

                </nav>

                <p class="uppercase tracking-[0.45em] text-[#B08D57]">

                    Level 4

                </p>

                <h1 class="mt-4 max-w-3xl font-serif text-6xl leading-tight">

                    Perfume

                    <span class="text-[#B08D57]">

                        Concentration

                    </span>

                </h1>

                <p class="mt-8 max-w-2xl text-lg leading-8 text-stone-600 dark:text-stone-400">

                    Discover the differences between Eau de Cologne, Eau de Toilette,
                    Eau de Parfum, and Parfum. Learn how fragrance oil concentration
                    influences longevity, projection, and the overall wearing
                    experience of a perfume.

                </p>

                {{-- Buttons --}}
                <div class="mt-12 flex flex-wrap gap-5">

                    <a
                        href="#about"
                        class="rounded-full bg-[#B08D57] px-8 py-3 text-white transition hover:opacity-90">

                        Start Learning

                    </a>

                    <a
                        href="{{ route('academy') }}"
                        class="rounded-full border border-stone-300 px-8 py-3 transition hover:border-[#B08D57] hover:text-[#B08D57] dark:border-stone-700">

                        Back to Academy

                    </a>

                </div>

            </div>

            {{-- Right --}}
            <div class="hidden justify-center lg:flex">

                <img
                    src="{{ asset('images/academy/concentration-hero.jpg') }}"
                    alt="Perfume Concentration"
                    class="max-h-[650px] rounded-[2rem] shadow-2xl">

            </div>

        </div>

    </div>

</section>