<div class="form-check {{ $class }}">
    <input
        class="form-check-input {{ $classInput }}"
        name="{{ $name }}"
        id="{{ $prefix.str_replace(array('[',']'), '', $name) }}_{{ $value }}"
        type="radio"
        value="{{ $value ?? 1 }}"
        {{ $checked == true ? 'checked' : '' }}
        {{ $attributes }}
    />
    <label class="form-check-label text-dark fs-5 lh-1 ms-3" for="{{ $prefix.str_replace(array('[',']'), '', $name) }}">
        {{ $caption }}
    </label>
</div>
