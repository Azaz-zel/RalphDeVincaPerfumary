<footer class="border-t border-stone-200 bg-stone-50 py-20 transition-colors duration-300 dark:border-stone-700 dark:bg-[#232323]">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        <div class="grid gap-12 md:grid-cols-3">

            {{-- Brand --}}
            <div>

                <p class="font-serif text-2xl text-stone-900 dark:text-stone-100">
                    Ralph de Vinca
                </p>

                <p class="mt-1 text-xs uppercase tracking-[0.35em] text-[#B08D57]">
                    Perfumary
                </p>

                <p class="mt-5 max-w-xs text-sm leading-7 text-stone-500 dark:text-stone-400">
                    A fragrance encyclopedia — explore perfumes, houses,
                    and the raw materials behind every scent.
                </p>

            </div>

            {{-- Discover --}}
            <div>

                <p class="text-xs uppercase tracking-[0.3em] text-stone-500">
                    Discover
                </p>

                <div class="mt-5 flex flex-col gap-3 text-sm text-stone-600 dark:text-stone-300">

                    <a href="{{ route('explore') }}" class="transition hover:text-[#B08D57]">Explore Perfumes</a>
                    <a href="{{ route('brands.index') }}" class="transition hover:text-[#B08D57]">Fragrance Houses</a>
                    <a href="{{ route('notes.index') }}" class="transition hover:text-[#B08D57]">Fragrance Notes</a>

                </div>

            </div>

            {{-- Learn --}}
            <div>

                <p class="text-xs uppercase tracking-[0.3em] text-stone-500">
                    Learn
                </p>

                <div class="mt-5 flex flex-col gap-3 text-sm text-stone-600 dark:text-stone-300">

                    <a href="{{ route('academy') }}" class="transition hover:text-[#B08D57]">Fragrance Academy</a>
                    <a href="{{ route('academy.notes') }}" class="transition hover:text-[#B08D57]">Understanding Notes</a>
                    <a href="{{ route('about') }}" class="transition hover:text-[#B08D57]">About</a>

                </div>

            </div>

        </div>

        <div class="mt-16 border-t border-stone-200 pt-8 text-center text-sm text-stone-500 dark:border-stone-700 dark:text-stone-400">

            © {{ date('Y') }} Ralph de Vinca Perfumary

        </div>

    </div>

</footer>
