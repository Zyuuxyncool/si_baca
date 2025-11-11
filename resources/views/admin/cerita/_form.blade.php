<form id="form_info">
    @csrf
    <div class="modal-header">
        <div class="card-title fs-3 fw-bold">{{ !empty($cerita) ? 'Ubah' : 'Tambah' }} Cerita</div>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <x-metronic-input name="nama" caption="Nama" :value="$cerita->nama ?? ''" />
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-3">
                <div class="alert alert-danger d-flex align-items-center p-5 mt-1 d-none w-100"
                    @error('file_foto') style="display: block!important;" @enderror id="file_foto_error">
                    <div class="d-flex flex-column align-items-start" id="file_foto_error_content">
                        @error('file_foto')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="d-none"><x-input type="file" name="file_photo" id="file_photo" alert="0" /><x-input name="delete_foto" id="delete_foto" alert="0" /></div>
                <img src="{{ ($cerita->photo ?? '') != '' ? Storage::url($cerita->photo) : asset('images/default.jpg') }}" id="preview_foto" alt="" class="w-100 h-auto object-fit-cover shadow-xs rounded-1" />
                <div class="d-flex flex-column gap-1">
                    <button class="btn btn-secondary btn-sm py-2 mt-3 fs-8" type="button" onclick="open_file('file_photo', 'preview_foto')">Cari Foto</button>
                    <button class="btn btn-secondary btn-sm py-2 mt-3 fs-8" type="button" onclick="remove_file('delete_foto', 'preview_foto', '{{ asset('images/default.jpg') }}')">Hapus Foto</button>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="alert alert-danger d-flex align-items-center p-5 mt-1 d-none w-100"
                    @error('file_video') style="display: block!important;" @enderror id="file_video_error">
                    <div class="d-flex flex-column align-items-start" id="file_video_error_content">
                        @error('file_video')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="d-none"><x-input type="file" name="file_video" id="file_video" alert="0" /><x-input name="delete_video" id="delete_video" alert="0" /></div>
                @if(!empty($cerita->video))
                    @php
                        $videoUrl = Storage::url($cerita->video);
                        $videoVersion = '';
                        try {
                            $rel = preg_replace('#^(storage/|public/)#', '', ltrim($cerita->video ?? '', '/'));
                            if ($rel) $videoVersion = Storage::disk('public')->lastModified($rel);
                        } catch (\Exception $e) { $videoVersion = ''; }
                        // poster: prefer cerita photo (we removed generated video posters)
                        $poster = '';
                        if (!empty($cerita->photo)) $poster = Storage::url($cerita->photo);
                    @endphp
                    <video id="preview_video" class="w-100 h-auto shadow-xs rounded-1" controls style="max-height:420px;" poster="{{ $poster }}">
                        <source src="{{ $videoUrl }}{{ $videoVersion ? ('?v=' . $videoVersion) : '' }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                <div id="preview_video" class="w-100 h-auto shadow-xs rounded-1 d-flex align-items-center justify-content-center" style="min-height:240px;background:#f5f5f5;color:#666;">
                    <small>Tidak ada video</small>
                    </div>
                    @endif
                    <div class="d-flex flex-column gap-1 mt-2">
                        <div class="d-flex gap-2">
                        <button class="btn btn-secondary btn-sm py-2 fs-8" type="button" onclick="open_file('file_video', 'preview_video')">Cari Video</button>
                        <button class="btn btn-secondary btn-sm py-2 fs-8" type="button" onclick="remove_file('delete_video', 'preview_video', '')">Hapus Video</button>
                    </div>
                    <small class="text-muted">Format yang direkomendasikan: MP4. Ukuran maksimal tergantung pengaturan server.</small>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <x-metronic-textarea name="deskripsi" caption="Deskripsi" :value="$cerita->deskripsi ?? ''" :rows="6" :viewtype="2" />
                </div>
            </div>
        </div>

    <div class="modal-footer d-flex justify-content-end py-6">
        <button type="button" onclick="init()" class="btn btn-light btn-active-light-primary me-2">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    init_form_element();
    init_form({{ $cerita->id ?? '' }});
</script>
