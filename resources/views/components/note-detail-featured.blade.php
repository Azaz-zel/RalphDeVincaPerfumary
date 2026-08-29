@props(['note'])

<section class="py-24">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        {{-- Heading --}}
        <div class="text-center">

            <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">
                Featured Fragrances
            </p>

            <h2 class="mt-3 font-serif text-4xl">
                Fragrances Featuring {{ $note->name }}
            </h2>

            <p class="mx-auto mt-5 max-w-2xl leading-8 text-stone-600 dark:text-stone-400">

                Explore fragrances that feature
                {{ $note->name }}
                as one of their fragrance notes.

            </p>

        </div>


        {{-- Grid --}}
        @if ($note->perfumes->count())

            <div class="mt-16 grid gap-8 md:grid-cols-2 xl:grid-cols-4">

                @foreach ($note->perfumes->take(4) as $perfume)

                    <x-explore-perfume
                        :image="$perfume->image"
                        :brand="$perfume->brand->name ?? 'Unknown Brand'"
                        :brand-slug="$perfume->brand?->slug"
                        :name="$perfume->name"
                        :slug="$perfume->slug"
                        :family="$perfume->fragranceFamily->name ?? 'Unknown Family'"
                        :rating="$perfume->rating ?? null"
                    />

                @endforeach

            </div>

        @else

            <div class="mt-16 rounded-3xl border border-stone-200 bg-white p-12 text-center dark:border-stone-700 dark:bg-[#232323]">

                <p class="text-stone-500 dark:text-stone-400">
                    No fragrances featuring {{ $note->name }} have been added yet.
                </p>

            </div>

        @endif


        {{-- Button --}}
        <div class="mt-16 text-center">

            <a
                href="{{ route('explore') }}"
                class="inline-flex items-center gap-3 rounded-full border border-[#B08D57] px-8 py-3 font-medium text-[#B08D57] transition hover:bg-[#B08D57] hover:text-white">

                Explore More Fragrances

                <span>→</span>

            </a>

        </div>

    </div>

</section>