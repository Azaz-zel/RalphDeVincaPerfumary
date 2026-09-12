@props(['brands'])

@if($brands->isNotEmpty())

    <p class="mb-8 text-stone-500">
        {{ $brands->count() }} {{ Str::plural('house', $brands->count()) }}
    </p>

    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

        @foreach($brands as $brand)

            <a href="{{ route('brand.detail', $brand->slug) }}"
                class="group overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl dark:border-stone-700 dark:bg-[#232323]">

                <div class="overflow-hidden">

                    <img loading="lazy" decoding="async"
                        src="{{ $brand->photo_url }}"
                        alt="{{ $brand->name }}"
                        onerror="this.onerror=null;this.src='{{ route('placeholder.brand', $brand->slug) }}';"
                        class="aspect-[4/5] w-full object-cover transition duration-700 group-hover:scale-105">

                </div>

                <div class="p-6">

                    <p class="text-xs uppercase tracking-[0.25em] text-stone-500">
                        {{ ucfirst($brand->type) }}
                    </p>

                    <h2 class="mt-2 font-serif text-2xl">
                        {{ $brand->name }}
                    </h2>

                    @if($brand->founded_year || $brand->founded_location)
                        <p class="mt-2 text-sm text-stone-500">
                            @if($brand->founded_location){{ $brand->founded_location }}@endif
                            @if($brand->founded_year && $brand->founded_location) &bull; @endif
                            @if($brand->founded_year){{ $brand->founded_year }}@endif
                        </p>
                    @endif

                    <div class="mt-5 flex items-center justify-between text-sm">

                        <span class="text-stone-500">
                            {{ $brand->perfumes_count }} {{ Str::plural('fragrance', $brand->perfumes_count) }}
                        </span>

                        <span class="font-medium text-[#B08D57] transition group-hover:translate-x-1">
                            View →
                        </span>

                    </div>

                </div>

            </a>

        @endforeach

    </div>

@else

    <div class="rounded-3xl border border-stone-200 bg-white p-16 text-center dark:border-stone-700 dark:bg-[#232323]">

        <p class="font-serif text-2xl">No houses found</p>

        <p class="mt-3 text-stone-500 dark:text-stone-400">
            Try a different search term or clear your filters.
        </p>

        <a href="{{ route('brands.index') }}"
            class="mt-8 inline-block rounded-full bg-[#B08D57] px-7 py-3 text-white transition hover:opacity-90">
            View All Houses
        </a>

    </div>

@endif
