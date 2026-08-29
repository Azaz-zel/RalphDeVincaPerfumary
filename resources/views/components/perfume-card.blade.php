<div
    class="group overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl dark:border-stone-700 dark:bg-[#232323]">

    <div class="overflow-hidden">

        <img loading="lazy" decoding="async"
            src="{{ asset($image) }}"
            alt="{{ $name }}"
            onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';"
            class="h-80 w-full object-cover transition duration-700 group-hover:scale-105">

    </div>

    <div class="p-6">

        <p class="text-sm uppercase tracking-[0.25em] text-stone-500">
            {{ $brand }}
        </p>

        <h3 class="mt-2 font-serif text-2xl">
            {{ $name }}
        </h3>

        <span
            class="mt-5 inline-block rounded-full bg-stone-100 px-3 py-1 text-xs dark:bg-stone-700">

            {{ $family }}

        </span>

        <a
            href="#"
            class="mt-8 inline-flex items-center gap-2 font-medium text-[#B08D57]">

            Read More →

        </a>

    </div>

</div>