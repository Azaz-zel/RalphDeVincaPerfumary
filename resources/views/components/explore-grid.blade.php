@props(['perfumes', 'search' => ''])

<section class="pb-20">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        <div class="mb-10">

            <div class="flex items-center justify-between">

                <h2 class="font-serif text-3xl">
                    Explore Perfumes
                </h2>

                <span class="text-stone-500">
                    {{ $perfumes->total() }} Perfumes
                </span>

            </div>

            @if (!empty($search))
                <p class="mt-3 text-sm text-stone-500 dark:text-stone-400">
                    Search results for
                    <span class="font-medium text-stone-800 dark:text-stone-200">
                        "{{ $search }}"
                    </span>
                </p>
            @endif

        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">

            @foreach ($perfumes as $perfume)

                <x-explore-perfume
                    :image="$perfume->image"
                    :brand="$perfume->brand->name"
                    :brand-slug="$perfume->brand->slug"
                    :name="$perfume->name"
                    :slug="$perfume->slug"
                    :family="$perfume->fragranceFamily->name"
                    :gender="$perfume->gender"
                    :rating="$perfume->rating"
                />

            @endforeach

        </div>

    </div>

</section>