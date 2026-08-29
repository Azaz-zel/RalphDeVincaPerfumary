<section class="py-24">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        {{-- Heading --}}
        <div class="mb-16 flex flex-wrap items-end justify-between gap-6">

            <div>

                <p class="text-sm uppercase tracking-[0.4em] text-[#B08D57]">
                    Learn
                </p>

                <h2 class="mt-3 font-serif text-4xl font-semibold lg:text-5xl">
                    Fragrance Academy
                </h2>

                <p class="mt-5 max-w-2xl text-base text-stone-600 dark:text-stone-400 lg:text-lg">

                    Learn perfume step by step through structured lessons,
                    from the fundamentals to advanced fragrance knowledge.

                </p>

            </div>

            <a
                href="{{ route('academy') }}"
                class="font-medium text-[#B08D57] transition hover:underline">

                View All →

            </a>

        </div>

        {{-- Cards --}}
        <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">

            @foreach([

                [
                    'Introduction to Perfume',
                    'Learn the fundamentals of perfumery, fragrance structure, and how perfumes evolve from the first spray to the dry down.',
                    'academy/introduction.jpg',
                    route('academy.introduction')
                ],

                [
                    'Fragrance Notes',
                    'Understand Top, Heart, and Base Notes, and discover how every ingredient shapes the scent journey.',
                    'academy/fragrance-notes.png',
                    route('academy.notes')
                ],

                [
                    'Fragrance Families',
                    'Explore Floral, Woody, Amber, Citrus, Fresh, Gourmand, and many other fragrance families.',
                    'academy/fragrance-families.jpg',
                    route('academy.families')
                ]

            ] as [$title, $description, $image, $url])

                <a
                    href="{{ $url }}"
                    class="group overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm transition duration-300 hover:-translate-y-2 hover:shadow-xl dark:border-stone-800 dark:bg-[#242424]">

                    {{-- Image --}}
                    <div class="overflow-hidden">

                        <img loading="lazy" decoding="async"
                            src="{{ asset('images/' . $image) }}"
                            alt="{{ $title }}"
                            class="h-64 w-full object-cover transition duration-500 group-hover:scale-105">

                    </div>

                    {{-- Content --}}
                    <div class="p-7">

                        <p class="text-xs uppercase tracking-[0.35em] text-[#B08D57]">
                            Academy
                        </p>

                        <h3 class="mt-4 font-serif text-3xl">
                            {{ $title }}
                        </h3>

                        <p class="mt-4 leading-7 text-stone-600 dark:text-stone-400">
                            {{ $description }}
                        </p>

                        <div class="mt-8 inline-flex items-center gap-2 font-medium text-[#B08D57] transition group-hover:gap-3">

                            Read Lesson

                            <svg xmlns="http://www.w3.org/2000/svg"
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

                        </div>

                    </div>

                </a>

            @endforeach

        </div>

    </div>

</section>