@props(['brand', 'perfumes'])

<section class="py-24">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        {{-- Heading --}}
        <div class="text-center">

            <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">

                Signature Collection

            </p>

            <h2 class="mt-4 font-serif text-4xl">

                Signature Fragrances

            </h2>

            <p class="mx-auto mt-5 max-w-2xl leading-8 text-stone-600 dark:text-stone-400">

                Explore {{ $brand->name }}'s most celebrated fragrances, each representing
                the maison's heritage, craftsmanship, and timeless elegance.

            </p>

        </div>

        {{-- Grid --}}
        @if($perfumes->isNotEmpty())

            @php
                $gridClass = match($perfumes->count()) {
                    1 => 'max-w-xs mx-auto',
                    2 => 'max-w-2xl mx-auto sm:grid-cols-2',
                    3 => 'max-w-4xl mx-auto sm:grid-cols-2 lg:grid-cols-3',
                    default => 'sm:grid-cols-2 lg:grid-cols-4',
                };
            @endphp

            <div class="mt-16 grid gap-8 {{ $gridClass }}">

                @foreach($perfumes as $perfume)

                    <x-explore-perfume
                        :image="$perfume->image"
                        :brand="$brand->name"
                        :name="$perfume->name"
                        :slug="$perfume->slug"
                        :family="$perfume->fragranceFamily->name ?? 'Unknown Family'"
                        :gender="$perfume->gender"
                        :rating="$perfume->rating"
                    />

                @endforeach

            </div>

        @else

            <div class="mt-16 rounded-3xl border border-stone-200 bg-white p-12 text-center dark:border-stone-700 dark:bg-[#232323]">

                <p class="text-stone-500 dark:text-stone-400">
                    No fragrances have been added for {{ $brand->name }} yet.
                </p>

            </div>

        @endif

        {{-- CTA --}}
        <div class="mt-16 flex flex-wrap items-center justify-center gap-4">

            <a
                href="{{ route('explore') }}"
                class="rounded-full border border-stone-300 px-7 py-3 transition hover:border-[#B08D57] hover:text-[#B08D57]">

                ← Back to Explore

            </a>

            <a
                href="{{ route('explore', ['search' => $brand->name]) }}"
                class="rounded-full bg-[#B08D57] px-7 py-3 text-white transition hover:opacity-90">

                View All {{ $brand->name }} Fragrances →

            </a>

        </div>

    </div>

</section>
