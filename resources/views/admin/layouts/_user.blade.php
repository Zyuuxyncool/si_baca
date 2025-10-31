@php($user = auth()->user())
@if(!empty($user))
    <div class="d-flex align-items-center ms-lg-5" id="kt_header_user_menu_toggle">
        <div class="btn btn-active-light d-flex align-items-center bg-hover-light py-2 px-2 px-md-3" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            <div class="d-none d-md-flex flex-column align-items-end justify-content-center me-2">
                <span class="text-dark fs-base fw-bold lh-1">{{ $user->name }}</span>
                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ $active_role }}</a>
            </div>
            <div class="symbol symbol-20px symbol-md-30px ms-3">
                <img src="{{ asset('images/user_menubar.jpg') }}" alt="image" />
            </div>
        </div>
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-auto" data-kt-menu="true">
            <div class="menu-item px-3">
                <div class="menu-content d-flex align-items-center px-3">
                    <div class="symbol symbol-50px me-5">
                        <img alt="Logo" src="{{ asset('images/user_menubar.jpg') }}" />
                    </div>
                    <div class="d-flex flex-column">
                        <div class="fw-bold d-flex align-items-center fs-5">{{ $user->name }}</div>
                        <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ $user->email }}</a>
                    </div>
                </div>
            </div>
            <div class="separator my-2"></div>
            <div class="menu-item px-5">
                <a href="{{ has_route('logout') }}" class="menu-link px-5 d-flex justify-content-between">
                    <span>Sign Out</span>
                    <i class="ki-duotone ki-exit-right fs-2"><span class="path1"></span><span class="path2"></span></i>
                </a>
            </div>
        </div>
    </div>
@endif
