@push('modals')
    <div class="modal fade" tabindex="-1" id="modal_perusahaan">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal_perusahaan_title">Cari dan Pilih Perusahaan</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row align-items-center gap-3 mb-6">
                        <x-input name="modal_perusahaan_search" class="form-control-solid" caption="Search ..." onkeyup="search_perusahaan()" />
                        <button class="btn btn-sm btn-success text-nowrap" onclick="add_and_select_perusahaan()" id="button_add_select_perusahaan">Tambahkan & Pilih</button>
                    </div>
                    <div id="modal_perusahaan_body"></div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        let perusahaan_target_id = 'perusahaan_id';
        let $modal_perusahaan = $('#modal_perusahaan'), $modal_perusahaan_body = $('#modal_perusahaan_body'), $modal_perusahaan_search = $('#modal_perusahaan_search');
        let open_search_perusahaan = (target_id) => {
            perusahaan_target_id = target_id;
            search_perusahaan();
            $modal_perusahaan.modal('show');
        }
        let select_perusahaan = (id) => {
            $('#' + perusahaan_target_id).val(id).trigger('change');
            $modal_perusahaan.modal('hide');
        }
        let search_perusahaan = () => $.post("{{ route('admin.perusahaan.search') }}", {_token: '{{ csrf_token() }}', min: true, limit: 15, nama: $modal_perusahaan_search.val()}, (result) => $modal_perusahaan_body.html(result)).fail((xhr) => $modal_perusahaan_body.html(xhr.responseText));

        add_and_select_perusahaan = () => {
            $.post("{{ route('admin.perusahaan.store') }}", {_token: '{{ csrf_token() }}', nama: $modal_perusahaan_search.val()}, (result) => {
                select_perusahaan(result.id);
            }).fail((xhr) => $modal_perusahaan_body.html(xhr.responseText));
        }
    </script>
@endpush
