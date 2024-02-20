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
