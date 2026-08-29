<section class="pb-20">

    <div class="mx-auto max-w-screen-2xl px-8 lg:px-12">

        <div class="mb-10 flex items-center justify-between">

            <div class="h-9 w-64 rounded-full bg-stone-200 dark:bg-stone-700 animate-pulse"></div>

            <div class="h-5 w-28 rounded-full bg-stone-200 dark:bg-stone-700 animate-pulse"></div>

        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">

            @for ($i = 0; $i < 8; $i++)

                <x-loading-card />

            @endfor

        </div>

    </div>

</section>