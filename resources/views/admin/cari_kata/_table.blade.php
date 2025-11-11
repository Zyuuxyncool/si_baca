<div class="table-responsive">
    <table class="table align-middle table-row-dashed fs-6 table-sm">
        <thead>
        <tr class="text-center bg-secondary text-dark fw-bold fs-7 text-uppercase border-bottom-0">
            <th class="w-10px text-center rounded-start">#</th>
            <th class="text-start">Title</th>
            <th class="text-center">Cerita</th>
            <th class="text-center">Grid</th>
            <th class="text-center"># Words</th>
            <th class="text-center">Points</th>
            <th class="text-center">Active</th>
            <th class="text-center">Created</th>
            <th class="text-center w-50px pe-4 rounded-end">Aksi</th>
        </tr>
        </thead>
        <tbody class="">
        @php
            $no = 1;
        @endphp
        @if($templates instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php
                $no = (($templates->currentPage()-1) * $templates->perPage()) + 1;
            @endphp
        @endif
        @foreach($templates as $template)
            @php
                $words = [];
                if (!empty($template->content) && is_array($template->content)) {
                    if (isset($template->content['words']) && is_array($template->content['words'])) $words = $template->content['words'];
                    elseif (isset($template->content[0])) $words = $template->content; // support direct array
                }
            @endphp
            <tr>
                <td class="text-center align-middle">{{ $no++ }}</td>
                <td class="align-middle">{{ $template->title ?? '-' }}</td>
                <td class="text-center align-middle">{{ optional($template->cerita)->nama ?? '-' }}</td>
                <td class="text-center align-middle">{{ $template->grid_rows ?? '-' }} x {{ $template->grid_cols ?? '-' }}</td>
                <td class="text-center align-middle">{{ count($words) }}</td>
                <td class="text-center align-middle">{{ $template->points_default ?? '-' }}</td>
                <td class="text-center align-middle">{{ $template->active ? 'Yes' : 'No' }}</td>
                <td class="text-center align-middle">{{ $template->created_at ? $template->created_at->format('Y-m-d') : '-' }}</td>
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
