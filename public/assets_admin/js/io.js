let get_form_data = ($form) => {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};
    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });
    return indexed_array;
}

let add_commas = (nStr) =>{
    nStr += '';
    let x = nStr.split('.');
    let x1 = x[0];
    let x2 = x.length > 1 ? '.' + x[1] : '';
    let rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
}

let remove_commas = (nStr) => {
    nStr = nStr.replace(/\./g,'');
    nStr = nStr.replace(/\,/g,'.');
    return nStr;
}

let remove_space = (nStr) => {
    nStr = nStr.replace(/\ /g,'');
    return nStr;
}

let format_date = (value) => {
    if (value !== '' && value !== null) {
        let data = value.split('-');
        return [data[2], data[1], data[0]].join('-');
    }
    return '';
}

let error_handle = (data) => {
    try {
        let errors = JSON.parse(data);
        errors = errors.errors;
        if (errors === undefined) console.log(data);
        $('.alert.alert-danger').addClass('d-none');
        $.each(errors, (i, value) => {
            $('#' + i + '_error').removeClass('d-none');
            $('#' + i + '_error_content').html(value.join(', '));
        });
    } catch (e) { }
}
let init_select2 = () => {
    const elements = document.querySelectorAll('[data-control="select2"], [data-kt-select2="true"]');
    elements.forEach( (element) => {
        const options = { dir: document.body.getAttribute("direction") };
        if (element.getAttribute("data-hide-search") === "true") options.minimumResultsForSearch = Infinity;
        $(element).select2(options);
    });
    $(document).on("select2:open", () => {
        const searchFields = document.querySelectorAll(".select2-container--open .select2-search__field");
        if (searchFields.length > 0) searchFields[searchFields.length - 1].focus();
    });
}
let init_form_element = () => {
    KTImageInput.createInstances();
    init_select2();
    $('.datepicker').datepicker({
        autoclose: true,
        format: "dd-mm-yyyy",
        orientation: 'bottom'
    });
    $('.timepicker').flatpickr({
        enableTime: true,
        noCalendar: true,
        enableSeconds: true,
        dateFormat: "H:i:S",
        time_24hr: true,
    });
    $('.autonumeric')
        .attr('data-a-sep', '.')
        .attr('data-a-dec',',')
        .autoNumeric({mDec: '0', vMax:'9999999999999999999999999', vMin: '-99999999999999999'});
        unformatOnSubmit: true
    $('.autonumeric-decimal')
        .attr('data-a-sep', '.')
        .attr('data-a-dec', ',')
        .autoNumeric({
            mDec: '2',
            vMax:'999999'
        });
    $(".only-numeric").on('keydown', (e) => {
        if ($.inArray(e.key, ["Backspace", "Delete", "Tab", "Escape", "Enter", "ArrowLeft", "ArrowRight"]) !== -1) {
            return;
        }
        if ((e.key >= "0" && e.key <= "9")) {
            return;
        }
        e.preventDefault();
    });
}
let get_location = ($target, tingkat, parent_id = '', selected = '', trigger_select2 = true) => {
    $.get(baseUrl + '/api/lokasi' + (parent_id !== '' ? ('/' + parent_id) : ''), (result) => {
        $target.html('');
        let caption = '';
        if (tingkat === 1) caption = 'Provinsi';
        if (tingkat === 2) caption = 'Kabupaten/Kota';
        if (tingkat === 3) caption = 'Kecamatan';
        if (tingkat === 4) caption = 'Desa/Kelurahan';
        $target.append('<option value="" data-id="">-Pilih ' + caption + '-</option>');
        if (parent_id !== '' || tingkat === 1) {
            $.each(result, (i, value) => {
                let attr = value.nama.toString().toLowerCase() === selected.toString().toLowerCase() ? 'selected' : '';
                $target.append('<option data-id="' + value.id + '" ' + attr + '>' + value.nama + '</option>');
            });
        }
        if (trigger_select2 === true) $target.change().select2();
        else $target.change();
    }).fail((xhr) => {
        console.log(xhr.responseText);
    });
}
let get_selected_page = (page, selected_page) => {
    let result = selected_page;
    if (page.toString() === '+1') result++;
    else if (page.toString() === '-1') result--;
    else result = page;
    return result;
}

let swal_delete_params = {
    title: 'Hapus Data ?',
    icon: 'question',
    showDenyButton: true,
    confirmButtonText: 'Delete',
    denyButtonText: 'Cancel',
    confirmButtonColor: '#F46A6A',
    denyButtonColor: '#bdbdbd'
}

