<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-1">
    @php($breadcrumbs = $breadcrumbs ?? [])
    @foreach($breadcrumbs as $breadcrumb)
        <li class="breadcrumb-item text-muted">
            <a href="{{ has_route($breadcrumb['route'], $breadcrumb['params'] ?? []) }}" class="text-muted text-hover-primary">{{ $breadcrumb['caption'] }}</a>
        </li>
        @if($breadcrumb != last($breadcrumbs))
            <li class="breadcrumb-item">
                <span class="bullet bg-gray w-5px h-2px"></span>
            </li>
        @endif
    @endforeach
</ul>
