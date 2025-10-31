<div id="kt_header" class="header align-items-stretch">
    <div class="container-xxl d-flex align-items-stretch justify-content-between">
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 w-lg-auto me-5">
            @if (count($current_side_menu ?? []) > 0)
                <div class="btn btn-icon btn-active-icon-primary ms-n2 me-2 d-flex d-lg-none" id="kt_aside_toggle">
                    <i class="ki-duotone ki-abstract-14 fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            @endif
            <a href="{{ url('') }}">
                <h1 class="header-title mb-0">Nusantara <span class="text-siap">Store</span></h1>
            </a>
        </div>

        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
            <div class="d-flex align-items-stretch ms-6" id="kt_header_nav">
                @include('admin.layouts._menu')
            </div>
            <div class="d-flex align-items-stretch flex-shrink-0">
                {{--                @include('layouts._notification') --}}
                @auth
                    @include('admin.layouts._roles')
                    @include('admin.layouts._switcher')
                    @include('admin.layouts._user')
                @endauth
                <div class="d-flex d-lg-none align-items-center" title="Show header menu">
                    <button class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px"
                        id="kt_header_menu_mobile_toggle">
                        <i class="ki-duotone ki-text-align-left fs-1 fw-bold"><span class="path1"></span><span
                                class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
