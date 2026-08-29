<section class="py-28 bg-stone-50 dark:bg-[#1B1A17]">

    <div class="mx-auto max-w-screen-2xl px-8 lg:px-12">

        <div class="grid items-center gap-20 lg:grid-cols-2">

            {{-- Left --}}
            <div>

                <p class="uppercase tracking-[0.35em] text-[#B08D57]">

                    Concentration Scale

                </p>

                <h2 class="mt-5 font-serif text-5xl leading-tight">

                    From Fresh Splash
                    <br>
                    to Pure Perfume

                </h2>

                <p class="mt-8 mb-12 leading-8 text-stone-600 dark:text-stone-400">

                    Perfume concentration exists on a spectrum. As the amount of
                    fragrance oil increases, scents generally become richer,
                    longer-lasting, and more intense on the skin.

                </p>

                <div class="mt-14 space-y-8">

                    @foreach([
                        ['Eau Fraîche','1–3%','Very Light'],
                        ['Eau de Cologne','2–5%','Fresh'],
                        ['Eau de Toilette','5–15%','Balanced'],
                        ['Eau de Parfum','15–20%','Rich'],
                        ['Parfum','20–40%','Luxury']
                    ] as [$name,$oil,$style])

                    <div class="mb-10 flex items-start gap-8">

                    {{-- Number --}}
                    <div class="mt-1 flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#B08D57] text-sm font-semibold text-white">

                        {{ $loop->iteration }}

                    </div>

                    {{-- Content --}}
                    <div class="flex-1">

                        <div class="flex items-end justify-between">

                            <h3 class="font-serif text-[2rem] text-2xl leading-none whitespace-nowrap">

                                {{ $name }}

                            </h3>

                            <span class="text-sm font-medium text-[#B08D57]">

                                {{ $oil }}

                            </span>

                        </div>

                        <p class="mt-3 text-xs uppercase tracking-[0.35em] text-stone-500">

                            {{ $style }}

                        </p>

                        <div class="mt-4 h-2 overflow-hidden rounded-full bg-stone-200 dark:bg-stone-700">

                            <div
                                class="h-full rounded-full bg-[#B08D57]"
                                style="width: {{ [15,30,55,75,100][$loop->index] }}%">
                            </div>

                        </div>

                    </div>

                </div>

                    @endforeach

                </div>

            </div>

            {{-- Right --}}
            <div class="overflow-hidden rounded-[2rem]">

                <img loading="lazy" decoding="async"
                    src="{{ asset('images/academy/concentration-scale.jpg') }}"
                    alt="Perfume Concentration Scale"
                    class="w-full rounded-[2rem] shadow-2xl">

            </div>

        </div>

    </div>

</section>