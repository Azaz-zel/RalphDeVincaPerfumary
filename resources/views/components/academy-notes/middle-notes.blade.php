<section class="py-28 bg-white dark:bg-[#1C1C1C]">

    <div class="mx-auto max-w-screen-2xl px-8 lg:px-12">

        <div class="grid items-center gap-20 lg:grid-cols-2">

            {{-- Content --}}
            <div>

                <p class="uppercase tracking-[0.35em] text-[#B08D57]">

                    Stage 02

                </p>

                <h2 class="mt-5 font-serif text-5xl">

                    Heart Notes

                </h2>

                <p class="mt-8 leading-8 text-stone-600 dark:text-stone-400">

                    As the Top Notes begin to fade, the Heart Notes emerge and
                    reveal the true personality of the fragrance. They form the
                    central theme of a perfume, adding depth, richness, and
                    character to the composition.

                </p>

                <p class="mt-6 leading-8 text-stone-600 dark:text-stone-400">

                    Heart Notes generally appear within 15–30 minutes after
                    application and remain noticeable for several hours. This is
                    the stage where floral bouquets, soft spices, herbs, and
                    fruity accords create the perfume's signature identity.

                </p>

                <div class="mt-12">

                    <p class="mb-5 uppercase tracking-[0.35em] text-sm text-[#B08D57]">

                        Common Heart Note Ingredients

                    </p>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">

                        @foreach([

                            ['Rose','Elegant • Floral'],
                            ['Jasmine','Rich • White Floral'],
                            ['Lavender','Fresh • Aromatic'],
                            ['Geranium','Green • Rosy'],
                            ['Cardamom','Warm • Spicy'],
                            ['Cinnamon','Sweet • Spicy']

                        ] as [$ingredient,$desc])

                            <div class="rounded-2xl border border-stone-200 bg-white p-5 transition duration-300 hover:-translate-y-1 hover:border-[#B08D57] hover:shadow-lg dark:border-stone-700 dark:bg-[#242424]">

                                <h4 class="font-serif text-xl">

                                    {{ $ingredient }}

                                </h4>

                                <p class="mt-2 text-sm text-stone-500 dark:text-stone-400">

                                    {{ $desc }}

                                </p>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

            {{-- Image --}}
            <div>

                <img loading="lazy" decoding="async"
                    src="{{ asset('images/academy/middle-notes.jpg') }}"
                    alt="Heart Notes"
                    class="w-full rounded-[2rem] shadow-2xl">

            </div>

        </div>

    </div>

</section>