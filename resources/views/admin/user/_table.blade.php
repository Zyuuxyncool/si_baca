<div class="table-responsive">
    <table class="table align-middle table-row-dashed fs-6 table-sm">
        <thead>
        <tr class="text-start bg-secondary text-dark fw-bold fs-7 text-uppercase border-bottom-0">
            <th class="w-10px ps-4 rounded-start">#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th class="text-center w-50px pe-4 rounded-end">Aksi</th>
        </tr>
        </thead>
        <tbody class="">
        @php($no = 1)
        @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
            @php($no = (($users->currentPage()-1) * $users->perPage()) + 1)
        @endif
        @foreach($users as $user)
            <tr>
                <td class="ps-4">{{ $no++ }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ join(', ', $user->list_akses->pluck('akses')->toArray()) }}</td>
                <td class="text-end text-nowrap">
                    <button class="btn btn-sm btn-secondary ps-7" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Action <i class="ki-duotone ki-down fs-5 ms-1"></i>
                    </button>
                    <div class="menu menu-sub menu-sub-dropdown dropdown-menu menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-auto py-4" data-kt-menu="true">
                        <div class="menu-item px-3"><a onclick="info({{ $user->id }})" href="javascript:void(0)" class="menu-link px-3">Edit</a></div>
                        @if(empty($user->buyer) && empty($user->seller) && empty($user->sekolah))
                            <div class="menu-item px-3"><a onclick="confirm_delete({{ $user->id }})" href="javascript:void(0)" class="menu-link px-3">Delete</a></div>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex flex-row justify-content-center">
    @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
        {{ $users->links('vendor.pagination.custom') }}
    @endif
</div>
