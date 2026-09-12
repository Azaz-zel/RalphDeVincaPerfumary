@props(['matches' => []])

<section class="pb-24">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        @if(count($matches))

            <div class="mb-12 border-t border-stone-200 pt-16 text-center dark:border-stone-700">

                <p class="text-sm uppercase tracking-[0.4em] text-[#B08D57]">
                    Also Worth Trying
                </p>

                <h2 class="mt-3 font-serif text-4xl font-semibold lg:text-5xl">
                    Close Runners-Up
                </h2>

                <p class="mx-auto mt-5 max-w-2xl text-base text-stone-600 dark:text-stone-400 lg:text-lg">
                    Scent is personal, so it is worth sampling a few before
                    settling on one.
                </p>

            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">

                @foreach($matches as $match)

                    <div>

                        <x-explore-perfume
                            :image="$match->photo_url"
                            :brand="$match->brand->name"
                            :brand-slug="$match->brand->slug"
                            :name="$match->name"
                            :slug="$match->slug"
                            :family="$match->fragranceFamily?->name ?? 'Fragrance'"
                            :gender="$match->gender"
                            :rating="$match->rating" />

                        <p class="mt-3 text-center text-sm text-stone-500">
                            {{ $match->match_score }}% fit
                        </p>

                    </div>

                @endforeach

            </div>

        @endif

        {{-- Next steps --}}
        <div class="mt-20 rounded-3xl border border-stone-200 bg-white p-10 text-center shadow-sm dark:border-stone-700 dark:bg-[#232323] lg:p-14">

            <h2 class="font-serif text-3xl font-semibold lg:text-4xl">
                Not quite it?
            </h2>

            <p class="mx-auto mt-4 max-w-xl leading-8 text-stone-600 dark:text-stone-400">
                Tastes shift with the seasons. Retake the quiz with different
                answers, or browse the full catalogue yourself.
            </p>

            <div class="mt-10 flex flex-wrap justify-center gap-4">

                <a href="{{ route('quiz') }}"
                    class="rounded-full bg-[#B08D57] px-8 py-4 font-medium text-white transition hover:opacity-90">
                    Retake the Quiz
                </a>

                <a href="{{ route('explore') }}"
                    class="rounded-full border border-stone-300 px-8 py-4 font-medium transition hover:border-[#B08D57] hover:text-[#B08D57] dark:border-stone-600">
                    Explore All Perfumes
                </a>

            </div>

        </div>

    </div>

</section>
