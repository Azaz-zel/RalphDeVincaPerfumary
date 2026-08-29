<section class="py-28 bg-white dark:bg-[#1C1C1C]">

    <div class="mx-auto max-w-screen-2xl px-8 lg:px-12">

        {{-- Heading --}}
        <div class="mx-auto max-w-3xl text-center">

            <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">

                Creation Process

            </p>

            <h2 class="mt-5 font-serif text-5xl">

                From Nature to Bottle

            </h2>

            <p class="mx-auto mt-8 max-w-3xl leading-8 text-stone-600 dark:text-stone-400">

                Every perfume begins with carefully selected raw materials and
                passes through a meticulous production process. From extracting
                aromatic ingredients to blending and aging the fragrance, each
                step contributes to the final masterpiece inside every bottle.

            </p>

        </div>

        {{-- Steps --}}
        <div class="mt-24 space-y-12">

            @foreach([

                [
                    '01',
                    'Ingredient Selection',
                    'Every perfume begins with selecting high-quality raw materials. Perfumers combine natural ingredients such as flowers, woods, spices, fruits, and resins with carefully developed aroma molecules to achieve a desired scent profile. The quality of these ingredients determines the character and uniqueness of the final fragrance.',
                    'academy/process-ingredients.jpg'
                ],

                [
                    '02',
                    'Extraction',
                    'Once the ingredients have been chosen, their aromatic compounds are extracted using specialized techniques such as steam distillation, cold pressing, solvent extraction, or enfleurage. Each method is selected to preserve the natural aroma while capturing the purest essence of the material.',
                    'academy/process-extraction.jpg'
                ],

                [
                    '03',
                    'Blending',
                    'The extracted fragrance materials are carefully measured and blended according to a unique perfume formula. This stage requires exceptional precision, as even the smallest adjustment can significantly change the balance, harmony, and emotional character of the fragrance.',
                    'academy/process-blending.jpg'
                ],

                [
                    '04',
                    'Maceration',
                    'After blending, the perfume is left to mature in controlled conditions for several weeks or even months. During this process, known as maceration, the ingredients gradually bond together, resulting in a smoother, richer, and more balanced fragrance composition.',
                    'academy/process-maceration.jpg'
                ],

                [
                    '05',
                    'Filtration & Bottling',
                    'Before reaching consumers, the matured perfume is filtered to remove impurities and ensure exceptional clarity. It is then bottled, packaged, and carefully inspected, completing its transformation from raw ingredients into a finished luxury fragrance.',
                    'academy/process-bottling.jpg'
                ]

            ] as [$number, $title, $description, $image])

                <div class="grid items-center gap-14 lg:grid-cols-2">

                    {{-- Image --}}
                    <div class="{{ $loop->even ? 'lg:order-2' : '' }}">

                        <img loading="lazy" decoding="async"
                            src="{{ asset('images/' . $image) }}"
                            alt="{{ $title }}"
                            class="h-[380px] w-full rounded-[2rem] object-cover shadow-xl">

                    </div>

                    {{-- Content --}}
                    <div class="{{ $loop->even ? 'lg:order-1' : '' }}">

                        <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">

                            Step {{ $number }}

                        </p>

                        <h3 class="mt-5 font-serif text-4xl">

                            {{ $title }}

                        </h3>

                        <p class="mt-3 leading-8 text-stone-600 dark:text-stone-400">

                            {{ $description }}

                        </p>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>