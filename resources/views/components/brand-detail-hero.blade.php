@props(['brand'])

<section class="py-10">

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

            <span class="text-[#B08D57]">

                {{ $brand->name }}

            </span>

        </nav>

        <div class="grid items-center gap-16 lg:grid-cols-2">

            {{-- Image --}}
            <div>

                <img
                    src="{{ $brand->hero_image ? asset($brand->hero_image) : route('placeholder.brand', $brand->slug) }}"
                    alt="{{ $brand->name }}"
                    onerror="this.onerror=null;this.src='{{ route('placeholder.brand', $brand->slug) }}';"
                    class="w-full rounded-3xl object-cover shadow-xl">

            </div>

            {{-- Content --}}
            <div>

                <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">

                    {{ $brand->type === 'niche' ? 'Niche Perfume House' : 'Luxury Fashion House' }}

                </p>

                <h1 class="mt-4 font-serif text-5xl">

                    {{ $brand->name }}

                </h1>

                @if($brand->founded_location || $brand->founded_year)

                    <p class="mt-4 text-lg text-stone-500">

                        @if($brand->founded_location)
                            Founded in {{ $brand->founded_location }}
                        @else
                            Founded
                        @endif

                        @if($brand->founded_year)
                            &bull; {{ $brand->founded_year }}
                        @endif

                    </p>

                @endif

                @if($brand->tagline)

                    <p class="mt-8 leading-8 text-stone-600 dark:text-stone-400">

                        {{ $brand->tagline }}

                    </p>

                @endif

                <div class="mt-10 flex flex-wrap gap-3">

                    <span class="rounded-full bg-stone-100 px-4 py-2 dark:bg-stone-800">
                        {{ ucfirst($brand->type) }}
                    </span>

                    <span class="rounded-full bg-stone-100 px-4 py-2 dark:bg-stone-800">
                        Fragrance
                    </span>

                    @if($brand->founded_location)
                        <span class="rounded-full bg-stone-100 px-4 py-2 dark:bg-stone-800">
                            {{ $brand->founded_location }}
                        </span>
                    @endif

                </div>

            </div>

        </div>

    </div>

</section>
