@php($notifications = $notifications ?? [])
@auth
    <div class="d-flex align-items-center ms-1 ms-lg-2">
        <div class="btn btn-icon btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px position-relative" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            <i class="ki-duotone ki-notification-on fs-1"><span class="path1"></span><span class="path2"></span></i>
        </div>
        <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-500px" data-kt-menu="true" id="kt_menu_notifications">
            <div class="d-flex flex-column" role="tabpanel">
                <div class="scroll-y mh-325px my-5 px-8">
                    @if(count($notifications) == 0)
                        <div class="text-center my-10"><i> - No new notification - </i></div>
                    @endif
                    @foreach($notifications as $notification)
                        @if($notification->flag === 0)
                            <div class="d-flex flex-stack py-4">
                                <div class="d-flex align-items-center">
                                    <div class="mb-0 me-2">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">{{ $notification->title }}</a>
                                        <div class="text-gray-400 fs-7">{{ $notification->content }}</div>
                                    </div>
                                </div>
                                <span class="badge badge-light fs-8">{{ $notification->created_at }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
                @if(count($notifications) > 0)
                    <div class="py-3 text-center border-top">
                        <a href="{{ has_route('notification') }}" class="btn btn-color-gray-600 btn-active-color-primary">View All
                            <i class="ki-duotone ki-arrow-right fs-5"><span class="path1"></span><span class="path2"></span></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endauth
