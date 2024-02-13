<?php

use App\Models\Question;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('layouts.app')]
class extends Component
{
    public function with(): array
    {
        return [
            'questions' => Question::latest()->paginate(),
        ];
    }

    public function delete(Question $question): void
    {
        if (! auth()->user()->is_admin) throw new AuthorizationException;

        $question->delete();
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Questions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <x-button-link :href="route('questions.create')" wire:navigate>
                            {{ __('Create Question') }}
                        </x-button-link>
                    </div>

                    <div class="mb-4 min-w-full overflow-hidden overflow-x-auto align-middle sm:rounded-md">
                        <table class="min-w-full border dark:border-gray-600 divide-y divide-gray-200 dark:divide-gray-500">
                            <thead>
                                <tr>
                                    <th class="w-16 bg-gray-50 dark:bg-gray-600 px-6 py-3 text-left">
                                    </th>
                                    <th class="bg-gray-50 dark:bg-gray-600 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500 dark:text-gray-300">{{ __('Question text') }}</span>
                                    </th>
                                    <th class="w-40 bg-gray-50 dark:bg-gray-600 px-6 py-3 text-left">
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-500 divide-solid">
                                @forelse($questions as $question)
                                    <tr class="bg-white dark:bg-gray-900">
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 dark:text-gray-100 whitespace-no-wrap">
                                            {{ $question->id }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 dark:text-gray-100 whitespace-no-wrap">
                                            {{ $question->question_text }}
                                        </td>
                                        <td class="px-6 py-4 flex items-center justify-end gap-2">
                                            <x-button-link href="{{ route('questions.edit', $question->id) }}">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                </span>
                                                <span class="hidden sm:block">{{ __('Edit') }}</span>
                                            </x-button-link>
                                            <x-danger-button wire:click="delete({{ $question }})">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </span>
                                                <span class="hidden sm:block">{{ __('Delete') }}</span>
                                            </x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="dark:bg-gray-900">
                                        <td colspan="3" class="px-6 py-4 text-center leading-5 text-gray-900 dark:text-gray-100 whitespace-no-wrap">
                                            {{ __('No questions were found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $questions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
