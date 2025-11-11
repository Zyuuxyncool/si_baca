<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CeritaService;
use App\Services\GameService;
use App\Services\RuangTekaService;
use App\Services\CariKataService;
use App\Models\GameScore;

class GameController extends Controller
{
    protected $gameService, $ceritaService, $ruangTekaService, $cariKataService;

    public function __construct()
    {
        $this->gameService = new GameService();
        $this->ceritaService = new CeritaService();
        $this->ruangTekaService = new RuangTekaService();
        $this->cariKataService = new CariKataService();
    }

    public function menuCariKata(Request $request, $nama)
    {
        $cerita = $this->ceritaService->find($nama, 'nama');
        $cari_katas = $this->cariKataService->search(['cerita_id' => $cerita->id]);
        // attach current user's latest score per template (robust: check both template id columns)
        try {
            $userId = auth()->id();
            if ($userId) {
                $ids = [];
                foreach ($cari_katas as $t) { $ids[] = $t->id; }
                if (!empty($ids)) {
                    $scores = GameScore::where('user_id', $userId)
                        ->where('cerita_id', $cerita->id ?? null)
                        ->where(function($q) use ($ids) {
                            $q->whereIn('game_template_id2', $ids)->orWhereIn('game_template_id', $ids);
                        })
                        ->get();
                    $map = [];
                    foreach ($scores as $s) {
                        if (!empty($s->game_template_id2) && in_array($s->game_template_id2, $ids)) $map[$s->game_template_id2] = $s;
                        if (!empty($s->game_template_id) && in_array($s->game_template_id, $ids)) $map[$s->game_template_id] = $s;
                    }
                    foreach ($cari_katas as $t) {
                        $s = $map[$t->id] ?? null;
                        $t->score = $s->score ?? null;
                        $t->last_played = $s ? $s->updated_at : null;
                    }
                }
            }
        } catch (\Throwable $__e) { \Log::warning('menuCariKata score attach failed: '.$__e->getMessage()); }

        return view('user.games.menu_cari_kata', compact('cari_katas', 'cerita'));
    }

    public function menuRuangTeka(Request $request, $nama)
    {
        $cerita = $this->ceritaService->find($nama, 'nama');
        $ruang_tekas = $this->ruangTekaService->search(['cerita_id' => $cerita->id]);
        // attach current user's latest score per template (robust: check both template id columns)
        try {
            $userId = auth()->id();
            if ($userId) {
                $ids = [];
                foreach ($ruang_tekas as $t) { $ids[] = $t->id; }
                if (!empty($ids)) {
                    $scores = GameScore::where('user_id', $userId)
                        ->where('cerita_id', $cerita->id ?? null)
                        ->where(function($q) use ($ids) {
                            $q->whereIn('game_template_id', $ids)->orWhereIn('game_template_id2', $ids);
                        })
                        ->get();
                    $map = [];
                    foreach ($scores as $s) {
                        if (!empty($s->game_template_id) && in_array($s->game_template_id, $ids)) $map[$s->game_template_id] = $s;
                        if (!empty($s->game_template_id2) && in_array($s->game_template_id2, $ids)) $map[$s->game_template_id2] = $s;
                    }
                    foreach ($ruang_tekas as $t) {
                        $s = $map[$t->id] ?? null;
                        $t->score = $s->score ?? null;
                        $t->last_played = $s ? $s->updated_at : null;
                    }
                }
            }
        } catch (\Throwable $__e) { \Log::warning('menuRuangTeka score attach failed: '.$__e->getMessage()); }

        return view('user.games.menu_ruang_teka', compact('ruang_tekas', 'cerita'));
    }


