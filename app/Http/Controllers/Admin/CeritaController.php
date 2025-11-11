<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\CeritaService;
use App\Services\DocumentService;
use App\Jobs\TranscodeVideoJob;
use Illuminate\Http\Request;

class CeritaController extends Controller
{
    protected $ceritaService;

    public function __construct()
    {
        $this->ceritaService = new CeritaService();
    }

    public function index()
    {
        return view('admin.cerita.index');
    }

    public function search(Request $request)
    {
        $ceritas = $this->ceritaService->search($request->all());
        return view('admin.cerita._table', compact('ceritas'));
    }

    public function create()
    {
        return view('admin.cerita._form');
    }

    public function store(Request $request)
    {
        $filename = DocumentService::save_file($request, 'file_photo', 'public/images/cerita');
        if ($filename !== '') $request->merge(['photo' => $filename]);
        $filename = DocumentService::save_file($request, 'file_video', 'public/videos/cerita');
        if ($filename !== '') $request->merge(['video' => $filename]);
        // store cerita
        $cerita = $this->ceritaService->store($request->all());

        // If a video was uploaded, dispatch background transcode job and mark processing
        if (!empty($filename) && $cerita) {
            try {
                $cerita->update(['video_processing' => true]);
            } catch (\Exception $e) {}
            TranscodeVideoJob::dispatch($filename, $cerita->id);
        }
        return $cerita;
    }

    public function edit($id)
    {
        $cerita = $this->ceritaService->find($id);
        return view('admin.cerita._form', compact('cerita'));
    }

    public function update(Request $request, $id)
    {
        $filename = DocumentService::save_file($request, 'file_photo', 'public/images/cerita');
        if ($filename !== '') $request->merge(['photo' => $filename]);
        $filename = DocumentService::save_file($request, 'file_video', 'public/videos/cerita');
        if ($filename !== '') $request->merge(['video' => $filename]);
        $cerita = $this->ceritaService->update($id, $request->all());
        if (!empty($filename) && $cerita) {
            try {
                $cerita->update(['video_processing' => true]);
            } catch (\Exception $e) {}
            TranscodeVideoJob::dispatch($filename, $cerita->id);
        }
        return $cerita;
    }

    public function destroy($id)
    {
        $cerita = $this->ceritaService->find($id);
        if (!empty($cerita)) {
            DocumentService::delete_file($cerita->photo);
            DocumentService::delete_file($cerita->video);
        }
        return $this->ceritaService->delete($id);
    }

    /**
     * Return JSON status for video processing state.
     */
    public function status($id)
    {
        $cerita = $this->ceritaService->find($id);
        if (! $cerita) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json(['video_processing' => (bool) ($cerita->video_processing ?? false)]);
    }

}