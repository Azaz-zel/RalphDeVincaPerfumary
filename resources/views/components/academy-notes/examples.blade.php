<section class="py-28 bg-stone-50 dark:bg-[#1B1A17]">

    <div class="mx-auto max-w-7xl px-8">

        {{-- Heading --}}
        <div class="mx-auto max-w-3xl text-center">

            <p class="uppercase tracking-[0.4em] text-[#B08D57]">

                Real World Examples

            </p>

            <h2 class="mt-5 font-serif text-5xl">

                Discover the Pyramid in Real Perfumes

            </h2>

            <p class="mt-8 leading-8 text-stone-600 dark:text-stone-400">

                Every fragrance follows the same fundamental structure, but the
                ingredients used in each layer create a completely different
                personality. Here are a few iconic perfumes and the notes that
                define their fragrance journey.

            </p>

        </div>

        {{-- Cards --}}
        <div class="mt-20 grid gap-8 lg:grid-cols-3">

            @foreach([

                [
                    'Dior Sauvage',
                    'dior-sauvage.jpg',
                    'Bergamot',
                    'Lavender • Pepper',
                    'Ambroxan • Cedar',
                    'Fresh Aromatic'
                ],

                [
                    'Bleu de Chanel',
                    'bleu-de-chanel.jpg',
                    'Lemon • Mint',
                    'Ginger • Jasmine',
                    'Incense • Sandalwood',
                    'Woody Aromatic'
                ],

                [
                    'YSL Libre',
                    'ysl-libre.jpg',
                    'Mandarin • Lavender',
                    'Orange Blossom',
                    'Vanilla • Ambergris',
                    'Floral Amber'
                ]

            ] as [$name,$image,$top,$heart,$base,$family])

            <div
                class="group overflow-hidden rounded-[2rem] border border-stone-200 bg-white transition duration-300 hover:-translate-y-2 hover:shadow-xl">

                <div class="h-[420px] overflow-hidden">
                    <img loading="lazy" decoding="async"
                        src="{{ asset('images/perfumes/'.$image) }}"
                        alt="{{ $name }}"
                        class="h-full w-full object-contain transition duration-500 group-hover:scale-105">
                </div>

                <div class="p-8">

                    <span class="rounded-full bg-stone-100 px-3 py-1 text-xs dark:bg-stone-800">

                        {{ $family }}

                    </span>

                    <h3 class="mt-5 font-serif text-3xl">

                        {{ $name }}

                    </h3>

                    <div class="mt-8 space-y-6">

                        <div>

                            <p class="text-xs uppercase tracking-[0.3em] text-[#B08D57]">

                                Top Notes

                            </p>

                            <p class="mt-2">

                                {{ $top }}

                            </p>

                        </div>

                        <div>

                            <p class="text-xs uppercase tracking-[0.3em] text-[#B08D57]">

                                Heart Notes

                            </p>

                            <p class="mt-2">

                                {{ $heart }}

                            </p>

                        </div>

                        <div>

                            <p class="text-xs uppercase tracking-[0.3em] text-[#B08D57]">

                                Base Notes

                            </p>

                            <p class="mt-2">

                                {{ $base }}

                            </p>

                        </div>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</section>