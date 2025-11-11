<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ruang Teka - {{ data_get($cerita, 'nama', 'Ruang Teka') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        :root{--panel-radius:20px}
        html,body{height:100%}
        body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial;color:#fff}
        .bg-photo{position:fixed;inset:0;background-size:cover;background-position:center;filter:blur(14px) brightness(.6);transform:scale(1.02);z-index:-2}
        .bg-overlay{position:fixed;inset:0;background:linear-gradient(90deg, rgba(0,0,0,0.35), rgba(0,0,0,0.35));z-index:-1}

        .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:3rem}
        .panel{width:100%;max-width:1100px;background:rgba(255,255,255,0.04);backdrop-filter:blur(6px);border-radius:var(--panel-radius);overflow:hidden;box-shadow:0 12px 40px rgba(0,0,0,.5);padding:2rem}

        .header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem}
        .game-title{font-size:20px;font-weight:800;margin:0}
        .game-sub{color:#ddd;font-size:13px}

        .content{display:flex;gap:1.4rem;flex-wrap:wrap}
        .left{flex:1 1 620px;min-width:320px}
        .right{width:320px;min-width:260px}

        /* grid box */
        .grid-wrap{background:#f5f6f7; border-radius:12px; padding:12px;}
        .crossword{width:100%;max-width:100%;border-collapse:collapse;table-layout:fixed;background:#fff}
        /* cells scale based on number of columns (set via --cols) so the grid fits mobile screens */
        .crossword td{
            width:calc(100% / var(--cols));
            height:calc( (min(60vh, 80vw)) / var(--cols) );
            max-width:42px;max-height:42px;min-width:26px;min-height:26px;
            border:1px solid #d7d7d7;padding:0;position:relative;overflow:hidden;
        }
        .crossword td{position:relative}
        .cell-num{position:absolute;left:4px;top:2px;font-size:11px;background:#10b981;color:#fff;padding:2px 6px;border-radius:999px;font-weight:700;line-height:1}
    .clue-done{text-decoration:line-through;opacity:.6}
    .crossword input.correct{background:linear-gradient(90deg,#bbf7d0,#bbf7d0) !important}
    .crossword input.incorrect{background:#ffd7d7 !important}
        .crossword td.block{background:#333;border:1px solid #333}
        .crossword input{width:100%;height:100%;border:0;text-align:center;font-weight:700;font-size:clamp(12px,3.5vw,18px);line-height:1;text-transform:uppercase;background:transparent}
        .crossword input:focus{outline:2px solid #7dd3fc}

        .clues{background:rgba(0,0,0,0.04);padding:12px;border-radius:8px}
        .clue-list{max-height:56vh;overflow:auto;padding-right:6px}
        .clue{padding:6px;border-radius:6px;margin-bottom:6px;background:transparent}
        .clue:hover{background:rgba(255,255,255,0.02);cursor:pointer}
        .clue .num{display:inline-block;width:28px;height:22px;background:#10b981;color:#fff;border-radius:999px;text-align:center;margin-right:8px;font-weight:700}

        .controls{display:flex;gap:.6rem;flex-wrap:wrap;margin-top:12px}
        .btn-ghost{background:transparent;border:1px solid rgba(255,255,255,0.08);color:#fff}

        @media(max-width:900px){
            .content{flex-direction:column}
            .right{width:100%}
        }
        @media(max-width:600px){
            .panel{padding:1rem;border-radius:14px}
            .grid-wrap{padding:8px}
            .controls{gap:.4rem}
            .controls .btn{flex:1}
            .clue-list{max-height:40vh}
        }
        /* Mobile floating back button and responsive hide for desktop back link */
        .mobile-back-btn{display:none}
        .mobile-back-btn{position:fixed;left:12px;top:12px;width:44px;height:44px;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.6);color:#fff;z-index:60;box-shadow:0 6px 18px rgba(0,0,0,0.35);backdrop-filter:blur(6px)}
        .mobile-back-btn:active{transform:translateY(1px)}
        .desktop-back-link{display:inline-block}
        @media(max-width:600px){ .desktop-back-link{display:none} .mobile-back-btn{display:inline-flex} }
    </style>
</head>
<body>
@php
    // select template from list (id via query) or fall back to first
    $id = request()->get('id');
    $template = null;
    // if controller provided a selectedTemplate (generated in-memory), prefer it
    if (isset($selectedTemplate) && $selectedTemplate) {
        $template = $selectedTemplate;
    }
    if ($ruang_tekas instanceof \Illuminate\Pagination\LengthAwarePaginator) {
        $collection = $ruang_tekas->items();
    } else {
        $collection = is_iterable($ruang_tekas) ? $ruang_tekas : [];
    }
    if ($id) {
        foreach ($collection as $t) {
            if (isset($t->id) && $t->id == $id) { $template = $t; break; }
        }
    }
    if (!$template) {
        // try first
        if ($ruang_tekas instanceof \Illuminate\Pagination\LengthAwarePaginator) $template = $ruang_tekas->first();
        elseif (is_array($collection)) $template = $collection[0] ?? null;
        elseif (is_object($collection) && method_exists($collection, 'first')) $template = $collection->first();
    }

    $grid = $template->grid ?? [];
    $clues = $template->clues ?? ['across' => [], 'down' => []];
    $rows = $template->grid_rows ?? count($grid);
    $cols = $template->grid_cols ?? (count($grid[0] ?? []) ?: 10);
    // count playable (non-block) cells so we can show a friendly message when none exist
    $playableCount = 0;
    if (is_array($grid)) {
        foreach ($grid as $r) {
            if (!is_array($r)) continue;
            foreach ($r as $c) {
                if ($c !== null) $playableCount++;
            }
        }
    }
@endphp

@php
    $photoUrl = '';
    if (!empty($cerita) && !empty($cerita->photo)) {
        try {
            $photoUrl = Storage::url($cerita->photo);
        } catch (\Exception $e) {
            $photoUrl = '';
        }
    }
@endphp
<div class="bg-photo" style="background-image: url('{{ $photoUrl }}')"></div>
<div class="bg-overlay"></div>

<div class="wrap">
    <div class="panel">
        <div class="header">
            <div>
                <h2 class="game-title">Ruang Teka-Teki</h2>
                <div class="game-sub">{{ $template->title ?? 'Template' }} — Uji pemahamanmu setelah membaca.</div>
            </div>
            <div>
                <a href="{{ isset($cerita) ? route('user.games.menu_ruang_teka.index', ['nama' => data_get($cerita,'nama')]) : url()->previous() }}" class="btn btn-outline-light btn-sm desktop-back-link">Kembali</a>
            </div>
        </div>

        {{-- Floating back button for mobile screens --}}
        <a href="{{ isset($cerita) ? route('user.games.menu_ruang_teka.index', ['nama' => data_get($cerita,'nama')]) : url()->previous() }}" class="mobile-back-btn" aria-label="Kembali ke Cerita">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>

        <div class="content">
            <div class="left">
                <div class="grid-wrap">
                    @if($playableCount <= 0)
                        <div class="p-4 text-center" style="min-height:40vh;display:flex;align-items:center;justify-content:center;flex-direction:column">
                            <div style="font-size:18px;font-weight:700;margin-bottom:8px">Template belum berisi sel huruf</div>
                            <div style="max-width:420px;color:#ccc">Halaman ini menampilkan kotak hitam karena template teka-teki tidak memiliki sel huruf (semua sel bertipe blok). Untuk memainkan, edit template di panel admin dan pastikan grid berisi sel kosong ('') untuk huruf, bukan null.</div>
                            <div style="margin-top:12px">
                                <a class="btn btn-outline-light btn-sm" href="{{ url()->previous() }}">Kembali</a>
                            </div>
                        </div>
                    @else
                        <div id="gridContainer" style="overflow:auto;">
                            <!-- grid will be rendered here -->
                        </div>
                    @endif
                    <div class="controls">
                        <button id="finishBtn" class="btn btn-primary btn-sm">Selesaikan Permainan</button>
                        <button id="resetBtn" class="btn btn-outline-light btn-sm">Ulangi</button>
                    </div>
                </div>
            </div>

            <div class="right">
                <div class="clues">
                    <h5 style="margin-top:0">Petunjuk</h5>
                    <div class="clue-list">
                        <h6 style="margin:6px 0">Mendatar</h6>
                        @foreach(($clues['across'] ?? []) as $item)
                            <div class="clue" data-dir="across" data-num="{{ $item['num'] ?? '' }}" data-row="{{ $item['row'] ?? 0 }}" data-col="{{ $item['col'] ?? 0 }}">
                                <span class="num">{{ $item['num'] ?? '' }}</span>
                                <div style="display:inline-block;vertical-align:middle;font-weight:700;margin-left:8px;color:#fff">{{ strtoupper($item['clue'] ?? '') }}</div>
                            </div>
                        @endforeach

                        <h6 style="margin:6px 0">Menurun</h6>
                        @foreach(($clues['down'] ?? []) as $item)
                            <div class="clue" data-dir="down" data-num="{{ $item['num'] ?? '' }}" data-row="{{ $item['row'] ?? 0 }}" data-col="{{ $item['col'] ?? 0 }}">
                                <span class="num">{{ $item['num'] ?? '' }}</span>
                                <div style="display:inline-block;vertical-align:middle;font-weight:700;margin-left:8px;color:#fff">{{ strtoupper($item['clue'] ?? '') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// include SweetAlert2
const _saScript = document.createElement('script');
_saScript.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
document.head.appendChild(_saScript);

const TEMPLATE_ID = {{ intval($template->id ?? 0) }};
const CERITA_ID = {{ intval($cerita->id ?? 0) }};
const POINTS_PER_CLUE = {{ intval($template->points_default ?? 10) }};

    // Prepare template data for JS
    const GRID = {!! json_encode($grid) !!};
    const ROWS = {{ intval($rows) }};
    const COLS = {{ intval($cols) }};
    const CLUES = {!! json_encode($clues) !!};

    function buildGrid() {
        const container = document.getElementById('gridContainer');
        container.innerHTML = '';
        const table = document.createElement('table');
        table.className = 'crossword';
        for (let r = 0; r < ROWS; r++) {
            const tr = document.createElement('tr');
            for (let c = 0; c < COLS; c++) {
                const td = document.createElement('td');
                // Treat a cell as a block only when it's explicitly null or undefined.
                // Some templates may use empty string ('') for an empty letter cell —
                // earlier check used a truthy test which made '' become a block (black).
                const hasRow = typeof GRID[r] !== 'undefined' && GRID[r] !== null;
                const hasCell = hasRow && (typeof GRID[r][c] !== 'undefined') && GRID[r][c] !== null;
                const ch = hasCell ? GRID[r][c] : null;
                if (ch === null) {
                    td.className = 'block';
                } else {
                    // determine if this cell is the start of any clue to show number
                    const num = getCellNumber(r,c);
                    if (num !== null) {
                        const span = document.createElement('span');
                        span.className = 'cell-num';
                        span.textContent = String(num);
                        td.appendChild(span);
                    }

                    const input = document.createElement('input');
                    input.maxLength = 1;
                    input.dataset.letter = ch.toUpperCase();
                    input.dataset.row = r;
                    input.dataset.col = c;
                    input.addEventListener('input', onInput);
                    input.addEventListener('keydown', onKeyDown);
                    td.appendChild(input);
                }
                tr.appendChild(td);
            }
            table.appendChild(tr);
        }
    // expose number of columns to CSS so cells can scale responsively
    table.style.setProperty('--cols', COLS);
    container.appendChild(table);
    }

    function getCellNumber(r, c) {
        // search across clues first then down; prefer the first matching num
        if (Array.isArray(CLUES.across)) {
            for (let i = 0; i < CLUES.across.length; i++) {
                const it = CLUES.across[i];
                if ((parseInt(it.row) || 0) === r && (parseInt(it.col) || 0) === c) return it.num ?? null;
            }
        }
        if (Array.isArray(CLUES.down)) {
            for (let i = 0; i < CLUES.down.length; i++) {
                const it = CLUES.down[i];
                if ((parseInt(it.row) || 0) === r && (parseInt(it.col) || 0) === c) return it.num ?? null;
            }
        }
        return null;
    }

    function onInput(e){
        const val = e.target.value.toUpperCase();
        e.target.value = val;
        // validate cell and related clues, then move to next cell
        const r = parseInt(e.target.dataset.row);
        const c = parseInt(e.target.dataset.col);
        validateCell(e.target);
        validateCluesAround(r, c);
        focusNext(r, c);
    }

    function onKeyDown(e){
        const key = e.key;
        const r = parseInt(e.target.dataset.row);
        const c = parseInt(e.target.dataset.col);
        if (key === 'Backspace') {
            if (e.target.value === '') focusPrev(r, c);
        } else if (key === 'ArrowRight') { e.preventDefault(); focusNext(r,c); }
        else if (key === 'ArrowLeft') { e.preventDefault(); focusPrev(r,c); }
        else if (key === 'ArrowUp') { e.preventDefault(); focusDir(r,c,-1,0); }
        else if (key === 'ArrowDown') { e.preventDefault(); focusDir(r,c,1,0); }
    }

    function focusNext(r,c){
        for (let cc = c+1; cc < COLS; cc++){
            const inp = getInput(r,cc); if (inp) { inp.focus(); return; }
        }
    }
    function focusPrev(r,c){
        for (let cc = c-1; cc >=0; cc--){ const inp = getInput(r,cc); if (inp) { inp.focus(); return; } }
    }
    function focusDir(r,c,dr,dc){
        let rr = r+dr, cc = c+dc; while(rr>=0 && rr<ROWS && cc>=0 && cc<COLS){ const inp = getInput(rr,cc); if(inp){ inp.focus(); return; } rr+=dr; cc+=dc; }
    }
    function getInput(r,c){ const table = document.querySelector('.crossword'); if(!table) return null; const row = table.rows[r]; if(!row) return null; const cell = row.cells[c]; if(!cell) return null; return cell.querySelector('input'); }

    function checkAnswers(){
        // legacy: manual check not used in auto-check mode
        const inputs = document.querySelectorAll('.crossword input');
        inputs.forEach(inp => validateCell(inp));
    }

    function revealAnswers(){
        const inputs = document.querySelectorAll('.crossword input');
        inputs.forEach(inp => { inp.value = inp.dataset.letter || ''; inp.style.background = 'transparent'; });
        // after reveal, mark all as correct and mark clues done
        document.querySelectorAll('.crossword input').forEach(i => i.classList.add('correct'));
        // mark all clues done
        document.querySelectorAll('.clue').forEach(c => c.classList.add('clue-done'));
    }

    function resetGrid(){
        const inputs = document.querySelectorAll('.crossword input');
        inputs.forEach(inp => { inp.value = ''; inp.style.background = 'transparent'; });
        // focus first
        const first = document.querySelector('.crossword input'); if(first) first.focus();
    }

    // clue click focuses the starting cell
    function initClueClicks(){
        document.querySelectorAll('.clue').forEach(el => {
            el.addEventListener('click', () => {
                const r = parseInt(el.dataset.row);
                const c = parseInt(el.dataset.col);
                const inp = getInput(r,c); if(inp) { inp.focus(); highlightClue(el); }
            });
        });
    }

    function validateCell(inp){
        const expected = (inp.dataset.letter || '').toUpperCase();
        const val = (inp.value || '').toUpperCase();
        inp.classList.remove('correct','incorrect');
        if (val === '') {
            // clear
            return;
        }
        if (val === expected) {
            inp.classList.add('correct');
        } else {
            inp.classList.add('incorrect');
        }
    }

    function validateCluesAround(r,c){
        // find any clue that includes this cell (across or down) and validate it
        ['across','down'].forEach(dir => {
            const list = Array.isArray(CLUES[dir]) ? CLUES[dir] : Object.values(CLUES[dir] || {});
            list.forEach(cl => {
                const rr = parseInt(cl.row) || 0;
                const cc = parseInt(cl.col) || 0;
                const len = (cl.answer || '').length || 0;
                if (dir === 'across') {
                    if (r === rr && c >= cc && c < cc + len) checkClueSolved(cl, dir);
                } else {
                    if (c === cc && r >= rr && r < rr + len) checkClueSolved(cl, dir);
                }
            });
        });
    }

    function checkClueSolved(cl, dir){
        const r = parseInt(cl.row) || 0;
        const c = parseInt(cl.col) || 0;
        const answer = (cl.answer || '').toUpperCase();
        let solved = true;
        for (let i = 0; i < answer.length; i++){
            const rr = r + (dir === 'down' ? i : 0);
            const cc = c + (dir === 'across' ? i : 0);
            const inp = getInput(rr, cc);
            const val = inp ? (inp.value || '').toUpperCase() : '';
            if (val !== answer.charAt(i)) { solved = false; break; }
        }
        // find clue element by num
        const num = cl.num;
        const clueEl = document.querySelector('.clue[data-num="' + num + '"]');
        if (solved) {
            if (clueEl) clueEl.classList.add('clue-done');
        } else {
            if (clueEl) clueEl.classList.remove('clue-done');
        }
    }

    function highlightClue(el){
        // briefly add focus style to clue
        document.querySelectorAll('.clue').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        setTimeout(()=>el.classList.remove('active'), 1200);
    }

    document.addEventListener('DOMContentLoaded', function(){
        buildGrid();
        initClueClicks();

        // Wire the reset button
        const reset = document.getElementById('resetBtn');
        if (reset) reset.addEventListener('click', resetGrid);

        // Finish / submit flow
        const finishBtn = document.getElementById('finishBtn');
        const FINISH_URL = {!! json_encode(route('user.cerita.ruang_teka.finish', ['nama' => data_get($cerita,'nama')])) !!};
        const REDIRECT_URL = {!! json_encode(route('user.games.menu_ruang_teka.index', ['nama' => data_get($cerita,'nama')])) !!};

        function calculatePoints(){
            const done = document.querySelectorAll('.clue.clue-done').length;
            const total = (Array.isArray(CLUES.across) ? CLUES.across.length : 0) + (Array.isArray(CLUES.down) ? CLUES.down.length : 0);
            const points = done * (parseInt(POINTS_PER_CLUE) || 0);
            return { done, total, points };
        }

        async function submitScore(points, solved){
            try {
                const payload = { template_id: TEMPLATE_ID, cerita_id: CERITA_ID, score: points, solved_count: solved };
                const res = await fetch(FINISH_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });
                if (!res.ok) throw new Error('Server returned ' + res.status);
                return await res.json();
            } catch (err) {
                console.error(err);
                throw err;
            }
        }

        if (finishBtn) {
            finishBtn.addEventListener('click', async () => {
                const stats = calculatePoints();
                // show confirmation with SweetAlert2 (loaded earlier)
                const { value: confirm } = await Swal.fire({
                    title: 'Selesaikan Permainan?',
                    html: `<div style="text-align:left">Tuntas: <strong>${stats.done}</strong> / ${stats.total}<br>Nilai: <strong>${stats.points}</strong></div>`,
                    showCancelButton: true,
                    confirmButtonText: 'Selesai & Simpan',
                    cancelButtonText: 'Batal',
                    allowOutsideClick: false
                });

                if (!confirm) return;

                finishBtn.disabled = true;
                try {
                    const json = await submitScore(stats.points, stats.done);
                    await Swal.fire({ icon: 'success', title: 'Tersimpan', text: 'Skor permainan telah disimpan.' });
                    // redirect back to menu or provided redirect
                    window.location.href = json.redirect || REDIRECT_URL || document.referrer || '/';
                } catch (err) {
                    await Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat menyimpan skor, coba lagi.' });
                    finishBtn.disabled = false;
                }
            });
        }

        // focus first input
        const first = document.querySelector('.crossword input'); if(first) first.focus();
    });
</script>
</body>
</html>
