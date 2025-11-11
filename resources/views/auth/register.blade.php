@extends('admin.layouts.layout')

@section('title', 'Register - Nusantara Store')

@section('body-class', 'auth-bg')

@push('styles')
    <style>
        body {
            background: linear-gradient(135deg, #F3E9DD 0%, #D6EADF 50%, #F0E1C0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }

        .register-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.12);
            width: 100%;
            max-width: 440px;
            padding: 45px 50px;
            transition: all 0.3s ease;
        }

        .register-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.15);
        }

        .register-title {
            font-size: 34px;
            font-weight: 800;
            background: linear-gradient(90deg, #9E6B3E, #00796B);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }

        .register-subtitle {
            font-size: 15px;
            color: #6b7280;
        }

        /* Bootstrap-friendly logo sizing */
        .register-logo {
            max-height: 112px; /* desktop default */
            width: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .form-control {
            border-radius: 12px;
            padding: 10px 15px;
            border: 1px solid #E0E0E0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #00796B;
            box-shadow: 0 0 0 3px rgba(0, 121, 107, 0.15);
        }

        .btn-primary {
            background: linear-gradient(90deg, #00796B, #9E6B3E);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #00695C, #8E5F35);
            transform: scale(1.03);
            box-shadow: 0 6px 15px rgba(0, 121, 107, 0.3);
        }

        .login-links a {
            text-decoration: none;
            color: #9E6B3E;
            transition: color 0.3s ease;
        }

        .login-links a:hover {
            color: #7B542C;
        }

        @media (max-width: 576px) {
            .register-card {
                padding: 35px 25px;
                max-width: 90%;
            }
        }
    </style>
@endpush

@section('body')
    <div class="register-card">
        <div class="text-center mb-4">
            <img src="{{ asset('images/1759230372585.png') }}" alt="Logo" class="img-fluid mx-auto d-block register-logo">
            <p class="register-subtitle">Buat akun untuk mulai</p>
        </div>

        <form action="{{ route('register.proses', ['role' => 'User']) }}" method="post">
            @csrf
            <div class="mb-3">
                <x-metronic-input name="name" type="text" caption="Nama Lengkap" placeholder="Masukkan Nama Lengkap"
                    :value="old('name')" :viewtype="2" />
            </div>

            <div class="mb-3">
                <x-metronic-input name="email" type="text" caption="Email" placeholder="Masukkan Email"
                    :value="old('email')" :viewtype="2" />
            </div>

            <div class="mb-3">
                <x-metronic-input name="password" type="password" caption="Password" placeholder="Masukkan Password"
                    :viewtype="2" />
            </div>

            <div class="mb-4">
                <x-metronic-input name="password_confirmation" type="password" caption="Konfirmasi Password"
                    placeholder="Ulangi Password" :viewtype="2" />
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">DAFTAR</button>

            <div class="login-links text-center">
                <a href="{{ route('login') }}" class="fw-semibold">Sudah Punya Akun? Masuk Disini</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        init_form_element()
    </script>
@endpush
