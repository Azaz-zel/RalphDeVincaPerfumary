@props(['brand' => null, 'note' => null])

@if($brand || $note)

<section class="py-24">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        {{-- Heading --}}
        <div class="mb-16 text-center">

            <p class="text-sm uppercase tracking-[0.4em] text-[#B08D57]">
                Today's Spotlight
            </p>

            <h2 class="mt-3 font-serif text-4xl font-semibold lg:text-5xl">
                Worth Discovering
            </h2>

            <p class="mx-auto mt-5 max-w-2xl text-base text-stone-600 dark:text-stone-400 lg:text-lg">
                A house and an ingredient we think deserve your attention today.
            </p>

        </div>

        <div class="grid gap-8 lg:grid-cols-2">

            {{-- Featured House --}}
            @if($brand)

                <a href="{{ route('brand.detail', $brand->slug) }}"
                    class="group flex flex-col overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm transition duration-500 hover:-translate-y-2 hover:shadow-2xl dark:border-stone-700 dark:bg-[#232323]">

                    <div class="overflow-hidden">

                        <img loading="lazy" decoding="async"
                            src="{{ $brand->hero_image ? asset($brand->hero_image) : route('placeholder.brand', $brand->slug) }}"
                            onerror="this.onerror=null;this.src='{{ route('placeholder.brand', $brand->slug) }}';"
                            alt="{{ $brand->name }}"
                            class="h-64 w-full object-cover transition duration-700 group-hover:scale-105">

                    </div>

                    <div class="flex flex-1 flex-col p-8">

                        <p class="text-xs uppercase tracking-[0.35em] text-[#B08D57]">
                            Featured House
                        </p>

                        <h3 class="mt-4 font-serif text-3xl">
                            {{ $brand->name }}
                        </h3>

                        @if($brand->founded_year || $brand->founded_location)
                            <p class="mt-2 text-sm text-stone-500">
                                @if($brand->founded_location){{ $brand->founded_location }}@endif
                                @if($brand->founded_year && $brand->founded_location) &bull; @endif
                                @if($brand->founded_year){{ $brand->founded_year }}@endif
                            </p>
                        @endif

                        <p class="mt-4 flex-1 leading-8 text-stone-600 dark:text-stone-400">
                            {{ Str::limit($brand->about_intro, 180) }}
                        </p>

                        <div class="mt-8 flex items-center justify-between">

                            <span class="text-sm text-stone-500">
                                {{ $brand->perfumes_count }} {{ Str::plural('fragrance', $brand->perfumes_count) }}
                            </span>

                            <span class="font-medium text-[#B08D57] transition group-hover:translate-x-1">
                                Discover →
                            </span>

                        </div>

                    </div>

                </a>

            @endif

            {{-- Featured Note --}}
            @if($note)

                <a href="{{ route('note.detail', $note->slug) }}"
                    class="group flex flex-col overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm transition duration-500 hover:-translate-y-2 hover:shadow-2xl dark:border-stone-700 dark:bg-[#232323]">

                    <div class="overflow-hidden">

                        <img loading="lazy" decoding="async"
                            src="{{ $note->image ? asset($note->image) : route('placeholder.note', $note->slug) }}"
                            onerror="this.onerror=null;this.src='{{ route('placeholder.note', $note->slug) }}';"
                            alt="{{ $note->name }}"
                            class="h-64 w-full object-cover transition duration-700 group-hover:scale-105">

                    </div>

                    <div class="flex flex-1 flex-col p-8">

                        <p class="text-xs uppercase tracking-[0.35em] text-[#B08D57]">
                            Featured Note
                        </p>

                        <h3 class="mt-4 font-serif text-3xl">
                            {{ $note->name }}
                        </h3>

                        <div class="mt-3 flex flex-wrap gap-2">

                            @if($note->fragrance_family)
                                <span class="rounded-full bg-stone-100 px-3 py-1 text-xs dark:bg-stone-800">
                                    {{ $note->fragrance_family }}
                                </span>
                            @endif

                            @if($note->common_role)
                                <span class="rounded-full bg-stone-100 px-3 py-1 text-xs dark:bg-stone-800">
                                    {{ $note->common_role }}
                                </span>
                            @endif

                            @if($note->ingredient_type)
                                <span class="rounded-full bg-stone-100 px-3 py-1 text-xs dark:bg-stone-800">
                                    {{ $note->ingredient_type }}
                                </span>
                            @endif

                        </div>

                        <p class="mt-4 flex-1 leading-8 text-stone-600 dark:text-stone-400">
                            {{ Str::limit($note->description, 180) }}
                        </p>

                        <div class="mt-8 flex items-center justify-between">

                            <span class="text-sm text-stone-500">
                                Used in {{ $note->perfumes_count }} {{ Str::plural('perfume', $note->perfumes_count) }}
                            </span>

                            <span class="font-medium text-[#B08D57] transition group-hover:translate-x-1">
                                Discover →
                            </span>

                        </div>

                    </div>

                </a>

            @endif

        </div>

    </div>

</section>

@endif
