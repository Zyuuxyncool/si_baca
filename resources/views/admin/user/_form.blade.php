<form id="form_info">
    @csrf
    <div class="modal-header">
        <div class="card-title fs-3 fw-bold">{{ !empty($user) ? 'Ubah' : 'Tambah' }} User</div>
    </div>
    <div class="modal-body">
        @if(empty($user))
            <x-metronic-select name="akses" caption="Akses" :options="$list_akses" :value="$user->akses ?? ''" />
        @endif
        <x-metronic-input name="name" caption="Nama" :value="$user->name ?? ''" />
        <x-metronic-input name="email" caption="Email" :value="$user->email ?? ''" />
        <x-metronic-input name="password" caption="Password" type="password" />
        <x-metronic-input name="password_confirmation" caption="Ulangi Password" type="password" />
    </div>
    <div class="modal-footer d-flex justify-content-end py-6">
        <button type="button" onclick="init()" class="btn btn-light btn-active-light-primary me-2">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    init_form_element();
    init_form({{ $user->id ?? '' }});
</script>
