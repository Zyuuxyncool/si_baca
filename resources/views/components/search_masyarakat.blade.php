@push('modals')
    <div class="modal fade" tabindex="-1" id="modal_masyarakat">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal_masyarakat_title">Cari dan Pilih Pencaker</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    <x-input name="modal_masyarakat_search" class="form-control-solid mb-6" caption="Search ..." onkeyup="search_masyarakat()" />
                    <div id="modal_masyarakat_body"></div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        let masyarakat_target_id = 'masyarakat_id';
        let $modal_masyarakat = $('#modal_masyarakat'), $modal_masyarakat_body = $('#modal_masyarakat_body'), $modal_masyarakat_search = $('#modal_masyarakat_search');
        let open_search_masyarakat = (target_id) => {
            masyarakat_target_id = target_id;
            search_masyarakat();
            $modal_masyarakat.modal('show');
        }
        let select_masyarakat = (id) => {
            $('#' + masyarakat_target_id).val(id).trigger('change');
            $modal_masyarakat.modal('hide');
        }
        let search_masyarakat = () => $.post("{{ route('admin.masyarakat.search') }}", {_token: '{{ csrf_token() }}', min: true, limit: 15, nama: $modal_masyarakat_search.val()}, (result) => $modal_masyarakat_body.html(result)).fail((xhr) => $modal_masyarakat_body.html(xhr.responseText));
    </script>
@endpush
