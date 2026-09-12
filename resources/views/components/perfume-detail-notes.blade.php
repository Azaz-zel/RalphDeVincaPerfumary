@props(['perfume'])

<section class="py-24">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        {{-- Heading --}}
        <div class="text-center">

            <p class="text-sm uppercase tracking-[0.3em] text-[#B08D57]">
                Fragrance Notes
            </p>

            <h2 class="mt-4 font-serif text-4xl">
                Explore the Notes
            </h2>

            <p class="mx-auto mt-5 max-w-2xl leading-8 text-stone-600 dark:text-stone-400">
                Discover the ingredients that shape the character
                and personality of this fragrance.
            </p>

        </div>


        {{-- Notes Container --}}
        <div class="mt-16 grid gap-8 lg:grid-cols-3">


            {{-- ================================================= --}}
            {{-- TOP NOTES --}}
            {{-- ================================================= --}}

            <div
                class="rounded-3xl border border-stone-200 bg-white p-8 shadow-sm
                       dark:border-stone-700 dark:bg-[#232323]">

                <div class="mb-8 text-center">

                    <p class="text-xs uppercase tracking-[0.3em] text-[#B08D57]">
                        Opening
                    </p>

                    <h3 class="mt-2 font-serif text-2xl">
                        Top Notes
                    </h3>

                    <p class="mt-2 text-sm text-stone-500">
                        The first impression
                    </p>

                </div>


                <div class="flex flex-wrap justify-center gap-6">

                    @forelse($perfume->topNotes as $note)

                        <a
                            href="{{ route('note.detail', $note->slug) }}"
                            class="group w-24 text-center">

                            <div
                                class="mx-auto h-24 w-24 overflow-hidden rounded-full
                                       bg-stone-100 shadow-md
                                       dark:bg-stone-800">

                                @if($note->image)

                                    <img loading="lazy" decoding="async"
                                        src="{{ $note->photo_url }}"
                                        alt="{{ $note->name }}"
                                        onerror="this.onerror=null;this.src='{{ route('placeholder.note', $note->slug) }}';"
                                        class="h-full w-full object-cover
                                               transition duration-500
                                               group-hover:scale-110">

                                @endif

                            </div>

                            <p
                                class="mt-3 text-sm font-medium transition
                                       group-hover:text-[#B08D57]">

                                {{ $note->name }}

                            </p>

                        </a>

                    @empty

                        <p class="text-sm text-stone-500">
                            No top notes available.
                        </p>

                    @endforelse

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- MIDDLE NOTES --}}
            {{-- ================================================= --}}

            <div
                class="rounded-3xl border border-stone-200 bg-white p-8 shadow-sm
                       dark:border-stone-700 dark:bg-[#232323]">

                <div class="mb-8 text-center">

                    <p class="text-xs uppercase tracking-[0.3em] text-[#B08D57]">
                        Heart
                    </p>

                    <h3 class="mt-2 font-serif text-2xl">
                        Middle Notes
                    </h3>

                    <p class="mt-2 text-sm text-stone-500">
                        The character of the fragrance
                    </p>

                </div>


                <div class="flex flex-wrap justify-center gap-6">

                    @forelse($perfume->middleNotes as $note)

                        <a
                            href="{{ route('note.detail', $note->slug) }}"
                            class="group w-24 text-center">

                            <div
                                class="mx-auto h-24 w-24 overflow-hidden rounded-full
                                       bg-stone-100 shadow-md
                                       dark:bg-stone-800">

                                @if($note->image)

                                    <img loading="lazy" decoding="async"
                                        src="{{ $note->photo_url }}"
                                        alt="{{ $note->name }}"
                                        onerror="this.onerror=null;this.src='{{ route('placeholder.note', $note->slug) }}';"
                                        class="h-full w-full object-cover
                                               transition duration-500
                                               group-hover:scale-110">

                                @endif

                            </div>

                            <p
                                class="mt-3 text-sm font-medium transition
                                       group-hover:text-[#B08D57]">

                                {{ $note->name }}

                            </p>

                        </a>

                    @empty

                        <p class="text-sm text-stone-500">
                            No middle notes available.
                        </p>

                    @endforelse

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- BASE NOTES --}}
            {{-- ================================================= --}}

            <div
                class="rounded-3xl border border-stone-200 bg-white p-8 shadow-sm
                       dark:border-stone-700 dark:bg-[#232323]">

                <div class="mb-8 text-center">

                    <p class="text-xs uppercase tracking-[0.3em] text-[#B08D57]">
                        Dry Down
                    </p>

                    <h3 class="mt-2 font-serif text-2xl">
                        Base Notes
                    </h3>

                    <p class="mt-2 text-sm text-stone-500">
                        The lasting impression
                    </p>

                </div>


                <div class="flex flex-wrap justify-center gap-6">

                    @forelse($perfume->baseNotes as $note)

                        <a
                            href="{{ route('note.detail', $note->slug) }}"
                            class="group w-24 text-center">

                            <div
                                class="mx-auto h-24 w-24 overflow-hidden rounded-full
                                       bg-stone-100 shadow-md
                                       dark:bg-stone-800">

                                @if($note->image)

                                    <img loading="lazy" decoding="async"
                                        src="{{ $note->photo_url }}"
                                        alt="{{ $note->name }}"
                                        onerror="this.onerror=null;this.src='{{ route('placeholder.note', $note->slug) }}';"
                                        class="h-full w-full object-cover
                                               transition duration-500
                                               group-hover:scale-110">

                                @endif

                            </div>

                            <p
                                class="mt-3 text-sm font-medium transition
                                       group-hover:text-[#B08D57]">

                                {{ $note->name }}

                            </p>

                        </a>

                    @empty

                        <p class="text-sm text-stone-500">
                            No base notes available.
                        </p>

                    @endforelse

                </div>

            </div>


        </div>

    </div>

</section>