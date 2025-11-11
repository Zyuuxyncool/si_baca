@extends('admin.layouts.index')

@section('title')
    Dashboard -
@endsection

@section('content')
    <div class="content flex-column-fluid" id="kt_content">
        <div class="toolbar d-flex flex-stack flex-wrap mb-5 mb-lg-7" id="kt_toolbar">
            <div class="page-title d-flex flex-column py-1">
                <h1 class="d-flex align-items-center my-1"><span class="text-dark fw-bold fs-1">Dashboard</span></h1>
                @include('admin.layouts._breadcrumb')
            </div>
            <div class="d-flex align-items-center py-1 gap-6">
            </div>
        </div>

        <div class="w-100 mx-auto">
        </div>
    </div>
@endsection
