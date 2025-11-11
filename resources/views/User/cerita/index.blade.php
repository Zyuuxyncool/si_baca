<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cerita</title>

  <!-- Swiper -->
  <link rel="stylesheet" href="https://unpkg.com/swiper@9/swiper-bundle.min.css" />
  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    :root {
      --bg-start: #b1d56b;
      --bg-end: #7ab34e;
      --card-radius: 26px;
      --card-border: 14px;
      --shadow: 0 24px 40px rgba(0, 0, 0, .25);
    }

    body {
      margin: 0;
      font-family: system-ui, sans-serif;
      background: radial-gradient(circle at top right, #f5ffdd 0%, #bdd143 55%, #7ab34e 90%, #5AA03E 100%);
      color: #0b1b0f;
      background-size: cover;
      background-repeat: no-repeat;
      overflow: hidden;
    }

    .page {
      min-height: 100dvh;
      display: grid;
      grid-template-rows: auto 1fr;
      padding: 100px 18px 48px;
    }

    .title {
      text-align: center;
      font-weight: 800;
      font-size: clamp(28px, 6vw, 48px);
      color: #fff;
      text-shadow: 0 4px 0 rgba(0, 0, 0, .18);
      margin-bottom: 32px;
    }

    .carousel-wrap {
      display: grid;
      place-items: center;
      overflow: visible;
    }

    /* ✅ Swiper layout yang lebih proporsional */
    .swiper {
      width: min(1000px, 90vw);
      padding: 24px 0;
    }

    .swiper-slide {
      width: 80%;
      max-width: 720px;
      transition: transform .35s ease, opacity .35s ease, filter .35s ease;
    }

    .story-card {
      position: relative;
      border-radius: var(--card-radius);
      background: #e8f2e1;
      box-shadow: var(--shadow);
      padding: var(--card-border);
      overflow: hidden;
    }

    .story-card::before {
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

    .story-title {
      position: absolute;
      left: 50%;
      bottom: 10px;
      transform: translateX(-50%);
      background: #fff;
      color: #0b1b0f;
      padding: 12px 20px;
      font-weight: 800;
      border-radius: 999px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, .20);
      font-size: clamp(14px, 2.5vw, 20px);
      white-space: nowrap;
    }

    /* Desktop-specific styling: make center slide look like screenshot while preserving mobile rules below */
    @media (min-width: 769px) {
      /* stronger desktop look: bigger white frame, larger center card, side cards pushed further out */
      :root {
        --card-radius: 34px;
        --card-border: 24px; /* thicker white frame like screenshot */
        --shadow: 0 40px 80px rgba(0,0,0,.35);
      }

      .swiper {
        width: min(1300px, 98vw);
        padding: 36px 0;
        overflow: visible; /* allow side cards to peek out */
      }

      /* make the centered slide occupy most of the area */
      .swiper-slide {
        width: 78%;
        max-width: 980px;
      }

      .swiper-wrapper { align-items: center; }

      .story-card {
        padding: var(--card-border);
        border-radius: var(--card-radius);
        box-shadow: var(--shadow);
        position: relative;
        z-index: 1;
      }

      .story-card::before {
        padding: var(--card-border);
        background: linear-gradient(#fff, #fff) padding-box;
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        border-radius: var(--card-radius);
      }

      .story-media {
        aspect-ratio: 16/9;
        border-radius: calc(var(--card-radius) - 18px);
        background: linear-gradient(180deg, #dff3ff, #bce7ff 40%, #9ae28a 70%);
      }

      .story-title {
        bottom: 26px;
        padding: 16px 30px;
        font-size: 18px;
        box-shadow: 0 14px 40px rgba(0,0,0,.32);
      }

      /* Active slide should sit on top */
      .swiper-slide-active { z-index: 60; }
      .swiper-slide-prev, .swiper-slide-next { z-index: 20; }

      /* push side slides further and shrink them to create the 'peeking' effect */
      .swiper-slide-prev .story-card {
        transform: translateX(-240px) rotate(-9deg) scale(0.76);
        filter: grayscale(.22) brightness(.78) contrast(.95);
      }

      .swiper-slide-next .story-card {
        transform: translateX(240px) rotate(9deg) scale(0.76);
        filter: grayscale(.22) brightness(.78) contrast(.95);
      }

      /* ensure non-active slides are dimmer */
      .swiper-slide:not(.swiper-slide-active) .story-card {
        opacity: 0.95;
      }

      /* navigation circles moved a bit inward so they sit over the side cards */
      .swiper-button-next,
      .swiper-button-prev {
        width: 56px;
        height: 56px;
        background: #0b0b0b;
        color: #fff;
        border-radius: 50%;
        box-shadow: 0 12px 30px rgba(0, 0, 0, .35);
        top: 50%;
        transform: translateY(-50%);
      }

      /* move them closer to the center card so they overlap the side cards like the screenshot */
      .swiper-button-prev { left: calc(50% - 620px); }
      .swiper-button-next { right: calc(50% - 620px); }

      .swiper-button-next:after,
      .swiper-button-prev:after { font-size: 20px; font-weight: 900; }

      /* lock overlay slightly inset on side cards */
      .lock-overlay {
        width: 62px;
        height: 62px;
        right: -6px;
        bottom: -6px;
        font-size: 26px;
        opacity: 0;
        transition: .25s ease;
      }

      .swiper-slide:not(.swiper-slide-active) .lock-overlay { opacity: 1; }
      /* Move the whole page slightly up on desktop and avoid vertical scrolling */
      body {
        overflow-y: hidden; /* desktop: prevent page vertical scroll so carousel stays fixed */
      }

      .page {
        /* reduce top/bottom padding so content fits in viewport */
        padding-top: 72px;
        padding-bottom: 12px;
      }
    }

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

    .swiper-slide:not(.swiper-slide-active) .story-card {
      filter: grayscale(.2) brightness(.85) contrast(.95);
    }

    /* ✅ Posisi slide kanan & kiri dibuat pas */
    .swiper-slide-prev .story-card {
      transform: translateX(-20px) rotate(-5deg) scale(0.9);
    }

    .swiper-slide-next .story-card {
      transform: translateX(20px) rotate(5deg) scale(0.9);
    }

    /* Tombol navigasi diposisikan ke dalam */
    .swiper-button-next,
    .swiper-button-prev {
      width: 48px;
      height: 48px;
      background: #0b0b0b;
      color: #fff;
      border-radius: 50%;
      box-shadow: 0 8px 20px rgba(0, 0, 0, .35);
    }

    .swiper-button-next {
      right: 8%;
    }

    .swiper-button-prev {
      left: 8%;
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
      font-size: 20px;
      font-weight: 900;
    }

    @media (max-width: 768px) {
      body {
        overflow-y: auto;
        overflow-x: hidden;
      }

      .page {
        padding-top: 90px;
        padding-bottom: 40px;
      }

      .swiper {
        width: 90vw;
        padding: 10px 0;
      }

      .swiper-slide {
        width: 90%;
      }

      .story-title {
        font-size: 16px;
        padding: 10px 18px;
      }

      .swiper-button-next,
      .swiper-button-prev {
        width: 40px;
        height: 40px;
      }

      .swiper-button-prev {
        left: 4%;
      }

      .swiper-button-next {
        right: 4%;
      }

      .title {
        font-size: 32px;
        margin-bottom: 24px;
      }
    }
  </style>
</head>

<body>
  <!-- Navbar -->
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

    <button id="menu-btn" class="md:hidden p-2 rounded-full shadow-md flex items-center justify-center bg-white/10 backdrop-blur-md">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24"
        stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </nav>

  <!-- Menu Mobile -->
  <div id="menu" class="hidden fixed top-[88px] left-4 right-4 bg-white/10 backdrop-blur-md text-white text-center py-4 space-y-2 z-40 rounded-lg">
    <ul class="flex flex-col gap-2">
       <li><a href="{{ route('user.landing.index') }}" class="hover:text-yellow-200 transition">Beranda</a></li>
            <li><a href="{{ route('user.cerita.index') }}" class="hover:text-yellow-200 transition">Cerita</a></li>
            <li><a href="{{ route('user.si_baca.index') }}" class="hover:text-yellow-200 transition">Si Baca</a></li>
            <li><a href="{{ route('user.logo.index') }}" class="hover:text-yellow-200 transition">Logo</a></li>
            <li><a href="{{ route('user.kontak.index') }}" class="hover:text-yellow-200 transition">Kontak</a></li>
    </ul>
  </div>

  <!-- Main -->
  <main class="page">
    <h1 class="title">Cerita</h1>

    <div class="carousel-wrap">
      <div class="swiper" id="cerita-swiper">
        <div class="swiper-wrapper">
          @foreach($ceritas as $c)
            <div class="swiper-slide">
              <article class="story-card">
                <a href="{{ route('user.cerita.show', ['nama' => $c->nama]) }}" class="story-media">
                  @if(!empty($c->photo))
                    <img src="{{ Storage::url($c->photo) }}" alt="{{ $c->nama }}">
                  @else
                    <div style="width:100%;height:100%;background:linear-gradient(180deg,#eef8ff,#d7f1ff 40%, #b1eaa9 70%);"></div>
                  @endif
                  <div class="story-title">{{ $c->nama }}</div>
                </a>
                <div class="lock-overlay">🔒</div>
              </article>
            </div>
          @endforeach
        </div>

        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
    </div>
  </main>

  <!-- Script -->
  <script src="https://unpkg.com/swiper@9/swiper-bundle.min.js"></script>
  <script>
    const swiper = new Swiper('#cerita-swiper', {
      effect: 'coverflow',
      centeredSlides: true,
      slidesPerView: 'auto',
      loop: true,
      grabCursor: true,
      coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 120,
        modifier: 1,
        slideShadows: false,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        640: { slidesPerView: 1.2 },
        1024: { slidesPerView: 1.4 },
      },
    });

    const menuBtn = document.getElementById('menu-btn');
    const menu = document.getElementById('menu');
    menuBtn.addEventListener('click', () => {
      menu.classList.toggle('hidden');
    });
  </script>
</body>

</html>
