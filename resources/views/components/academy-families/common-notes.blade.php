<section class="py-28 from-[#F8F5F0] dark:bg-[#1B1A17]">

    <div class="mx-auto max-w-screen-2xl px-8 lg:px-12">

        {{-- Heading --}}
        <div class="mx-auto max-w-4xl text-center">

            <p class="uppercase tracking-[0.35em] text-[#B08D57]">
                Most Common Notes
            </p>

            <h2 class="mt-5 font-serif text-5xl">
                Learn the Ingredients Behind Every Fragrance
            </h2>

            <p class="mt-8 leading-8 text-stone-600 dark:text-stone-400">
                Every perfume is built from carefully selected ingredients known as fragrance notes.
                Learning these notes makes it easier to recognize fragrance families and discover
                perfumes that match your personal taste.
            </p>

        </div>

        {{-- Cards --}}
        <div class="mt-20 grid gap-8 md:grid-cols-2 xl:grid-cols-3">

            @foreach([

                [
                    'Bergamot',
                    'bergamot.jpg',
                    'Citrus',
                    'Bright, sparkling and slightly bitter. Bergamot gives perfumes a fresh opening and is one of the most iconic citrus ingredients.',
                    'Fresh • Sparkling • Elegant'
                ],

                [
                    'Rose',
                    'rose.jpg',
                    'Floral',
                    'Rose brings timeless elegance with a rich romantic floral aroma ranging from fresh petals to rich velvety blooms.',
                    'Romantic • Soft • Classic'
                ],

                [
                    'Sandalwood',
                    'sandalwood.jpg',
                    'Woody',
                    'Creamy, smooth, sexy and warm. Sandalwood provides depth, masculine and a luxurious woody base in many fragrances.',
                    'Creamy • Warm • Smooth'
                ],

                [
                    'Vanilla',
                    'vanilla.jpg',
                    'Gourmand',
                    'Sweet and comforting with creamy warmth. Vanilla softens fragrances while adding addictive richness.',
                    'Sweet • Cozy • Creamy'
                ],

                [
                    'Vetiver',
                    'vetiver.png',
                    'Woody',
                    'Earthy, smoky and dry with elegant green facets. A favorite ingredient in masculine and unisex perfumes.',
                    'Earthy • Dry • Green'
                ],

                [
                    'Jasmine',
                    'jasmine.jpg',
                    'Floral',
                    'Rich white floral with creamy and slightly fruity nuances. Jasmine adds sophistication and sensuality.',
                    'White Floral • Rich • Elegant'
                ],

            ] as [$title,$image,$family,$description,$smell])

            <div class="group overflow-hidden rounded-[2rem] border border-stone-200 bg-white transition duration-300 hover:-translate-y-2 hover:shadow-2xl dark:border-stone-700 dark:bg-[#242424]">

                {{-- Image --}}
                <div class="overflow-hidden">

                    <img loading="lazy" decoding="async"
                        src="{{ asset('images/academy/'.$image) }}"
                        alt="{{ $title }}"
                        class="h-72 w-full object-cover transition duration-700 group-hover:scale-105">

                </div>

                {{-- Content --}}
                <div class="p-8">

                    <p class="uppercase tracking-[0.35em] text-xs text-[#B08D57]">
                        {{ $family }}
                    </p>

                    <h3 class="mt-3 font-serif text-3xl">
                        {{ $title }}
                    </h3>

                    <p class="mt-5 leading-8 text-stone-600 dark:text-stone-400">
                        {{ $description }}
                    </p>

                    <div class="mt-8 border-t border-stone-200 pt-6 dark:border-stone-700">

                        <p class="uppercase tracking-[0.35em] text-xs text-[#B08D57]">
                            Smells Like
                        </p>

                        <p class="mt-3 text-sm text-stone-500 dark:text-stone-400">
                            {{ $smell }}
                        </p>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</section>