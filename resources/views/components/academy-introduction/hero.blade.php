<section class="py-24">

    <div class="mx-auto flex max-w-screen-2xl flex-col items-center gap-20 px-8 lg:flex-row lg:px-12">

        {{-- Left --}}
        <div class="w-full lg:w-1/2">

            {{-- Breadcrumb --}}
            <nav class="mb-8 text-sm text-stone-500">

                <a
                    href="{{ route('academy') }}"
                    class="transition hover:text-[#B08D57]">

                    Academy

                </a>

                <span class="mx-2">/</span>

                <span class="text-[#B08D57]">

                    Introduction to Perfume

                </span>

            </nav>

            {{-- Level --}}
            <p
                class="uppercase tracking-[0.45em] text-[#B08D57]">

                Level 1

            </p>

            {{-- Heading --}}
            <h1
                class="mt-6 font-serif text-5xl leading-tight lg:text-6xl">

                Introduction
                <br>

                <span class="text-[#B08D57]">

                    to Perfume

                </span>

            </h1>

            {{-- Description --}}
            <p
                class="mt-8 max-w-2xl text-lg leading-8 text-stone-600 dark:text-stone-400">

                Discover the fascinating world of fragrance and learn the essential
                foundations behind every perfume. Explore its history, understand
                how scents are structured, and uncover the artistry that transforms
                simple ingredients into unforgettable creations.

            </p>

            {{-- CTA --}}
            <div class="mt-10 flex flex-wrap gap-4">

                <a
                    href="#about"
                    class="rounded-full bg-[#B08D57] px-8 py-3 text-white transition hover:opacity-90">

                    Start Learning

                </a>

                <a
                    href="{{ route('academy') }}"
                    class="rounded-full border border-stone-300 px-8 py-3 transition hover:border-[#B08D57] hover:text-[#B08D57]">

                    Back to Academy

                </a>

            </div>

        </div>

        {{-- Right --}}
        <div class="flex w-full justify-center lg:w-1/2">

            <img
                src="{{ asset('images/academy/introduction-hero.jpg') }}"
                alt="Introduction to Perfume"
                class="w-full max-w-lg rounded-4xl object-cover shadow-2xl">

        </div>

    </div>

</section>