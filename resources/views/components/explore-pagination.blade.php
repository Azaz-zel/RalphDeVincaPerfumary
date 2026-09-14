@props(['perfumes'])

{{--
    Explore used to carry its own copy of the pagination markup, which meant
    the mobile overflow had to be fixed twice. It now just wraps the shared
    component in the section spacing the page expects.
--}}
@if ($perfumes->hasPages())

<section class="pb-20">

    <div class="mx-auto max-w-7xl px-8 lg:px-16">

        <x-pagination :paginator="$perfumes" route="explore" />

    </div>

</section>

@endif
