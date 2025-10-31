<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cerita</title>

    <!-- Swiper (Carousel) -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@9/swiper-bundle.min.css" />

    <style>
        :root {
            --bg-start: #b1d56b;
            --bg-end: #7ab34e;
            --card-radius: 26px;
            --card-border: 14px;
            --accent: #ffffff;
            --shadow: 0 24px 40px rgba(0, 0, 0, .25);
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji";
            /* Use the provided circular gradient */
            background: radial-gradient(circle at top right, #f5ffdd 0%, #bdd143 55%, #7ab34e 90%, #5AA03E 100%);
            color: #0b1b0f;
            overflow: hidden;
        }

        .page {
            min-height: 100dvh;
            display: grid;
            grid-template-rows: auto 1fr;
            padding: 20px 18px 48px;
            /* account for fixed nav */
        }

        /* Top navigation (fallback styling if Tailwind isn't loaded) */
        #top-nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 16px clamp(24px, 5vw, 64px);
            background: rgba(255, 255, 255, 0);
            z-index: 50;
        }

        #top-nav ul {
            list-style: none;
            margin: 0;
            padding: 8px 0;
            gap: 32px;
            display: none;
        }

        #top-nav a {
            color: #fff;
            font-weight: 700;
            text-decoration: none;
        }

        #top-nav a:hover {
            color: #fde68a;
        }

        #top-nav img {
            height: clamp(80px, 12vw, 100px);
            width: auto;
            display: block;
        }

        #menu-btn {
            background: none;
            border: none;
            font-size: 28px;
            color: #fff;
            margin-left: auto;
            cursor: pointer;
        }

        @media (min-width: 768px) {
            #top-nav ul {
                display: flex;
            }

            #menu-btn {
                display: none;
            }
        }

        #menu {
            display: none;
            /* hidden by default (fallback when Tailwind isn't present) */
            position: fixed;
            top: 64px;
            left: 0;
            width: 100%;
            background: #5AA03E;
            color: #fff;
            text-align: center;
            padding: 12px 0;
            z-index: 40;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .25);
        }

        @media (min-width: 768px) {
            #menu {
                display: none !important;
            }

            /* never show desktop dropdown */
        }

        #menu a {
            display: block;
            padding: 6px 0;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }

        #menu a:hover {
            color: #fde68a;
        }

        #menu.shown {
            display: block;
        }

        .title {
            text-align: center;
            font-weight: 800;
            font-size: clamp(28px, 5.3vw, 64px);
            color: #fff;
            text-shadow: 0 4px 0 rgba(0, 0, 0, .18);
            letter-spacing: .5px;
            margin: 8px 0 22px;
        }

        /* Swiper sizing */
        .carousel-wrap {
            display: grid;
            place-items: center;
            overflow: visible;
            /* allow side slides to extend fully */
        }

        .swiper {
            width: min(1100px, 94vw);
            padding: 36px 52px;
            /* space for arrows */
            overflow: visible;
            /* prevent clipping of rotated slides top/bottom */
        }

        .swiper-slide {
            width: min(860px, 80vw);
            transition: transform .35s ease, opacity .35s ease, filter .35s ease;
        }

        /* Card look like the reference */
        .story-card {
            position: relative;
            border-radius: var(--card-radius);
            background: #e8f2e1;
            box-shadow: var(--shadow);
            padding: var(--card-border);
            overflow: hidden;
            border: 0 solid transparent;
        }

        .story-card::before {
            /* thick white frame */
            content: "";
            position: absolute;
            inset: 0;
            border-radius: var(--card-radius);
            padding: var(--card-border);
            background: linear-gradient(#fff, #fff) padding-box;
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }

        .story-media {
            position: relative;
            border-radius: calc(var(--card-radius) - 10px);
            overflow: hidden;
            aspect-ratio: 16/10;
            background: linear-gradient(180deg, #dff3ff, #bce7ff 40%, #9ae28a 70%);
        }

        .story-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Title pill at bottom */
        .story-title {
            position: absolute;
            left: 50%;
            bottom: 10px;
            transform: translateX(-50%);
            background: #fff;
            color: #0b1b0f;
            padding: 14px 24px;
            font-weight: 800;
            border-radius: 999px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .20);
            white-space: nowrap;
            font-size: clamp(16px, 2.4vw, 24px);
        }

        /* Dim and tilt side slides to mimic reference */
        .swiper-slide:not(.swiper-slide-active) .story-card {
            filter: grayscale(.2) brightness(.85) contrast(.95);
        }

        .swiper-slide-prev .story-card {
            transform: rotate(-9deg) scale(.92);
        }

        .swiper-slide-next .story-card {
            transform: rotate(9deg) scale(.92);
        }

        /* Lock overlay for non-active slides */
        .lock-overlay {
            position: absolute;
            right: -10px;
            bottom: -10px;
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: rgba(0, 0, 0, .7);
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 28px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .35);
            opacity: 0;
            transition: .25s ease;
        }

        .swiper-slide:not(.swiper-slide-active) .lock-overlay {
            opacity: 1;
        }

        /* Navigation arrows */
        .swiper-button-next,
        .swiper-button-prev {
            width: 54px;
            height: 54px;
            background: #0b0b0b;
            color: #fff;
            border-radius: 50%;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .35);
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 22px;
            font-weight: 900;
        }

        .swiper-button-prev {
            left: 16px;
        }

        .swiper-button-next {
            right: 16px;
        }

        /* Responsiveness */
        @media (max-width: 640px) {
            .swiper {
                padding: 24px 48px;
            }

            .swiper-slide {
                width: 88vw;
            }
        }
    </style>
