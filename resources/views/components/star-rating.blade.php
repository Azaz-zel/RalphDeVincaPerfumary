@props(['value', 'max' => 5])

@php
    $value = (float) $value;
@endphp

<span class="inline-flex items-center gap-0.5">

    @for ($i = 1; $i <= $max; $i++)

        @php
            $fill = max(0, min(100, ($value - ($i - 1)) * 100));
        @endphp

        <span class="relative inline-block h-[1em] w-[1em] align-middle" style="line-height:0;">

            <svg viewBox="0 0 20 20" class="h-[1em] w-[1em] text-stone-300 dark:text-stone-600" fill="currentColor">
                <path d="M10 1.5l2.6 5.6 6.1.6-4.6 4.1 1.3 6-5.4-3.1-5.4 3.1 1.3-6-4.6-4.1 6.1-.6z"/>
            </svg>

            @if($fill > 0)

                <span class="absolute inset-0 top-0 left-0 overflow-hidden" style="width: {{ $fill }}%;">
                    <svg viewBox="0 0 20 20" class="h-[1em] w-[1em] text-[#B08D57]" fill="currentColor">
                        <path d="M10 1.5l2.6 5.6 6.1.6-4.6 4.1 1.3 6-5.4-3.1-5.4 3.1 1.3-6-4.6-4.1 6.1-.6z"/>
                    </svg>
                </span>

            @endif

        </span>

    @endfor

</span>
