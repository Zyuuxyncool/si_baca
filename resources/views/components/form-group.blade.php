<div class="form-group mb-3 {{ $class }}" id="{{ $id != '' ? 'form_group_'.$id : '' }}">
    <label for="{{ $id }}">{{ $caption }}</label>
    {{ $slot }}
</div>
