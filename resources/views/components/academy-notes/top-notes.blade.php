<section class="py-28 bg-stone-50 dark:bg-[#1B1A17]">

    <div class="mx-auto max-w-screen-2xl px-8 lg:px-12">

        {{-- Heading --}}
        <div class="mx-auto max-w-3xl text-center">

            <p class="uppercase tracking-[0.35em] text-[#B08D57]">

                Stage 01

            </p>

            <h2 class="mt-5 font-serif text-5xl">

                Top Notes

            </h2>

            <p class="mx-auto mt-8 max-w-3xl leading-8 text-stone-600 dark:text-stone-400">

                Top Notes are the first scents you notice immediately after
                spraying a perfume. They create the fragrance's opening impression,
                introducing freshness and brightness before gradually giving way
                to the heart of the composition.

            </p>

        </div>

        {{-- Content --}}
        <div class="mt-24 grid items-center gap-20 lg:grid-cols-2">

            {{-- Image --}}
            <div>

                <img loading="lazy" decoding="async"
                    src="{{ asset('images/academy/top-notes.jpg') }}"
                    alt="Top Notes"
                    class="w-full rounded-[2rem] shadow-2xl">

            </div>

            {{-- Text --}}
            <div>

                <h3 class="font-serif text-4xl">

                    The First Impression

                </h3>

                <p class="mt-8 leading-8 text-stone-600 dark:text-stone-400">

                    Top Notes are composed of the lightest and most volatile
                    fragrance molecules. Because they evaporate quickly, they
                    usually last between <strong>5 to 15 minutes</strong> before
                    transitioning into the Heart Notes.

                </p>

                <p class="mt-6 leading-8 text-stone-600 dark:text-stone-400">

                    Although short-lived, Top Notes play a crucial role in
                    capturing attention and shaping your first perception of a
                    perfume. A fresh and inviting opening often determines whether
                    someone wants to continue experiencing the fragrance.

                </p>

                {{-- Common Top Note Ingredients --}}
                <div class="mt-14">

                    <p class="mb-6 text-sm uppercase tracking-[0.35em] text-[#B08D57]">

                        Common Top Note Ingredients

                    </p>

                    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">

                        @foreach([

                            [
                                'Bergamot',
                                'Fresh • Citrusy',
                            ],

                            [
                                'Lemon',
                                'Bright • Sparkling',
                            ],

                            [
                                'Grapefruit',
                                'Zesty • Crisp',
                            ],

                            [
                                'Mint',
                                'Cool • Aromatic',
                            ],

                            [
                                'Basil',
                                'Green • Herbal',
                            ],

                            [
                                'Neroli',
                                'Floral • Fresh',
                            ],

                        ] as [$ingredient, $character])

                            <div
                                class="group rounded-2xl border border-stone-200 bg-white p-5 transition duration-300 hover:-translate-y-1 hover:border-[#B08D57] hover:shadow-lg dark:border-stone-700 dark:bg-[#242424]">

                                <h4 class="font-serif text-xl">

                                    {{ $ingredient }}

                                </class=>

                                <p
                                    class="mt-2 text-sm tracking-wide text-stone-500 dark:text-stone-400">

                                    {{ $character }}

                                </p>

                            </div>

                        @endforeach

                    </div>

                </div>

        </div>

    </div>

</section>