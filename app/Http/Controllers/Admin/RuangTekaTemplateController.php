<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RuangTekaService;
use App\Services\DocumentService;

class RuangTekaTemplateController extends Controller
{
    protected $ruangTekaService;

    public function __construct()
    {
        $this->ruangTekaService = new RuangTekaService();
    }

    public function index()
    {
        return view('admin.ruang_teka.index');
    }

    public function search(Request $request)
    {
        $templates = $this->ruangTekaService->search($request->all());
        return view('admin.ruang_teka._table', ['templates' => $templates]);
    }

    public function create()
    {
        $ceritas = $this->ruangTekaService->ceritaOptions();
        return view('admin.ruang_teka._form', compact('ceritas'));
    }

    public function store(Request $request)
    {
        $filename = DocumentService::save_file($request, 'file_poster', 'public/images/ruang_teka');
        if ($filename !== '') $request->merge(['poster' => $filename]);

        $data = $request->all();
        // ensure content is JSON if provided as string
        if (!empty($data['content']) && is_string($data['content'])) {
            $data['content'] = json_decode($data['content'], true);
        }
        // ensure clues is decoded from JSON string to array if needed
        if (!empty($data['clues']) && is_string($data['clues'])) {
            $decoded = json_decode($data['clues'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['clues'] = $decoded;
            } else {
                // if decode fails, set to empty structure to avoid DB errors
                $data['clues'] = ['across' => [], 'down' => []];
            }
        }
        $template = $this->ruangTekaService->store($data);
        return response()->json($template);
    }

    public function edit($id)
    {
        $template = $this->ruangTekaService->find($id);
        $ceritas = $this->ruangTekaService->ceritaOptions();
        return view('admin.ruang_teka._form', compact('template','ceritas'));
    }

    public function update(Request $request, $id)
    {
        $filename = DocumentService::save_file($request, 'file_poster', 'public/images/ruang_teka');
        if ($filename !== '') $request->merge(['poster' => $filename]);

        $data = $request->all();
        if (!empty($data['content']) && is_string($data['content'])) {
            $data['content'] = json_decode($data['content'], true);
        }
        // ensure clues is decoded from JSON string to array if needed
        if (!empty($data['clues']) && is_string($data['clues'])) {
            $decoded = json_decode($data['clues'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['clues'] = $decoded;
            } else {
                $data['clues'] = ['across' => [], 'down' => []];
            }
        }
        $template = $this->ruangTekaService->update($id, $data);
        return response()->json($template);
    }

    public function destroy($id)
    {
        $res = $this->ruangTekaService->delete($id);
        if ($res === true) return response()->json(['deleted' => true]);
        return response()->json($res, 422);
    }

    public function generate(Request $request)
    {
        $data = $request->all();
        $clues = $data['clues'] ?? [];
        $rows = intval($data['rows'] ?? 10);
        $cols = intval($data['cols'] ?? 10);
        $res = $this->ruangTekaService->generateGridFromClues($clues, $rows, $cols);
        if (!empty($res['errors'])) {
            return response()->json(['errors' => $res['errors']], 422);
        }
        return response()->json(['grid' => $res['grid']]);
    }

    /**
     * Generate grid from clues and save to the specified template.
     * Expects: template_id, clues, rows, cols
     */
    public function generateAndSave(Request $request)
    {
        $data = $request->all();
        $templateId = $data['template_id'] ?? null;
        if (empty($templateId)) return response()->json(['error' => 'template_id required'], 422);
        $template = $this->ruangTekaService->find($templateId);
        if (empty($template)) return response()->json(['error' => 'Template not found'], 404);

        $clues = $data['clues'] ?? ($template->clues ?? []);
        $rows = intval($data['rows'] ?? $template->grid_rows ?? 10);
        $cols = intval($data['cols'] ?? $template->grid_cols ?? 10);
        $res = $this->ruangTekaService->generateGridFromClues($clues, $rows, $cols);
        if (!empty($res['errors'])) {
            return response()->json(['errors' => $res['errors']], 422);
        }

        // persist generated grid and dimension values
        $template->grid = $res['grid'];
        $template->grid_rows = $rows;
        $template->grid_cols = $cols;
        $template->save();

        return response()->json(['saved' => true, 'grid' => $res['grid']]);
    }
}
