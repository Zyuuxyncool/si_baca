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
            grid-template-columns: 1fr 1.5fr;
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

            .top-half,
            .bottom-half {
                flex: none;
                height: auto;
                min-height: 50vh;
                padding: 30px 20px;
            }

            .color-container {
                height: auto;
            }

            .content-wrapper {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .title-section {
                text-align: center;
                order: 1;
            }

            .logo-section {
                order: 2;
                /* Perubahan: Atur tinggi spesifik untuk penempatan logo absolute di mobile */
                height: 250px;
            }

            .sibaca-logo-img {
                /* Perubahan: Sedikit lebih kecil di mobile */
                height: 120%;
                bottom: -30px;
            }

            .description-section {
                order: 3;
                text-align: center;
                padding-right: 0;
            }

            .bottom-half {
                /* Perubahan: Padding atas disesuaikan di mobile */
                padding-top: 50px;
            }

            .title-section h2 {
                font-size: 48px;
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

</html>
