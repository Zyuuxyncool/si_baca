<div>
    <div class="dropzone dropzone-queue mb-2" id="dropzone_{{ $id }}">
        <div class="dropzone-panel mb-lg-0 mb-2">
            <x-input type="hidden" :name="$name" :prefix="$prefix" />
            <x-input type="hidden" name="{{ 'delete_' . $name }}" :prefix="$prefix" />
            <a class="dropzone-select btn btn-sm btn-primary me-2">Select file</a>
            <a class="dropzone-remove-all btn btn-sm btn-light-primary">Remove All</a>
        </div>

        <div class="dropzone-items wm-200px">
            <div class="dropzone-item" style="display:none">
                <div class="dropzone-file">
                    <div class="dropzone-filename" title="some_image_file_name.jpg">
                        <span data-dz-name>some_image_file_name.jpg</span>
                        <strong>(<span data-dz-size>340kb</span>)</strong>
                    </div>
                    <div class="dropzone-error" data-dz-errormessage></div>
                </div>

                <div class="dropzone-progress">
                    <div class="progress">
                        <div class="progress-bar bg-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress></div>
                    </div>
                </div>

                <div class="dropzone-toolbar" onclick="delete_newfile()">
                    <span class="dropzone-delete" data-dz-remove><i class="bi bi-x fs-1"></i></span>
                </div>
            </div>
        </div>
    </div>
    <span class="form-text text-muted">Max file size is 2MB.</span>

    @if(!empty($preview))
        <div class="d-flex flex-row justify-content-between dropzone-item p-3 bg-light mt-3" id="existing_{{ $prefix }}{{ $name }}">
            <a target="_blank" href="{{ asset('storage/' . $preview['filename']) }}" class="dropzone-file">
                <div class="dropzone-filename text-dark" title="{{ $preview['caption'] ?? '' }}">
                    <span data-dz-name>{{ $preview['caption'] ?? '' }}</span>
                    <strong>(<span data-dz-size>{{ $preview['size'] ?? '' }}</span>)</strong>
                </div>
                <div class="dropzone-error" data-dz-errormessage></div>
            </a>

            <div class="dropzone-toolbar cursor-pointer" onclick="delete_existing()">
                <span class="dropzone-delete" data-dz-remove><i class="bi bi-x fs-1"></i></span>
            </div>
        </div>
    @endif

    <script>
        selectorId = '#dropzone_{{ $id }}';
        dropzone = document.querySelector(selectorId);

        previewNode = dropzone.querySelector(selectorId + " .dropzone-item");
        previewNode.id = "";
        previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        delete_existing = () => {
            $('#delete_{{ $prefix }}{{ $name }}').val('{{ $preview->file ?? '' }}');
            $('#existing_{{ $prefix }}{{ $name }}').remove();
        }

        delete_newfile = () => {
            $('#{{ $prefix }}{{ $name }}').val('');
        }

        fileDropzone = new Dropzone(selectorId, {
            url: "{{ $url }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
            },
            maxFiles: 1,
            maxFilesize: 2,
            previewTemplate: previewTemplate,
            previewsContainer: selectorId + " .dropzone-items",
            clickable: selectorId + " .dropzone-select"
        });

        fileDropzone.on('error', (file, xhr) => {
            $('.dropzone-error').html(xhr.message);
            console.log(xhr);
            console.log(file);
        });

        fileDropzone.on('success', (file, response) => {
            $('#{{ $prefix }}{{ $name }}').val(response.filename);
            // console.log(response);
        });

        fileDropzone.on("addedfile", (file, response) => {
            const dropzoneItems = dropzone.querySelectorAll('.dropzone-item');
            dropzoneItems.forEach(dropzoneItem => dropzoneItem.style.display = '');
        });

        fileDropzone.on("totaluploadprogress", (progress) => {
            const progressBars = dropzone.querySelectorAll('.progress-bar');
            progressBars.forEach(progressBar => {
                progressBar.style.width = progress + "%";
            });
        });

        fileDropzone.on("sending", (file) => {
            const progressBars = dropzone.querySelectorAll('.progress-bar');
            progressBars.forEach(progressBar => {
                progressBar.style.opacity = "1";
            });
        });

        fileDropzone.on("complete", function (progress) {
            const progressBars = dropzone.querySelectorAll('.dz-complete');
            setTimeout(() => {
                progressBars.forEach(progressBar => {
                    progressBar.querySelector('.progress-bar').style.opacity = "0";
                    progressBar.querySelector('.progress').style.opacity = "0";
                });
            }, 300);
        });
    </script>
</div>
