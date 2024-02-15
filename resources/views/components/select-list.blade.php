<div wire:ignore class="mt-1 w-full">
    <select class="select2 w-full" {{ $attributes }}>
        @if(!isset($attributes['multiple']))
            <option></option>
        @endif
        @foreach($options as $key => $value)
            <option value="{{ $key }}" @selected(in_array($key, $selectedOptions))>{{ $value }}</option>
        @endforeach
    </select>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            const el = $('#{{ $attributes['id'] }}')

            const initSelect = () => {
                el.select2({
                    placeholder: '{{ __('Select your option') }}',
                    allowClear: !el.attr('required')
                })
            }

            initSelect()

            Livewire.hook('commit', ({ succeed }) => {
                succeed(() => {
                    queueMicrotask(() => {
                        initSelect()
                    })
                })
            })

            el.on('change', () => {
                let data = $(this)
                // let data = $(this).select2('val')
                console.log('data', data)
                if (data === '') {
                    data = null
                }
                @this.set('{{ $attributes['wire:model'] }}', data)
            })
        });
    </script>
@endpush
