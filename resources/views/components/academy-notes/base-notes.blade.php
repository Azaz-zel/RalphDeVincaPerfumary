<section class="py-28 bg-stone-50 dark:bg-[#1B1A17]">

    <div class="mx-auto max-w-screen-2xl px-8 lg:px-12">

        <div class="grid items-center gap-20 lg:grid-cols-2">

            {{-- Image --}}
            <div>

                <img loading="lazy" decoding="async"
                    src="{{ asset('images/academy/base-notes.jpg') }}"
                    alt="Base Notes"
                    class="w-full rounded-[2rem] shadow-2xl">

            </div>

            {{-- Content --}}
            <div>

                <p class="uppercase tracking-[0.35em] text-[#B08D57]">

                    Stage 03

                </p>

                <h2 class="mt-5 font-serif text-5xl">

                    Base Notes

                </h2>

                <p class="mt-8 leading-8 text-stone-600 dark:text-stone-400">

                    Base Notes are the final stage of a fragrance, emerging once
                    the Heart Notes begin to soften. They create the lasting
                    foundation of the perfume, providing depth, warmth, and
                    longevity that can remain on the skin for many hours.

                </p>

                <p class="mt-6 leading-8 text-stone-600 dark:text-stone-400">

                    Rich woods, resins, vanilla, musk, and amber are commonly
                    used as Base Notes because they evaporate slowly. These
                    ingredients anchor the fragrance, allowing every other note
                    to blend harmoniously while leaving a memorable trail long
                    after the first spray.

                </p>

                {{-- Ingredients --}}
                <div class="mt-12">

                    <p class="mb-5 text-sm uppercase tracking-[0.35em] text-[#B08D57]">

                        Common Base Note Ingredients

                    </p>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">

                        @foreach([

                            ['Sandalwood','Creamy • Woody'],
                            ['Vanilla','Sweet • Warm'],
                            ['Amber','Rich • Resinous'],
                            ['Patchouli','Earthy • Deep'],
                            ['Tonka Bean','Warm • Gourmand'],
                            ['Musk','Soft • Clean']

                        ] as [$ingredient,$description])

                            <div
                                class="group rounded-2xl border border-stone-200 bg-white p-5 transition duration-300 hover:-translate-y-1 hover:border-[#B08D57] hover:shadow-lg dark:border-stone-700 dark:bg-[#242424]">

                                <h4 class="font-serif text-xl">

                                    {{ $ingredient }}

                                </h4>

                                <p class="mt-2 text-sm text-stone-500 dark:text-stone-400">

                                    {{ $description }}

                                </p>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>