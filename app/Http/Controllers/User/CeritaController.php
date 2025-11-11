<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\CeritaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CeritaController extends Controller
{
    protected $ceritaService;

    public function __construct()
    {
        $this->ceritaService = new CeritaService();
    }

    public function index()
    {
        $ceritas = $this->ceritaService->search([]);
        return view('user.cerita.index', compact('ceritas'));
    }

    public function show($nama)
    {
        $cerita = $this->ceritaService->find($nama, 'nama');
        if (empty($cerita)) abort(404);

        try {
            // gunakan route menu yang tersedia: user.games.menu_ruang_teka.index dan user.games.menu_cari_kata.index
            $endGames = [
                [
                    'slug' => 'ruang_teka',
                    'title' => 'Ruang Teka',
                    'icon' => '💡',
                    'route' => route('user.games.menu_ruang_teka.index', ['nama' => $cerita->nama])
                ],
                [
                    'slug' => 'cari_kata',
                    'title' => 'Cari Kata',
                    'icon' => '🔍',
                    'route' => route('user.games.menu_cari_kata.index', ['nama' => $cerita->nama])
                ],
            ];
        } catch (\Exception $e) {
            $endGames = [
                ['slug' => 'ruang_teka', 'title' => 'Ruang Teka', 'icon' => '💡', 'route' => '#'],
                ['slug' => 'cari_kata', 'title' => 'Cari Kata', 'icon' => '🔍', 'route' => '#'],
            ];
        }

        return view('user.cerita.show', compact('cerita', 'endGames'));
    }

    /**
     * Stream video file via Apache X-Sendfile (ultra cepat)
     */
    public function streamVideo($nama, Request $request)
    {
        $cerita = $this->ceritaService->find($nama, 'nama');
        if (empty($cerita) || empty($cerita->video)) {
            abort(404);
        }

        $disk = Storage::disk('public');

        // Hilangkan prefix 'public/' atau 'storage/' biar cocok dengan disk public
        $videoPath = preg_replace('#^(storage/|public/)#', '', ltrim($cerita->video, '/'));

        if (!$disk->exists($videoPath)) {
            abort(404, "Video tidak ditemukan di disk public: {$videoPath}");
        }

        $fullPath = $disk->path($videoPath);
        $mime = mime_content_type($fullPath) ?: 'video/mp4';
        $size = filesize($fullPath);

        /**
         * ⚙️ Bagian ini hanya aktif kalau nanti kamu pindah ke Nginx
         * (X-Accel-Redirect), di Apache ini akan dilewati.
         */
        $xAccelBase = env('X_ACCEL_REDIRECT_BASE');
        if ($xAccelBase && config('filesystems.disks.public.driver') === 'local') {
            $internal = rtrim($xAccelBase, '/') . '/' . ltrim($videoPath, '/');
            return response('', 200)
                ->header('X-Accel-Redirect', $internal)
                ->header('Content-Type', $mime)
                ->header('Content-Length', (string) $size)
                ->header('Accept-Ranges', 'bytes');
        }

        /**
         * 🚀 Bagian utama untuk Apache (gunakan mod_xsendfile)
         */
        if (env('USE_X_SENDFILE', false)) {
            return response('', 200)
                ->header('X-Sendfile', $fullPath)
                ->header('Content-Type', $mime)
                ->header('Content-Length', (string) $size)
                ->header('Accept-Ranges', 'bytes');
        }

        /**
         * 🐢 Fallback: stream manual via PHP (lebih lambat, tapi aman)
         */
        $start = 0;
        $end = $size - 1;
        $status = 200;

        if ($request->headers->has('range')) {
            $range = $request->header('range');
            if (preg_match('/bytes=(\d+)-(\d+)?/', $range, $matches)) {
                $status = 206;
                $start = intval($matches[1]);
                if (isset($matches[2]) && $matches[2] !== '') {
                    $end = intval($matches[2]);
                }
                if ($start > $end) abort(416);
                if ($end > $size - 1) $end = $size - 1;
            }
        }

        $length = $end - $start + 1;
        $response = new StreamedResponse(function () use ($fullPath, $start, $end) {
            @set_time_limit(0);
            @ignore_user_abort(true);
            while (ob_get_level()) {@ob_end_clean();}
            $buffer = 1024 * 128;
            $handle = fopen($fullPath, 'rb');
            if ($handle === false) return;
            try {
                fseek($handle, $start);
                $bytesToSend = $end - $start + 1;
                while (!feof($handle) && $bytesToSend > 0) {
                    $read = fread($handle, min($buffer, $bytesToSend));
                    if ($read === false) break;
                    echo $read; flush();
                    $bytesToSend -= strlen($read);
                }
            } finally {
                fclose($handle);
            }
        }, $status);

        $response->headers->set('Content-Type', $mime);
        $response->headers->set('Accept-Ranges', 'bytes');
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Content-Length', (string) $length);
        if ($status === 206) {
            $response->headers->set('Content-Range', sprintf('bytes %d-%d/%d', $start, $end, $size));
        }

        return $response;
    }

    public function status($nama)
    {
        $cerita = $this->ceritaService->find($nama, 'nama');
        if (empty($cerita)) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json([
            'video_processing' => (bool) $cerita->video_processing,
            'video_processed_at' => $cerita->video_processed_at ? $cerita->video_processed_at->toDateTimeString() : null,
        ]);
    }
}
