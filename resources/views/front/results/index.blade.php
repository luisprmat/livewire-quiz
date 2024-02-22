<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4 min-w-full overflow-hidden overflow-x-auto align-middle sm:rounded-md border dark:border-gray-600">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-500">
                            <thead>
                                <tr>
                                    <th class="w-16 bg-gray-50 dark:bg-gray-600 px-6 py-3 text-left">
                                    </th>
                                    <th class="bg-gray-50 dark:bg-gray-600 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500 dark:text-gray-300">{{ __('Quiz Title') }}</span>
                                    </th>
                                    <th class="bg-gray-50 dark:bg-gray-600 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500 dark:text-gray-300">{{ __('Date') }}</span>
                                    </th>
                                    <th class="bg-gray-50 dark:bg-gray-600 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500 dark:text-gray-300">{{ __('Result') }}</span>
                                    </th>

                                    <th class="w-40 bg-gray-50 dark:bg-gray-600 px-6 py-3 text-left">
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-500 divide-solid">
                                @forelse($results as $result)
                                    <tr class="bg-white dark:bg-gray-900">
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 dark:text-gray-100 whitespace-no-wrap">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 dark:text-gray-100 whitespace-no-wrap">
                                            {{ $result->quiz->title }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 dark:text-gray-100 whitespace-no-wrap">
                                            {{ $result->created_at }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 dark:text-gray-100 whitespace-no-wrap">
                                            {{ $result->result.'/'.$result->questions_count }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 dark:text-gray-100 whitespace-no-wrap text-end">
                                            <x-button-link :href="route('results.show', $result)" style="secondary">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </span>
                                                <span class="hidden sm:block">{{ __('View') }}</span>
                                            </x-button-link>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="dark:bg-gray-900">
                                        <td colspan="5" class="px-6 py-4 text-center leading-5 text-gray-900 dark:text-gray-100 whitespace-no-wrap">
                                            {{ __('No results were found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $results->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
