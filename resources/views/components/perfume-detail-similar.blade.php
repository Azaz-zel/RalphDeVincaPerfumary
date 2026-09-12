@props([
    'perfume',
    'similarPerfumes',
])

<section class="py-24">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        {{-- Heading --}}
        <div class="text-center">

            <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">
                Similar Fragrances
            </p>

            <h2 class="mt-3 font-serif text-4xl">
                You May Also Like
            </h2>

            <p class="mx-auto mt-4 max-w-2xl text-stone-600 dark:text-stone-400">
                Explore fragrances with similar scent profiles,
                character, and overall wearing experience.
            </p>

        </div>


        {{-- Cards --}}
        <div class="mt-16 grid gap-8 md:grid-cols-2 xl:grid-cols-4">

            @forelse($similarPerfumes as $similar)

                <x-explore-perfume
                    :image="$similar->photo_url"
                    :brand="$similar->brand?->name ?? 'Unknown Brand'"
                    :brand-slug="$similar->brand?->slug"
                    :name="$similar->name"
                    :slug="$similar->slug"
                    :family="$similar->fragranceFamily?->name ?? 'Unknown Family'"
                    :gender="$similar->gender ?? 'Unisex'"
                    :season="$similar->seasons->first()?->name ?? 'All Season'"
                    :rating="number_format((float) $similar->rating, 1)"
                />

            @empty

                <div class="col-span-full py-12 text-center">

                    <p class="text-stone-500">
                        No similar fragrances available yet.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>