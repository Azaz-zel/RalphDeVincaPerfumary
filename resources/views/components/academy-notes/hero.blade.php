<section class="py-24">

    {{-- Background --}}
    <div class="mx-auto flex max-w-screen-2xl flex-col items-center gap-20 px-8 lg:flex-row lg:px-12">

        <div class="grid items-center gap-20 lg:grid-cols-2">

            {{-- Left --}}
            <div>

                {{-- Breadcrumb --}}
                <nav class="mb-8 text-sm text-stone-500">

                    <a
                        href="{{ route('academy') }}"
                        class="transition hover:text-[#B08D57]">

                        Academy

                    </a>

                    <span class="mx-2">/</span>

                    <span class="text-[#B08D57]">

                        Understanding Fragrance Notes

                    </span>

                </nav>

                <p class="uppercase tracking-[0.45em] text-[#B08D57]">

                    Level 2

                </p>

                <h1 class="mt-6 max-w-3xl font-serif text-6xl leading-tight ">

                    Understanding

                    <span class="text-[#B08D57]">

                    Fragrance Notes

                    </span>

                </h1>

                <p class="mt-8 max-w-2xl text-lg leading-8 text-stone-600 dark:text-stone-400">

                    Discover how every fragrance unfolds through Top Notes,
                    Heart Notes, and Base Notes. Learn why perfumes change
                    over time and how each layer contributes to a complete
                    olfactory experience.

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
            <div class="hidden lg:flex justify-center">

                <img
                    src="{{ asset('images/academy/notes-hero.png') }}"
                    alt="Fragrance Notes"
                    class="max-h-[650px] rounded-[2rem] shadow-2xl">

            </div>

        </div>

    </div>

</section>