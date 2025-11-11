<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apa itu Si Baca?</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS Dasar */
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #1a1a1a;
            /* overflow: hidden; Dihapus agar tidak membatasi konten yang keluar batas */
        }

        /* Container utama untuk efek perbatasan dan konten */
        .color-container {
            width: 100%;
            height: 100vh;
            /* Mengambil seluruh tinggi viewport */
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Bagian Atas: Hijau Muda Kekuningan (untuk Logo & Judul) */
        .top-half {
            background: linear-gradient(to bottom, #d2e47c 0%, #c8d96f 100%);
            flex: 1;
            border-bottom: 2px solid white;
            box-sizing: border-box;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            /* Perubahan: Tambahkan padding bawah untuk memberi ruang bagi logo yang tumpang tindih */
            padding-bottom: 50px;
        }

        /* Bagian Bawah: Hijau Zaitun Tua (untuk Deskripsi) */
        .bottom-half {
            background: linear-gradient(to bottom, #7fb83e 0%, #5d9037 100%);
            flex: 1;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            /* Perubahan: Tambahkan padding atas agar deskripsi tidak tertutup logo */
            padding-top: 100px;
        }

        .content-wrapper {
            max-width: 1200px;
            width: 90%;
            height: 100%;
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 50px;
            /* align-items: center; */
            padding: 0 87px;
        }

        /* Tata Letak Bagian Atas */
        .top-half .content-wrapper {
            grid-template-columns: 1fr 1.5fr;
        }

        .logo-section {
            /* Perubahan: Hapus properti height: 100% yang membatasi tinggi logo */
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 10;
            padding: 0;
        }

        /* LOGO BARU (Gambar kedua) - Perbesaran dan Tumpang Tindih */
        .sibaca-logo-img {
            position: absolute;
            left: 70%;
            transform: translate(-60%);
            height: 247%;
            max-width: 350%;
            bottom: -478px;
            object-fit: contain;
            filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.4));
        }

        .title-section {
            text-align: left;
            padding-top: 125px;
        }

        .title-section h2 {
            font-size: 72px;
            font-weight: 900;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            line-height: 1.1;
        }

        .title-section .text-dark-green {
            color: #4a7c2e;
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.5);
        }

        /* Tata Letak Bagian Bawah */
        .bottom-half .content-wrapper {
            /* grid-template-columns: 1fr 1.5fr; */
        }

        .description-placeholder {
            /* Kolom kiri pada bagian bawah (kosong di gambar asli) */
            height: 100%;
        }

        .description-section {
            color: white;
            text-align: left;
            padding-right: 20px;
        }

        .description-section p {
            font-size: 20px;
            line-height: 1.6;
        }

        /* Responsif untuk Mobile */
        @media (max-width: 768px) {

            /* Mobile layout: stack cleanly and scale assets for small screens */
            .top-half,
            .bottom-half {
                flex: none;
                height: auto;
                min-height: auto;
                padding: 16px 12px;
            }

            /* ensure container fills viewport so body background doesn't show */
            .color-container {
                height: auto;
                min-height: 100vh;
            }

            .content-wrapper {
                grid-template-columns: 1fr;
                gap: 8px;
                padding: 0 14px;
            }

            /* Show a small podium/logo on mobile (scaled & centered) instead of the huge desktop version */
            .logo-section {
                display: block;
                order: 1;
                height: auto;
                padding: 6px 0 0 0;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .sibaca-logo-img {
               display: block;
                position: relative;
                left: auto;
                transform: none;
                height: auto;
                width: 500px;
                max-width: 300%;
                bottom: -115px;
                object-fit: contain;
                margin: 0 auto 12px auto;
                filter: drop-shadow(0 6px 18px rgba(0, 0, 0, 0.2));
            }

            /* Shrink the nav logo on mobile so it doesn't dominate the header */
            nav img { height: 44px; }

            /* Center the title on mobile and tighten spacing so it wraps neatly; move it slightly down */
            .title-section {
                order: 2;
                text-align: center;
                padding: 147px 0px 0 12px; /* increased top padding to push title down */
            }

            .title-section h2 {
                font-size: 38px;
                line-height: 1.02;
                margin: 6px 0 0 0;
                font-weight: 900;
                letter-spacing: -0.5px;
            }

            /* Make the description fill the green background: stretch full width but keep text left-aligned */
            .description-section {
                order: 3;
                text-align: left; /* paragraph text remains left aligned */
                display: flex;
                justify-content: stretch; /* make the paragraph fill available width */
                padding: 18px 12px 8px 12px;
            }

            .bottom-half {
                padding-top: 6px;
                padding-bottom: 28px;
            }

            .description-section p {
                font-size: 15px;
                line-height: 1.6;
                max-width: none;
                width: 100%;
                margin: 0;
                text-align: left;
                padding: 0 8px; /* small inner padding so text isn't flush to edges */
            }

            /* hide the empty left placeholder column on mobile so description can occupy full area */
            .description-placeholder { display: none; }

            /* Make top and bottom halves each occupy about half the viewport on mobile so there's no gap */
            .top-half { min-height: 48vh; }
            .bottom-half { min-height: 52vh; }
        }
    </style>
</head>

<body>
    <nav class="fixed top-0 left-0 w-full flex items-center justify-between md:justify-start px-6 md:px-16 py-4 bg-[#ffffff00] z-50">
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

        <button id="menu-btn" class="md:hidden p-2 rounded-full shadow-md flex items-center justify-center" aria-label="Buka menu"
            style="background-color: rgba(255,255,255,0.12); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);">
            <!-- Hamburger icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </nav>

    <div id="menu" class="hidden fixed top-[88px] left-4 right-4 bg-transparent text-white text-center py-4 space-y-2 z-40 shadow-lg rounded-lg"
        style="background-color: rgba(255,255,255,0.12); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);">
        <ul class="flex flex-col gap-2">
            <li><a href="{{ route('user.landing.index') }}" class="block py-2 text-lg font-semibold rounded text-white hover:text-orange-500 transition">Beranda</a></li>
            <li><a href="{{ route('user.cerita.index') }}" class="block py-2 text-lg font-semibold rounded text-white hover:text-orange-500 transition">Cerita</a></li>
            <li><a href="{{ route('user.si_baca.index') }}" class="block py-2 text-lg font-semibold rounded text-white hover:text-orange-500 transition">Si Baca</a></li>
            <li><a href="{{ route('user.logo.index') }}" class="block py-2 text-lg font-semibold rounded text-white hover:text-orange-500 transition">Logo</a></li>
            <li><a href="{{ route('user.kontak.index') }}" class="block py-2 text-lg font-semibold rounded text-white hover:text-orange-500 transition">Kontak</a></li>
        </ul>
    </div>
    <div class="color-container">
        <div class="top-half">
            <div class="content-wrapper">

                <div class="logo-section">
                    <img src="{{ asset('images/8_20251030_185101_0000.png') }}" alt="Logo Si Baca di Podium"
                        class="sibaca-logo-img">
                </div>

                <div class="title-section">
                    <h2>
                        Apa itu <br>
                        <span class="text-dark-green">Si Baca?</span>
                    </h2>
                </div>

            </div>
        </div>

        <div class="bottom-half">
            <div class="content-wrapper">

                <div class="description-placeholder"></div>

                <div class="description-section">
                    <p>
                        <strong>Si Baca (Sidoarjo Membaca)</strong> adalah sebuah platform digital yang didedikasikan
                        untuk membangkitkan semangat literasi, khususnya di kalangan generasi muda, agar
                        mereka dapat menyelami dan memahami kekayaan sejarah yang tersimpan di
                        Kabupaten Sidoarjo.
                    </p>
                </div>

            </div>
        </div>
    </div>
</body>
   <script>
        // Toggle mobile menu
        const menuBtn = document.getElementById('menu-btn');
        const menu = document.getElementById('menu');

        menuBtn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</html>
