<section class="py-28">

    <div class="mx-auto max-w-7xl px-8">

        <div class="mb-16 flex items-end justify-between">

            <div>

                <p class="uppercase tracking-[0.35em] text-[#B08D57]">

                    Journal

                </p>

                <h2 class="font-serif text-5xl font-semibold">

                    Latest Articles

                </h2>

                <p class="mt-5 max-w-2xl text-lg text-stone-600 dark:text-stone-400">

                    Discover fragrance guides, buying tips, reviews,
                    and stories curated for perfume enthusiasts.

                </p>

            </div>

            <a
                href="{{ route('articles') }}"
                class="hidden font-medium text-[#B08D57] hover:underline md:block">

                View All →

            </a>

        </div>

        <div class="grid gap-8 lg:grid-cols-3">

            <x-article-card
                image="images/articles/top-notes-vs-base-notes.jpg"
                category="Beginner Guide"
                title="Top Notes vs Base Notes"
                description="Understand how perfumes evolve from the first spray to the final dry down."
                time="5 min read" />

            <x-article-card
                image="images/articles/signature-scent.jpg"
                category="Buying Guide"
                title="How to Choose Your Signature Scent"
                description="Find a fragrance that truly matches your personality and daily lifestyle."
                time="6 min read" />

            <x-article-card
                image="images/articles/designer-vs-niche.jpg"
                category="Fragrance Insight"
                title="Designer vs Niche Perfume"
                description="Discover the differences between designer and niche fragrances."
                time="7 min read" />

        </div>

    </div>

</section>