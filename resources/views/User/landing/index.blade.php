<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Si Baca - Sidoarjo Membaca</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex flex-col bg-gradient-to-b from-[#5AA03E] via-[#A5D65E] to-[#E2F8A8]">

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
        <a href="#" class="block hover:text-yellow-200">Kontak</a>
    </div>

    <section
        class="relative flex items-center justify-center px-6 md:px-16 pt-32 md:pt-40 pb-20 md:pb-28 overflow-hidden flex-grow min-h-screen">

        <!-- Background kiri -->
        <img src="{{ asset('images/Gelombang_2-removebg-preview.png') }}" alt="Ilustrasi kiri"
            class="absolute bottom-0 left-0 w-56 md:w-[1000px] select-none pointer-events-none drop-shadow-md">

        <!-- Background kanan -->
        <img src="{{ asset('images/Gelombang_1-removebg-preview.png') }}" alt="Ilustrasi kanan"
            class="absolute top-0 right-[-40px] md:right-[-80px] w-72 md:w-[1000px] select-none pointer-events-none drop-shadow-md">

        <!-- Konten utama -->
        <div class="z-10 max-w-lg">
            <h1 class="text-5xl md:text-8xl font-extrabold text-white drop-shadow-[4px_4px_0px_rgba(0,0,0,0.25)]">
                Si Baca
            </h1>
            <p
                class="text-lg md:text-xl mt-3 text-white font-semibold drop-shadow-[2px_2px_2px_rgba(0,0,0,0.3)] text-right">
                Menyibak Sejarah <br> Menginspirasi Anak Bangsa
            </p>

            <a href="{{ route('login') }}">
                <button
                    class="mt-8 bg-white text-black text-xl md:text-2xl font-extrabold px-12 py-4 rounded-full shadow-lg hover:bg-yellow-100 transition">
                    Login / Register
                </button>
            </a>

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
