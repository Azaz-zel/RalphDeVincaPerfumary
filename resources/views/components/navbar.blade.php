@php
    $navLinks = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'Explore', 'route' => 'explore'],
        ['label' => 'Find Your Scent', 'route' => 'quiz'],
        ['label' => 'Brands', 'route' => 'brands.index'],
        ['label' => 'Notes', 'route' => 'notes.index'],
        ['label' => 'Academy', 'route' => 'academy'],
        ['label' => 'About', 'route' => 'about'],
    ];
@endphp

<nav class="sticky top-0 z-50 border-b border-stone-200 bg-stone-50/90 backdrop-blur transition-colors duration-300 dark:border-stone-700 dark:bg-[#232323]/90">

    <div class="flex h-20 items-center justify-between px-6 sm:px-10 xl:px-16 2xl:px-24">

        <!-- Logo -->
        <a href="{{ route('home') }}" class="leading-none">

            <span class="block font-serif text-2xl font-semibold tracking-tight text-stone-900 transition hover:text-[#B08D57] dark:text-stone-100 sm:text-[2.1rem]">
                Ralph de Vinca
            </span>

            <span class="mt-1 block text-[9px] uppercase tracking-[0.4em] text-stone-500 dark:text-stone-400 sm:text-[11px] sm:tracking-[0.5em]">
                Perfumary
            </span>

        </a>

        <!-- Desktop menu -->
        <div class="hidden items-center gap-8 text-sm font-medium text-stone-700 transition-colors duration-200 dark:text-stone-200 lg:flex xl:gap-10">

            @foreach($navLinks as $link)

                <a href="{{ route($link['route']) }}"
                    class="transition hover:text-[#B08D57] {{ request()->routeIs($link['route']) ? 'text-[#B08D57]' : '' }}">
                    {{ $link['label'] }}
                </a>

            @endforeach

        </div>

        <!-- Mobile menu button -->
        <button
            type="button"
            id="mobile-menu-button"
            aria-label="Open menu"
            aria-expanded="false"
            aria-controls="mobile-menu"
            class="flex h-11 w-11 items-center justify-center rounded-xl border border-stone-300 text-stone-700 transition hover:border-[#B08D57] hover:text-[#B08D57] lg:hidden">

            <svg id="mobile-menu-icon-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
            </svg>

            <svg id="mobile-menu-icon-close" xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M18 6L6 18"/>
            </svg>

        </button>

    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden border-t border-stone-200 bg-stone-50 dark:border-stone-700 dark:bg-[#232323] lg:hidden">

        <div class="flex flex-col px-6 py-4 sm:px-10">

            @foreach($navLinks as $link)

                <a href="{{ route($link['route']) }}"
                    class="border-b border-stone-200 py-4 text-sm font-medium transition last:border-0 hover:text-[#B08D57] dark:border-stone-700 {{ request()->routeIs($link['route']) ? 'text-[#B08D57]' : 'text-stone-700 dark:text-stone-200' }}">
                    {{ $link['label'] }}
                </a>

            @endforeach

        </div>

    </div>

</nav>
