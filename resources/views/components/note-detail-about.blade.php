@props(['note'])

<section class="py-24">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        <div class="grid items-center gap-16 lg:grid-cols-2">

            {{-- Image --}}
            <div>

                <img loading="lazy" decoding="async"
                    src="{{ asset($note->about_image ?? $note->image) }}"
                    alt="{{ $note->name }}"
                    onerror="this.onerror=null;this.src='{{ route('placeholder.note', $note->slug) }}';"
                    class="w-full rounded-3xl object-cover shadow-lg">

            </div>

            {{-- Content --}}
            <div>

                <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">

                    About {{ $note->name }}

                </p>

                <h2 class="mt-4 font-serif text-4xl">

                    Discover the Character of {{ $note->name }}

                </h2>

                <div class="mt-8 space-y-6 leading-8 text-stone-600 dark:text-stone-400">

                    <p>
                        {{ $note->description }}
                    </p>

                    <p>
                        In perfumery, {{ strtolower($note->name) }} contributes
                        its distinctive character to a fragrance, helping shape
                        the way the composition develops from its opening through
                        the later stages.
                    </p>

                    <p>
                        Its aromatic character can be described through
                        {{ strtolower($note->aroma) }},
                        making it a versatile ingredient across different
                        fragrance compositions.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>