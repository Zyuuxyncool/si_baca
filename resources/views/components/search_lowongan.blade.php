@push('modals')
    <div class="modal fade" tabindex="-1" id="modal_lowongan">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal_lowongan_title">Cari dan Pilih Lowongan</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    <x-input name="modal_lowongan_search" class="form-control-solid mb-6" caption="Search ..." onkeyup="search_lowongan()" />
                    <div id="modal_lowongan_body"></div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        let lowongan_target_id = 'lowongan_id', selected_lowongan_params = [];
        let $modal_lowongan = $('#modal_lowongan'), $modal_lowongan_body = $('#modal_lowongan_body'), $modal_lowongan_search = $('#modal_lowongan_search');
        let open_search_lowongan = (target_id, params = []) => {
            selected_lowongan_params = params;
            lowongan_target_id = target_id;
            search_lowongan();
            $modal_lowongan.modal('show');
        }
        let select_lowongan = (id) => {
            $('#' + lowongan_target_id).val(id).trigger('change');
            $modal_lowongan.modal('hide');
        }
        let search_lowongan = () => {
            selected_lowongan_params['_token'] = '{{ csrf_token() }}';
            selected_lowongan_params['min'] = true;
            selected_lowongan_params['limit'] = 15;
            selected_lowongan_params['nama'] = $modal_lowongan_search.val();
            $.post("{{ route('admin.lowongan.search') }}", selected_lowongan_params, (result) => $modal_lowongan_body.html(result)).fail((xhr) => $modal_lowongan_body.html(xhr.responseText));
        }
    </script>
@endpush
