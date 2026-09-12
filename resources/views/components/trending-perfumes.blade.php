@props(['trending'])

@if($trending->isNotEmpty())

<section class="py-24">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        {{-- Heading --}}
        <div class="mb-16 flex flex-wrap items-end justify-between gap-6">

            <div>
                <p class="text-sm uppercase tracking-[0.4em] text-[#B08D57]">
                    Explore
                </p>

                <h2 class="mt-3 font-serif text-4xl font-semibold lg:text-5xl">
                    Trending Perfumes
                </h2>

                <p class="mt-5 text-base text-stone-600 dark:text-stone-400 lg:text-lg">
                    Discover today's most loved fragrances.
                </p>

            </div>

            <a
                href="{{ route('explore', ['trending' => 1]) }}"
                class="font-medium text-[#B08D57] transition hover:underline">

                View All →

            </a>

        </div>

        {{-- Grid --}}
        <div class="grid gap-8 sm:grid-cols-2 xl:grid-cols-4">

            @foreach($trending as $perfume)

                <x-explore-perfume
                    :image="$perfume->photo_url"
                    :brand="$perfume->brand?->name ?? 'Unknown Brand'"
                    :brand-slug="$perfume->brand?->slug"
                    :name="$perfume->name"
                    :slug="$perfume->slug"
                    :family="$perfume->fragranceFamily?->name ?? 'Unknown Family'"
                    :gender="$perfume->gender"
                    :season="$perfume->seasons->first()?->name ?? 'All Season'"
                    :rating="number_format((float) $perfume->rating, 1)"
                />

            @endforeach

        </div>

    </div>

</section>

@endif
