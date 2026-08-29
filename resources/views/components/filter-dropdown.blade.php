@props([
    'id',
    'title',
    'items' => [],
    'multiple' => false
])

<div class="relative">

    <button
        id="{{ $id }}-toggle"
        type="button"
        class="dropdown-toggle flex items-center gap-2 rounded-full border border-stone-300 bg-white px-5 py-2 text-sm transition hover:border-[#B08D57] dark:border-stone-700 dark:bg-[#232323]">

        <span id="{{ $id }}-label">

            {{ $title }}

        </span>

        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4 transition-transform duration-200"
            id="{{ $id }}-arrow"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">

            <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"/>

        </svg>

    </button>

    <div
        id="{{ $id }}-dropdown"
        class="dropdown absolute left-0 top-14 z-40 hidden w-72 rounded-2xl border border-stone-200 bg-white p-5 shadow-xl dark:border-stone-700 dark:bg-[#232323]">

        <h3 class="mb-4 text-lg font-semibold">

            {{ $title }}

        </h3>

        <div class="space-y-3">

            @foreach($items as $item)

                <label class="flex cursor-pointer items-center gap-3">

                    @if($multiple)

                        <input
                        type="checkbox"
                        value="{{ $item }}"
                        class="{{ $id }}-input rounded accent-[#B08D57]"
                        @checked(in_array($item, (array) request()->input($id, [])))>

                    @else

                        <input
                            type="radio"
                            name="{{ $id }}"
                            value="{{ $item }}"
                            class="{{ $id }}-input accent-[#B08D57]">

                    @endif

                    <span>

                        {{ $item }}

                    </span>

                </label>

            @endforeach

        </div>

        <div class="mt-6 flex justify-end gap-3">

            <button
                id="{{ $id }}-reset"
                type="button"
                class="text-sm text-stone-500 hover:text-red-500">

                Reset

            </button>

            <button
                id="{{ $id }}-apply"
                type="button"
                class="rounded-full bg-[#B08D57] px-5 py-2 text-sm text-white">

                Apply

            </button>

        </div>

    </div>

</div>