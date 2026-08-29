<div
    class="group flex h-full flex-col overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl dark:border-stone-700 dark:bg-[#232323]">

    <div class="overflow-hidden">

        <img loading="lazy" decoding="async"
            src="{{ asset($image) }}"
            alt="{{ $title }}"
            class="h-60 w-full object-cover transition duration-700 group-hover:scale-105">

    </div>

    <div class="flex flex-1 flex-col p-6">

        <span
            class="w-fit rounded-full bg-[#B08D57]/10 px-3 py-1 text-xs font-medium text-[#B08D57]">

            {{ $category }}

        </span>

        <h3 class="mt-5 font-serif text-2xl leading-snug">

            {{ $title }}

        </h3>

        <p class="mt-4 flex-1 text-stone-600 dark:text-stone-400">

            {{ $description }}

        </p>

        <div class="mt-8 flex items-center justify-between">

            <span class="text-sm text-stone-500">
                {{ $time }}
            </span>

            <a
                href="{{ route('articles') }}"
                class="font-medium text-[#B08D57]">

                Read →

            </a>

        </div>

    </div>

</div>