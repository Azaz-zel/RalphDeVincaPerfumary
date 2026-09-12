@props(['brand'])

<section class="py-24">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        <div class="grid items-center gap-16 lg:grid-cols-2">

            {{-- Image --}}
            <div>

                <img loading="lazy" decoding="async"
                    src="{{ $brand->about_image && file_exists(public_path($brand->about_image)) ? asset($brand->about_image) : $brand->photo_url }}"
                    alt="{{ $brand->name }} Boutique"
                    onerror="this.onerror=null;this.src='{{ route('placeholder.brand', $brand->slug) }}';"
                    class="w-full rounded-3xl object-cover shadow-lg">

                {{-- Wikimedia Commons photos are CC-licensed, so the author is credited. --}}
                @if($brand->image_credit || $brand->image_license)

                    <p class="mt-3 text-xs leading-5 text-stone-500">

                        Photo:

                        @if($brand->image_source)
                            <a href="{{ $brand->image_source }}"
                                target="_blank"
                                rel="noopener noreferrer nofollow"
                                class="underline underline-offset-2 transition hover:text-[#B08D57]">{{ $brand->image_credit ?: 'Wikimedia Commons' }}</a>
                        @else
                            {{ $brand->image_credit }}
                        @endif

                        @if($brand->image_license)
                            &bull; {{ $brand->image_license }}
                        @endif

                    </p>

                @endif

            </div>

            {{-- Content --}}
            <div>

                <p class="text-sm uppercase tracking-[0.35em] text-[#B08D57]">

                    About {{ $brand->name }}

                </p>

                <div class="mt-10 grid grid-cols-2 gap-8 border-t border-stone-200 pt-8 dark:border-stone-700">

                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-stone-500">
                            Founded
                        </p>

                        <p class="mt-2 font-serif text-2xl">
                            {{ $brand->founded_year ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-stone-500">
                            Headquarters
                        </p>

                        <p class="mt-2 font-serif text-2xl">
                            {{ $brand->founded_location ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-stone-500">
                            Industry
                        </p>

                        <p class="mt-2 font-serif text-2xl">
                            {{ $brand->type === 'niche' ? 'Niche Perfumery' : 'Luxury & Fragrance' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-stone-500">
                            Founder
                        </p>

                        <p class="mt-2 font-serif text-2xl">
                            {{ $brand->founder ?? '-' }}
                        </p>
                    </div>

                </div>

                <h2 class="mt-10 font-serif text-4xl">

                    {{ $brand->tagline ?? "The Story of {$brand->name}" }}

                </h2>

                <div class="mt-8 space-y-6 leading-8 text-stone-600 dark:text-stone-400">

                    @forelse(explode("\n\n", $brand->about ?? '') as $paragraph)

                        @if(trim($paragraph) !== '')
                            <p>{{ $paragraph }}</p>
                        @endif

                    @empty

                        <p>
                            {{ $brand->name }} is a {{ $brand->type === 'niche' ? 'niche' : 'designer' }}
                            perfume house celebrated for its craftsmanship and distinctive fragrances.
                        </p>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</section>
