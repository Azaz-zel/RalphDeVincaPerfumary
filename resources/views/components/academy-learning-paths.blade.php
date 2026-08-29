<section id="learning-paths" class="bg-stone-50 py-24 dark:bg-[#1C1C1C]">

    <div class="mx-auto max-w-screen-2xl px-8 lg:px-12">

        {{-- Heading --}}
        <div class="text-center">

            <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">

                Learning Paths

            </p>

            <h2 class="mt-4 font-serif text-4xl lg:text-5xl">

                Your Fragrance Journey
                <br>
                Starts Here

            </h2>

            <p class="mx-auto mt-6 max-w-3xl leading-8 text-stone-600 dark:text-stone-400">

                Learn perfumery step by step through eight carefully structured lessons.
                Begin with the fundamentals and gradually build the knowledge needed
                to confidently explore, understand, and choose fragrances.

            </p>

        </div>

        {{-- Cards --}}
        <div class="mt-20 grid gap-8 md:grid-cols-2 xl:grid-cols-4">

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
            ],

            [
                'Perfume Concentration',
                'Learn the differences between Eau de Cologne, Eau de Toilette, Eau de Parfum, and Parfum.',
                'academy/concentration.jpg',
                route('academy.concentration')
            ],

            [
                'Performance & Longevity',
                'Discover what affects projection, sillage, and longevity, and why some perfumes last longer than others.',
                'academy/performance.jpg',
                route('academy.performance')
            ],

            [
                'Seasons & Occasions',
                'Find the perfect fragrance for summer, winter, work, formal events, or everyday wear.',
                'academy/seasons.jpg',
                route('academy.seasons')
            ],

            [
                'How to Apply Perfume',
                'Master the correct application techniques to maximize longevity without overwhelming those around you.',
                'academy/apply.jpg',
                route('academy.application')
            ],

            [
                'Build Your Collection',
                'Learn how to build a versatile fragrance wardrobe for every mood, season, and occasion.',
                'academy/collection.jpg',
                route('academy.collection')
            ]

        ] as [$title, $description, $image, $url])

            <a
                href="{{ $url }}"
                class="group overflow-hidden rounded-3xl border border-stone-200 bg-white transition duration-300 hover:-translate-y-2 hover:shadow-xl dark:border-stone-800 dark:bg-[#242424]">

                <div class="overflow-hidden">
                    <img loading="lazy" decoding="async"
                        src="{{ asset('images/' . $image) }}"
                        alt="{{ $title }}"
                        class="h-60 w-full object-cover transition duration-500 group-hover:scale-105">
                </div>

                <div class="p-7">

                    <p class="text-xs uppercase tracking-[0.35em] text-[#B08D57]">
                        LEVEL {{ $loop->iteration }}
                    </p>

                    <h3 class="mt-4 font-serif text-2xl">
                        {{ $title }}
                    </h3>

                    <p class="mt-4 leading-7 text-stone-600 dark:text-stone-400">
                        {{ $description }}
                    </p>

                    <div class="mt-8 flex items-center gap-2 text-[#B08D57]">
                        Read Lesson
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round"
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