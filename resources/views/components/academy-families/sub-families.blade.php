<section class="py-28">

    <div class="mx-auto max-w-screen-2xl px-8 lg:px-12">

        {{-- Heading --}}
        <div class="mx-auto max-w-3xl text-center">

            <p class="uppercase tracking-[0.35em] text-[#B08D57]">

                Explore Deeper

            </p>

            <h2 class="mt-5 font-serif text-5xl">

                Every Family Has
                <br>
                Its Own Variations

            </h2>

            <p class="mt-8 leading-8 text-stone-600 dark:text-stone-400">

                Each fragrance family contains smaller subcategories that highlight
                different ingredients, styles, and personalities. These variations
                make it easier to understand why two perfumes can belong to the
                same family while smelling completely different.

            </p>

        </div>

        {{-- Grid --}}
        <div class="mt-20 grid gap-8 md:grid-cols-2 xl:grid-cols-3">

            @foreach([

                [
                    'Floral',
                    ['Rose Floral', 'White Floral', 'Powdery Floral', 'Fruity Floral']
                ],

                [
                    'Woody',
                    ['Sandalwood', 'Cedarwood', 'Vetiver', 'Patchouli']
                ],

                [
                    'Fresh',
                    ['Green', 'Aquatic', 'Aromatic', 'Herbal']
                ],

                [
                    'Citrus',
                    ['Bergamot', 'Lemon', 'Orange', 'Grapefruit']
                ],

                [
                    'Amber',
                    ['Vanilla', 'Resinous', 'Incense', 'Spicy']
                ],

                [
                    'Gourmand',
                    ['Vanilla', 'Coffee', 'Chocolate', 'Caramel']
                ],

            ] as [$family, $items])

            <div class="rounded-[2rem] border border-stone-200 bg-white p-8 dark:border-stone-700 dark:bg-[#242424]">

                <p class="uppercase tracking-[0.3em] text-sm text-[#B08D57]">

                    {{ $family }}

                </p>

                <ul class="mt-8 space-y-5">

                    @foreach($items as $item)

                    <li class="flex items-center gap-4">

                        <span class="h-2.5 w-2.5 rounded-full bg-[#B08D57]"></span>

                        <span class="text-lg text-stone-700 dark:text-stone-300">

                            {{ $item }}

                        </span>

                    </li>

                    @endforeach

                </ul>

            </div>

            @endforeach

        </div>

    </div>

</section>