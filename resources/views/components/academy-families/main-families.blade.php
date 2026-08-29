<section class="py-28 bg-stone-50 dark:bg-[#1B1A17]">

    <div class="mx-auto max-w-screen-2xl px-8 lg:px-12">

        {{-- Heading --}}
        <div class="mx-auto max-w-3xl text-center">

            <p class="uppercase tracking-[0.35em] text-[#B08D57]">

                The Main Families

            </p>

            <h2 class="mt-5 font-serif text-5xl">

                Discover the World's
                <br>
                Most Popular Fragrance Families

            </h2>

            <p class="mt-8 leading-8 text-stone-600 dark:text-stone-400">

                Although thousands of perfumes exist, most belong to a handful
                of major fragrance families. Each family has its own distinctive
                personality, mood, and signature ingredients.

            </p>

        </div>

        {{-- Cards --}}
        <div class="mt-20 grid gap-8 md:grid-cols-2 xl:grid-cols-3">

            @foreach([

                [
                    'Floral',
                    'floral.jpg',
                    'Elegant • Romantic',
                    'Centered around flowers such as Rose, Jasmine, Lily, and Orange Blossom. Floral fragrances are elegant, and expressive, ranging from delicate bouquets to rich white florals suitable for every occasion.',
                    'Spring • Romantic • Elegant'
                ],

                [
                    'Woody',
                    'woody.jpg',
                    'Warm • Sophisticated',
                    'Built upon Sandalwood, Cedarwood, Vetiver, and Patchouli, Woody fragrances offer warmth, depth, and sophistication with a refined character perfect for daily wear or formal occasions.',
                    'Autumn • Formal • Evening'
                ],

                [
                    'Fresh',
                    'fresh.jpg',
                    'Clean • Energizing',
                    'Fresh fragrances evoke the feeling of clean air, green leaves, herbs,
                    and aquatic accords. Bright, uplifting, and easy to wear, they are perfect
                    for warm weather and anyone who enjoys a crisp, energetic scent profile.',
                    'Summer • Office • Daily Wear'
                ],

                [
                    'Citrus',
                    'citrus.jpg',
                    'Bright • Vibrant',
                    'Inspired by fruits such as Bergamot, Lemon, Orange, and Grapefruit,
                    Citrus fragrances deliver a sparkling, vibrant opening full of freshness.
                    Their energetic character makes them an excellent choice for daytime wear
                    and summer climates.',
                    'Daytime • Hot Weather • Casual'
                ],

                [
                    'Amber',
                    'amber.jpg',
                    'Rich • Sensual',
                    'Amber fragrances combine warm resins, spices, vanilla, aromatic, and balsamic notes
                    to create luxurious depth, sexiest, and sensuality. Rich and enveloping, this family
                    is especially popular during evenings and cooler seasons.',
                    'Night • Winter • Formal Events'
                ],

                [
                    'Gourmand',
                    'gourmand.jpg',
                    'Sweet • Comforting',
                    'Gourmand fragrances are inspired by edible notes such as Vanilla,
                    Chocolate, Coffee, Caramel, and Tonka Bean. Sweet, comforting, and
                    deliciously addictive, they create a cozy atmosphere while leaving
                    a memorable impression.',
                    'Date Night • Winter • Cozy Moments'
                ],

            ] as [$title,$image,$subtitle,$description,$bestFor])

            <div class="group flex flex-col overflow-hidden rounded-[2rem] border border-stone-200 bg-white transition duration-300 hover:-translate-y-2 hover:shadow-2xl dark:border-stone-700 dark:bg-[#242424]">

                {{-- Image --}}
                <div class="overflow-hidden">

                    <img loading="lazy" decoding="async"
                        src="{{ asset('images/academy/'.$image) }}"
                        alt="{{ $title }}"
                        class="h-80 w-full object-cover transition duration-700 group-hover:scale-105">

                </div>

                {{-- Content --}}
                <div class="flex flex-1 flex-col p-8">

                    <p class="uppercase tracking-[0.3em] text-sm text-[#B08D57]">
                        {{ $subtitle }}
                    </p>

                    <h3 class="mt-4 font-serif text-3xl">
                        {{ $title }}
                    </h3>

                    <p class="mt-6 leading-8 text-stone-600 dark:text-stone-400">
                        {{ $description }}
                    </p>

                    {{-- Best For --}}
                    <div class="mt-auto pt-8">

                        <div class="border-t border-stone-200 dark:border-stone-700"></div>

                        <p class="mt-6 text-xs uppercase tracking-[0.35em] text-[#B08D57]">
                            Best For
                        </p>

                        <p class="mt-3 text-sm leading-7 text-stone-500 dark:text-stone-400">
                            {{ $bestFor }}
                        </p>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</section>