</head>

<body>
    <!-- Top Navigation -->
    <nav id="top-nav"
        class="fixed top-0 left-0 w-full flex items-center justify-start px-6 md:px-16 py-4 bg-[#ffffff00] z-50">
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

        <button class="md:hidden text-white text-3xl" id="menu-btn" aria-label="Menu">☰</button>
    </nav>

    <div id="menu"
        class="hidden fixed top-[64px] left-0 w-full bg-[#5AA03E] text-white text-center py-3 space-y-2 z-40 shadow-md">
        <a href="{{ route('user.cerita.index') }}" class="block hover:text-yellow-200">Cerita</a>
        <a href="{{ route('user.si_baca.index') }}" class="block hover:text-yellow-200">Si Baca</a>
        <a href="{{ route('user.logo.index') }}" class="block hover:text-yellow-200">Logo</a>
        <a href="{{ route('user.kontak.index') }}" class="block hover:text-yellow-200">Kontak</a>
    </div>

    <main class="page">
        <h1 class="title">Cerita</h1>

        <div class="carousel-wrap">
            <div class="swiper" id="cerita-swiper">
                <div class="swiper-wrapper">
                    <!-- Slide 1 -->
                    <div class="swiper-slide">
                        <article class="story-card">
                            <div class="story-media">
                                <!-- Optional image; keep empty to use gradient background -->
                                {{-- <img src="{{ asset('images/cerita/sarip-tambak-oso.jpg') }}" alt="Sarip Tambak Oso"> --}}
                                <div class="story-title">Sarip Tambak Oso</div>
                            </div>
                            <div class="lock-overlay" aria-hidden="true" title="Terkunci">🔒</div>
                        </article>
                    </div>

                    <!-- Slide 2 -->
                    <div class="swiper-slide">
                        <article class="story-card">
                            <div class="story-media"
                                style="background: linear-gradient(180deg,#e6f7ff,#c6ecff 40%, #a2e59a 70%);">
                                {{-- <img src="{{ asset('images/cerita/cerita-2.jpg') }}" alt="Cerita 2"> --}}
                                <div class="story-title">Cerita 2</div>
                            </div>
                            <div class="lock-overlay" aria-hidden="true" title="Terkunci">🔒</div>
                        </article>
                    </div>

                    <!-- Slide 3 -->
                    <div class="swiper-slide">
                        <article class="story-card">
                            <div class="story-media"
                                style="background: linear-gradient(180deg,#e8f5ff,#d3efff 40%, #a9e5a0 70%);">
                                <div class="story-title">Cerita 3</div>
                            </div>
                            <div class="lock-overlay" aria-hidden="true" title="Terkunci">🔒</div>
                        </article>
                    </div>

                    <!-- Slide 4 -->
                    <div class="swiper-slide">
                        <article class="story-card">
                            <div class="story-media"
                                style="background: linear-gradient(180deg,#eef8ff,#d7f1ff 40%, #b1eaa9 70%);">
                                <div class="story-title">Cerita 4</div>
                            </div>
                            <div class="lock-overlay" aria-hidden="true" title="Terkunci">🔒</div>
                        </article>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="swiper-button-prev" aria-label="Sebelumnya"></div>
                <div class="swiper-button-next" aria-label="Berikutnya"></div>
            </div>
        </div>
    </main>

    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper@9/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('#cerita-swiper', {
            effect: 'coverflow',
            centeredSlides: true,
            slidesPerView: 'auto',
            loop: true,
            grabCursor: true,
            coverflowEffect: {
                rotate: 0, // rotation handled by CSS for prev/next
                stretch: 0,
                depth: 160,
                modifier: 1,
                slideShadows: false,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            keyboard: {
                enabled: true
            },
        });
        // Optional: if you want lock icons to show only when not active (already handled in CSS)
        // No extra JS needed.

        // Mobile menu toggle
        (function() {
            const btn = document.getElementById('menu-btn');
            const menu = document.getElementById('menu');
            if (btn && menu) {
                btn.addEventListener('click', () => {
                    menu.classList.toggle('shown');
                });
            }
        })();
    </script>
</body>

</html>
