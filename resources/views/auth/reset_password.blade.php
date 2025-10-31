@extends('admin.layouts.layout')

@section('title')
    Reset Password -
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
                    <form action="{{ route('reset_password.proses', $token) }}" method="post">
                        @csrf
                        <a href="{{ url('/') }}" class=""><img alt="Logo" src="{{ asset('images/logo.png') }}" class="h-70px mb-6"/></a>
                        <h1 class="fw-bolder text-gray-900 mb-2">Reset Password</h1>
                        <div class="fs-6 fw-semibold text-gray-500 mb-10">Apabila informasi dibawah ini benar user kamu, maka masukan password baru untuk akun kamu</div>

                        <div class="d-flex flex-column mb-6">
                            <p class="mb-0">NIK : {{ $user->masyarakat->nik }}</p>
                            <p class="mb-0 fw-bolder">Nama Lengkap : {{ $user->masyarakat->nama }}</p>
                        </div>

                        <div class="d-flex flex-column mx-lg-20 mb-6">
                            <x-metronic-input name="password" type="password" caption="Password" :viewtype="2" />
                            <x-metronic-input name="password_confirmation" type="password" caption="Ulangi Password" :viewtype="2" />
                        </div>

                        <div class="d-flex flex-column mx-lg-20 mb-6">
                            <button type="submit" class="btn btn-primary mb-3 ps-8">
                                <i class="ki-duotone ki-entrance-left fs-3 me-0 ms-1"><span class="path1"></span><span class="path2"></span></i>
                                Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
