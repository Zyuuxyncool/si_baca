@props(['file' => null, 'id' => null, 'thumbClass' => 'img-fluid', 'buttonText' => null])

@php
    use Illuminate\Support\Facades\Storage;

    // Pastikan ID unik untuk setiap komponen
    $id = $id ?? 'preview_image_' . uniqid();

    // Pastikan file disimpan di disk 'public'
    $imageUrl = $file && Storage::disk('public')->exists($file)
        ? Storage::disk('public')->url($file)
        : null;
@endphp

@if ($imageUrl)
    <!-- Thumbnail Preview -->
    <a href="#" data-bs-toggle="modal" data-bs-target="#{{ $id }}_modal" class="d-inline-block">
        <img src="{{ $imageUrl }}" alt="Preview Gambar" class="{{ $thumbClass }} rounded shadow-sm" />
    </a>

    <!-- Modal Preview -->
    <div class="modal fade" id="{{ $id }}_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-semibold">Preview Gambar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img src="{{ $imageUrl }}" alt="Preview" class="img-fluid" style="max-height:80vh; object-fit:contain;">
                </div>
            </div>
        </div>
    </div>
@else
    <span class="text-muted fst-italic">Tidak ada gambar</span>
@endif
