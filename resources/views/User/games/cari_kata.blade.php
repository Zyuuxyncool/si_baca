<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Cari Kata - {{ data_get($cerita,'nama','Cari Kata') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body{background:#0f172a;color:#fff;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial}
        /* background / panel styles like ruang_teka for consistent look */
        .bg-photo{position:fixed;inset:0;background-size:cover;background-position:center;filter:blur(14px) brightness(.6);transform:scale(1.02);z-index:-2}
        .bg-overlay{position:fixed;inset:0;background:linear-gradient(90deg, rgba(0,0,0,0.35), rgba(0,0,0,0.35));z-index:-1}
        .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:3rem}
        .panel{width:100%;max-width:1100px;background:rgba(255,255,255,0.04);backdrop-filter:blur(6px);border-radius:var(--panel-radius,12px);overflow:hidden;box-shadow:0 12px 40px rgba(0,0,0,.5);padding:1.5rem}
    .grid{border-collapse:separate !important;border-spacing:6px !important;table-layout:fixed;width:100%}
    /* keep table semantics (td as table-cell) to avoid layout collapse on some browsers */
    .grid td{width:calc(100% / var(--cols));height:calc(min(60vh, 80vw) / var(--cols));border:1px solid rgba(255,255,255,0.12);box-sizing:border-box;padding:0;margin:0;text-align:center;font-weight:700;transition:background .14s ease,color .14s ease;cursor:pointer;user-select:none;touch-action:none;border-radius:6px;background:rgba(255,255,255,0.02)}
    .grid td .cell-inner{display:flex;align-items:center;justify-content:center;width:100%;height:100%;font-weight:700}
    .grid td.highlight{background:linear-gradient(90deg,#34d399,#86efac);color:#052e16}
    /* daftar kata header and clue styles */
    .card-body h6{color:#fff;margin-bottom:12px}
    .words .word{color:#f8fafc}
    .words .word .hint-text{display:block}
    /* strike-through only the hint/text, keep badge visible */
    .words .word.found .hint-text,
    .words .word.clue-done .hint-text { text-decoration:line-through; opacity:.6; color:#c7e1d0 }
    /* selection visuals */
    .grid td.selecting{background:linear-gradient(90deg,#bbf7d0,#bbf7d0);color:#052e16}
    .grid td.selected-correct{background:linear-gradient(90deg,#16a34a,#86efac);color:#052e16}
    .grid td.selected-wrong{background:linear-gradient(90deg,#fecaca,#ffb4b4);color:#5f1a1a}
    .words .word{padding:6px;margin-bottom:6px;border-radius:6px;background:rgba(255,255,255,0.02);cursor:pointer}
        @media(max-width:900px){
            .layout{flex-direction:column}
            .right{width:100%}
            .panel{padding:12px}
            .grid{border-spacing:4px}
            /* make cells slightly wider on mobile so touch is easier */
            .grid td{height:36px;width:40px;min-width:36px;max-width:48px}
            .grid td .cell-inner{font-size:14px}
        }
        @media(max-width:420px){
            .panel{padding:10px}
            .grid td{height:32px;width:38px;min-width:34px;max-width:44px}
            .grid td .cell-inner{font-size:13px}
        }
    </style>
</head>
<body>
@php
    $photoUrl = '';
    if (!empty($cerita) && !empty($cerita->photo)) {
        try { $photoUrl = Storage::url($cerita->photo); } catch (\Exception $e) { $photoUrl = ''; }
    }
@endphp
<div class="bg-photo" style="background-image: url('{{ $photoUrl }}')"></div>
<div class="bg-overlay"></div>
<div class="wrap">
    <div class="panel">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="mb-0">Cari Kata</h3>
            <small class="text-muted">{{ $selectedTemplate->title ?? 'Pilih template untuk mulai' }}</small>
        </div>
        <div>
            <a href="{{ route('user.games.menu_cari_kata.index', ['nama' => data_get($cerita,'nama')]) }}" class="btn btn-outline-light btn-sm">Kembali</a>
        </div>
    </div>

    @if(empty($selectedTemplate))
        <div class="alert alert-warning">Pilih template cari kata di menu sebelah kiri, atau hubungi admin jika permainan belum disiapkan.</div>
    @elseif(empty($selectedTemplate->grid))
        <div class="alert alert-warning">
            Template ditemukan tetapi permainan belum dapat dibuat secara otomatis.
            @php $wcount = is_array($selectedTemplate->words_list) ? count($selectedTemplate->words_list) : 0; @endphp
            <div>Jumlah kata pada template: <strong>{{ $wcount }}</strong>.</div>
            <div class="mt-2">Saran: Periksa pengaturan grid (Rows/Cols), arah yang diperbolehkan, atau tambahkan sedikit ruang (mis. grid 5x lebih besar) di panel admin lalu klik <em>Generate Preview</em>.</div>
            @if(!empty($selectedTemplate->words_list))
                <div class="mt-2"><strong>Beberapa kata:</strong>
                    <ul class="mb-0">
                        @foreach(array_slice($selectedTemplate->words_list,0,10) as $w)
                            @php $txt = is_array($w) ? ($w['word'] ?? '') : ($w ?? ''); @endphp
                            <li>{{ strtoupper($txt) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @else
        @php
            // Prefer an explicit saved grid on the template. Admin's "Generate & Save"
            // stores the grid and solution in template->meta; prefer those so the
            // user sees the exact same grid the admin saved.
            $grid = $selectedTemplate->grid ?? data_get($selectedTemplate, 'meta.grid', []);
            $solution = $selectedTemplate->solution ?? data_get($selectedTemplate, 'meta.solution', []);

            // Rows/Cols: prefer explicit template values, then meta, then infer from grid
            $rows = $selectedTemplate->grid_rows ?? data_get($selectedTemplate, 'meta.grid_rows', count($grid) ?: 12);
            $cols = $selectedTemplate->grid_cols ?? data_get($selectedTemplate, 'meta.grid_cols', (count($grid[0] ?? []) ?: 12));

            // Words list can live on words_list, content.words, or meta.words
            $words = $selectedTemplate->words_list ?? data_get($selectedTemplate, 'content.words', data_get($selectedTemplate, 'meta.words', []));
        @endphp

        <div class="d-flex layout gap-3">
            <div class="left flex-grow-1">
                <div id="gridWrap" style="overflow:auto;background:rgba(0,0,0,0.04);padding:12px;border-radius:8px">
                    <table id="wordGrid" class="grid" style="--cols:{{ $cols }};width:100%;border-collapse:collapse;table-layout:fixed">
                        @foreach($grid as $r)
                            <tr>
                                @foreach($r as $c)
                                    <td data-row="{{ $loop->parent->index }}" data-col="{{ $loop->index }}"><div class="cell-inner">{{ $c }}</div></td>
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button id="finishBtn" class="btn btn-primary btn-sm">Selesaikan & Simpan</button>
                    <button id="resetBtn" class="btn btn-outline-light btn-sm">Ulangi</button>
                </div>
            </div>

            <div class="right" style="width:320px;min-width:240px">
                <div class="card" style="background:transparent;border:0">
                    <div class="card-body p-0">
                        <h6>Daftar Petunjuk</h6>
                        <div class="words mt-2" id="wordsList">
                            @foreach($words as $w)
                                @php
                                    // words array may contain objects like {word, clue, points}
                                    $wordText = is_array($w) ? ($w['word'] ?? '') : ($w ?? '');
                                    $clueText = is_array($w) ? ($w['clue'] ?? '') : '';
                                    // fallback to showing the word when no clue provided
                                    $display = $clueText ? $clueText : $wordText;
                                @endphp
                                <div class="word d-flex align-items-center justify-content-between" data-word="{{ strtoupper($wordText) }}"> 
                                    <div class="hint-text">{{ strtoupper($display) }}</div>
                                    <div class="badge bg-success text-dark ms-2 d-none">✓</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>
</div>

<script>
// small interaction logic: clicking a word highlights its solution coordinates
const SOLUTIONS = {!! json_encode($solution ?? []) !!};
const FINISH_URL = {!! json_encode(route('user.cerita.ruang_teka.finish', ['nama' => data_get($cerita,'nama')])) !!};
const TEMPLATE_ID = {{ intval($selectedTemplate->id ?? 0) }};
const CERITA_ID = {{ intval($cerita->id ?? 0) }};
const POINTS_PER_WORD = {{ intval($selectedTemplate->points_default ?? 5) }};
// server-side auth id (fallback) — may be null if unauthenticated
const AUTH_ID = {!! json_encode(auth()->id()) !!};
// Feature flags
// Jika false maka interaksi sentuh (touch) untuk seleksi kata dinonaktifkan.
const ENABLE_TOUCH_SELECTION = false;

function clearHighlights(){ document.querySelectorAll('#wordGrid td').forEach(td=>td.classList.remove('highlight','selecting','selected-correct','selected-wrong')); }
function highlightCoords(coords, cls = 'highlight'){ clearHighlights(); coords.forEach(c=>{
    const selector = `#wordGrid td[data-row="${c.r}"][data-col="${c.c}"]`;
    const td = document.querySelector(selector); if(td) td.classList.add(cls);
}); }

    // map solutions by uppercase word and also build sequence-key map for fast lookup
const solMap = {};
const solSeqMap = {}; // key: 'r:c|r:c|...' => word
(SOLUTIONS || []).forEach(s=>{
    if (!s || !s.word || !Array.isArray(s.coords)) return;
    const word = (s.word||'').toUpperCase();
    solMap[word] = s.coords || [];
    const seq = (s.coords||[]).map(cc=>`${cc.r}:${cc.c}`).join('|');
    if (seq) solSeqMap[seq] = word;
    const rev = (s.coords||[]).slice().reverse().map(cc=>`${cc.r}:${cc.c}`).join('|');
    if (rev) solSeqMap[rev] = word;
});


document.addEventListener('DOMContentLoaded', ()=>{
    // build a simple grid array from the table for fallback searching
    const ROWS = {{ intval($rows ?? 0) }};
    const COLS = {{ intval($cols ?? 0) }};
    const gridRows = Array.from(document.querySelectorAll('#wordGrid tr'));
    const gridChars = gridRows.map(tr => Array.from(tr.children).map(td => (td.textContent||'').trim().toUpperCase()));

    // search word in all 8 directions as a fallback when solution coords are not present
    function normalizeCell(s){
        if (!s) return '';
        try { s = s.normalize('NFD').replace(/\p{M}/gu, ''); } catch(e) { /* fallback if normalize or unicode class unsupported */ }
        return (s||'').toString().replace(/[^A-Z0-9]/gi,'').toUpperCase();
    }

    function findWordInGrid(word){
        if (!word) return [];
        const w = word.toUpperCase().replace(/[^A-Z0-9]/g,'');
        if (!w) return [];
        const dirs = [[1,0],[-1,0],[0,1],[0,-1],[1,1],[1,-1],[-1,1],[-1,-1]];
        for(let r=0;r<gridChars.length;r++){
            for(let c=0;c<(gridChars[r]||[]).length;c++){
                const firstCell = normalizeCell(gridChars[r][c]);
                if (!firstCell) continue;
                if (w.indexOf(firstCell[0]) !== 0 && firstCell.indexOf(w[0]) !== 0 && firstCell.indexOf(w[0]) === -1) {
                    // fast skip if first letters clearly don't match
                    if (firstCell[0] !== w[0]) continue;
                }
                // try each direction
                for(const d of dirs){
                    const coords = [];
                    let rr = r, cc = c;
                    let iIndex = 0;
                    let ok = true;
                    while(iIndex < w.length){
                        if (rr < 0 || cc < 0 || rr >= gridChars.length || cc >= (gridChars[rr]||[]).length) { ok = false; break; }
                        const cellRaw = gridChars[rr][cc] || '';
                        const cell = normalizeCell(cellRaw);
                        if (!cell) { ok = false; break; }
                        // if the remaining word starts with the full cell string, consume it
                        const rem = w.substr(iIndex);
                        if (rem.indexOf(cell) === 0) {
                            coords.push({ r: rr, c: cc });
                            iIndex += cell.length;
                            rr += d[0]; cc += d[1];
                            continue;
                        }
                        // otherwise, try single-char match (some grids may contain multi-char cells or slight differences)
                        if (rem[0] === cell[0]) {
                            coords.push({ r: rr, c: cc });
                            iIndex += 1;
                            rr += d[0]; cc += d[1];
                            continue;
                        }
                        ok = false; break;
                    }
                    if (ok && iIndex === w.length) return coords;
                }
            }
        }
        return [];
    }

    // NOTE: clicking a clue should NOT reveal the answer or highlight the grid.
    // To avoid accidental reveals, clicking a clue will only scroll the grid into view
    // on small screens (help the user find the area) but will not show letters or highlights.
    document.querySelectorAll('.words .word').forEach(el => {
        el.addEventListener('click', (ev) => {
            ev.preventDefault();
            const wrap = document.getElementById('gridWrap');
            if (wrap && typeof wrap.scrollIntoView === 'function') {
                wrap.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            // do not reveal or highlight the solution here
            return false;
        });
    });

    // --- Drag/trace selection implementation ---
    let selecting = false;
    let selectedSeq = []; // array of {r,c}

    function cellKey(r,c){ return `${r}:${c}`; }
    function cellFromEl(el){ return { r: parseInt(el.dataset.row), c: parseInt(el.dataset.col) }; }

    function addCellToSelection(el){
        if (!el || !el.dataset) return;
        const r = parseInt(el.dataset.row); const c = parseInt(el.dataset.col);
        const key = cellKey(r,c);
        // avoid duplicates
        if (selectedSeq.length && selectedSeq[selectedSeq.length-1] && cellKey(selectedSeq[selectedSeq.length-1].r, selectedSeq[selectedSeq.length-1].c) === key) return;
        // if already exists earlier, trim back to that position (support backtracking)
        const idx = selectedSeq.findIndex(s => cellKey(s.r,s.c) === key);
        if (idx !== -1) selectedSeq = selectedSeq.slice(0, idx+1);
        else selectedSeq.push({r,c});
        el.classList.add('selecting');
    }

    function clearSelectionVisual(){
        document.querySelectorAll('#wordGrid td.selecting').forEach(td=>td.classList.remove('selecting'));
    }

    function finalizeSelection(){
        if (!selectedSeq.length) return;
        const seqKey = selectedSeq.map(s=>cellKey(s.r,s.c)).join('|');
        const matchedWord = solSeqMap[seqKey] || null;
            if (matchedWord) {
            // mark solution cells as permanently highlighted
            selectedSeq.forEach(s => {
                const td = document.querySelector(`#wordGrid td[data-row="${s.r}"][data-col="${s.c}"]`);
                if (td) td.classList.remove('selecting'), td.classList.add('selected-correct');
            });
            // mark word in list as found
            const wordEl = document.querySelector(`.words .word[data-word="${matchedWord}"]`);
            if (wordEl) {
                    wordEl.classList.add('found','clue-done');
                wordEl.querySelector('.badge')?.classList.remove('d-none');
            }
        } else {
            // flash wrong selection
            selectedSeq.forEach(s => {
                const td = document.querySelector(`#wordGrid td[data-row="${s.r}"][data-col="${s.c}"]`);
                if (td) td.classList.remove('selecting'), td.classList.add('selected-wrong');
            });
            setTimeout(()=>{ document.querySelectorAll('#wordGrid td.selected-wrong').forEach(td=>td.classList.remove('selected-wrong')); }, 500);
        }
        selectedSeq = [];
        clearSelectionVisual();
    }

    // mouse events
    document.querySelectorAll('#wordGrid td').forEach(td => {
        td.addEventListener('mousedown', (ev) => {
            ev.preventDefault(); selecting = true; selectedSeq = []; addCellToSelection(td);
        });
        td.addEventListener('mouseenter', (ev) => { if (selecting) addCellToSelection(td); });
        td.addEventListener('mouseup', (ev) => { if (selecting) { finalizeSelection(); selecting = false; } });
        // touch events (only if explicitly enabled)
        if (ENABLE_TOUCH_SELECTION) {
            td.addEventListener('touchstart', (ev) => { ev.preventDefault(); selecting = true; selectedSeq = []; addCellToSelection(td); });
            td.addEventListener('touchmove', (ev) => { 
                ev.preventDefault();
                const touch = ev.touches[0];
                let el = document.elementFromPoint(touch.clientX, touch.clientY);
                // ensure we have the TD element (could be a child text node/span)
                if (el && el.tagName !== 'TD' && el.closest) el = el.closest('td');
                if (el && el.tagName === 'TD') addCellToSelection(el);
            });
            td.addEventListener('touchend', (ev) => { if (selecting) { finalizeSelection(); selecting = false; } });
        }
    });

    // handle mouseup anywhere to finalize selection (in case mouseup outside cell)
    document.addEventListener('mouseup', ()=>{ if (selecting) { finalizeSelection(); selecting = false; } });

    // end drag/trace implementation

    document.getElementById('resetBtn')?.addEventListener('click', ()=>{
        clearHighlights(); document.querySelectorAll('.words .word').forEach(w=>{ w.classList.remove('found','clue-done'); w.querySelector('.badge')?.classList.add('d-none'); });
    });

    document.getElementById('finishBtn')?.addEventListener('click', async ()=>{
        // compute score: found words count * points per word
        const found = document.querySelectorAll('.words .word.found').length;
        const total = document.querySelectorAll('.words .word').length;
        const points = found * (POINTS_PER_WORD || 0);
        // confirm and post
        if (typeof Swal === 'undefined') {
            alert(`Selesai: ${found}/${total} - Nilai: ${points}`);
            return;
        }
        const { value: ok } = await Swal.fire({ title: 'Selesaikan permainan?', html:`<div style="text-align:left">Tuntas: <strong>${found}</strong>/${total}<br>Nilai: <strong>${points}</strong></div>`, showCancelButton:true, confirmButtonText:'Selesai & Simpan' });
        if (!ok) return;
        try {
            const res = await fetch(FINISH_URL, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ template_id: TEMPLATE_ID, cerita_id: CERITA_ID, score: points, user_id: AUTH_ID })
            });
            if (!res.ok) throw new Error('Server returned ' + res.status);
            const data = await res.json();
            await Swal.fire({ icon:'success', title:'Tersimpan', text:'Skor tersimpan.' });
            window.location.href = document.referrer || '/';
        } catch (err) {
            console.error(err);
            await Swal.fire({ icon:'error', title:'Gagal', text: err.message || 'Gagal menyimpan skor' });
        }
    });
});
</script>

<!-- load SweetAlert for finish dialog -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
