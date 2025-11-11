<form id="form_info">
    @csrf
    <div class="modal-header">
        <div class="card-title fs-3 fw-bold">Tambahkan Pesan / Cerita</div>
    </div>
    <div class="modal-body">
        <x-metronic-input name="nama" caption="Nama" :value="old('nama')" />
        <x-metronic-textarea name="pesan" caption="Pesan / Cerita">{{ old('pesan') }}</x-metronic-textarea>
    </div>
    <div class="modal-footer d-flex justify-content-end py-4 px-6">
        <button type="button" data-bs-dismiss="modal" onclick="$('#modal_info').modal('hide')"
            class="btn btn-light btn-active-light-primary me-2">Batal</button>
        <button type="submit" class="btn btn-primary">Kirim</button>
    </div>
</form>

<!-- Removed inline init_form() call to avoid double-binding the submit handler.
     The parent page will call init_form() after inserting this partial via AJAX. -->
