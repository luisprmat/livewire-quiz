<?php

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\Volt\Component;

new
#[Layout('layouts.app')]
class extends Component {
    public ?Quiz $quiz = null;

    #[Validate('string|required')]
    public string $title = '';

    #[Validate('string|nullable')]
    public string $slug = '';

    #[Validate('string|nullable')]
    public string|null $description = '';

    #[Validate('boolean')]
    public bool $published = false;

    #[Validate('boolean')]
    public bool $public = false;

    public bool $editing = false;

    #[Validate('array')]
    public array $questions = [];

    public array $listsForFields = [];

    public function mount(Quiz $quiz): void
    {
        $this->initListsForFields();

        if ($quiz->exists) {
            $this->quiz = $quiz;
            $this->editing = true;
            $this->title = $quiz->title;
            $this->slug = $quiz->slug;
            $this->description = $quiz->description;
            $this->published = $quiz->published;
            $this->public = $quiz->public;

            $this->questions = $quiz->questions()->pluck('id')->toArray();
        } else {
            $this->published = false;
            $this->public = false;
        }
    }

    public function updatedTitle(): void
    {
        $this->slug = Str::slug($this->title);
    }

    public function save(): Redirector|RedirectResponse
    {
        $this->validate();

        if (empty($this->quiz)) {
            $this->quiz = Quiz::create($this->only(['title', 'slug', 'description', 'published', 'public']));
        } else {
            $this->quiz->update($this->only(['title', 'slug', 'description', 'published', 'public']));
        }

        $this->quiz->questions()->sync($this->questions);

        return to_route('quizzes');
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['questions'] = Question::pluck('question_text', 'id')->toArray();
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $editing ? __('Edit Quiz') : __('Create Quiz') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form wire:submit.prevent="save">
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input wire:model.lazy="title" id="title" class="block mt-1 w-full" name="title" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="slug" :value="__('Slug')" />
                            <x-text-input wire:model.lazy="slug" id="slug" class="block mt-1 w-full" name="slug" />
                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <x-textarea wire:model.defer="description" id="description" class="block mt-1 w-full" name="description" />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="questions" :value="__('Questions')" />
                            <x-select-list id="questions" name="questions" :options="$listsForFields['questions']" :selectedOptions="$questions" wire:model="questions" multiple />
                            <x-input-error :messages="$errors->get('questions')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <div class="flex items-center">
                                <x-input-label for="published" :value="__('Published')" />
                                <x-checkbox wire:model="published" id="published" class="mr-1 ml-2" />
                            </div>
                            <x-input-error :messages="$errors->get('published')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <div class="flex items-center">
                                <x-input-label for="public" :value="__('Public')" />
                                <x-checkbox wire:model="public" id="public" class="mr-1 ml-2" />
                            </div>
                            <x-input-error :messages="$errors->get('public')" class="mt-2" />
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
