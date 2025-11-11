<div class="table-responsive">
    <table class="table align-middle table-row-dashed fs-6 table-sm">
        <thead>
        <tr class="text-start bg-secondary text-dark fw-bold fs-7 text-uppercase border-bottom-0">
            <th class="w-10px ps-4 rounded-start">#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Pesan/Masukan</th>
            <!-- <th class="text-center w-50px pe-4 rounded-end">Aksi</th> -->
        </tr>
        </thead>
        <tbody class="">
        @php($no = 1)
        @if($masukans instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($masukans->currentPage()-1) * $masukans->perPage()) + 1)
        @endif
        @foreach($masukans as $masukan)
            <tr>
                <td class="ps-4">{{ $no++ }}</td>
                <td class="align-top">{{ $masukan->nama }}</td>
                <td class="align-top text-break" style="max-width:200px;">{{ $masukan->user->email }}</td>
                <td class="align-top text-break" style="max-width:480px; white-space:normal;">{{ $masukan->masukan }}</td>
                <!-- <td class="text-end text-nowrap">
                    <button class="btn btn-sm btn-secondary ps-7" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Action <i class="ki-duotone ki-down fs-5 ms-1"></i>
                    </button>
                    <div class="menu menu-sub menu-sub-dropdown dropdown-menu menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-auto py-4" data-kt-menu="true">
                        <div class="menu-item px-3"><a onclick="info({{ $masukan->id }})" href="javascript:void(0)" class="menu-link px-3">Edit</a></div>
                        @if(empty($masukan->buyer) && empty($masukan->seller) && empty($masukan->sekolah))
                            <div class="menu-item px-3"><a onclick="confirm_delete({{ $masukan->id }})" href="javascript:void(0)" class="menu-link px-3">Delete</a></div>
                        @endif
                    </div>
                </td> -->
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex flex-row justify-content-center">
    @if($masukans instanceof \Illuminate\Pagination\LengthAwarePaginator)
        {{ $masukans->links('vendor.pagination.custom') }}
    @endif
</div>
