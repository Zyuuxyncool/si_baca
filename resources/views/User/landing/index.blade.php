<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="google-site-verification" content="C7CVEZZatgXwzrkD-0wsqeHrZa7tQsYxU_MPxfgEdFA" />
<meta name="description" content="Si Baca adalah platform literasi digital dari Sidoarjo yang menyibak sejarah dan menginspirasi anak bangsa.">

    <title>Si Baca - Sidoarjo Membaca</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex flex-col bg-gradient-to-b from-[#5AA03E] via-[#A5D65E] to-[#E2F8A8]">

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

    <!-- Mobile sliding panel (modern look) -->
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

    <section
        class="relative flex items-center justify-center px-6 md:px-16 pt-32 md:pt-40 pb-20 md:pb-28 overflow-hidden flex-grow min-h-screen">

        <!-- Background kiri (diperbesar untuk layar hp) -->
           <img src="{{ asset('images/Gelombang_2-removebg-preview.png') }}" alt="Ilustrasi kiri"
               class="absolute bottom-0 left-[-0px] w-[700px] md:left-0 md:w-[1000px] select-none pointer-events-none drop-shadow-md">

        <!-- Background kanan (diperbesar untuk layar hp) -->
           <img src="{{ asset('images/Gelombang_1-removebg-preview.png') }}" alt="Ilustrasi kanan"
               class="absolute top-0 right-[-80px] md:right-[-80px] w-[700px] md:w-[1000px] select-none pointer-events-none drop-shadow-md">

        <!-- Konten utama -->
        <div class="z-10 max-w-lg">
            <h1 class="text-5xl md:text-8xl font-extrabold text-white drop-shadow-[4px_4px_0px_rgba(0,0,0,0.25)]">
                Si Baca
            </h1>
            <p
                class="text-lg md:text-xl mt-3 text-white font-semibold drop-shadow-[2px_2px_2px_rgba(0,0,0,0.3)] text-right">
                Menyibak Sejarah <br> Menginspirasi Anak Bangsa
            </p>

            @guest
                <a href="{{ route('login') }}">
                    <button
                        class="mt-8 bg-white text-black text-xl md:text-2xl font-extrabold px-12 py-4 rounded-full shadow-lg hover:bg-yellow-100 transition">
                        Login / Register
                    </button>
                </a>
            @else
                <a href="{{ route('logout') }}">
                    <button
                        class="mt-8 bg-white text-black text-xl md:text-2xl font-extrabold px-12 py-4 rounded-full shadow-lg hover:bg-red-100 transition">
                        Logout
                    </button>
                </a>
            @endguest

        </div>

    </section>


    <script>
        const menuBtn = document.getElementById('menu-btn');
        const menu = document.getElementById('menu');
        menuBtn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</body>

</html>
