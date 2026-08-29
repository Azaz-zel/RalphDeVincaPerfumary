@props(['brand'])

<section class="bg-stone-50 py-24 dark:bg-[#1C1C1C]">

    <div class="mx-auto max-w-5xl px-8 lg:px-16">

        {{-- Heading --}}
        <div class="text-center">

            <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">
                Brand History
            </p>

            <h2 class="mt-4 font-serif text-4xl">
                A Legacy of Innovation & Elegance
            </h2>

            <p class="mx-auto mt-5 max-w-2xl leading-8 text-stone-600 dark:text-stone-400">
                From founding vision to signature fragrances, discover the milestones
                that shaped {{ $brand->name }} into what it is today.
            </p>

        </div>

        @if($brand->milestones->isNotEmpty())

            {{-- Timeline --}}
            <div class="relative mt-20">

                {{-- Vertical Line --}}
                <div class="absolute left-4 top-0 h-full w-px bg-stone-300 dark:bg-stone-700"></div>

                @foreach($brand->milestones as $milestone)

                    <div class="relative mb-12 pl-14 last:mb-0">

                        {{-- Circle --}}
                        <div class="absolute left-0 top-2 h-8 w-8 rounded-full border-4 border-white bg-[#B08D57] dark:border-[#1C1C1C]"></div>

                        <p class="text-sm uppercase tracking-[0.3em] text-[#B08D57]">
                            {{ $milestone->year }}
                        </p>

                        <h3 class="mt-2 font-serif text-2xl">
                            {{ $milestone->title }}
                        </h3>

                        <p class="mt-4 leading-8 text-stone-600 dark:text-stone-400">
                            {{ $milestone->description }}
                        </p>

                    </div>

                @endforeach

            </div>

        @else

            <div class="mt-16 rounded-3xl border border-stone-200 bg-white p-12 text-center dark:border-stone-700 dark:bg-[#232323]">

                <p class="text-stone-500 dark:text-stone-400">
                    A detailed history for {{ $brand->name }} has not been added yet.
                </p>

            </div>

        @endif

    </div>

</section>
