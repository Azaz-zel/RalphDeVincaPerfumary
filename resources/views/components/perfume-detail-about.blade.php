@props(['perfume'])

<div class="mx-auto max-w-5xl rounded-3xl border border-stone-200 bg-white p-8 shadow-sm dark:border-stone-700 dark:bg-[#232323]">

    {{-- Header --}}
    <div class="text-center">

        <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">
            Ralph de Vinca Rating
        </p>

        <h2 class="mt-3 font-serif text-6xl">

            {{ number_format((float) $perfume->rating, 1) }}

            <span class="text-3xl text-stone-400">
                /5
            </span>

        </h2>

        <div class="mt-3 text-2xl">
            <x-star-rating :value="$perfume->rating ?? 0" />
        </div>

        <p class="mt-3 font-medium">
            {{ $perfume->rating >= 4.3 ? 'Exceptional' : ($perfume->rating >= 4.0 ? 'Excellent' : 'Good') }}
        </p>

        @if($perfume->rating_count)

            <p class="mt-2 text-sm text-stone-500">
                Based on {{ number_format($perfume->rating_count) }} ratings
            </p>

        @endif

    </div>


    {{-- Breakdown --}}
    <div class="mt-10 space-y-6">

        @foreach($perfume->ratingBreakdown() as [$title, $score])

            <div>

                <div class="mb-2 flex justify-between">

                    <span>
                        {{ $title }}
                    </span>

                    <span class="font-medium">
                        {{ $score }}/5
                    </span>

                </div>

                <div class="h-2 rounded-full bg-stone-200 dark:bg-stone-700">

                    <div
                        class="h-full rounded-full bg-[#B08D57]"
                        style="width: {{ $score * 20 }}%;">
                    </div>

                </div>

            </div>

        @endforeach

    </div>


    {{-- Footer --}}
    <div class="mt-16 grid gap-8 border-t border-stone-200 pt-10 dark:border-stone-700 lg:grid-cols-2">

        {{-- Editor's Verdict --}}
        <div>

            <p class="text-sm uppercase tracking-[0.3em] text-[#B08D57]">
                Editor's Verdict
            </p>

            <blockquote class="mt-5 font-serif text-2xl italic leading-relaxed">

                “{{ $perfume->description ?? 'No description available for this fragrance.' }}”

            </blockquote>

        </div>


        {{-- Overall Impression --}}
        <div>

            <p class="text-sm uppercase tracking-[0.3em] text-[#B08D57]">
                Overall Impression
            </p>

            <div class="mt-6 flex flex-wrap gap-3">

                @foreach($perfume->overallImpressionTags() as $item)

                    <span class="rounded-full bg-stone-100 px-4 py-2 dark:bg-stone-800">
                        {{ $item }}
                    </span>

                @endforeach

            </div>

        </div>

    </div>

</div>