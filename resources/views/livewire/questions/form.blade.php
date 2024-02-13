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

    public function save()
    {
        $validated = $this->form->validate();

        if (! $this->form->question->exists) {
            Question::create($validated);
        } else {
            $this->form->question->update($validated);
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
                            <x-textarea wire:model="form.question_text" id="question_text" class="block mt-1 w-full" name="question_text" />
                            <x-input-error :messages="$errors->get('form.question_text')" class="mt-2" />
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
