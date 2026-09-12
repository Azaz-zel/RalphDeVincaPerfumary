@props(['perfume'])

<section class="py-9">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        {{-- Breadcrumb --}}
        <nav class="mb-10 text-sm text-stone-500">

            <a
                href="{{ route('home') }}"
                class="transition hover:text-[#B08D57]">

                Home

            </a>

            <span class="mx-2">/</span>

            <a
                href="{{ route('explore') }}"
                class="transition hover:text-[#B08D57]">

                Explore

            </a>

            <span class="mx-2">/</span>

            <a
                href="{{ route('brand.detail', $perfume->brand->slug) }}"
                class="inline-block text-sm uppercase tracking-[0.35em] text-[#B08D57] transition hover:opacity-70">

                {{ $perfume->brand->name }}

            </a>

            <span class="mx-2">/</span>

            <span class="text-[#B08D57]">

                {{ $perfume->name }}

            </span>

        </nav>

        <div class="grid items-center gap-16 lg:grid-cols-2">

            {{-- Image --}}
            <div>

                <img
                    src="{{ $perfume->photo_url }}"
                    alt="{{ $perfume->name }}"
                    onerror="this.onerror=null;this.src='{{ route('placeholder.perfume', $perfume->slug) }}';"
                    class="w-full rounded-3xl object-cover shadow-xl">

                {{-- No free photo of this bottle exists, so the house's own
                     picture stands in; say so rather than let it pass as the product. --}}
                @if($perfume->image_is_brand_fallback)

                    <p class="mt-3 text-xs leading-5 text-stone-500">
                        Pictured: {{ $perfume->brand->name }}. No photograph of this bottle
                        is available under a free licence.
                    </p>

                @endif

                {{-- Wikimedia Commons photos are CC-licensed, so the author is credited. --}}
                @if($perfume->image_credit || $perfume->image_license)

                    <p class="mt-3 text-xs leading-5 text-stone-500">

                        Photo:

                        @if($perfume->image_source)
                            <a href="{{ $perfume->image_source }}"
                                target="_blank"
                                rel="noopener noreferrer nofollow"
                                class="underline underline-offset-2 transition hover:text-[#B08D57]">{{ $perfume->image_credit ?: 'Wikimedia Commons' }}</a>
                        @else
                            {{ $perfume->image_credit }}
                        @endif

                        @if($perfume->image_license)
                            &bull; {{ $perfume->image_license }}
                        @endif

                    </p>

                @endif

            </div>

            {{-- Information --}}
            <div>

                {{-- Brand --}}
                <p
                    class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">

                    {{ $perfume->brand->name }}

                </p>

                {{-- Name --}}
                <h1
                    class="mt-3 font-serif text-5xl">

                    {{ $perfume->name }}

                </h1>

                {{-- Concentration --}}
                @if($perfume->concentration)

                    <p
                        class="mt-3 text-lg text-stone-500">

                        {{ $perfume->concentration }}

                    </p>

                @endif

                {{-- Rating --}}
                <div
                    class="mt-6 flex items-center gap-3">

                    <x-star-rating :value="$perfume->rating ?? 0" />

                    <span class="font-medium">

                        {{ $perfume->rating ?? '—' }}/5

                    </span>

                </div>

                {{-- Badges --}}
                <div
                    class="mt-8 flex flex-wrap gap-3">

                    {{-- Fragrance Family --}}
                    @if($perfume->fragranceFamily)

                        <span
                            class="rounded-full bg-stone-100 px-4 py-2 text-sm dark:bg-stone-800">

                            {{ $perfume->fragranceFamily->name }}

                        </span>

                    @endif

                    {{-- Gender --}}
                    @if($perfume->gender)

                        <span
                            class="rounded-full bg-stone-100 px-4 py-2 text-sm dark:bg-stone-800">

                            {{ $perfume->gender }}

                        </span>

                    @endif

                    {{-- Seasons --}}
                    @foreach($perfume->seasons as $season)

                        <span
                            class="rounded-full bg-stone-100 px-4 py-2 text-sm dark:bg-stone-800">

                            {{ $season->name }}

                        </span>

                    @endforeach

                    {{-- Brand Type --}}
                    @if($perfume->brand->type)

                        <span
                            class="rounded-full bg-stone-100 px-4 py-2 text-sm dark:bg-stone-800">

                            {{ ucfirst($perfume->brand->type) }}

                        </span>

                    @endif

                </div>

                {{-- Description --}}
                @if($perfume->description)

                    <p
                        class="mt-8 leading-8 text-stone-600 dark:text-stone-400">

                        {{ $perfume->description }}

                    </p>

                @endif

                {{-- CTA --}}
                <div
                    class="mt-10 flex flex-wrap gap-4">

                    <a
                        href="{{ route('brand.detail', $perfume->brand->slug) }}"
                        class="rounded-full bg-[#B08D57] px-7 py-3 text-white transition hover:opacity-90">

                        View Brand

                    </a>

                    <a
                        href="{{ route('explore') }}"
                        class="rounded-full border border-stone-300 px-7 py-3 transition hover:border-[#B08D57] hover:text-[#B08D57]">

                        Back to Explore

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>