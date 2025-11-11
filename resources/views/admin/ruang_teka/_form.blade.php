<form id="form_info">
    @csrf
    <input type="hidden" id="template_id" value="{{ $template->id ?? '' }}" />
    <div class="modal-header">
        <div class="card-title fs-3 fw-bold">{{ !empty($template) ? 'Ubah' : 'Tambah' }} Template Ruang Teka</div>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <x-metronic-select name="cerita_id" caption="Pilih Cerita" :options="$ceritas" :value="$template->cerita_id ?? ''" :viewtype="2" />
            </div>
            <div class="col-12 mt-3">
                <x-metronic-input name="title" caption="Judul Template" :value="$template->title ?? ''" />
            </div>

            <div class="col-4 mt-3">
                @php $difficulties = ['easy' => 'Easy','medium' => 'Medium','hard' => 'Hard']; @endphp
                <x-metronic-select name="difficulty" caption="Difficulty" :options="$difficulties" :value="$template->difficulty ?? ''" :viewtype="2" />
            </div>
            <div class="col-4 mt-3">
                <x-metronic-input type="number" name="points_default" caption="Points Default" :value="$template->points_default ?? 10" />
            </div>
            <div class="col-4 mt-3">
                <x-metronic-input type="number" name="time_limit" caption="Time Limit per Question (s)" :value="$template->time_limit ?? ''" />
            </div>

            <div class="col-6 mt-3">
                <label class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="shuffle_questions" value="1" {{ ($template->meta['shuffle_questions'] ?? false) ? 'checked' : '' }} />
                    <span class="form-check-label">Shuffle Questions</span>
                </label>
            </div>

            <div class="col-6 mt-3">
                <label class="form-label fw-bold">Poster (opsional)</label>
                <x-input type="file" name="file_poster" id="file_poster" />
            </div>

            <div class="col-12 mt-3">
                <x-metronic-textarea name="instructions" caption="Instruksi" :value="$template->instructions ?? ''" :rows="4" :viewtype="2" />
            </div>

            <div class="col-6 mt-3">
                <x-metronic-input type="number" name="grid_rows" id="grid_rows" caption="Grid Rows" :value="$template->grid_rows ?? 10" />
            </div>
            <div class="col-6 mt-3">
                <x-metronic-input type="number" name="grid_cols" id="grid_cols" caption="Grid Cols" :value="$template->grid_cols ?? 10" />
            </div>

            <div class="col-12 mt-3">
                <label class="form-label fw-bold">Clues</label>
                <small class="text-muted d-block mb-2">Tambahkan petunjuk per baris. Gunakan tombol <strong>Tambah Clue</strong> untuk menambah baris. Saat disimpan, sistem akan men-generate JSON sesuai format internal.</small>
                <small class="text-muted d-block mb-2">Kolom:
                    <strong>Arah</strong> = Pilih <em>Mendatar</em> (across) atau <em>Menurun</em> (down). 
                    <strong>No</strong> = Nomor petunjuk/soal. 
                    <strong>Baris</strong> = Index baris mulai dari 0 (mulai dari atas). 
                    <strong>Kolom</strong> = Index kolom mulai dari 0 (mulai dari kiri). 
                    <strong>Jawaban</strong> = Kata yang harus diisi. 
                    <strong>Petunjuk</strong> = Teks petunjuk untuk pemain.
                </small>

                <div class="table-responsive">
                <table class="table table-sm table-bordered w-100" id="clues_table">
                    <thead>
                        <tr>
                            <th style="width:10%">Arah</th>
                            <th style="width:8%">No</th>
                            <th style="width:8%">Baris</th>
                            <th style="width:8%">Kolom</th>
                            <th style="width:20%">Jawaban</th>
                            <th style="width:30%">Petunjuk</th>
                            <th style="width:6%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <button type="button" class="btn btn-primary btn-sm me-2" id="add_clue_btn">Tambah Clue</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clear_clues_btn">Bersihkan</button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="generate_preview_btn">Generate Preview</button>
                        <button type="button" class="btn btn-success btn-sm ms-2" id="generate_save_btn">Generate & Save</button>
                        <input type="hidden" id="ruang_teka_generate_save_url" value="{{ route('admin.ruang_teka.generate_save') }}" />
                    </div>
                </div>

                <input type="hidden" name="clues" id="clues_input" value='@json($template->clues ?? ["across"=>[],"down"=>[]])' />
                <input type="hidden" id="ruang_teka_generate_url" value="{{ route('admin.ruang_teka.generate') }}" />
                <input type="hidden" id="template_save_id" value="{{ $template->id ?? '' }}" />
                <div id="ruang_teka_preview" class="mt-3"></div>
            </div>

            <!-- Questions/content editor removed for Ruang Teka templates (not used) -->
        </div>
    </div>

    <div class="modal-footer d-flex justify-content-end py-6">
        <button type="button" onclick="init()" class="btn btn-light btn-active-light-primary me-2">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    // Initialize form widgets
    init_form_element();
    init_form({{ $template->id ?? "''" }});

    // Clues table editor + generate preview
    (function(){
    const cluesInput = document.getElementById('clues_input');
    const cluesTableBody = document.querySelector('#clues_table tbody');
    const previewDiv = document.getElementById('ruang_teka_preview');
    const generateUrlInput = document.getElementById('ruang_teka_generate_url');

        // initial clues from server
            const initialClues = {!! json_encode($template->clues ?? ['across'=>[],'down'=>[]]) !!};

        function createRow(item = {}){
            const tr = document.createElement('tr');
            const dir = item.dir ?? (item.hasOwnProperty('num') && item.hasOwnProperty('answer') ? (item.dir ?? 'across') : 'across');
            tr.innerHTML = `
                <td>
                    <select class="form-select form-select-sm clue-dir">
                        <option value="across" ${dir === 'across' ? 'selected' : ''}>Mendatar</option>
                        <option value="down" ${dir === 'down' ? 'selected' : ''}>Menurun</option>
                    </select>
                </td>
                <td><input type="number" min="0" placeholder="No" class="form-control form-control-sm clue-num" value="${ item.num ?? '' }"></td>
                <td><input type="number" min="0" placeholder="0" class="form-control form-control-sm clue-row" value="${ item.row ?? '' }"></td>
                <td><input type="number" min="0" placeholder="0" class="form-control form-control-sm clue-col" value="${ item.col ?? '' }"></td>
                <td><input type="text" placeholder="Jawaban" class="form-control form-control-sm clue-answer" value="${ (item.answer ?? '') }"></td>
                <td><input type="text" placeholder="Petunjuk" class="form-control form-control-sm clue-text" value="${ (item.clue ?? '') }"></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-clue-btn">Hapus</button></td>
            `;
            const rem = tr.querySelector('.remove-clue-btn');
            if (rem) rem.addEventListener('click', () => tr.remove());
            return tr;
        }

        function populateInitial(){
            cluesTableBody.innerHTML = '';
            // across: accept either an Array or an Object (assoc arrays may become objects)
            if (Array.isArray(initialClues.across)) {
                initialClues.across.forEach(i => {
                    const it = Object.assign({dir:'across'}, i);
                    cluesTableBody.appendChild(createRow(it));
                });
            } else if (initialClues.across && typeof initialClues.across === 'object') {
                Object.values(initialClues.across).forEach(i => {
                    const it = Object.assign({dir:'across'}, i);
                    cluesTableBody.appendChild(createRow(it));
                });
            }
            // down
            if (Array.isArray(initialClues.down)) {
                initialClues.down.forEach(i => {
                    const it = Object.assign({dir:'down'}, i);
                    cluesTableBody.appendChild(createRow(it));
                });
            } else if (initialClues.down && typeof initialClues.down === 'object') {
                Object.values(initialClues.down).forEach(i => {
                    const it = Object.assign({dir:'down'}, i);
                    cluesTableBody.appendChild(createRow(it));
                });
            }
            // if no rows, add one empty row
            if (cluesTableBody.children.length === 0) cluesTableBody.appendChild(createRow());
        }

        function getCluesFromTable(){
            const rows = cluesTableBody.querySelectorAll('tr');
            const result = {across:[], down:[]};
            rows.forEach(r => {
                const dir = r.querySelector('.clue-dir').value;
                const num = parseInt(r.querySelector('.clue-num').value) || null;
                const row = parseInt(r.querySelector('.clue-row').value) || 0;
                const col = parseInt(r.querySelector('.clue-col').value) || 0;
                const answer = (r.querySelector('.clue-answer').value || '').toString().trim();
                const clue = (r.querySelector('.clue-text').value || '').toString().trim();
                if (!answer) return; // skip empty answers
                const item = { num: num, row: row, col: col, answer: answer, clue: clue };
                if (dir === 'across') result.across.push(item);
                else result.down.push(item);
            });
            return result;
        }

        // delegated handlers attached to the modal container so clicks bubble reliably
        const rootDelegator = document.getElementById('modal_info_ruang_teka') || document;
        rootDelegator.addEventListener('click', function(ev) {
            const t = ev.target;
            if (!t) return;
            // add clue button
            if (t.id === 'add_clue_btn' || t.closest && t.closest('#add_clue_btn')) {
                // append to the current clues table body
                const localBody = document.querySelector('#clues_table tbody');
                if (localBody) localBody.appendChild(createRow());
                return;
            }
            // generate preview button (id or any child inside)
            if (t.id === 'generate_preview_btn' || t.closest && t.closest('#generate_preview_btn')) {
                (async function(){
                    const clues = getCluesFromTable();
                    const rows = parseInt(document.getElementById('grid_rows').value) || 10;
                    const cols = parseInt(document.getElementById('grid_cols').value) || 10;
                    const url = generateUrlInput ? generateUrlInput.value : '{{ route('admin.ruang_teka.generate') }}';
                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ clues, rows, cols })
                        });
                        const body = await res.json();
                        if (!res.ok) {
                            const err = body.errors ? body.errors.join('\n') : (body.message || 'Error generating preview');
                            alert(err);
                            return;
                        }
                        const localPreview = document.getElementById('ruang_teka_preview');
                        if (localPreview) localPreview.innerHTML = renderGridHTML(body.grid);
                    } catch (e) {
                        alert('Request failed: ' + e.message);
                    }
                })();
                return;
            }
            // generate & save
            if (t.id === 'generate_save_btn' || t.closest && t.closest('#generate_save_btn')) {
                (async function(){
                    const clues = getCluesFromTable();
                    const rows = parseInt(document.getElementById('grid_rows').value) || 10;
                    const cols = parseInt(document.getElementById('grid_cols').value) || 10;
                    const url = document.getElementById('ruang_teka_generate_save_url').value;
                    const templateId = document.getElementById('template_id').value || document.getElementById('template_save_id').value;
                    if (!templateId) { alert('Template ID missing. Save template first before generating.'); return; }
                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ template_id: templateId, clues, rows, cols })
                        });
                        const body = await res.json();
                        if (!res.ok) {
                            const err = body.errors ? body.errors.join('\n') : (body.message || 'Error generating and saving');
                            alert(err);
                            return;
                        }
                        alert('Grid generated and saved successfully');
                        // render preview
                        const localPreview = document.getElementById('ruang_teka_preview');
                        if (localPreview) localPreview.innerHTML = renderGridHTML(body.grid || body.grid);
                    } catch (e) {
                        alert('Request failed: ' + e.message);
                    }
                })();
                return;
            }
            // remove-clue button
            if (t.classList && t.classList.contains('remove-clue-btn')) {
                const row = t.closest('tr');
                if (row) row.remove();
                return;
            }
    }, false);

        // submit: serialize clues into hidden input (delegated)
        // Use capture=true so this handler runs before other submit handlers
        // (e.g. jQuery's submit handler) which may read FormData.
        document.addEventListener('submit', function(ev) {
            const form = ev.target;
            if (!form || form.id !== 'form_info') return;
            try {
                const input = document.getElementById('clues_input');
                if (input) input.value = JSON.stringify(getCluesFromTable());
            } catch (e) {}
        }, true);

        function renderGridHTML(grid) {
            if (!Array.isArray(grid)) return '<div class="text-danger">Invalid grid</div>';
            let html = '<div class="card p-3"><table class="table table-sm table-bordered m-0" style="border-collapse: collapse;">';
            for (let r=0;r<grid.length;r++){
                html += '<tr>';
                for (let c=0;c<grid[r].length;c++){
                    const cell = grid[r][c];
                    const display = cell === null ? '&nbsp;' : (cell === '' ? '&nbsp;' : cell);
                    html += '<td style="width:28px;height:28px;text-align:center;padding:2px;background:#fff;">'+display+'</td>';
                }
                html += '</tr>';
            }
            html += '</table></div>';
            return html;
        }

        populateInitial();
    })();
</script>
