@push('modals')
    <div class="modal fade" tabindex="-1" id="modal_location">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Cari Lokasi</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="modal_location_body"></div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        let location_target_id = 'location_id', location_target_name = 'location_name', location_parent_id = '';
        let $modal_location = $('#modal_location'), $modal_location_body = $('#modal_location_body');
        let open_search_location = (target_id, target_name, parent_id = '') => {
            location_target_id = target_id;
            location_target_name = target_name;
            if (parent_id !== '') location_parent_id = parent_id;
            search_location();
            $modal_location.modal('show');
        }
        let select_location = (id, name) => {
            location_parent_id = id;
            if (location_target_id !== '') $('#' + location_target_id).val(id);
            if (location_target_name !== '') $('#' + location_target_name).val(name).trigger('change');
            $modal_location.modal('hide');
        }
        let search_location = () => $.get("{{ route('get_location') }}?parent_id=" + location_parent_id, (result) => $modal_location_body.html(result)).fail((xhr) => $modal_location_body.html(xhr.responseText));
    </script>
@endpush
