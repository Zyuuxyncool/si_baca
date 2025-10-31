<div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
    <div class="menu menu-rounded menu-column gap-lg-1 menu-lg-row menu-root-here-bg-desktop menu-active-bg menu-state-primary menu-title-gray-800 menu-arrow-gray-400 align-items-stretch my-5 my-lg-0 px-2 px-lg-0 fw-semibold fs-6" id="#kt_header_menu" data-kt-menu="true">
        @php($menus = $menus ?? [])
        @foreach($menus as $menu)
            @if(empty($menu['sub_menus']))
                <a href="{{ has_route($menu['route'], $menu['params'] ?? []) }}" class="menu-item {{ ($current_menu ?? []) === $menu ? 'here show' : '' }}">
                    <span class="menu-link py-3"><span class="menu-title">{{ $menu['caption'] }}</span></span>
                </a>
            @endif
            @if(!empty($menu['sub_menus']))
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item {{ ($current_menu ?? []) === $menu ? 'here' : '' }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                    <span class="menu-link py-3"><span class="menu-title">{{ $menu['caption'] }}</span><span class="menu-arrow d-lg-none"></span></span>
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 min-w-lg-200px">
                        @foreach($menu['sub_menus'] as $sub_menu)
                            <div class="menu-item">
                                <a class="menu-link {{ ($current_sub_menu ?? []) === $sub_menu ? 'active' : '' }} py-3" href="{{ has_route($sub_menu['route'], $sub_menu['params'] ?? []) }}">
                                    <span class="menu-title">{{ $sub_menu['caption'] }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
