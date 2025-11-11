<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TranscodeService
{
    /**
     * Transcode uploaded video to H.264 (libx264) if it appears to be HEVC (hvc1).
     * This runs synchronously and requires ffmpeg installed on the server.
     *
     * @param string $storedPath Path as returned by Storage (e.g. "public/videos/cerita/xxx.mp4")
     * @return bool true if transcode was performed (or not needed), false on error
     */
    public static function transcodeIfNeeded(string $storedPath): bool
    {
        try {
            if (empty($storedPath)) return false;

            // Normalize to disk-relative path for the 'public' disk
            $relative = preg_replace('#^(storage/|public/)#', '', ltrim($storedPath, '/'));

            $disk = Storage::disk('public');
            if (!$disk->exists($relative)) {
                Log::warning("TranscodeService: file not found on disk public: {$relative}");
                return false;
            }

            $fullPath = $disk->path($relative);

            // Quick check for HEVC identifier in the file. If not found, skip.
            $hasHevc = false;
            // Use grep -a to search binary; if grep not available this will silently fail and skip transcode.
            $cmdCheck = 'grep -aob "hvc1" ' . escapeshellarg($fullPath) . ' 2>/dev/null | head -n 1';
            $checkOut = null;
            @exec($cmdCheck, $checkOut, $checkRet);
            if (!empty($checkOut)) $hasHevc = true;

            // If no HEVC found, nothing to do
            if (!$hasHevc) {
                Log::info("TranscodeService: no HEVC detected for {$relative}, skipping transcode");
                return true;
            }

            // Make sure ffmpeg exists
            $ff = trim((string) shell_exec('which ffmpeg 2>/dev/null'));
            if ($ff === '') {
                Log::warning('TranscodeService: ffmpeg not found on server; cannot transcode video.');
                return false;
            }

            // Create temporary output file next to original
            $tmp = $fullPath . '.transcode.tmp.mp4';

            // ffmpeg command: transcode video to H.264, audio to AAC, movflags +faststart for progressive streaming
            $cmd = escapeshellcmd($ff)
                . ' -y -i ' . escapeshellarg($fullPath)
                . ' -c:v libx264 -preset medium -crf 23'
                . ' -c:a aac -b:a 128k'
                . ' -movflags +faststart '
                . escapeshellarg($tmp)
                . ' 2>&1';

            Log::info("TranscodeService: running ffmpeg for {$relative}");
            $output = [];
            $ret = 1;
            @exec($cmd, $output, $ret);

            if ($ret !== 0) {
                Log::error("TranscodeService: ffmpeg failed for {$relative}", ['ret' => $ret, 'output' => $output]);
                // cleanup tmp if exists
                if (file_exists($tmp)) @unlink($tmp);
                return false;
            }

            // Replace original with transcoded file
            if (!@rename($tmp, $fullPath)) {
                // try copy then unlink
                if (@copy($tmp, $fullPath)) {
                    @unlink($tmp);
                } else {
                    Log::error("TranscodeService: failed to replace original with transcoded file for {$relative}");
                    if (file_exists($tmp)) @unlink($tmp);
                    return false;
                }
            }

            Log::info("TranscodeService: transcode completed for {$relative}");
            return true;

        } catch (\Throwable $e) {
            Log::error('TranscodeService exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Transcode and generate a poster (thumbnail) for the video.
     * Returns the stored poster path (e.g. 'public/videos/cerita/xxx.jpg') on success, or empty string on failure.
     *
     * @param string $storedPath
     * @return string
     */
    public static function transcodeAndMakePoster(string $storedPath): string
    {
        try {
            // First transcode (if needed)
            $ok = self::transcodeIfNeeded($storedPath);
            if (!$ok) return '';

            // Normalize relative path
            $relative = preg_replace('#^(storage/|public/)#', '', ltrim($storedPath, '/'));
            $disk = Storage::disk('public');
            if (!$disk->exists($relative)) return '';
            $fullPath = $disk->path($relative);

            // Build poster filename: same basename with .jpg
            $basename = pathinfo($relative, PATHINFO_FILENAME);
            $posterRel = dirname($relative) . '/' . $basename . '.jpg';
            $posterFull = $disk->path($posterRel);

            // Ensure ffmpeg present
            $ff = trim((string) shell_exec('which ffmpeg 2>/dev/null'));
            if ($ff === '') {
                Log::warning('TranscodeService: ffmpeg not found; cannot generate poster.');
                return '';
            }

            // Generate poster at 3 seconds (or first frame if shorter)
            $tmp = $posterFull . '.tmp.jpg';
            $cmd = escapeshellcmd($ff)
                . ' -y -ss 00:00:03 -i ' . escapeshellarg($fullPath)
                . ' -vframes 1 -q:v 2 ' . escapeshellarg($tmp) . ' 2>&1';

            $output = [];
            $ret = 1;
            @exec($cmd, $output, $ret);
            if ($ret !== 0 || !file_exists($tmp)) {
                Log::warning('TranscodeService: poster generation failed', ['ret' => $ret, 'output' => $output]);
                if (file_exists($tmp)) @unlink($tmp);
                return '';
            }

            // Move tmp poster into final location
            // Ensure destination dir exists
            $destDir = dirname($posterFull);
            if (!is_dir($destDir)) @mkdir($destDir, 0755, true);
            if (!@rename($tmp, $posterFull)) {
                if (@copy($tmp, $posterFull)) @unlink($tmp);
                else { if (file_exists($tmp)) @unlink($tmp); return ''; }
            }

            Log::info("TranscodeService: poster created for {$relative} -> {$posterRel}");
            // Return stored path in same format as Storage::putFileAs returns (prefix with 'public/')
            return 'public/' . ltrim($posterRel, '/');
        } catch (\Throwable $e) {
            Log::error('TranscodeService transcodeAndMakePoster exception: ' . $e->getMessage());
            return '';
        }
    }
}
