<textarea
    name="{{ $name }}"
    id="{{ $prefix.$name }}"
    rows="{{ $rows }}"
    class="form-control {{ $class }}"
    placeholder="{{ $caption }}"
    {{ $required != '' ? 'required' : '' }}
    {{ $attributes }}
>{{ $value }}</textarea>
