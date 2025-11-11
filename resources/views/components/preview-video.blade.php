@props(['file' => '', 'id' => null, 'modelId' => null, 'processing' => false, 'buttonText' => 'Preview Video', 'btnClass' => 'btn btn-secondary btn-sm'])

    @php
    $id = $id ?? 'preview_video_' . uniqid();
    $videoUrl = $file ? Storage::url($file) : null;
    $videoVersion = '';
    if ($file) {
        try {
            $rel = preg_replace('#^(storage/|public/)#', '', ltrim($file, '/'));
            if ($rel) $videoVersion = Storage::disk('public')->lastModified($rel);
        } catch (\Exception $e) { $videoVersion = ''; }
    }
@endphp

<!-- Button to open modal -->
<button type="button" class="{{ $btnClass }}" data-bs-toggle="modal" data-bs-target="#{{ $id }}_modal">
    {{ $buttonText }}
</button>

<!-- Modal -->
<div class="modal fade" id="{{ $id }}_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center" data-cerita-id="{{ $modelId }}" data-cerita-processing="{{ $processing ? 1 : 0 }}">
                    @if($processing)
                        <div class="text-center" style="width:100%">
                            <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem;margin-bottom:1rem"></div>
                            <div>Video sedang disiapkan. Silakan tunggu beberapa saat.</div>
                        </div>
                    @elseif($videoUrl)
                        <video id="{{ $id }}_player" class="w-100" controls style="max-height:80vh;">
                            <source src="{{ $videoUrl }}{{ $videoVersion ? ('?v=' . $videoVersion) : '' }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <div class="text-center text-muted">Tidak ada video untuk ditampilkan.</div>
                    @endif
                </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
    <script>
        // Pause video when modal closes to avoid audio continuing
        document.addEventListener('hidden.bs.modal', function (event) {
            const modal = event.target;
            const vids = modal.querySelectorAll('video');
            vids.forEach(v => { try { v.pause(); v.currentTime = 0; } catch(e){} });
        });

        // When a preview modal opens for a video that's being processed,
        // show a SweetAlert loading dialog and poll the status endpoint.
        document.addEventListener('shown.bs.modal', function (event) {
            const modal = event.target;
            if (!modal) return;
            const body = modal.querySelector('.modal-body');
            if (!body) return;
            const ceritaId = body.getAttribute('data-cerita-id');
            const processing = body.getAttribute('data-cerita-processing') === '1';
            if (!ceritaId || !processing) return;

            // Build status URL from a data attribute if present or guess pattern
            // We'll use a URL pattern: /admin/cerita/{id}/status
            const statusUrl = '/admin/cerita/' + ceritaId + '/status';

            // Show Swal loading
            try {
                Swal.fire({
                    title: 'Memproses video...',
                    html: 'Transcode berjalan di background. Menunggu selesai...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            } catch (e) { }

            // Poll the status endpoint until video_processing becomes false
            const interval = 3000; // ms
            let attempts = 0;
            const maxAttempts = 120; // safety timeout ~6 minutes

            const timer = setInterval(() => {
                attempts++;
                fetch(statusUrl, { credentials: 'same-origin' })
                    .then(r => r.json())
                    .then(json => {
                        if (json && json.video_processing === false) {
                            clearInterval(timer);
                            try { Swal.close(); } catch (e) {}
                            try {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Selesai',
                                    text: 'Video sudah siap ditonton.'
                                });
                            } catch (e) {}

                            // Replace modal body with the video element if source is present
                            const videoSrc = body.querySelector('source') ? body.querySelector('source').getAttribute('src') : null;
                            if (videoSrc) {
                                body.innerHTML = '<video class="w-100" controls style="max-height:80vh;"><source src="' + videoSrc + '" type="video/mp4">Your browser does not support the video tag.</video>';
                            } else {
                                // If source not present, attempt to reload page fragment by reloading the modal content
                                // (optional: user can close and re-open)
                            }
                        }
                    }).catch(err => {
                        // ignore network errors and keep polling
                    });

                if (attempts >= maxAttempts) {
                    clearInterval(timer);
                    try { Swal.close(); } catch (e) {}
                    try {
                        Swal.fire({
                            icon: 'error',
                            title: 'Timeout',
                            text: 'Proses transcode tampaknya memakan waktu lama. Silakan cek kembali nanti.'
                        });
                    } catch (e) {}
                }
            }, interval);
        });
    </script>
    @endpush
@endonce
