<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CariKataService;
use App\Services\DocumentService;

class CariKataTemplateController extends Controller
{
    protected $cariKataService;

    public function __construct()
    {
        $this->cariKataService = new CariKataService();
    }

    public function index()
    {
        return view('admin.cari_kata.index');
    }

    public function search(Request $request)
    {
        $templates = $this->cariKataService->search($request->all());
        return view('admin.cari_kata._table', ['templates' => $templates]);
    }

    public function create()
    {
        $ceritas = $this->cariKataService->ceritaOptions();
        return view('admin.cari_kata._form', compact('ceritas'));
    }

    public function store(Request $request)
    {
        $filename = DocumentService::save_file($request, 'file_poster', 'public/images/cari_kata');
        if ($filename !== '') $request->merge(['poster' => $filename]);

        $data = $request->all();
        if (!empty($data['content']) && is_string($data['content'])) {
            $data['content'] = json_decode($data['content'], true);
        }
        $template = $this->cariKataService->store($data);
        return $template;
    }

    public function edit($id)
    {
        $template = $this->cariKataService->find($id);
        $ceritas = $this->cariKataService->ceritaOptions();
        return view('admin.cari_kata._form', compact('template','ceritas'));
    }

    public function update(Request $request, $id)
    {
        $filename = DocumentService::save_file($request, 'file_poster', 'public/images/cari_kata');
        if ($filename !== '') $request->merge(['poster' => $filename]);

        $data = $request->all();
        if (!empty($data['content']) && is_string($data['content'])) {
            $data['content'] = json_decode($data['content'], true);
        }
        $template = $this->cariKataService->update($id, $data);
        return $template;
    }

    public function destroy($id)
    {
        return $this->cariKataService->delete($id);
    }

    /**
     * AJAX: generate grid preview using current words/settings
     */
    public function generate(Request $request)
    {
        $words = $request->input('words', []);
        $rows = intval($request->input('grid_rows', 12));
        $cols = intval($request->input('grid_cols', 12));
        $directions = $request->input('directions', ['horizontal','vertical','diagonal','reverse']);
        $allowOverlap = $request->input('allow_overlap', true) ? true : false;
        $seed = $request->input('seed', null);

        $result = $this->cariKataService->generateGrid(array_column($words, 'word'), $rows, $cols, $directions, $allowOverlap, $seed);
        if ($result === null) {
            return response()->json(['error' => 'Gagal membuat grid, coba ubah ukuran atau rule'], 422);
        }
        return response()->json($result);
    }

    /**
     * AJAX: generate grid and persist into template (admin action)
     * Expects: template_id, words, grid_rows, grid_cols, directions, allow_overlap
     */
    public function generateAndSave(Request $request)
    {
        $templateId = $request->input('template_id');
        if (empty($templateId)) return response()->json(['error' => 'template_id required'], 422);
        $template = $this->cariKataService->find($templateId);
        if (empty($template)) return response()->json(['error' => 'Template not found'], 404);

        $words = $request->input('words', []);
        // if words empty, try reading from template content
        if (empty($words)) {
            $content = $template->content ?? [];
            if (is_array($content) && !empty($content['words'])) $words = $content['words'];
        }

        $rows = intval($request->input('grid_rows', $template->grid_rows ?? 12));
        $cols = intval($request->input('grid_cols', $template->grid_cols ?? 12));
        $directions = $request->input('directions', $template->directions ?? ['horizontal','vertical','diagonal','reverse']);
        $allowOverlap = $request->input('allow_overlap', $template->allow_overlap ?? true) ? true : false;

        $result = $this->cariKataService->generateGrid(array_column($words, 'word'), $rows, $cols, $directions, $allowOverlap);
        if ($result === null) {
            return response()->json(['error' => 'Gagal membuat grid, coba ubah ukuran atau rule'], 422);
        }

        // persist generated grid and solution into template meta and rows/cols
        $meta = $template->meta ?? [];
        $meta['grid'] = $result['grid'];
        $meta['solution'] = $result['solution'] ?? null;
        $template->meta = $meta;
        $template->grid_rows = $rows;
        $template->grid_cols = $cols;
        $template->save();

        return response()->json(['saved' => true, 'grid' => $result['grid'], 'solution' => $result['solution'] ?? null]);
    }
}