    public function cariKata(Request $request, $nama)
    {
        // provide cerita context and allow selecting a template via ?id=
        $cerita = $this->ceritaService->find($nama, 'nama');
        $cari_katas = $this->cariKataService->search(array_merge($request->all(), ['cerita_id' => $cerita->id ?? null]));

        $selectedTemplate = null;
        $id = $request->get('id');
        if ($id) {
            $selectedTemplate = $this->cariKataService->find($id);
            if ($selectedTemplate) {
                // If admin previously generated & saved a grid into template->meta, prefer that
                $meta = $selectedTemplate->meta ?? null;
                if (is_array($meta) && !empty($meta['grid'])) {
                    $selectedTemplate->grid = $meta['grid'];
                    if (!empty($meta['solution'])) $selectedTemplate->solution = $meta['solution'];
                    // words_list prefer content.words, fallback to meta (if any)
                    $selectedTemplate->words_list = $selectedTemplate->content['words'] ?? ($meta['words'] ?? []);
                    // ensure rows/cols reflect saved grid if provided
                    $selectedTemplate->grid_rows = $selectedTemplate->grid_rows ?? count($meta['grid']);
                    $selectedTemplate->grid_cols = $selectedTemplate->grid_cols ?? count($meta['grid'][0] ?? []);
                }
                // attempt to generate grid from template content.words if template has no precomputed grid
                $content = $selectedTemplate->content ?? null;
                $words = [];
                if (is_array($content) && !empty($content['words'])) $words = array_map(function($w){ return strtoupper($w['word'] ?? ($w)); }, $content['words']);
                // fallback when content is just an array of strings
                if (empty($words) && is_array($content)) {
                    foreach ($content as $k => $v) {
                        if (is_array($v) && isset($v['word'])) $words[] = strtoupper($v['word']);
                    }
                }

                $rows = $selectedTemplate->grid_rows ?? intval($request->get('rows', 12));
                $cols = $selectedTemplate->grid_cols ?? intval($request->get('cols', 12));
                $directions = $selectedTemplate->directions ?? ['horizontal','vertical','diagonal','reverse'];
                $allowOverlap = $selectedTemplate->allow_overlap ?? true;

                // Only attempt to generate a grid when there is no saved/preset grid.
                // Admin-saved grids are already assigned to $selectedTemplate->grid above,
                // so skip generation to preserve the admin preview/persisted layout.
                if (!empty($words) && empty($selectedTemplate->grid)) {
                    // use a deterministic seed so generated grid is stable across page refreshes
                    $seed = $selectedTemplate->id ?? crc32(json_encode($words));
                    $res = $this->cariKataService->generateGrid($words, $rows, $cols, $directions, $allowOverlap, $seed);
                    if (!empty($res) && isset($res['grid'])) {
                        // attach generated grid and solution for view rendering (in-memory only)
                        $selectedTemplate->grid = $res['grid'];
                        $selectedTemplate->solution = $res['solution'];
                        $selectedTemplate->words_list = $content['words'] ?? array_map(function($w){ return ['word'=> $w]; }, $words);
                    } else {
                        // Try a fallback with larger grid to increase chance of placement
                        $tryRows = max(12, intval($rows) + 5);
                        $tryCols = max(12, intval($cols) + 5);
                        $res2 = $this->cariKataService->generateGrid($words, $tryRows, $tryCols, $directions, $allowOverlap, $seed);
                        if (!empty($res2) && isset($res2['grid'])) {
                            $selectedTemplate->grid = $res2['grid'];
                            $selectedTemplate->solution = $res2['solution'];
                            $selectedTemplate->grid_rows = $tryRows;
                            $selectedTemplate->grid_cols = $tryCols;
                            $selectedTemplate->words_list = $content['words'] ?? array_map(function($w){ return ['word'=> $w]; }, $words);
                        } else {
                            // keep a marker so view can show helpful diagnostics
                            $selectedTemplate->generation_failed = true;
                            $selectedTemplate->words_list = $content['words'] ?? array_map(function($w){ return ['word'=> $w]; }, $words);
                        }
                    }
                }
            }
        }

        return view('user.games.cari_kata', compact('cari_katas', 'cerita', 'selectedTemplate'));
    }

