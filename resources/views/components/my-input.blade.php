
<div class="row mb-6 input-group"  id="form_group_{{ $prefix.$name }}">
    <label class="col-lg-3 col-form-label {{ $required == 1 ? 'required' : '' }} fw-bold fs-6">{{ $caption }}</label>
    <div class="col-lg-9">
        <x-input
            :type="$type"
            :name="$name"
            :prefix="$prefix"
            class="form-control {{ $class }}"
            :caption="$placeholder"
            :value="$value"
            :required="$required"
            {{ $attributes }}
        />
    </div>
</div>
<br>
