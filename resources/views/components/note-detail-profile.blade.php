@props(['note'])

<section class="bg-stone-50 py-24 dark:bg-[#1C1C1C]">

    <div class="mx-auto max-w-5xl px-8 lg:px-16">

        {{-- Heading --}}
        <div class="text-center">

            <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">
                Fragrance Profile
            </p>

            <h2 class="mt-3 font-serif text-4xl">
                Ingredient Information
            </h2>

            <p class="mx-auto mt-5 max-w-2xl leading-8 text-stone-600 dark:text-stone-400">
                Essential information about {{ $note->name }}
                and its role in modern perfumery.
            </p>

        </div>


        {{-- Profile --}}
        <div class="mt-10 overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm dark:border-stone-700 dark:bg-[#232323]">

            @foreach([

                ['Fragrance Family', $note->fragrance_family],
                ['Origin', $note->origin],
                ['Extraction', $note->extraction],
                ['Common Role', $note->common_role],
                ['Ingredient Type', $note->ingredient_type],
                ['Longevity', $note->longevity],
                ['Intensity', $note->intensity],
                ['Best Paired With', $note->best_paired_with],

            ] as [$title, $value])

                <div class="flex items-center justify-between gap-8 border-b border-stone-200 px-8 py-6 last:border-none dark:border-stone-700">

                    <p class="text-sm uppercase tracking-[0.25em] text-stone-500">
                        {{ $title }}
                    </p>

                    <p class="font-serif text-2xl text-right">
                        {{ $value ?? '—' }}
                    </p>

                </div>

            @endforeach

        </div>

    </div>

</section>