@props(['note'])

<section class="py-9">

    <div class="mx-auto max-w-6xl px-8">

        <div class="grid items-center gap-12 lg:grid-cols-2">

            {{-- LEFT --}}
            <div>

                {{-- Breadcrumb --}}
                <nav class="mb-8 flex items-center gap-2 text-sm text-stone-500">

                    <a
                        href="{{ route('explore') }}"
                        class="transition hover:text-[#B08D57]">
                        Explore
                    </a>

                    <span>/</span>

                    <span class="text-[#B08D57]">
                        {{ $note->name }}
                    </span>

                </nav>


                {{-- Label --}}
                <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">
                    Fragrance Note
                </p>


                {{-- Name --}}
                <h1 class="mt-4 font-serif text-6xl">
                    {{ $note->name }}
                </h1>


                {{-- Description --}}
                <p class="mt-6 text-lg leading-8 text-stone-600 dark:text-stone-400">
                    {{ $note->description }}
                </p>


                {{-- Aroma --}}
                <div class="mt-8 flex flex-wrap gap-3">

                    @if (!empty($note->aroma))

                        <div class="mt-8 flex flex-wrap gap-3">

                            @foreach (explode(',', $note->aroma) as $aroma)

                                <span class="rounded-full bg-stone-100 px-4 py-2 dark:bg-stone-800">
                                    {{ trim($aroma) }}
                                </span>

                            @endforeach

                        </div>

                    @endif

                </div>

            </div>


            {{-- IMAGE --}}
            <div>

                <img
                    src="{{ $note->photo_url }}"
                    alt="{{ $note->name }}"
                    onerror="this.onerror=null;this.src='{{ route('placeholder.note', $note->slug) }}';"
                    class="mx-auto w-80">

            </div>

        </div>

    </div>

</section>