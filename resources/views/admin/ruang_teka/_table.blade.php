<div class="table-responsive">
    <table class="table align-middle table-row-dashed fs-6 table-sm">
        <thead>
        <tr class="text-center bg-secondary text-dark fw-bold fs-7 text-uppercase border-bottom-0">
            <th class="w-10px text-center rounded-start">#</th>
            <th class="text-start">Judul</th>
            <th class="text-center">Cerita</th>
            <th class="text-center">Grid</th>
            <th class="text-center">Jumlah Clue</th>
            <th class="text-center">Poster</th>
            <th class="text-center w-50px pe-4 rounded-end">Aksi</th>
        </tr>
        </thead>
        <tbody class="">
        @php
            $no = 1;
        @endphp
        @if($templates instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php
                $no = (($templates->currentPage() - 1) * $templates->perPage()) + 1;
            @endphp
        @endif
        @foreach($templates as $template)
            @php
                // normalize title and story name
                $title = $template->title ?? $template->nama ?? '—';
                $storyName = $template->cerita_name ?? ($template->cerita->nama ?? ($template->cerita->title ?? '-')) ?? '-';
                // grid rows/cols
                $rows = $template->grid_rows ?? $template->rows ?? null;
                $cols = $template->grid_cols ?? $template->cols ?? null;
                // clues count (across + down)
                $clues = [];
                if (!empty($template->clues)) {
                    if (is_string($template->clues)) {
                        $clues = json_decode($template->clues, true) ?: [];
                    } elseif (is_array($template->clues)) {
                        $clues = $template->clues;
                    }
                }
                $clueCount = 0;
                if (is_array($clues)) {
                    $clueCount += is_array($clues['across'] ?? null) ? count($clues['across']) : 0;
                    $clueCount += is_array($clues['down'] ?? null) ? count($clues['down']) : 0;
                }
                // poster file
                $poster = $template->poster ?? $template->file_poster ?? $template->photo ?? null;
            @endphp
            <tr>
                <td class="text-center align-middle">{{ $no++ }}</td>
                <td class="align-middle">{{ $title }}</td>
                <td class="text-center align-middle">{{ $storyName }}</td>
                <td class="text-center align-middle">{{ $rows && $cols ? $rows . ' x ' . $cols : '-' }}</td>
                <td class="text-center align-middle">{{ $clueCount }}</td>
                <td class="py-0 align-middle text-center">
                    @if(!empty($poster))
                        <x-preview-image :file="$poster" id="preview_image_{{ $template->id }}" thumbClass="h-40px w-40px object-fit-cover d-inline-block" />
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td class="text-center text-nowrap align-middle">
                    <button class="btn btn-sm btn-secondary ps-7" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Action <i class="ki-duotone ki-down fs-5 ms-1"></i>
                    </button>
                    <div class="menu menu-sub menu-sub-dropdown dropdown-menu menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-auto py-4" data-kt-menu="true">
                        <div class="menu-item px-3"><a onclick="info({{ $template->id }})" href="javascript:void(0)" class="menu-link px-3">Edit</a></div>
                        <div class="menu-item px-3"><a onclick="confirm_delete({{ $template->id }})" href="javascript:void(0)" class="menu-link px-3">Delete</a></div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex flex-row justify-content-center">
    @if($templates instanceof \Illuminate\Pagination\LengthAwarePaginator)
        {{ $templates->links('vendor.pagination.custom') }}
    @endif
</div>
