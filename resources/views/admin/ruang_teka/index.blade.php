@extends('admin.layouts.index')

@section('title')
    Cerita -
@endsection

@section('content')
    <div class="content flex-column-fluid" id="kt_content">
        <div class="toolbar d-flex flex-stack flex-wrap mb-5 mb-lg-7" id="kt_toolbar">
            <div class="page-title d-flex flex-column py-1">
                <h1 class="d-flex align-items-center my-1"><span class="text-dark fw-bold fs-1">Ruang Teka</span></h1>
                @include('admin.layouts._breadcrumb')
            </div>
            <div class="d-flex align-items-center py-1 gap-6">
                <button type="button" onclick="info()" class="btn btn-flex btn-sm btn-primary fw-bold border-0 fs-6 h-40px">Ruang Teka Baru</button>
            </div>
        </div>

        <div class="w-100 mx-auto">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <form id="form_search" class="w-100">
                        <button type="submit" class="d-none">Search</button>
                        @csrf
                        <div class="card-title d-flex flex-row align-items-center gap-4">
                            <div class="d-flex align-items-center position-relative gap-6 w-100 w-lg-250px">
                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4"><span class="path1"></span><span class="path2"></span></i>
                                <x-input name="nama" prefix="search_" caption="Search cerita" class="w-lg-250px ps-12" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body pt-0" id="table"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modal_info">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="modal_info_ruang_teka">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let $form_search = $('#form_search'),
            $table = $('#table'),
            $modal_info = $('#modal_info'),
            $modal_info_ruang_teka = $('#modal_info_ruang_teka');
        let selected_page = 1, _token = '{{ csrf_token() }}', base_url = '{{ route('admin.ruang_teka.index') }}';

        let init = () => {
            $modal_info_ruang_teka.html('');
            try { $modal_info.modal('hide'); } catch (e) { }
            search_data(selected_page);
        }

        let search_data = (page = 1) => {
            let data = get_form_data($form_search);
            data.paginate = 10;
            data.page = selected_page = get_selected_page(page, selected_page);
            $.post(base_url + '/search', data, (result) => $table.html(result)).fail((xhr) => $table.html(xhr.responseText));
        }

        let display_modal_info = (categories) => {
            // safer injection: parse HTML and recreate script tags to avoid appendChild '&' errors
            try {
                const parser = new DOMParser();
                const doc = parser.parseFromString(categories, 'text/html');
                const container = document.getElementById('modal_info_ruang_teka');
                // clear existing
                while (container.firstChild) container.removeChild(container.firstChild);
                // move nodes, recreate scripts to ensure execution
                Array.from(doc.body.childNodes).forEach(node => {
                    if (node.nodeName === 'SCRIPT') {
                        const s = document.createElement('script');
                        if (node.src) s.src = node.src;
                        else s.text = node.textContent || node.innerText || '';
                        container.appendChild(s);
                    } else {
                        container.appendChild(document.importNode(node, true));
                    }
                });
            } catch (e) {
                // fallback to jQuery html if parser fails
                $modal_info_ruang_teka.html(categories);
            }

            $modal_info.modal('show');
            try { init_form_element(); } catch (e) {}
            try {
                const tid = $('#modal_info_ruang_teka').find('#template_id').val() || '';
                init_form(tid);
                // call any form-specific initializer if provided
                if (window.initRuangTekaForm) try { window.initRuangTekaForm(); } catch (e) {}
            } catch (e) {}
        }

        let info = (id = '') => {
            $.get(base_url + '/' + (id === '' ? 'create' : (id + '/edit')), (result) => display_modal_info(result)).fail((xhr) => display_modal_info(xhr.responseText));
        }

        let confirm_delete = (id) => {
            Swal.fire(swal_delete_params).then((result) => {
                if (result.isConfirmed) $.post(base_url + '/' + id, {_method: 'delete', _token}, () => swal.fire('Sucessfully Deleted').then(() => init())).fail((xhr) => {
                    if (xhr.error) swal.fire(xhr.error);
                    else $table.html(xhr.responseText);
                });
            });
        }

        let init_form = (id = '') => {
            let $form_info = $('#form_info');
            $form_info.submit((e) => {
                e.preventDefault();
                let url = base_url;
                let data = new FormData($form_info.get(0));
                if (id !== '') url += '/' + id + '?_method=put';
                // Tampilkan Swal loading saat proses penyimpanan
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url,
                    type: 'post',
                    data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: () => {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil disimpan!'
                        }).then(() => init());
                    },
                }).fail((xhr) => {
                    // tutup loading
                    try { Swal.close(); } catch (e) {}
                    // tampilkan validasi di form
                    error_handle(xhr.responseText);
                    // juga tampilkan alert ringkas
                    let msg = 'Terjadi kesalahan saat menyimpan.';
                    try {
                        const body = JSON.parse(xhr.responseText);
                        if (body.message) msg = body.message;
                    } catch (e) {}
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: msg
                    });
                });
            });
        }

        $form_search.submit((e) => {
            e.preventDefault();
            search_data();
        });

        init_form_element();
        init();
    </script>
@endpush
