<div class="table-responsive">
    <table class="table align-middle table-row-dashed fs-6 table-sm">
        <thead>
        <tr class="text-center bg-secondary text-dark fw-bold fs-7 text-uppercase border-bottom-0">
            <th class="w-10px text-center rounded-start">#</th>
            <th class="text-center">Nama</th>
            <th class="text-center">Photo</th>
            <th class="text-center">Video</th>
            <th class="text-center w-50px pe-4 rounded-end">Aksi</th>
        </tr>
        </thead>
        <tbody class="">
        @php($no = 1)
        @if($ceritas instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($ceritas->currentPage()-1) * $ceritas->perPage()) + 1)
        @endif
        @foreach($ceritas as $cerita)
            <tr>
                <td class="text-center align-middle">{{ $no++ }}</td>
                <td class="text-center align-middle">{{ $cerita->nama }}</td>
                <td class="py-0 align-middle text-center">
                    @if(!empty($cerita->photo))
                        <x-preview-image :file="$cerita->photo" id="preview_image_{{ $cerita->id }}" thumbClass="h-30px w-30px object-fit-cover d-inline-block" />
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td class="py-0 align-middle text-center">
                    @if(!empty($cerita->video))
                        <x-preview-video :file="$cerita->video" id="preview_video_{{ $cerita->id }}" :modelId="$cerita->id" :processing="$cerita->video_processing" buttonText="View Video" btnClass="btn btn-secondary btn-sm" />
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td class="text-center text-nowrap align-middle">
                    <button class="btn btn-sm btn-secondary ps-7" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Action <i class="ki-duotone ki-down fs-5 ms-1"></i>
                    </button>
                    <div class="menu menu-sub menu-sub-dropdown dropdown-menu menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-auto py-4" data-kt-menu="true">
                        <div class="menu-item px-3"><a onclick="info({{ $cerita->id }})" href="javascript:void(0)" class="menu-link px-3">Edit</a></div>
                            <div class="menu-item px-3"><a onclick="confirm_delete({{ $cerita->id }})" href="javascript:void(0)" class="menu-link px-3">Delete</a></div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex flex-row justify-content-center">
    @if($ceritas instanceof \Illuminate\Pagination\LengthAwarePaginator)
        {{ $ceritas->links('vendor.pagination.custom') }}
    @endif
</div>
