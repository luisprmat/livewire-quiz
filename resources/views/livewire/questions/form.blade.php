<?php

use App\Livewire\Forms\QuestionForm;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\Volt\Component;

new
#[Layout('layouts.app')]
class extends Component {
    public QuestionForm $form;

    public function mount(Question $question)
    {
        $this->form->setQuestion($question);

        $this->form->editing = $question->exists;
    }

    public function addQuestionsOption(): void
    {
        $this->form->addQuestionsOption();
    }

    public function removeQuestionsOption(int $index): void
    {
        $this->form->removeQuestionsOption($index);
    }

    public function save(): Redirector
    {
        $validated = $this->form->validate();

        if (! $this->form->question->exists) {
            Question::create($validated);
        } else {
            $this->form->question->update($validated);
        }

        $this->form->question->questionOptions()->forceDelete();

        foreach ($this->form->questionOptions as $option) {
            $this->form->question->questionOptions()->create($option);
        }

        $this->form->reset();

        return to_route('questions');
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $form->editing ? __('Edit Question') : __('Create Question') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form wire:submit.prevent="save">
                        <div>
                            <x-input-label for="question_text" :value="__('Question text')" />
                            <x-textarea wire:model="form.question_text" id="question_text" class="block mt-1 w-full" name="question_text" required />
                            <x-input-error :messages="$errors->get('form.question_text')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="question_options" :value="__('Question options')"/>
                            @foreach($form->questionOptions as $index => $questionOption)
                                <div class="flex mt-2">
                                    <x-text-input type="text" wire:model="form.questionOptions.{{ $index }}.option" class="w-full" name="questions_options_{{ $index }}" id="questions_options_{{ $index }}" autocomplete="off"/>

                                    <div class="flex items-center">
                                        <label for="questionOptions.{{ $index }}.correct" class="inline-flex items-center ml-2">
                                            <x-checkbox class="ml-2" wire:model="form.questionOptions.{{ $index }}.correct" id="questionOptions.{{ $index }}.correct" />
                                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Correct') }}</span>
                                        </label>

                                        <x-danger-button wire:click="removeQuestionsOption({{ $index }})" type="button" class="ml-2">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </span>
                                            <span class="hidden sm:block">{{ __('Delete') }}</span>
                                        </x-danger-button>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('form.questionOptions.' . $index . '.option')" class="mt-2" />
                            @endforeach

                            <x-input-error :messages="$errors->get('form.questionOptions')" class="mt-2" />

                            <x-primary-button wire:click="addQuestionsOption" type="button" class="mt-2">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </span>
                                <span class="hidden sm:block">
                                    {{ __('Add') }}
                                </span>
                            </x-primary-button>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="code_snippet" :value="__('Code snippet')" />
                            <x-textarea wire:model="form.code_snippet" id="code_snippet" class="block mt-1 w-full" name="code_snippet" />
                            <x-input-error :messages="$errors->get('form.code_snippet')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="answer_explanation" :value="__('Answer explanation')" />
                            <x-textarea wire:model="form.answer_explanation" id="answer_explanation" class="block mt-1 w-full" name="answer_explanation" />
                            <x-input-error :messages="$errors->get('form.answer_explanation')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="more_info_link" :value="__('More info link')" />
                            <x-text-input wire:model="form.more_info_link" id="more_info_link" class="block mt-1 w-full" name="more_info_link" />
                            <x-input-error :messages="$errors->get('form.more_info_link')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-primary-button>
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
