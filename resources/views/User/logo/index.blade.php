<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logo Si Baca</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #c8d96f 0%, #7fb83e 50%, #4a7c2e 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Curved corner decoration - One Full Circle */
        .corner-decoration {
            position: absolute;
            top: 50%;
            right: -360px;
            width: 730px;
            height: 730px;
            background: #4a7c2e;
            border-radius: 50%;
            z-index: 1;
            transform: translateY(-50%);
        }

        /* Bottom right corner decoration - Hidden */
        .corner-decoration-bottom {
            display: none;
        }

        .container {
            position: relative;
            z-index: 10;
            max-width: 1400px;
            margin: 0 auto;
            padding: 140px 40px 60px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .left-section {
            position: relative;
        }

        h1 {
            font-size: 96px;
            font-weight: 900;
            color: white;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
        }

        .description {
            color: white;
            font-size: 18px;
            line-height: 1.8;
            text-align: justify;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .description strong {
            font-weight: 700;
        }

        .highlight-text {
            font-weight: 700;
        }

        .right-section {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo-container {
            position: relative;
            width: 450px;
            height: 300px;
        }

        .logo-image {
            width: 100%;
            height: auto;
            filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.3));
        }

        @media (max-width: 1200px) {
            .container {
                grid-template-columns: 1fr;
                gap: 60px;
                padding: 140px 30px 60px;
            }

            h1 {
                font-size: 72px;
            }

            .logo-container {
                width: 350px;
                height: auto;
            }

            .corner-decoration {
                width: 700px;
                height: 700px;
                right: -120px;
            }
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 56px;
            }

            .description {
                font-size: 16px;
            }

            /* Tidy mobile logo: center, scale, and avoid clipping */
            .logo-container {
                width: 220px; /* slightly smaller so it fits */
                height: auto;
                margin: 0 auto;
                display: block;
                position: relative;
                top: 0;
            }

            .logo-image {
                width: 100%;
                height: auto;
                display: block;
                max-height: 280px; /* avoid very tall images that overflow */
            }

            /* Remove large decoration on mobile so it doesn't cover content */
            .corner-decoration {
                display: none;
            }

            /* Ensure the nav and hamburger sit above everything */
            nav { z-index: 99999; }
            #menu-btn { z-index: 100000; }

            /* Hide right-side image and show only the text column on mobile */
            .right-section { display: none; }

            /* Make left column (text) full width and add comfortable padding */
            .container {
                grid-template-columns: 1fr;
                padding: 150px 35px 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Curved corner decoration -->
    <div class="corner-decoration"></div>
    <div class="corner-decoration-bottom"></div>

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

    <!-- Main Content -->
    <div class="container">
        <div class="left-section">
            <h1 class="text-3xl md:text-8xl font-extrabold text-white drop-shadow-[4px_4px_0px_rgba(0,0,0,0.25)]">
                Logo
            </h1>
            <br>
            <div class="description">
                <p class="mb-6">
                    Logo <strong>Si Baca</strong> dirancang secara khusus dan filosofis, merangkum makna yang mendalam.
                    Desainnya berlandaskan implementasi logo Kabupaten Sidoarjo, yang terdiri dari lima elemen utama:
                    <strong>udang, ikan bandeng, bintang, padi, dan kapas.</strong> Kelima aksen ini dipadukan dengan
                    palet warna cerah, yang mencerminkan semangat literasi yang riang dan menyenangkan.
                </p>

                <p>
                    Kata <strong>Si Baca</strong> dikelilingi oleh lima aksen utama logo Kabupaten Sidoarjo dan diakhiri
                    dengan tumpuan sebuah buku. Visualisasi ini melambangkan dedikasi dan semangat Sidoarjo dalam
                    berliterasi melalui platform digital ini. Tak lupa, sentuhan motif batik ditambahkan pada logo untuk
                    mengingatkan bahwa kearifan lokal tak akan pernah pudar oleh waktu, melainkan akan selalu abadi
                    seiring dengan kemajuan zaman.
                </p>
            </div>
        </div>

        <div class="right-section">
            <div class="logo-container">
                <img src="{{ asset('images/Logo_Si_Baca_fix_GreenScreen-removebg-preview.png') }}" alt="Logo Si Baca"
                    class="logo-image">
            </div>
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
