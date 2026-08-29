@props(['notes'])

@if($notes->isNotEmpty())

    <p class="mb-8 text-stone-500">
        {{ $notes->total() }} {{ Str::plural('note', $notes->total()) }}
    </p>

    <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">

        @foreach($notes as $note)

            <a href="{{ route('note.detail', $note->slug) }}"
                class="group rounded-3xl border border-stone-200 bg-white p-5 text-center shadow-sm transition-all duration-500 hover:-translate-y-1.5 hover:shadow-xl dark:border-stone-700 dark:bg-[#232323]">

                <div class="mx-auto h-20 w-20 overflow-hidden rounded-full bg-stone-100 dark:bg-stone-800">

                    <img loading="lazy" decoding="async"
                        src="{{ $note->image ? asset($note->image) : route('placeholder.note', $note->slug) }}"
                        alt="{{ $note->name }}"
                        onerror="this.onerror=null;this.src='{{ route('placeholder.note', $note->slug) }}';"
                        class="h-full w-full object-cover transition duration-500 group-hover:scale-110">

                </div>

                <h2 class="mt-4 font-medium leading-snug transition group-hover:text-[#B08D57]">
                    {{ $note->name }}
                </h2>

                @if($note->fragrance_family)
                    <p class="mt-1.5 text-xs text-stone-500">
                        {{ $note->fragrance_family }}
                    </p>
                @endif

                <p class="mt-3 text-xs text-stone-400">
                    {{ $note->perfumes_count }} {{ Str::plural('perfume', $note->perfumes_count) }}
                </p>

            </a>

        @endforeach

    </div>

@else

    <div class="rounded-3xl border border-stone-200 bg-white p-16 text-center dark:border-stone-700 dark:bg-[#232323]">

        <p class="font-serif text-2xl">No notes found</p>

        <p class="mt-3 text-stone-500 dark:text-stone-400">
            Try a different search term or clear your filters.
        </p>

        <a href="{{ route('notes.index') }}"
            class="mt-8 inline-block rounded-full bg-[#B08D57] px-7 py-3 text-white transition hover:opacity-90">
            View All Notes
        </a>

    </div>

@endif
