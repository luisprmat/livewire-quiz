<?php

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\Test;
use App\Models\TestAnswer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\Volt\Component;

new
#[Layout('layouts.app')]
class extends Component {
    public Quiz $quiz;

    public Collection $questions;

    public Question $currentQuestion;

    public int $currentQuestionIndex = 0;

    public array $questionsAnswers = [];

    public int $startTimeSeconds = 0;

    public function mount(): void
    {
        $this->startTimeSeconds = now()->timestamp;

        $this->questions = Question::query()
            ->inRandomOrder()
            ->whereRelation('quizzes','id', $this->quiz->id)
            ->with('questionOptions')
            ->get();

        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];

        for($i = 0; $i < $this->questionsCount; $i++) {
            $this->questionsAnswers[$i] = [];
        }
    }

    #[Computed]
    public function questionsCount(): int
    {
        return $this->questions->count();
    }

    public function changeQuestion(): void
    {
        $this->currentQuestionIndex++;

        if ($this->currentQuestionIndex >= $this->questionsCount) {
            $this->submit();
            return;
        }

        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
    }

    public function submit(): Redirector|RedirectResponse
    {
        $result = 0;

        $test = Test::create([
            'user_id'    => auth()->id(),
            'quiz_id'    => $this->quiz->id,
            'result'     => 0,
            'ip_address' => request()->ip(),
            'time_spent' => now()->timestamp - $this->startTimeSeconds
        ]);

        foreach ($this->questionsAnswers as $key => $option) {
            $status = 0;

            if (! empty($option) && QuestionOption::find($option)->correct) {
                $status = 1;
                $result++;
            }

            TestAnswer::create([
                'user_id'     => auth()->id(),
                'test_id'     => $test->id,
                'question_id' => $this->questions[$key]->id,
                'option_id'   => $option ?? null,
                'correct'     => $status,
            ]);
        }

        $test->update([
            'result' => $result,
        ]);

        return to_route('home');
    }
}; ?>

<div
    x-data="{ secondsLeft: {{ config('quiz.secondsPerQuestion') }} }"
    x-init="setInterval(() => { if (secondsLeft > 1) { secondsLeft--; } else { secondsLeft = {{ config('quiz.secondsPerQuestion') }}; $wire.changeQuestion(); } }, 1000);"
>
    <div class="mb-2">
        {{ __('Time left for this question') }}: <span x-text="secondsLeft" class="font-bold"></span> {{ __('sec.') }}
    </div>

    <span class="text-bold">{{ __('Question') }} {{ $currentQuestionIndex + 1 }} {{ __('of') }} {{ $this->questionsCount }}:</span>
    <h2 class="mb-4 text-2xl">{{ $currentQuestion->question_text }}</h2>

    @if ($currentQuestion->code_snippet)
        <pre class="mb-4 border-2 border-solid bg-gray-50 dark:bg-gray-900 p-2 dark:border-gray-500 dark:text-gray-400">{{ $currentQuestion->code_snippet }}</pre>
    @endif

    @foreach($currentQuestion->questionOptions as $option)
        <div>
            <label for="option.{{ $option->id }}">
                <x-radio
                       id="option.{{ $option->id }}"
                       wire:model="questionsAnswers.{{ $currentQuestionIndex }}"
                       name="questionsAnswers.{{ $currentQuestionIndex }}"
                       value="{{ $option->id }}" />
                {{ $option->option }}
            </label>
        </div>
    @endforeach

    @if ($currentQuestionIndex < $this->questionsCount - 1)
        <div class="mt-4">
            <x-secondary-button x-on:click="secondsLeft = {{ config('quiz.secondsPerQuestion') }}; $wire.changeQuestion();">
                {{ __('Next question') }}
            </x-secondary-button>
        </div>
    @else
        <div class="mt-4">
            <x-primary-button wire:click="submit">{{ __('Submit') }}</x-primary-button>
        </div>
    @endif
</div>
