@props(['stats' => []])

<section class="py-24 transition-colors duration-300">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        {{-- Heading --}}
        <div class="mb-16 text-center">

            <p class="text-sm uppercase tracking-[0.4em] text-[#B08D57]">
                Browse
            </p>

            <h2 class="mt-3 font-serif text-4xl font-semibold lg:text-5xl">
                Choose Your Fragrance Journey
            </h2>

            <p class="mx-auto mt-5 max-w-2xl text-base text-stone-600 dark:text-stone-400 lg:text-lg">
                Begin your fragrance journey through curated paths.
            </p>

        </div>

        {{-- Bento Grid --}}
        <div class="grid gap-8 lg:grid-cols-3">

            {{-- Explore Perfumes (wide) --}}
            <a href="{{ route('explore') }}"
                class="group overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm transition duration-500 hover:-translate-y-2 hover:shadow-2xl dark:border-stone-700 dark:bg-[#232323] lg:col-span-2">

                <img loading="lazy" decoding="async"
                    src="{{ asset('images/ui/category-perfumes.jpg') }}"
                    class="h-72 w-full object-cover transition duration-700 group-hover:scale-105 sm:h-80"
                    alt="Explore Perfumes">

                <div class="p-8">

                    <p class="text-xs uppercase tracking-[0.35em] text-[#B08D57]">
                        {{ $stats['perfumes'] ?? '' }} Fragrances
                    </p>

                    <h3 class="mt-4 font-serif text-3xl lg:text-4xl">
                        Explore Perfumes
                    </h3>

                    <p class="mt-4 max-w-2xl leading-8 text-stone-600 dark:text-stone-400">
                        Discover luxury, niche, designer, and artisan fragrances
                        from around the world.
                    </p>

                    <span class="mt-8 inline-block font-medium text-[#B08D57] transition group-hover:translate-x-1">
                        Explore →
                    </span>

                </div>

            </a>

            {{-- Academy --}}
            <a href="{{ route('academy') }}"
                class="group overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm transition duration-500 hover:-translate-y-2 hover:shadow-2xl dark:border-stone-700 dark:bg-[#232323]">

                <img loading="lazy" decoding="async"
                    src="{{ asset('images/ui/category-academy.jpg') }}"
                    class="h-72 w-full object-cover transition duration-700 group-hover:scale-105 sm:h-80"
                    alt="Academy">

                <div class="p-8">

                    <p class="text-xs uppercase tracking-[0.35em] text-[#B08D57]">
                        Learn
                    </p>

                    <h3 class="mt-4 font-serif text-3xl">
                        Fragrance Academy
                    </h3>

                    <p class="mt-4 leading-7 text-stone-600 dark:text-stone-400">
                        Learn notes, concentration, projection,
                        longevity, and fragrance families.
                    </p>

                    <span class="mt-8 inline-block font-medium text-[#B08D57] transition group-hover:translate-x-1">
                        Learn →
                    </span>

                </div>

            </a>

            {{-- Brands --}}
            <a href="{{ route('brands.index') }}"
                class="group rounded-3xl border border-stone-200 bg-white p-8 shadow-sm transition duration-500 hover:-translate-y-2 hover:shadow-2xl dark:border-stone-700 dark:bg-[#232323]">

                <p class="text-xs uppercase tracking-[0.35em] text-[#B08D57]">
                    {{ $stats['brands'] ?? '' }} Houses
                </p>

                <h3 class="mt-4 font-serif text-3xl">
                    Fragrance Houses
                </h3>

                <p class="mt-4 leading-7 text-stone-600 dark:text-stone-400">
                    From centuries-old maisons to independent niche perfumers —
                    explore the houses behind the fragrances.
                </p>

                <span class="mt-8 inline-block font-medium text-[#B08D57] transition group-hover:translate-x-1">
                    Browse Houses →
                </span>

            </a>

            {{-- Notes --}}
            <a href="{{ route('notes.index') }}"
                class="group rounded-3xl border border-stone-200 bg-white p-8 shadow-sm transition duration-500 hover:-translate-y-2 hover:shadow-2xl dark:border-stone-700 dark:bg-[#232323] lg:col-span-2">

                <p class="text-xs uppercase tracking-[0.35em] text-[#B08D57]">
                    {{ $stats['notes'] ?? '' }} Raw Materials
                </p>

                <h3 class="mt-4 font-serif text-3xl">
                    Fragrance Notes
                </h3>

                <p class="mt-4 max-w-2xl leading-7 text-stone-600 dark:text-stone-400">
                    The building blocks of every scent — from rare naturals like
                    oud and orris to modern molecules like Iso E Super.
                </p>

                <span class="mt-8 inline-block font-medium text-[#B08D57] transition group-hover:translate-x-1">
                    Browse Notes →
                </span>

            </a>

        </div>

    </div>

</section>
