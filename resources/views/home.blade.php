<x-app-layout>
    <div class="pt-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h6 class="text-xl font-bold">{{ __('Public quizzes') }}</h6>

                    @forelse($public as $quiz)
                        <div class="px-4 py-2 w-full lg:w-6/12 xl:w-3/12">
                            <div class="flex relative flex-col mb-6 min-w-0 break-words bg-white rounded shadow-lg xl:mb-0 dark:bg-gray-700">
                                <div class="flex-auto p-4">
                                    <a href="{{ route('quiz.show', [$quiz, $quiz->slug]) }}">{{ $quiz->title }}</a>
                                    <p class="text-sm">{{ __('Questions') }}: <span>{{ $quiz->questions_count }}</span></p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="mt-2">{{ __('No public quizzes found.') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h6 class="text-xl font-bold">{{ __('Quizzes for Registered Users') }}</h6>

                    @forelse($registered as $quiz)
                        <div class="px-4 py-2 w-full lg:w-6/12 xl:w-3/12">
                            <div class="flex relative flex-col mb-6 min-w-0 break-words bg-white rounded shadow-lg xl:mb-0 dark:bg-gray-700">
                                <div class="flex-auto p-4">
                                    <a href="{{ route('quiz.show', [$quiz, $quiz->slug]) }}">{{ $quiz->title }}</a>
                                    <p class="text-sm">{{ __('Questions') }}: <span>{{ $quiz->questions_count }}</span></p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="mt-2">{{ __('No quizzes for registered users found.') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