let preview_image = (event, target_preview) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const el = document.getElementById(target_preview);
            if (!el) return;
            // If target is an <img>, set src. Otherwise, delegate to preview_file.
            if (el.tagName.toLowerCase() === 'img') {
                el.src = e.target.result;
            } else {
                // fallback to preview_file for other element types
                preview_file(event, target_preview);
            }
        };
        reader.readAsDataURL(file);
    }
}

let preview_file = (event, target_preview) => {
    const file = event.target.files[0];
    console.debug('preview_file called', { target_preview, file });
    if (!file) return console.debug('preview_file: no file selected');
    const el = document.getElementById(target_preview);
    if (!el) return console.debug('preview_file: preview element not found', target_preview);

    // Some browsers or files may not provide `file.type`; fallback to extension detection
    let type = file.type || '';
    if (!type && file.name) {
        const name = file.name.toString().toLowerCase();
        if (name.endsWith('.mp4') || name.endsWith('.webm') || name.endsWith('.ogg') || name.endsWith('.mov')) {
            type = 'video/mp4';
        } else if (name.match(/\.(png|jpe?g|gif|bmp|webp)$/)) {
            type = 'image/*';
        }
    }
    if (el.tagName.toLowerCase() === 'img' || type.startsWith('image/')) {
        // image preview
        const reader = new FileReader();
        reader.onload = (e) => el.src = e.target.result;
        reader.readAsDataURL(file);
        return;
    }

    if (el.tagName.toLowerCase() === 'video' || type.startsWith('video/')) {
        // video preview: if element is <video>, set its src; if it's a container div, replace with video element
        let videoEl = el.tagName.toLowerCase() === 'video' ? el : el.querySelector('video');
        const objectUrl = URL.createObjectURL(file);
        if (videoEl) {
            // remove existing <source> children
            console.debug('preview_file: using existing <video> element, setting source object URL', { objectUrl, type });
            while (videoEl.firstChild) videoEl.removeChild(videoEl.firstChild);
            const source = document.createElement('source');
            source.src = objectUrl;
            source.type = file.type || 'video/mp4';
            videoEl.appendChild(source);
                videoEl.load();
                // handle cases where browser cannot decode the video (codec unsupported)
                const onMeta = () => {
                    try { videoEl.removeEventListener('loadedmetadata', onMeta); } catch(e){}
                };
                const onErr = (e) => {
                    try { videoEl.removeEventListener('error', onErr); } catch(e){}
                    // fallback: show filename and size
                    const placeholder = document.createElement('div');
                    placeholder.id = target_preview;
                    placeholder.className = videoEl.className + ' d-flex flex-column align-items-center justify-content-center';
                    placeholder.style.minHeight = '120px';
                    placeholder.style.background = '#f5f5f5';
                    placeholder.style.color = '#666';
                    const nameEl = document.createElement('div');
                    nameEl.innerText = file.name + ' (' + Math.round(file.size/1024) + ' KB)';
                    const hint = document.createElement('small');
                    hint.className = 'text-muted';
                    hint.innerText = 'Preview tidak tersedia di browser ini (kemungkinan codec tidak didukung). File akan tetap diunggah.';
                    placeholder.appendChild(nameEl);
                    placeholder.appendChild(hint);
                    videoEl.parentNode.replaceChild(placeholder, videoEl);
                };
                videoEl.addEventListener('loadedmetadata', onMeta);
                videoEl.addEventListener('error', onErr);
            // ensure controls visible
            videoEl.controls = true;
        } else {
            console.debug('preview_file: creating new <video> element', { objectUrl, type });
            // replace container with a video element
            const newVideo = document.createElement('video');
            newVideo.id = target_preview;
            newVideo.className = el.className;
            newVideo.controls = true;
            newVideo.style.maxWidth = '100%';
            const source = document.createElement('source');
            source.src = objectUrl;
            source.type = file.type || 'video/mp4';
            newVideo.appendChild(source);
                // attach handlers for decode/fallback
                newVideo.addEventListener('loadedmetadata', () => {});
                newVideo.addEventListener('error', () => {
                    const placeholder = document.createElement('div');
                    placeholder.id = target_preview;
                    placeholder.className = newVideo.className + ' d-flex flex-column align-items-center justify-content-center';
                    placeholder.style.minHeight = '120px';
                    placeholder.style.background = '#f5f5f5';
                    placeholder.style.color = '#666';
                    const nameEl = document.createElement('div');
                    nameEl.innerText = file.name + ' (' + Math.round(file.size/1024) + ' KB)';
                    const hint = document.createElement('small');
                    hint.className = 'text-muted';
                    hint.innerText = 'Preview tidak tersedia di browser ini (kemungkinan codec tidak didukung). File akan tetap diunggah.';
                    placeholder.appendChild(nameEl);
                    placeholder.appendChild(hint);
                    el.parentNode.replaceChild(placeholder, el);
                });
            el.parentNode.replaceChild(newVideo, el);
        }
        // Also update modal player (if present) so preview in modal shows selected file
        try {
            const modalPlayer = document.getElementById(target_preview + '_player');
            if (modalPlayer) {
                while (modalPlayer.firstChild) modalPlayer.removeChild(modalPlayer.firstChild);
                const msource = document.createElement('source');
                msource.src = objectUrl;
                msource.type = file.type || 'video/mp4';
                modalPlayer.appendChild(msource);
                modalPlayer.load();
                modalPlayer.controls = true;
            }
        } catch (e) { console.warn(e); }
        return;
    }

    // fallback: for other file types, show filename
    if (el.tagName.toLowerCase() === 'div') {
        el.innerHTML = '<small>' + file.name + '</small>';
    } else {
        try { el.src = ''; } catch (e) {}
    }
}

