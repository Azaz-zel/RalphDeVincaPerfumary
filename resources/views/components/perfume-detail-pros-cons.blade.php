@props(['perfume'])

<section class="py-20">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        <div class="text-center">

            <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">
                Community Opinion
            </p>

            <h2 class="mt-3 font-serif text-4xl">
                Pros & Cons
            </h2>

            <p class="mx-auto mt-4 max-w-2xl text-stone-600 dark:text-stone-400">
                A balanced summary of what people love and dislike about this fragrance.
            </p>

        </div>

        <div class="mt-16 grid gap-8 lg:grid-cols-2">

            {{-- Pros --}}
            <div class="rounded-3xl border border-green-200 bg-green-50 p-8 dark:border-green-800 dark:bg-green-950/20">

                <div class="mb-6 flex items-center gap-3">

                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-600 text-xl text-white">
                        ✓
                    </div>

                    <h3 class="font-serif text-3xl">
                        Pros
                    </h3>

                </div>

                <ul class="space-y-4">

                    @foreach($perfume->communityPros() as $pro)

                        <li class="flex items-start gap-3">

                            <span class="mt-1 text-green-600">✔</span>

                            <span>{{ $pro }}</span>

                        </li>

                    @endforeach

                </ul>

            </div>

            {{-- Cons --}}
            <div class="rounded-3xl border border-red-200 bg-red-50 p-8 dark:border-red-800 dark:bg-red-950/20">

                <div class="mb-6 flex items-center gap-3">

                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-600 text-xl text-white">
                        ✕
                    </div>

                    <h3 class="font-serif text-3xl">
                        Cons
                    </h3>

                </div>

                <ul class="space-y-4">

                    @foreach($perfume->communityCons() as $con)

                        <li class="flex items-start gap-3">

                            <span class="mt-1 text-red-600">✖</span>

                            <span>{{ $con }}</span>

                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    </div>

</section>