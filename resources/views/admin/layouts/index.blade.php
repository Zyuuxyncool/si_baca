@extends('admin.layouts.layout')

@section('body-class')
    header-fixed header-tablet-and-mobile-fixed {{ (count($current_menu['side_menus'] ?? []) > 0) || (count($current_sub_menu['side_menus'] ?? []) > 0) ? 'aside-enabled' : '' }}
@endsection

@section('body')
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-column flex-column-fluid">
            @include('admin.layouts._header')

            <div id="kt_content_container" class="d-flex flex-column-fluid align-items-stretch container-xxl">
                @include('admin.layouts._side_menu')
                <div class="wrapper d-flex flex-column flex-row-fluid mt-5 mb-15" id="kt_wrapper">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
@endsection
