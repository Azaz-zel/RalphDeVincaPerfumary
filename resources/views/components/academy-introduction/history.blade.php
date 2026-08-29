<section class="py-28 bg-white dark:bg-[#1C1C1C]">

    <div class="mx-auto max-w-screen-2xl px-8 lg:px-12">

        {{-- Heading --}}
        <div class="mx-auto max-w-3xl text-center">

            <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">

                History of Perfume

            </p>

            <h2 class="mt-5 font-serif text-5xl">

                A Journey Through Time

            </h2>

            <p class="mx-auto mt-8 max-w-3xl leading-8 text-stone-600 dark:text-stone-400">

                The story of perfume spans thousands of years—from sacred rituals in
                Ancient Egypt to the luxury fragrance houses of modern France.
                Discover how perfume evolved into one of the world's most celebrated
                forms of art and self-expression.

            </p>

        </div>

        {{-- Timeline --}}
        <div class="mt-24 space-y-24">

            @foreach([

                [
                    '3000 BC',
                    'Ancient Egypt',
                    'Perfume played a vital role in religious ceremonies, medicine, and royal life. Egyptians believed fragrance connected humans with the divine, making scented oils and incense part of everyday culture. Aromatic materials such as myrrh, frankincense, and lotus were treasured for their spiritual significance and luxurious aroma. Their influence laid the earliest foundation for the art of perfumery as we know it today.',
                    'academy/history-egypt.jpg'
                ],

                [
                    '9th Century',
                    'The Arab World',
                    'During the Islamic Golden Age, Arab scholars revolutionized perfume making by perfecting distillation techniques. This innovation made it possible to extract essential oils with greater purity and consistency. Their scientific contributions transformed perfumery from a traditional craft into a discipline grounded in chemistry, influencing fragrance production for centuries to come.',
                    'academy/history-arab.jpg'
                ],

                [
                    '17th Century',
                    'France',
                    'By the seventeenth century, France had become the center of luxury perfumery, with the town of Grasse emerging as its beating heart. Surrounded by fields of jasmine, rose, and lavender, the region supplied the finest raw materials in Europe. Its craftsmanship and dedication to quality established France as the global benchmark for fine fragrance.',
                    'academy/history-france.jpg'
                ],

                [
                    'Today',
                    'Modern Perfumery',
                    'Modern perfumery combines centuries of tradition with cutting-edge innovation. Master perfumers now blend natural ingredients with advanced aroma molecules to create scents that are both artistic and technically refined. Today, perfume is more than a luxury product—it is a powerful form of personal identity, creativity, and self-expression.',
                    'academy/history-modern.jpg'
                ]

            ] as [$year, $title, $description, $image])

                <div class="grid items-center gap-16 lg:grid-cols-2">

                    {{-- Image --}}
                    <div class="{{ $loop->even ? 'lg:order-2' : '' }}">

                        <img loading="lazy" decoding="async"
                            src="{{ asset('images/' . $image) }}"
                            alt="{{ $title }}"
                            class="h-[420px] w-full rounded-[2rem] object-cover shadow-xl">

                    </div>

                    {{-- Content --}}
                    <div class="{{ $loop->even ? 'lg:order-1' : '' }}">

                        <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">

                            {{ $year }}

                        </p>

                        <h3 class="mt-5 font-serif text-4xl">

                            {{ $title }}

                        </h3>

                        <p class="mt-8 leading-8 text-stone-600 dark:text-stone-400">

                            {{ $description }}

                        </p>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>