<form id="form_info">
    @csrf
    <input type="hidden" id="template_id" value="{{ $template->id ?? '' }}" />
    <div class="modal-header">
        <div class="card-title fs-3 fw-bold">{{ !empty($template) ? 'Ubah' : 'Tambah' }} Template Cari Kata</div>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <x-metronic-select name="cerita_id" caption="Pilih Cerita" :options="$ceritas" :value="$template->cerita_id ?? ''" :viewtype="2" />
            </div>
            <div class="col-12 mt-3">
                <x-metronic-input name="title" caption="Judul Template" :value="$template->title ?? ''" />
            </div>
            <div class="col-12 mt-3">
                <label class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="active" value="1" {{ ($template->active ?? false) ? 'checked' : '' }} />
                    <span class="form-check-label">Aktif / Tampilkan</span>
                </label>
            </div>
            <div class="col-6 mt-3">
                <x-metronic-input type="number" name="grid_rows" caption="Grid Rows" :value="$template->grid_rows ?? 12" />
            </div>
            <div class="col-6 mt-3">
                <x-metronic-input type="number" name="grid_cols" caption="Grid Cols" :value="$template->grid_cols ?? 12" />
            </div>
            <div class="col-12 mt-3">
                <label class="form-label fw-bold">Arah yang diperbolehkan</label>
                <div class="d-flex gap-3">
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="directions[]" value="horizontal" {{ (empty($template) || in_array('horizontal', $template->directions ?? ['horizontal'])) ? 'checked' : '' }} />
                        <span class="form-check-label">Horizontal</span>
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="directions[]" value="vertical" {{ in_array('vertical', $template->directions ?? []) ? 'checked' : '' }} />
                        <span class="form-check-label">Vertical</span>
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="directions[]" value="diagonal" {{ in_array('diagonal', $template->directions ?? []) ? 'checked' : '' }} />
                        <span class="form-check-label">Diagonal</span>
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="directions[]" value="reverse" {{ in_array('reverse', $template->directions ?? []) ? 'checked' : '' }} />
                        <span class="form-check-label">Reverse</span>
                    </label>
                </div>
            </div>

            <div class="col-6 mt-3">
                <label class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="allow_overlap" value="1" {{ ($template->allow_overlap ?? true) ? 'checked' : '' }} />
                    <span class="form-check-label">Izinkan overlap kata</span>
                </label>
            </div>

            <div class="col-6 mt-3">
                <x-metronic-input type="number" name="points_default" caption="Points per Word" :value="$template->points_default ?? 5" />
            </div>

            <div class="col-12 mt-3">
                <label class="form-label fw-bold">Poster (opsional)</label>
                <x-input type="file" name="file_poster" id="file_poster" />
            </div>

            <div class="col-12 mt-3">
                <x-metronic-textarea name="instructions" caption="Instruksi" :value="$template->instructions ?? ''" :rows="4" :viewtype="2" />
            </div>

            <div class="col-12 mt-3">
                <label class="form-label fw-bold">Daftar Kata</label>
                <small class="text-muted d-block mb-2">Isi daftar kata di bawah. Klik "Tambah Kata" untuk menambahkan baris. Tidak perlu menulis JSON.</small>

                <input type="hidden" name="content" id="content" value="{{ !empty($template->content) ? json_encode($template->content) : '' }}" />

                <table class="table table-sm table-bordered" id="words_table">
                    <thead>
                        <tr>
                            <th style="width:40%">Kata</th>
                            <th style="width:40%">Petunjuk (opsional)</th>
                            <th style="width:10%">Point</th>
                            <th style="width:10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- rows injected by JS -->
                    </tbody>
                </table>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" id="add_word_btn">Tambah Kata</button>
                    <button type="button" class="btn btn-light btn-sm" id="generate_preview_btn">Generate Preview</button>
                    <button type="button" class="btn btn-success btn-sm" id="generate_save_btn">Generate & Save</button>
                </div>
                <div id="saved_grid_preview" class="mt-3" style="display:none"></div>
            </div>
        </div>
    </div>

    <div class="modal-footer d-flex justify-content-end py-6">
        <button type="button" onclick="init()" class="btn btn-light btn-active-light-primary me-2">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    init_form_element();
    init_form({{ $template->id ?? "''" }});

    (function(){
        window.initCariKataForm = function(){
            console.debug('initCariKataForm called');
            const tableBody = document.querySelector('#words_table tbody');
            if (!tableBody) return;
            if (tableBody.dataset.cariInited) return;
            tableBody.dataset.cariInited = '1';

            const addBtn = document.getElementById('add_word_btn');
            const contentInput = document.getElementById('content');
            const generatePreviewBtn = document.getElementById('generate_preview_btn');

    let initialWords = [];
        try {
            // Primary source: server-rendered JSON injected by Blade
            const raw = {!! json_encode($template->content ?? null) !!};
            if (raw) {
                if (Array.isArray(raw)) initialWords = raw;
                else if (raw.words && Array.isArray(raw.words)) initialWords = raw.words;
            }
        } catch (e) { initialWords = []; }

    // saved meta (grid + solution) if editing existing template
    const SAVED_META = {!! json_encode($template->meta ?? null) !!};

        // Fallback: sometimes the modal is injected and the above raw may be null
        // (or stored as a string in the hidden input). Try to parse the hidden
        // `content` input value as JSON if no initial words were found.
        if (initialWords.length === 0) {
            try {
                const hidden = document.getElementById('content');
                if (hidden && hidden.value) {
                    const parsed = JSON.parse(hidden.value);
                    if (Array.isArray(parsed)) initialWords = parsed;
                    else if (parsed && Array.isArray(parsed.words)) initialWords = parsed.words;
                }
            } catch (e) { /* ignore parse errors */ }
        }

        function createRow(item = {}){
            const tr = document.createElement('tr');
            const wordVal = (item.word ?? '').toString().replace(/"/g, '&quot;');
            const clueVal = (item.clue ?? '').toString().replace(/"/g, '&quot;');
            const pointsVal = (item.points === undefined || item.points === null) ? '' : item.points;
            tr.innerHTML = `
                <td><input type="text" class="form-control form-control-sm word-input" name="_word" value="${wordVal}" placeholder="KATA" required></td>
                <td><input type="text" class="form-control form-control-sm clue-input" name="_clue" value="${clueVal}" placeholder="Petunjuk (opsional)"></td>
                <td><input type="number" min="0" class="form-control form-control-sm points-input" name="_points" value="${pointsVal}" placeholder="0"></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row-btn">Hapus</button></td>
            `;
            tr.querySelector('.remove-row-btn').addEventListener('click', () => tr.remove());
            return tr;
        }

        function renderInitial(){
            tableBody.innerHTML = '';
            if (initialWords.length === 0) {
                tableBody.appendChild(createRow());
                return;
            }
            initialWords.forEach(w => tableBody.appendChild(createRow(w)));
        }

            addBtn.addEventListener('click', function(){
                console.debug('add_word_btn clicked');
                tableBody.appendChild(createRow());
            });

        function serialize(){
            const rows = tableBody.querySelectorAll('tr');
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
            const payload = { words };
            contentInput.value = JSON.stringify(payload);
            return payload;
        }

    // mark which words were placed according to solution array
        function markPlacedWords(solution){
            const placed = new Set();
            (solution || []).forEach(s => { if (s.word) placed.add((s.word||'').toUpperCase()); });
            const rows = tableBody.querySelectorAll('tr');
            rows.forEach(r => {
                const input = r.querySelector('.word-input');
                const removeBtn = r.querySelector('.remove-row-btn');
                if (!input) return;
                const w = (input.value||'').toUpperCase();
                // ensure badge element exists
                let badge = r.querySelector('.word-placement-badge');
                if (!badge && removeBtn) {
                    badge = document.createElement('span');
                    badge.className = 'word-placement-badge ms-2';
                    badge.style.fontSize = '0.75rem';
                    removeBtn.parentNode.appendChild(badge);
                }
                // reset styles
                input.style.background = '';
                if (badge) badge.textContent = '';
                if (placed.has(w)) {
                    input.style.background = 'linear-gradient(90deg,#d1fae5,#bbf7d0)';
                    if (badge) { badge.textContent = '✓ placed'; badge.style.color = '#065f46'; }
                } else {
                    // only mark missing if input not empty
                    if (w) {
                        input.style.background = 'linear-gradient(90deg,#fee2e2,#fecaca)';
                        if (badge) { badge.textContent = '✕ missing'; badge.style.color = '#7f1d1d'; }
                    }
                }
            });
        }

        // render saved grid preview inline (reuse for initial load and after save)
        function renderSavedPreview(grid, solution){
            try {
                const coordSet = new Set();
                (solution || []).forEach(s => {
                    if (!s.coords || !Array.isArray(s.coords)) return;
                    s.coords.forEach(c => coordSet.add(`${c.r}:${c.c}`));
                });

                let html = '<div class="p-2" style="max-height:60vh;overflow:auto;background:rgba(0,0,0,0.02);padding:8px;border-radius:6px">';
                html += '<div class="d-flex justify-content-between align-items-center mb-2"><strong>Preview Grid (lokasi kata berwarna hijau)</strong><button id="clear_saved_preview" class="btn btn-sm btn-outline-secondary">Hapus Preview</button></div>';
                html += '<div style="overflow:auto"><table class="table table-sm table-bordered text-center" style="border-collapse:collapse">';
                grid.forEach((row, r) => {
                    html += '<tr>';
                    row.forEach((cell, c) => {
                        const key = `${r}:${c}`;
                        if (coordSet.has(key)) {
                            html += `<td style="padding:6px;background:linear-gradient(90deg,#16a34a,#86efac);color:#052e16;font-weight:700">${cell}</td>`;
                        } else {
                            html += `<td style="padding:6px">${cell}</td>`;
                        }
                    });
                    html += '</tr>';
                });
                html += '</table></div></div>';

                const previewEl = document.getElementById('saved_grid_preview');
                if (previewEl) {
                    previewEl.style.display = 'block';
                    previewEl.innerHTML = html;
                    const clr = document.getElementById('clear_saved_preview');
                    if (clr) clr.addEventListener('click', () => { previewEl.style.display = 'none'; previewEl.innerHTML = ''; });
                }
                try { markPlacedWords(solution || []); } catch(e) { console.warn('markPlacedWords failed', e); }
            } catch (err) {
                console.warn('renderSavedPreview failed', err);
            }
        }

        const formEl = document.getElementById('form_info');
        if (formEl) {
            formEl.addEventListener('submit', function(e){
                serialize();
            });

            const submitBtn = formEl.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.addEventListener('click', function(){
                    try { serialize(); } catch (err) { console.warn('serialize failed on submit click', err); }
                });
            }
        }

        // generate preview (AJAX) - calls admin route to generate grid
        generatePreviewBtn.addEventListener('click', async function(){
            console.debug('generate_preview_btn clicked');
            const payload = serialize();
            // read grid settings
            const rows = document.querySelector('input[name="grid_rows"]')?.value || 12;
            const cols = document.querySelector('input[name="grid_cols"]')?.value || 12;
            const directions = Array.from(document.querySelectorAll('input[name="directions[]"]:checked')).map(i => i.value);
            const allowOverlap = document.querySelector('input[name="allow_overlap"]')?.checked ? 1 : 0;

            try {
                console.debug('generate preview payload', payload);
                const res = await fetch('{{ route('admin.cari_kata.generate') }}', {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ words: payload.words, grid_rows: rows, grid_cols: cols, directions, allow_overlap: allowOverlap })
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.error || 'Generate failed');
                // show simple preview modal
                let html = '<div class="p-3" style="max-height:60vh;overflow:auto"><table class="table table-sm table-bordered text-center">';
                data.grid.forEach(row => { html += '<tr>' + row.map(c => `<td style="padding:4px">${c}</td>`).join('') + '</tr>'; });
                html += '</table></div>';
                Swal.fire({ title: 'Preview Grid', html, width: '80%', showCloseButton: true });
                try { markPlacedWords(data.solution || []); } catch(e) { console.warn('markPlacedWords preview failed', e); }
            } catch (err) {
                console.error('generate preview error', err);
                Swal.fire({ icon: 'error', title: 'Gagal', text: err.message });
            }
        });

        // generate + save (persist) handler
        generateSaveBtn = document.getElementById('generate_save_btn');
        if (generateSaveBtn) {
            generateSaveBtn.addEventListener('click', async function(){
                console.debug('generate_save_btn clicked');
                const payload = serialize();
                const rows = document.querySelector('input[name="grid_rows"]')?.value || 12;
                const cols = document.querySelector('input[name="grid_cols"]')?.value || 12;
                const directions = Array.from(document.querySelectorAll('input[name="directions[]"]:checked')).map(i => i.value);
                const allowOverlap = document.querySelector('input[name="allow_overlap"]')?.checked ? 1 : 0;
                const templateId = document.getElementById('template_id')?.value || '';

                if (!templateId) {
                    Swal.fire({ icon: 'warning', title: 'Template belum dibuat', text: 'Simpan template terlebih dahulu sebelum menyimpan grid.' });
                    return;
                }

                try {
                    const res = await fetch('{{ route('admin.cari_kata.generate_save') }}', {
                        method: 'POST',
                        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ template_id: templateId, words: payload.words, grid_rows: rows, grid_cols: cols, directions, allow_overlap: allowOverlap })
                    });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.error || 'Save failed');
                        // Show saved grid inline and highlight solution positions (no modal)
                        try {
                            // render inline preview and mark placed words
                            renderSavedPreview(data.grid || [], data.solution || []);
                            // small success toast
                            try { Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Grid tersimpan', showConfirmButton:false, timer:1800 }); } catch(e) {}
                        } catch (err) {
                            console.warn('Failed to render saved grid preview', err);
                            try { Swal.fire({ icon: 'success', title: 'Tersimpan', text: 'Grid berhasil disimpan ke template.' }); } catch(e) {}
                        }
                } catch (err) {
                    console.error('generate save error', err);
                    Swal.fire({ icon: 'error', title: 'Gagal', text: err.message });
                }
            });
        }

        // initial render
        renderInitial();
        // if editing an existing template with saved grid, render it
        try {
            if (SAVED_META && Array.isArray(SAVED_META.grid) && SAVED_META.grid.length) {
                renderSavedPreview(SAVED_META.grid, SAVED_META.solution || []);
            }
        } catch(e) { console.warn('initial saved preview render failed', e); }
        // end initCariKataForm
        };

        // call immediately (for direct-render cases)
        try { window.initCariKataForm(); } catch (e) {}
    })();
</script>
