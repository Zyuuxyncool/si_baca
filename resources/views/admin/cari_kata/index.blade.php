@extends('admin.layouts.index')

@section('title')
    Cerita -
@endsection

@section('content')
    <div class="content flex-column-fluid" id="kt_content">
        <div class="toolbar d-flex flex-stack flex-wrap mb-5 mb-lg-7" id="kt_toolbar">
            <div class="page-title d-flex flex-column py-1">
                <h1 class="d-flex align-items-center my-1"><span class="text-dark fw-bold fs-1">Cari Kata</span></h1>
                @include('admin.layouts._breadcrumb')
            </div>
            <div class="d-flex align-items-center py-1 gap-6">
                <button type="button" onclick="info()" class="btn btn-flex btn-sm btn-primary fw-bold border-0 fs-6 h-40px">Cari Kata Baru</button>
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
            <div class="modal-content" id="modal_info_cari_kata">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let $form_search = $('#form_search'),
            $table = $('#table'),
            $modal_info = $('#modal_info'),
            $modal_info_cari_kata = $('#modal_info_cari_kata');
        let selected_page = 1, _token = '{{ csrf_token() }}', base_url = '{{ route('admin.cari_kata.index') }}';

        let init = () => {
            $modal_info_cari_kata.html('');
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
                // Safer insertion using a <template> so browser parses HTML natively
                // and script tags are replaced with new executable <script> nodes.
                try {
                    const container = $modal_info_cari_kata.get(0);
                    // clear current content
                    while (container.firstChild) container.removeChild(container.firstChild);

                    // Use DOMParser to safely parse HTML (handles stray '&' better than innerHTML on some inputs)
                    const parser = new DOMParser();
                    const doc = parser.parseFromString((typeof categories === 'string' ? categories : String(categories)).trim(), 'text/html');

                    // Import parsed nodes into the current document and append
                    Array.from(doc.body.childNodes).forEach(node => {
                        const imported = document.importNode(node, true);
                        container.appendChild(imported);
                    });

                    // Find any script tags and replace them with new ones so they execute.
                    // Use createTextNode for inline scripts to avoid HTML parsing errors
                    // (e.g. stray '&' characters) when setting script content.
                    const scripts = Array.from(container.querySelectorAll('script'));
                    scripts.forEach(oldScript => {
                        try {
                            // If script has src, create a new external script element
                            if (oldScript.src) {
                                const s = document.createElement('script');
                                s.src = oldScript.src;
                                s.async = false; // preserve execution order
                                if (oldScript.type) s.type = oldScript.type;
                                if (oldScript.parentNode) oldScript.parentNode.replaceChild(s, oldScript);
                            } else {
                                // Inline script: create new script and append a text node
                                const text = oldScript.textContent || oldScript.innerText || '';
                                const s = document.createElement('script');
                                if (oldScript.type) s.type = oldScript.type;
                                // Use createTextNode to avoid the browser re-parsing HTML entities
                                s.appendChild(document.createTextNode(text));
                                if (oldScript.parentNode) oldScript.parentNode.replaceChild(s, oldScript);
                            }
                        } catch (err) {
                            // Fallback: attempt a safer append replacement
                            try {
                                const s = document.createElement('script');
                                if (oldScript.src) { s.src = oldScript.src; s.async = false; }
                                if (oldScript.type) s.type = oldScript.type;
                                try { s.appendChild(document.createTextNode(oldScript.textContent || '')); } catch (e) {}
                                if (oldScript.parentNode) oldScript.parentNode.replaceChild(s, oldScript);
                            } catch (e) {
                                console.warn('Failed to replace script node', e);
                            }
                        }
                    });
                } catch (e) {
                    // fallback to jQuery/html if anything goes wrong
                    try { $modal_info_cari_kata.html(categories); } catch (er) { $modal_info_cari_kata.text(categories); }
                }

            $modal_info.modal('show');
                // initialize form elements inside injected modal (select2, datepickers, etc.)
            try { init_form_element(); } catch (e) {}
            // initialize form submit handler with template id (if any)
            try {
                const tid = $modal_info_cari_kata.find('#template_id').val() || '';
                init_form(tid);
                // try to call specific form initializer if present (bind add/generate handlers)
                // use a small retry loop because script replacement/execution timing can vary across browsers
                (function tryInit(attempt){
                    try {
                        if (typeof window.initCariKataForm === 'function'){
                            console.debug('display_modal_info: calling initCariKataForm (attempt', attempt, ')');
                            // call and then verify initialization marker
                            try { window.initCariKataForm(); } catch (err) { console.warn('initCariKataForm runtime error', err); }

                            // quick check if the form marked itself as initialized
                            const tb = document.querySelector('#modal_info_cari_kata #words_table tbody');
                            if (tb && tb.dataset && tb.dataset.cariInited) {
                                console.debug('display_modal_info: initCariKataForm succeeded');
                                return;
                            }
                        }
                    } catch(err){ console.warn('display_modal_info: initCariKataForm threw', err); }
                    if (attempt < 6) setTimeout(() => tryInit(attempt+1), 120);
                    else {
                        console.debug('display_modal_info: initCariKataForm not found after retries — attaching fallback handlers');
                        // Fallback: attach delegated handlers for add/generate that don't rely on inline script
                        try {
                            const container = document.getElementById('modal_info_cari_kata');
                            if (!container) return;
                            // ensure we only attach once
                            if (container.dataset.cariFallbackAttached) return;
                            container.dataset.cariFallbackAttached = '1';

                            // delegated add button
                            container.addEventListener('click', function(e){
                                const addBtn = e.target.closest('#add_word_btn');
                                if (addBtn) {
                                    e.preventDefault();
                                    const tbody = container.querySelector('#words_table tbody');
                                    if (!tbody) return;
                                    // create a simple row matching the form's row structure
                                    const tr = document.createElement('tr');
                                    tr.innerHTML = `
                                        <td><input type="text" class="form-control form-control-sm word-input" name="_word" value="" placeholder="KATA" required></td>
                                        <td><input type="text" class="form-control form-control-sm clue-input" name="_clue" value="" placeholder="Petunjuk (opsional)"></td>
                                        <td><input type="number" min="0" class="form-control form-control-sm points-input" name="_points" value="" placeholder="0"></td>
                                        <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row-btn">Hapus</button></td>
                                    `;
                                    // bind remove
                                    tr.querySelector('.remove-row-btn').addEventListener('click', () => tr.remove());
                                    tbody.appendChild(tr);
                                }
                            });

                            // delegated generate preview button
                            container.addEventListener('click', function(e){
                                const genBtn = e.target.closest('#generate_preview_btn');
                                if (!genBtn) return;
                                e.preventDefault();
                                // serialize rows
                                const tbody = container.querySelector('#words_table tbody');
                                if (!tbody) return;
                                const rows = tbody.querySelectorAll('tr');
                                const words = [];
                                rows.forEach(r => {
                                    const word = r.querySelector('.word-input')?.value?.trim();
                                    if (!word) return;
                                    const clue = r.querySelector('.clue-input')?.value?.trim() || '';
                                    const pointsVal = r.querySelector('.points-input')?.value;
                                    const points = pointsVal === null || pointsVal === '' ? null : parseInt(pointsVal);
                                    const obj = { word: word.toUpperCase() };
                                    if (clue) obj.clue = clue;
                                    if (points !== null) obj.points = points;
                                    words.push(obj);
                                });

                                // read grid settings
                                const rowsVal = container.querySelector('input[name="grid_rows"]')?.value || 12;
                                const colsVal = container.querySelector('input[name="grid_cols"]')?.value || 12;
                                const directions = Array.from(container.querySelectorAll('input[name="directions[]"]:checked')).map(i => i.value);
                                const allowOverlap = container.querySelector('input[name="allow_overlap"]')?.checked ? 1 : 0;

                                // call backend generate endpoint
                                (async function(){
                                    try {
                                        const res = await fetch('{{ route('admin.cari_kata.generate') }}', {
                                            method: 'POST',
                                            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                            body: JSON.stringify({ words, grid_rows: rowsVal, grid_cols: colsVal, directions, allow_overlap: allowOverlap })
                                        });
                                        const data = await res.json();
                                        if (!res.ok) throw new Error(data.error || 'Generate failed');
                                        let html = '<div class="p-3" style="max-height:60vh;overflow:auto"><table class="table table-sm table-bordered text-center">';
                                        data.grid.forEach(row => { html += '<tr>' + row.map(c => `<td style="padding:4px">${c}</td>`).join('') + '</tr>'; });
                                        html += '</table></div>';
                                        Swal.fire({ title: 'Preview Grid', html, width: '80%', showCloseButton: true });
                                    } catch (err) {
                                        console.error('generate preview error (fallback)', err);
                                        Swal.fire({ icon: 'error', title: 'Gagal', text: err.message });
                                    }
                                })();
                            });
                        } catch (e) { console.warn('display_modal_info fallback binding failed', e); }
                    }
                })(0);
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