    public function ruangTeka(Request $request, $nama)
    {
        // find cerita by nama so we can provide context (photo, nama) to the view
        $cerita = $this->ceritaService->find($nama, 'nama');
        // limit templates to this cerita
        $ruang_tekas = $this->ruangTekaService->search(array_merge($request->all(), ['cerita_id' => $cerita->id]));

        $selectedTemplate = null;
        $id = $request->get('id');
        if ($id) {
            $selectedTemplate = $this->ruangTekaService->find($id);
            // if selected template is present but has no playable cells, try to generate grid from clues (in-memory)
            if ($selectedTemplate) {
                $grid = $selectedTemplate->grid ?? [];
                $playable = 0;
                if (is_array($grid)) {
                    foreach ($grid as $r) {
                        if (!is_array($r)) continue;
                        foreach ($r as $c) { if ($c !== null) $playable++; }
                    }
                }
                if ($playable <= 0) {
                    $clues = $selectedTemplate->clues ?? ['across'=>[], 'down'=>[]];
                    $rows = $selectedTemplate->grid_rows ?? intval(request()->get('rows', 10));
                    $cols = $selectedTemplate->grid_cols ?? intval(request()->get('cols', 10));
                    if (!empty($clues) && (count($clues['across'] ?? []) || count($clues['down'] ?? []))) {
                        $res = $this->ruangTekaService->generateGridFromClues($clues, $rows, $cols);
                        if (empty($res['errors'])) {
                            // set generated grid in-memory so view can render it (do not persist automatically)
                            $selectedTemplate->grid = $res['grid'];
                            $selectedTemplate->grid_rows = $rows;
                            $selectedTemplate->grid_cols = $cols;
                        }
                    }
                }
            }
        }

        return view('user.games.ruang_teka', compact('ruang_tekas', 'cerita', 'selectedTemplate'));
    }

    /**
     * Finish game and store score
     */
    public function finish(Request $request, $nama)
    {
        try {
            $cerita = $this->ceritaService->find($nama, 'nama');
            $data = $request->all();
            // Debug: log incoming data and server auth id to diagnose missing user_id issues
            try { \Log::info('GameController::finish called', ['auth_id' => auth()->id(), 'incoming' => $data]); } catch (\Throwable $__e) { /* ignore logging errors */ }
            $score = intval($data['score'] ?? 0);
            $templateId = $data['template_id'] ?? null;

            $userId = auth()->id() ?? null;

            // Decide which template column to use.
            // Some templates are of type RuangTeka (crossword) and some are CariKata (word-search).
            // We populate `game_template_id` for RuangTeka templates and `game_template_id2` for CariKata templates.
            $game_template_id = null;
            $game_template_id2 = null;
            if (!empty($templateId)) {
                // try CariKata first
                try {
                    $cari = $this->cariKataService->find($templateId);
                } catch (\Throwable $e) { $cari = null; }
                if (!empty($cari)) {
                    $game_template_id2 = $templateId;
                } else {
                    // fallback to ruang teka
                    try {
                        $ruang = $this->ruangTekaService->find($templateId);
                    } catch (\Throwable $e) { $ruang = null; }
                    if (!empty($ruang)) {
                        $game_template_id = $templateId;
                    } else {
                        // Unknown template type: preserve backward compatibility by using game_template_id
                        $game_template_id = $templateId;
                    }
                }
            }

            $payload = [
                'user_id' => $userId,
                'cerita_id' => $cerita->id ?? null,
                'game_template_id' => $game_template_id,
                'game_template_id2' => $game_template_id2,
                'score' => $score,
            ];

                // Delegate update-or-create semantics to the service layer
                $saved = $this->gameService->store($payload);

            return response()->json(['saved' => true, 'id' => $saved->id ?? null]);
        } catch (\Throwable $e) {
            // Log the exception and return a JSON error for client-side debugging
            \Log::error('Failed to save game score: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['saved' => false, 'error' => $e->getMessage()], 500);
        }
    }
}