@push('modals')
    <div class="modal fade" tabindex="-1" id="modal_sekolah">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal_sekolah_title">Cari dan Pilih Sekolah</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row align-items-center gap-3 mb-6">
                        <x-input name="modal_sekolah_search" class="form-control-solid" caption="Search ..." onkeyup="search_sekolah()" />
                        <button class="btn btn-sm btn-success text-nowrap" onclick="add_and_select_sekolah()" id="button_add_select_sekolah">Tambahkan & Pilih</button>
                    </div>
                    <div id="modal_sekolah_body"></div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        let sekolah_target_id = 'sekolah_id';
        let $modal_sekolah = $('#modal_sekolah'), $modal_sekolah_body = $('#modal_sekolah_body'), $modal_sekolah_search = $('#modal_sekolah_search');
        let open_search_sekolah = (target_id) => {
            sekolah_target_id = target_id;
            search_sekolah();
            $modal_sekolah.modal('show');
        }
        let select_sekolah = (id) => {
            $('#' + sekolah_target_id).val(id).trigger('change');
            $modal_sekolah.modal('hide');
        }
        let search_sekolah = () => $.post("{{ route('admin.sekolah.search') }}", {_token: '{{ csrf_token() }}', min: true, limit: 15, nama: $modal_sekolah_search.val()}, (result) => $modal_sekolah_body.html(result)).fail((xhr) => $modal_sekolah_body.html(xhr.responseText));

        add_and_select_sekolah = () => {
            $.post("{{ route('admin.sekolah.store') }}", {_token: '{{ csrf_token() }}', nama: $modal_sekolah_search.val()}, (result) => {
                select_sekolah(result.id);
            }).fail((xhr) => $modal_sekolah_body.html(xhr.responseText));
        }
    </script>
@endpush
