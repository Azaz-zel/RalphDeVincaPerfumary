@props(['note'])

<section class="py-24">

    <div class="mx-auto max-w-5xl px-8 lg:px-16">

        {{-- Heading --}}
        <div class="text-center">

            <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">
                Aroma Characteristics
            </p>

            <h2 class="mt-3 font-serif text-4xl">
                Scent Profile
            </h2>

            <p class="mx-auto mt-5 max-w-2xl leading-8 text-stone-600 dark:text-stone-400">

                Discover the unique olfactory characteristics that define
                {{ $note->name }} in perfumery.

            </p>

        </div>


        {{-- Profile --}}
        @if ($note->characteristics)

            <div class="mt-10 rounded-3xl border border-stone-200 bg-white p-10 shadow-sm dark:border-stone-700 dark:bg-[#232323]">

                {{-- Floral --}}
                <div class="mb-8">

                    <div class="mb-3 flex justify-between">

                        <span class="font-medium">
                            Floral
                        </span>

                        <span class="text-stone-500">
                            {{ $note->characteristics->floral }}%
                        </span>

                    </div>

                    <div class="h-2 rounded-full bg-stone-200 dark:bg-stone-700">

                        <div
                            class="h-full rounded-full bg-[#B08D57]"
                            style="width: {{ $note->characteristics->floral }}%">
                        </div>

                    </div>

                </div>


                {{-- Woody --}}
                <div class="mb-8">

                    <div class="mb-3 flex justify-between">

                        <span class="font-medium">
                            Woody
                        </span>

                        <span class="text-stone-500">
                            {{ $note->characteristics->woody }}%
                        </span>

                    </div>

                    <div class="h-2 rounded-full bg-stone-200 dark:bg-stone-700">

                        <div
                            class="h-full rounded-full bg-[#B08D57]"
                            style="width: {{ $note->characteristics->woody }}%">
                        </div>

                    </div>

                </div>


                {{-- Warm --}}
                <div class="mb-8">

                    <div class="mb-3 flex justify-between">

                        <span class="font-medium">
                            Warm
                        </span>

                        <span class="text-stone-500">
                            {{ $note->characteristics->warm }}%
                        </span>

                    </div>

                    <div class="h-2 rounded-full bg-stone-200 dark:bg-stone-700">

                        <div
                            class="h-full rounded-full bg-[#B08D57]"
                            style="width: {{ $note->characteristics->warm }}%">
                        </div>

                    </div>

                </div>


                {{-- Sweet --}}
                <div class="mb-8">

                    <div class="mb-3 flex justify-between">

                        <span class="font-medium">
                            Sweet
                        </span>

                        <span class="text-stone-500">
                            {{ $note->characteristics->sweet }}%
                        </span>

                    </div>

                    <div class="h-2 rounded-full bg-stone-200 dark:bg-stone-700">

                        <div
                            class="h-full rounded-full bg-[#B08D57]"
                            style="width: {{ $note->characteristics->sweet }}%">
                        </div>

                    </div>

                </div>


                {{-- Bright --}}
                <div class="mb-8">

                    <div class="mb-3 flex justify-between">

                        <span class="font-medium">
                            Bright
                        </span>

                        <span class="text-stone-500">
                            {{ $note->characteristics->bright }}%
                        </span>

                    </div>

                    <div class="h-2 rounded-full bg-stone-200 dark:bg-stone-700">

                        <div
                            class="h-full rounded-full bg-[#B08D57]"
                            style="width: {{ $note->characteristics->bright }}%">
                        </div>

                    </div>

                </div>


                {{-- Fresh --}}
                <div>

                    <div class="mb-3 flex justify-between">

                        <span class="font-medium">
                            Fresh
                        </span>

                        <span class="text-stone-500">
                            {{ $note->characteristics->fresh }}%
                        </span>

                    </div>

                    <div class="h-2 rounded-full bg-stone-200 dark:bg-stone-700">

                        <div
                            class="h-full rounded-full bg-[#B08D57]"
                            style="width: {{ $note->characteristics->fresh }}%">
                        </div>

                    </div>

                </div>

            </div>

        @else

            <div class="mt-10 rounded-3xl border border-stone-200 bg-white p-10 text-center dark:border-stone-700 dark:bg-[#232323]">

                <p class="text-stone-500">
                    Scent profile information is not available yet.
                </p>

            </div>

        @endif


        {{-- Overall --}}
        @php
            $dominantTraits = $note->characteristics?->dominantTraits() ?? [];
        @endphp

        @if (!empty($dominantTraits))

            <div class="mt-10 text-center">

                <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">
                    Overall Character
                </p>

                <div class="mt-5 flex flex-wrap justify-center gap-3">

                    @foreach ($dominantTraits as $trait)

                        <span class="rounded-full border border-stone-300 px-5 py-2 dark:border-stone-700">
                            {{ $trait }}
                        </span>

                    @endforeach

                </div>

            </div>

        @endif

    </div>

</section>