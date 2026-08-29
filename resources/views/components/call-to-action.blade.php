@props(['stats' => []])

<section class="py-20">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        <div
            class="rounded-[40px]
            border border-stone-200
            bg-white
            px-10 py-14
            text-center
            shadow-sm
            transition-colors duration-300
            dark:border-stone-700
            dark:bg-[#232323]">

            {{-- Small Title --}}
            <p class="text-xs font-medium uppercase tracking-[0.45em] text-[#B08D57]">

                Ralph de Vinca

            </p>

            {{-- Heading --}}
            <h2 class="mt-5 font-serif text-4xl leading-tight lg:text-5xl">

                Begin Your
                <br>

                Fragrance Journey

            </h2>

            {{-- Description --}}
            <p class="mx-auto mt-5 max-w-2xl text-base leading-8 text-stone-600 transition-colors duration-300 dark:text-stone-400">

                Explore perfumes from around the world,
                understand fragrance notes,
                and discover the scent that truly
                matches your personality.

            </p>

            {{-- Buttons --}}
            <div class="mt-8 flex flex-wrap justify-center gap-4">

                <a
                    href="{{ route('explore') }}"
                    class="rounded-full bg-[#B08D57] px-8 py-3 font-medium text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">

                    Explore Perfumes →

                </a>

                <a
                    href="{{ route('academy') }}"
                    class="rounded-full border border-stone-300 px-8 py-3 font-medium transition-all duration-300 hover:border-[#B08D57] hover:text-[#B08D57] dark:border-stone-600">

                    Learn Academy →

                </a>

            </div>

            {{-- Divider --}}
            <div class="mx-auto mt-10 h-px w-40 bg-stone-200 dark:bg-stone-700"></div>

            {{-- Stats (live counts from the catalogue) --}}
            <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-3 sm:gap-6">

                <a href="{{ route('explore') }}" class="group">

                    <h3 class="font-serif text-3xl text-[#B08D57]">
                        {{ $stats['perfumes'] ?? 0 }}
                    </h3>

                    <p class="mt-2 text-xs uppercase tracking-[0.25em] text-stone-500 transition group-hover:text-[#B08D57]">

                        Perfumes

                    </p>

                </a>

                <a href="{{ route('brands.index') }}" class="group">

                    <h3 class="font-serif text-3xl text-[#B08D57]">
                        {{ $stats['brands'] ?? 0 }}
                    </h3>

                    <p class="mt-2 text-xs uppercase tracking-[0.25em] text-stone-500 transition group-hover:text-[#B08D57]">

                        Houses

                    </p>

                </a>

                <a href="{{ route('notes.index') }}" class="group">

                    <h3 class="font-serif text-3xl text-[#B08D57]">
                        {{ $stats['notes'] ?? 0 }}
                    </h3>

                    <p class="mt-2 text-xs uppercase tracking-[0.25em] text-stone-500 transition group-hover:text-[#B08D57]">

                        Notes

                    </p>

                </a>

            </div>

        </div>

    </div>

</section>