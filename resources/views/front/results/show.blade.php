<x-app-layout>
    <div class="pt-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h6 class="text-xl font-bold">{{ __('My Results') }}</h6>

                    <div class="mb-4 min-w-full overflow-hidden overflow-x-auto align-middle">
                        <table class="mt-4 w-full">
                            <tbody class="bg-white dark:bg-slate-800">
                                @if(auth()->user()?->is_admin)
                                    <tr class="w-28">
                                        <th class="border border-solid dark:border-slate-500 bg-slate-100 dark:bg-slate-900 px-6 py-3 text-left text-sm font-semibold uppercase text-slate-600 dark:text-slate-300">{{ __('User') }}</th>
                                        <td class="border border-solid dark:border-slate-500 px-6 py-3">{{ $test->user->name ?? __('Anonymous') }} <span class="italic">{{ $test->user? '('.$test->user->email.')' : '' }}</span> </td>
                                    </tr>
                                @endif
                                <tr class="w-28">
                                    <th class="border border-solid dark:border-slate-500 bg-slate-100 dark:bg-slate-900 px-6 py-3 text-left text-sm font-semibold uppercase text-slate-600 dark:text-slate-300">{{ __('Date') }}</th>
                                    <td class="border border-solid dark:border-slate-500 px-6 py-3">{{ $test->created_at ?? '' }}</td>
                                </tr>
                                <tr class="w-28">
                                    <th class="border border-solid dark:border-slate-500 bg-slate-100 dark:bg-slate-900 px-6 py-3 text-left text-sm font-semibold uppercase text-slate-600 dark:text-slate-300">{{ __('Result') }}</th>
                                    <td class="border border-solid dark:border-slate-500 px-6 py-3">
                                        {{ $test->result }} / {{ $total_questions }}
                                        @if($test->time_spent)
                                            ({{ __('time: :time minutes', ['time' => intval($test->time_spent / 60).':'. gmdate('s', $test->time_spent) ]) }})
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h6 class="text-xl font-bold">{{ __('Questions and Answers') }}</h6>

                    @foreach($results as $result)
                        <table class="table w-full my-4 bg-white">
                            <tbody class="bg-white dark:bg-gray-800">
                                <tr class="bg-slate-100 dark:bg-slate-900">
                                    <td class="w-1/3 lg:w-1/4">{{ __('Question') }} #{{ $loop->iteration }}</td>
                                    <td>{!! nl2br($result->question->question_text) !!}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Options') }}</td>
                                    <td>
                                        @foreach($result->question->questionOptions as $option)
                                            <li @class([
                                                'underline' => $result->option_id == $option->id,
                                                'font-bold' => $option->correct == 1,
                                            ])>
                                                {{ $option->option }}
                                                @if ($option->correct == 1) <span class="italic">({{ __('correct answer') }})</span> @endif
                                                @if ($result->option_id == $option->id) <span class="italic">({{ __('your answer') }})</span> @endif
                                            </li>
                                        @endforeach
                                        @if(is_null($result->option_id))
                                            <span class="font-bold italic">{{ __('Question unanswered.') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($result->question->answer_explanation || $result->question->more_info_link)
                                    <tr>
                                        <td>{{ __('Answer explanation') }}</td>
                                        <td>
                                            {!! $result->question->answer_explanation  !!}
                                            @if ($result->question->more_info_link)
                                                <div class="mt-4">
                                                    {{ __('Read more') }}:
                                                    <a href="{{ $result->question->more_info_link }}" class="hover:underline" target="_blank">
                                                        {{ $result->question->more_info_link }}
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        @if(!$loop->last)
                            <hr class="my-4 md:min-w-full dark:border-slate-500">
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
