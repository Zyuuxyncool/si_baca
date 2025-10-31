@if($viewtype === 1)
    <div class="row mb-6">
        <label class="col-lg-3 col-form-label {{ $required == 1 ? 'required' : '' }} fw-bold fs-6">{{ $caption }}</label>
        <div class="col-lg-9">
            <x-select :name="$name" class="" :options="$options" :value="$value" :caption="$placeholder" data-control="select2" {{ $attributes }} />
        </div>
    </div>
@endif
@if($viewtype === 2)
    <div class="form-group mb-4 d-flex flex-column align-items-start">
        <label class="col-form-label py-1 {{ $required == 1 ? 'required' : '' }} fw-bold fs-6">{{ $caption }}</label>
        <x-select :name="$name" class="" :options="$options" :value="$value" :caption="$placeholder" data-control="select2" {{ $attributes }} />
    </div>
@endif
