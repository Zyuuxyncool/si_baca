@if(count($all_roles ?? []) > 1)
    <div class="d-flex align-items-center ms-1 ms-lg-2">
        <a href="#" class="btn btn-icon btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px" data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            <i class="ki-duotone ki-shield theme-light-show fs-1">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span>
                <span class="path9"></span><span class="path10"></span>
            </i>
            <i class="ki-duotone ki-moon theme-dark-show fs-1"><span class="path1"></span><span class="path2"></span></i>
        </a>
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-auto" data-kt-menu="true" data-kt-element="theme-mode-menu">
            @foreach($all_roles ?? [] as $role)
                <div class="menu-item px-3 my-0">
                    <a href="{{ route('change_role', $role) }}" class="menu-link px-3 py-2">
                        <span class="menu-title">{{ $role }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
