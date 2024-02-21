<div wire:ignore class="mt-1 w-full">
    <select class="w-full" {{ $attributes }}>
        @if(!isset($attributes['multiple']))
            <option></option>
        @endif
        @foreach($options as $key => $value)
            <option value="{{ $key }}" @selected(in_array($key, $selectedOptions))>{{ $value }}</option>
        @endforeach
    </select>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    new TomSelect('#{{ $attributes['id'] }}', {
      plugins: {
        remove_button: {
            title: '{{ __('Remove this item') }}'
        }
      }
    })
</script>
