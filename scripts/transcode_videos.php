<?php
// CLI script to transcode existing videos in storage/app/public/videos/cerita
// Usage: php scripts/transcode_videos.php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use App\Services\TranscodeService;

echo "Scanning storage/app/public/videos/cerita for mp4 files...\n";
$disk = Storage::disk('public');
$dir = 'videos/cerita';
if (!$disk->exists($dir)) {
    echo "Directory public/{$dir} does not exist on disk public.\n";
    exit(1);
}

$files = $disk->files($dir);
$mp4s = array_filter($files, function($f){ return str_ends_with(strtolower($f), '.mp4'); });
if (empty($mp4s)) {
    echo "No mp4 files found.\n";
    exit(0);
}

foreach ($mp4s as $file) {
    echo "Processing {$file} ...\n";
    $storedPath = 'public/' . ltrim($file, '/');
    $ok = TranscodeService::transcodeIfNeeded($storedPath);
    echo $ok ? "OK\n" : "FAILED\n";
}

echo "Done.\n";