let open_file = (target, target_preview) => {
    const inputEl = document.getElementById(target);
    if (!inputEl) return console.warn('open_file: input not found', target);
    // remove any previous change listeners to avoid duplicate handlers
    const newInputEl = inputEl.cloneNode(true);
    inputEl.parentNode.replaceChild(newInputEl, inputEl);
    newInputEl.addEventListener('change', (event) => preview_file(event, target_preview));
    newInputEl.click();
};

let remove_file = (target, target_preview, default_url) => {
    console.log(target);
    const inputEl = document.getElementById(target);
    if (inputEl) inputEl.setAttribute('value', '1');
    const previewEl = document.getElementById(target_preview);
    if (!previewEl) return;
    // If preview is an <img>, set src to default_url
    if (previewEl.tagName.toLowerCase() === 'img') {
        previewEl.src = default_url;
        return;
    }
    // If preview is a <video>, remove sources and show empty poster or default_url
    if (previewEl.tagName.toLowerCase() === 'video') {
        while (previewEl.firstChild) previewEl.removeChild(previewEl.firstChild);
        if (default_url) {
            const source = document.createElement('source');
            source.src = default_url;
            previewEl.appendChild(source);
            previewEl.load();
        } else {
            // replace with a placeholder div
            const placeholder = document.createElement('div');
            placeholder.id = target_preview;
            placeholder.className = previewEl.className + ' d-flex align-items-center justify-content-center';
            placeholder.style.minHeight = '120px';
            placeholder.style.background = '#f5f5f5';
            placeholder.style.color = '#666';
            placeholder.innerHTML = '<small>Tidak ada video</small>';
            previewEl.parentNode.replaceChild(placeholder, previewEl);
        }
        // also clear modal player if exists
        try {
            const modalPlayer = document.getElementById(target_preview + '_player');
            if (modalPlayer) {
                while (modalPlayer.firstChild) modalPlayer.removeChild(modalPlayer.firstChild);
                // replace with placeholder text
                const ph = document.createElement('div');
                ph.className = 'text-center text-muted';
                ph.innerHTML = 'Tidak ada video untuk ditampilkan.';
                modalPlayer.parentNode.replaceChild(ph, modalPlayer);
            }
        } catch (e) {}
        return;
    }
    // Fallback: set innerHTML for container
    previewEl.innerHTML = default_url ? '<img src="' + default_url + '"/>' : '';
};

let setCookie = (name, value, days) => {
    const item = { value: value }; // Store the value

    if (days) {
        const now = new Date();
        item.expiry = now.getTime() + (days * 24 * 60 * 60 * 1000); // Calculate expiration time in milliseconds
    }

    localStorage.setItem(name, JSON.stringify(item)); // Store the value as a JSON string
}


let getCookie = (name) => {
    const itemStr = localStorage.getItem(name); // Get the stored item

    if (!itemStr) {
        return null; // If the item doesn't exist, return null
    }

    const item = JSON.parse(itemStr); // Parse the JSON string

    if (item.expiry) {
        const now = new Date();
        if (now.getTime() > item.expiry) {
            localStorage.removeItem(name); // If the item is expired, remove it
            return null; // Return null for expired items
        }
    }

    return item.value; // Return the stored value
}


let clearAnswerCookies = () => {
    for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i); // Get each key from localStorage

        // Check if the key starts with "answer_"
        if (key && key.startsWith("answer_")) {
            localStorage.removeItem(key); // Remove the item from localStorage
        }
    }
}
