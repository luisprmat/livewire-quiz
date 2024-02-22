<?php

use App\Models\QuestionQuiz;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('layouts.app')]
class extends Component {
    public Collection $quizzes;

    public int $quizId = 0;

    public function mount(): void
    {
        $this->quizzes = Quiz::where('public', 1)->where('published', 1)->get();
    }

    public function with(): array
    {
        $total_questions = QuestionQuiz::select('question_id')
            ->join('quizzes', 'question_quiz.quiz_id', '=', 'quizzes.id')
            ->where('quizzes.published', 1)
            ->when($this->quizId > 0, fn (Builder $query) => $query->where('quiz_id', $this->quizId))
            ->count();

        $users = User::select('users.name', DB::raw('sum(tests.result) as correct'), DB::raw('sum(tests.time_spent) as time_spent'))
            ->join('tests', 'users.id', '=', 'tests.user_id')
            ->whereNotNull('tests.quiz_id')
            ->whereNotNull('tests.time_spent')
            ->whereNull('tests.deleted_at')
            ->when($this->quizId > 0, fn (Builder $query) => $query->where('tests.quiz_id', $this->quizId))
            ->groupBy('users.id', 'users.name')
            ->orderBy('correct', 'desc')
            ->orderBy('time_spent')
            ->get();

        return [
            'users' => $users,
            'total_questions' => $total_questions,
        ];
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Leaderboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-select class="p-3 w-full text-sm leading-5 rounded border-0 shadow text-slate-600" wire:model.change="quizId">
                        <option value="0">--- {{ __('all quizzes') }} ---</option>
                        @foreach($quizzes as $quiz)
                            <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                        @endforeach
                    </x-select>

                    <div class="mt-4 min-w-full overflow-hidden overflow-x-auto align-middle sm:rounded-md border dark:border-gray-600">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-500">
                            <thead>
                                <tr>
                                    <th class="w-16 bg-gray-50 dark:bg-gray-600 px-6 py-3 text-left">
                                    </th>
                                    <th class="bg-gray-50 dark:bg-gray-600 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500 dark:text-gray-300">{{ __('Username') }}</span>
                                    </th>
                                    <th class="bg-gray-50 dark:bg-gray-600 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500 dark:text-gray-300">{{ __('Correct answers') }}</span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-500 divide-solid">
                                @forelse($users as $user)
                                    <tr @class(['bg-slate-100 dark:bg-slate-900' => auth()->check() && $user->name == auth()->user()->name])>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 dark:text-gray-100 whitespace-no-wrap">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 dark:text-gray-100 whitespace-no-wrap">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 dark:text-gray-100 whitespace-no-wrap">
                                            {{ $user->correct }} / {{ $total_questions }}
                                            ({{ __('time: :time minutes', ['time' => intval($user->time_spent / 60).':'. gmdate('s', $user->time_spent)]) }})
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="dark:bg-gray-900">
                                        <td colspan="3" class="px-6 py-4 text-center leading-5 text-gray-900 dark:text-gray-100 whitespace-no-wrap">
                                            {{ __('No results.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
