@props(['brand'])

<section class="bg-stone-50 py-24 dark:bg-[#1C1C1C]">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        <div class="grid gap-20 lg:grid-cols-2">

            {{-- Left --}}
            <div class="lg:sticky lg:top-32 lg:self-start">

                <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">

                    Brand Philosophy

                </p>

                <h2 class="mt-4 font-serif text-5xl leading-tight">

                    "{{ $brand->philosophy_quote ?? 'Perfume is a reflection of identity.' }}"

                </h2>

                <p class="mt-8 leading-8 text-stone-600 dark:text-stone-400">

                    {{ $brand->philosophy_intro ?? "Every {$brand->name} fragrance is created with the belief that scent should tell a story and become an extension of the person who wears it." }}

                </p>

            </div>

            {{-- Right --}}
            <div class="space-y-12">

                @forelse(($brand->philosophy_pillars ?? []) as $pillar)

                    <div class="border-l-2 border-[#B08D57] pl-8">

                        <h3 class="font-serif text-3xl">

                            {{ $pillar['title'] }}

                        </h3>

                        <p class="mt-4 leading-8 text-stone-600 dark:text-stone-400">

                            {{ $pillar['description'] }}

                        </p>

                    </div>

                @empty

                    <div class="border-l-2 border-[#B08D57] pl-8">

                        <h3 class="font-serif text-3xl">
                            Craftsmanship
                        </h3>

                        <p class="mt-4 leading-8 text-stone-600 dark:text-stone-400">
                            {{ $brand->name }} is built on a dedication to quality materials and meticulous
                            fragrance composition.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</section>
