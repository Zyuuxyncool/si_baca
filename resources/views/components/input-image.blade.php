<div
    class="image-input image-input-outline @if($preview == '') image-input-empty @endif"
    data-kt-image-input="true"
    style="background-image: url('{{ asset('img/default.png') }}')">

    <div
        class="image-input-wrapper {{ $class }}"
        @if($preview != '') style="background-image: url('{{ asset('storage/' . $preview) }}')" @endif
    ></div>

    <label class="btn btn-icon btn-color-muted btn-active-color-primary {{ $classButton }} bg-body shadow"
           data-kt-image-input-action="change"
           data-bs-toggle="tooltip"
           data-bs-dismiss="click"
           title="Ubah">
        {!! $editContent !!}

        <input type="file" id="{{ $id }}" name="{{ $name }}" accept=".png, .jpg, .jpeg" data-allowed-file-extensions="png jpg jpeg" />
        <input type="hidden" id="{{ $id }}_delete" name="{{ $name }}_delete" />
    </label>

    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
          data-kt-image-input-action="cancel"
          data-bs-toggle="tooltip"
          data-bs-dismiss="click"
          title="Batal Pilih">
        <i class="bi bi-x fs-2"></i>
    </span>

    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
          data-kt-image-input-action="remove"
          data-bs-toggle="tooltip"
          data-bs-dismiss="click"
          title="Hapus Foto">
        <i class="bi bi-x fs-2"></i>
    </span>
</div>
