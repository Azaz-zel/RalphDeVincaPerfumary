@props(['questions' => []])

@php
    $total = count($questions);
@endphp

{{--
    Built as a plain radio-button form so it still works with JavaScript off:
    every question is visible and the submit button sends them all at once.
    quiz.js then progressively turns it into a one-question-at-a-time stepper.
--}}
<section class="py-16 lg:py-24">

    <div class="mx-auto max-w-3xl px-8 lg:px-16">

        <form
            data-quiz
            action="{{ route('quiz.result') }}"
            method="GET">

            {{-- Progress: revealed by JS, since it only means something in stepper mode --}}
            <div class="mb-12" data-quiz-progress-bar hidden>

                <div class="flex items-baseline justify-between">

                    <p class="text-xs uppercase tracking-[0.35em] text-[#B08D57]">
                        Question
                        <span data-quiz-current>1</span>
                        of {{ $total }}
                    </p>

                    <button
                        type="button"
                        data-quiz-back
                        class="text-sm text-stone-500 underline-offset-4 transition hover:text-[#B08D57] hover:underline"
                        hidden>
                        Back
                    </button>

                </div>

                <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-stone-200 dark:bg-stone-800">

                    <div
                        data-quiz-progress
                        class="h-full rounded-full bg-[#B08D57] transition-all duration-500"
                        style="width: 0%"></div>

                </div>

            </div>

            @foreach($questions as $key => $question)

                <div data-quiz-step class="mt-20 first:mt-0">

                    <h2 class="font-serif text-3xl font-semibold leading-snug lg:text-4xl">
                        {{ $question['question'] }}
                    </h2>

                    <p class="mt-4 leading-8 text-stone-600 dark:text-stone-400">
                        {{ $question['hint'] }}
                    </p>

                    <div class="mt-10 grid gap-4 sm:grid-cols-2">

                        @foreach($question['options'] as $value => $option)

                            <label class="block cursor-pointer">

                                <input
                                    type="radio"
                                    name="{{ $key }}"
                                    value="{{ $value }}"
                                    class="peer sr-only">

                                <span class="block h-full rounded-3xl border border-stone-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#B08D57] hover:shadow-xl peer-checked:border-[#B08D57] peer-checked:bg-[#B08D57]/5 peer-focus-visible:border-[#B08D57] dark:border-stone-700 dark:bg-[#232323]">

                                    <span class="block font-serif text-xl">
                                        {{ $option['label'] }}
                                    </span>

                                    <span class="mt-2 block text-sm leading-6 text-stone-500">
                                        {{ $option['hint'] }}
                                    </span>

                                </span>

                            </label>

                        @endforeach

                    </div>

                </div>

            @endforeach

            <button
                type="submit"
                data-quiz-submit
                class="mt-16 rounded-full bg-[#B08D57] px-8 py-4 font-medium text-white transition hover:opacity-90">
                See My Matches
            </button>

        </form>

    </div>

</section>
