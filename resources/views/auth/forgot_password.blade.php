@extends('admin.layouts.layout')

@section('title')
    Lupa Password -
@endsection

@section('body-class')
    auth-bg
@endsection

@push('styles')
    <style>
        body {
            background: url('{{ asset('images/bg-login.jpg') }}') no-repeat center;
            background-size: cover;
        }

        [data-bs-theme="dark"] body {
            background: url('{{ asset('images/bg-login.jpg') }}') no-repeat center;
            background-size: cover;
        }
    </style>
@endpush

@section('body')
    <div class="d-flex flex-column flex-center flex-column-fluid">
        <div class="d-flex flex-column flex-center text-center p-10">
            <div class="card card-flush w-lg-650px py-5">
                <div class="card-body py-15 py-lg-20">
                    <form action="{{ route('forgot_password.proses') }}" method="post">
                        @csrf
                        <a href="{{ url('/') }}" class=""><img alt="Logo" src="{{ asset('images/logo.png') }}" class="h-70px mb-6"/></a>
                        <h1 class="fw-bolder text-gray-900 mb-2">Lupa Password</h1>
                        <div class="fs-6 fw-semibold text-gray-500 mb-10">Masukan email kamu untuk mendapatkan link reset password</div>

                        <div class="d-flex flex-column mx-lg-20 mb-6">
                            <x-metronic-input name="email" type="text" caption="Email" placeholder="Email" :viewtype="2" />
                        </div>

                        <div class="d-flex flex-column mx-lg-20 mb-6">
                            <button type="submit" class="btn btn-primary mb-3 ps-8">
                                <i class="ki-duotone ki-entrance-left fs-3 me-0 ms-1"><span class="path1"></span><span class="path2"></span></i>
                                Kirim Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
