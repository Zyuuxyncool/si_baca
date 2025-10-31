<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS Dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            /* Latar belakang gradient */
            background: linear-gradient(135deg, #4b8329 0%, #c8d96f 50%, #a8b830 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* padding: 20px; */
            /* Penyesuaian: Tambahkan padding atas agar konten tidak tertutup navbar fixed */
            /* padding-top: 120px; */
            overflow: hidden;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            gap: 80px;
            align-items: center;
            justify-content: center;
        }

        /* --- Bagian Kiri (Teks) --- */
        .left-section {
            flex: 1;
        }

        .left-section h1 {
            font-size: 120px;
            font-weight: 900;
            color: #4a3f2a;
            margin-bottom: 20px;
            line-height: 1;
        }

        .left-section h2 {
            font-size: 48px;
            font-weight: 700;
            color: #2c2415;
            margin-bottom: 10px;
        }

        .left-section p {
            font-size: 32px;
            color: #2c2415;
        }

        /* --- Bagian Kanan (Gambar Tunggal) --- */
        .right-section {
            /* Flex basis untuk menjaga ukuran di desktop */
            flex: 0 0 400px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .right-section img {
            position: absolute;
            left: 0%;
            transform: translate(43%);
            height: 100%;
            max-width: 80%;
            bottom: -50px;
            object-fit: contain;
            filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.4));
        }

        /* --- Media Queries --- */
        @media (max-width: 968px) {
            body {
                /* Kurangi padding atas untuk perangkat mobile */
                padding-top: 80px;
            }

            .container {
                flex-direction: column;
                gap: 40px;
                text-align: center;
            }

            .left-section h1 {
                font-size: 80px;
            }

            .left-section h2 {
                font-size: 36px;
            }

            .left-section p {
                font-size: 24px;
            }

            .right-section {
                flex: 0 0 auto;
                width: 80%;
                /* Atur lebar di mobile */
                max-width: 400px;
            }
        }

        @media (max-width: 480px) {
            .left-section h1 {
                font-size: 60px;
            }

            .left-section h2 {
                font-size: 28px;
            }

            .left-section p {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <nav class="fixed top-0 left-0 w-full flex items-center justify-start px-6 md:px-16 py-4 bg-[#ffffff00] z-50">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/1759230372585.png') }}" alt="Logo" class="h-[80px] md:h-[100px] w-auto">
        </div>

        <ul class="hidden p-4 md:flex space-x-8 text-white font-semibold drop-shadow-md">
             <li><a href="{{ route('user.landing.index') }}" class="hover:text-yellow-200 transition">Beranda</a></li>
            <li><a href="{{ route('user.cerita.index') }}" class="hover:text-yellow-200 transition">Cerita</a></li>
            <li><a href="{{ route('user.si_baca.index') }}" class="hover:text-yellow-200 transition">Si Baca</a></li>
            <li><a href="{{ route('user.logo.index') }}" class="hover:text-yellow-200 transition">Logo</a></li>
            <li><a href="{{ route('user.kontak.index') }}" class="hover:text-yellow-200 transition">Kontak</a></li>
        </ul>

        <button class="md:hidden text-white text-3xl" id="menu-btn">☰</button>
    </nav>

    <div id="menu"
        class="hidden fixed top-[64px] left-0 w-full bg-[#5AA03E] text-white text-center py-3 space-y-2 z-40 shadow-md">
        <a href="#" class="block hover:text-yellow-200">Cerita</a>
        <a href="#" class="block hover:text-yellow-200">Si Baca</a>
        <a href="#" class="block hover:text-yellow-200">Logo</a>
        <a href="{{ route('user.kontak.index') }}" class="block hover:text-yellow-200">Kontak</a>
    </div>

    <div class="container">
        <div class="left-section">
            <h1>Kontak</h1>
            <h2>Email</h2>
            <p>sibaccproject@gmail.com</p>
        </div>

        <div class="right-section">
            <img src="{{ asset('images/12_20251030_185102_0004[1].png') }}" alt="Kotak Masukan Pesan" class="">
        </div>
    </div>

    <script>
        // Toggle mobile menu
        const menuBtn = document.getElementById('menu-btn');
        const menu = document.getElementById('menu');

        menuBtn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</body>

</html>
