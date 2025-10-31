@if($viewtype === 1)
    <div class="row mb-6" id="form_group_{{ $prefix.$name }}">
        <label class="col-lg-3 col-form-label {{ $required == 1 ? 'required' : '' }} fw-bold fs-6">{{ $caption }}</label>
        <div class="col-lg-9">
            <x-input
                :type="$type"
                :name="$name"
                :prefix="$prefix"
                class="{{ $class }}"
                :caption="$placeholder"
                :value="$value"
                :required="$required"
                {{ $attributes }}
            />
        </div>
    </div>
@endif
@if($viewtype === 2)
    <div class="form-group mb-4 d-flex flex-column align-items-start" id="form_group_{{ $prefix.$name }}">
        <label class="col-form-label py-1 {{ $required == 1 ? 'required' : '' }} fw-bold fs-6">{{ $caption }}</label>
        <x-input
            :type="$type"
            :name="$name"
            :prefix="$prefix"
            class="{{ $class }}"
            :caption="$placeholder"
            :value="$value"
            :required="$required"
            {{ $attributes }}
        />
    </div>
@endif
