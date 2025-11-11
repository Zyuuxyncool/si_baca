<?php

namespace App\Jobs;

use App\Models\Cerita;
use App\Services\TranscodeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TranscodeVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // allow long-running

    protected $storedPath;
    protected $ceritaId;

    /**
     * Create a new job instance.
     *
     * @param string $storedPath
     * @param int|null $ceritaId
     */
    public function __construct(string $storedPath, $ceritaId = null)
    {
        $this->storedPath = $storedPath;
        $this->ceritaId = $ceritaId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Run transcode (no poster handling)
            $ok = TranscodeService::transcodeIfNeeded($this->storedPath);

            if ($this->ceritaId) {
                $cerita = Cerita::find($this->ceritaId);
                if ($cerita) {
                    $cerita->video_processing = false;
                    $cerita->video_processed_at = now();
                    $cerita->save();
                }
            }

            if (!$ok) {
                Log::warning('TranscodeVideoJob: transcode may have failed for ' . $this->storedPath);
            }
        } catch (\Throwable $e) {
            Log::error('TranscodeVideoJob exception: ' . $e->getMessage());
            // mark processing false so UI does not stay stuck
            if ($this->ceritaId) {
                try {
                    $cerita = Cerita::find($this->ceritaId);
                    if ($cerita) {
                        $cerita->video_processing = false;
                        $cerita->save();
                    }
                } catch (\Throwable $e) {}
            }
            throw $e; // allow retry logic if configured
        }
    }
}
