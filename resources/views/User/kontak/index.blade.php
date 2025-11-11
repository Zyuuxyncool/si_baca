<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontak</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #4b8329 0%, #c8d96f 50%, #a8b830 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      background-repeat: no-repeat;
      background-size: cover;
      min-height: 100vh;
    }

    .container {
      width: 100%;
      max-width: 1200px;
      display: flex;
      gap: 80px;
      align-items: center;
      justify-content: center;
    }

    /* --- Bagian Kiri --- */
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
      flex: 0 0 400px;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      min-height: 400px;
    }

    .right-section img {
     position: absolute;
     right: 90%;
     transform: translate(43%);
     height: auto;
     width: 400%;
     max-width: 1000px;
     top: 30px;
     object-fit: contain;
     filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.4));
    }
    

    @supports (height: 100dvh) {
      body {
        min-height: 100dvh;
      }
    }

    /* --- MOBILE --- */
    @media (max-width: 968px) {
      body {
        padding-top: 80px;
        overflow-x: hidden;
        overflow-y: auto;
      }

      .container {
        flex-direction: column;
        gap: 24px;
        text-align: center;
        padding: 0;
        margin: 0;
        width: 100%;
      }

      .left-section h1 {
        font-size: 64px;
        margin-bottom: 12px;
      }

      .left-section h2 {
        font-size: 28px;
        margin-bottom: 6px;
      }

      .left-section p {
        font-size: 18px;
      }

      .right-section {
        width: 100%;
        min-height: auto;
        position: relative;
        overflow: hidden;
        display: flex;
        justify-content: center;
      }

      .right-section img {
        position: relative;
        width: 120%;
        max-width: none;
        height: auto;
        left: 50%;
        transform: translateX(-50%);
        margin: 0 auto;
        display: block;
        filter: drop-shadow(0 6px 16px rgba(0, 0, 0, 0.25));
      }
    }

    @media (max-width: 480px) {
      .left-section h1 {
        font-size: 52px;
      }

      .left-section h2 {
        font-size: 24px;
      }

      .left-section p {
        font-size: 16px;
      }

      .right-section img {
        width: 160%;
        left: 80%;
        /* margin: 0 auto; */
        /* display: block; */
        bottom: 0;
        transform: translateX(-50%);
      }
    }
  </style>
</head>

<body>
  <nav
    class="fixed top-0 left-0 w-full flex items-center justify-between md:justify-start px-6 md:px-16 py-4 bg-[#ffffff00] z-50">
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

    <button id="menu-btn"
      class="md:hidden p-2 rounded-full shadow-md flex items-center justify-center bg-white/20 backdrop-blur-md"
      aria-label="Buka menu">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24"
        stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </nav>

  <div id="menu"
    class="hidden fixed top-[88px] left-4 right-4 bg-transparent text-white text-center py-4 space-y-2 z-40 shadow-lg rounded-lg bg-white/20 backdrop-blur-md">
    <ul class="flex flex-col gap-2">
      <li><a href="{{ route('user.landing.index') }}"
          class="block py-2 text-lg font-semibold rounded text-white hover:text-orange-500 transition">Beranda</a></li>
      <li><a href="{{ route('user.cerita.index') }}"
          class="block py-2 text-lg font-semibold rounded text-white hover:text-orange-500 transition">Cerita</a></li>
      <li><a href="{{ route('user.si_baca.index') }}"
          class="block py-2 text-lg font-semibold rounded text-white hover:text-orange-500 transition">Si Baca</a></li>
      <li><a href="{{ route('user.logo.index') }}"
          class="block py-2 text-lg font-semibold rounded text-white hover:text-orange-500 transition">Logo</a></li>
      <li><a href="{{ route('user.kontak.index') }}"
          class="block py-2 text-lg font-semibold rounded text-white hover:text-orange-500 transition">Kontak</a></li>
    </ul>
  </div>

  <div class="container">
    <div class="left-section">
      <h1>Kontak</h1>
      <h2>Email</h2>
      <p>sibacaproject@gmail.com</p>
    </div>

    <div class="right-section">
      <img src="{{ asset('images/12_20251030_185102_0004[1].png') }}" alt="Kotak Masukan Pesan" class="cursor-pointer"
        id="open-contact-modal">
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" tabindex="-1" id="modal_info">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="modal_info_pemasukan"></div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    const menuBtn = document.getElementById('menu-btn');
    const menu = document.getElementById('menu');
    menuBtn.addEventListener('click', () => menu.classList.toggle('hidden'));
  </script>

  <script>
    (function () {
      if (typeof $ !== 'undefined' && typeof bootstrap !== 'undefined' && !$.fn.modal) {
        $.fn.modal = function (action) {
          return this.each(function () {
            const instance = bootstrap.Modal.getOrCreateInstance(this);
            if (action === 'show') instance.show();
            else if (action === 'hide') instance.hide();
          });
        };
      }
    })();
  </script>

  <script>
    let $modal_info = $('#modal_info');
    let $modal_info_pemasukan = $('#modal_info_pemasukan');
    let _token = '{{ csrf_token() }}';
    let base_url = '{{ route('user.kontak.index') }}';
    const IS_AUTH = @json(auth()->check());

    let display_modal_info = (form_html) => {
      $modal_info_pemasukan.html(form_html);
      $modal_info.modal('show');
      init_form();
    }

    let info = () => {
      $.get(base_url + '/create', (result) => display_modal_info(result))
        .fail((xhr) => $modal_info_pemasukan.html(xhr.responseText));
    }

    let init_form = () => {
      let $form_info = $('#form_info');
      $form_info.submit((e) => {
        e.preventDefault();
        let url = base_url;
        let data = new FormData($form_info.get(0));

        $.ajax({
          url,
          type: 'post',
          data,
          cache: false,
          processData: false,
          contentType: false,
          success: () => {
            Swal.fire('Terima Kasih!', 'Pesan Anda berhasil dikirim!', 'success')
              .then(() => $modal_info.modal('hide'));
          },
          error: () => {
            Swal.fire('Error!', 'Gagal mengirim pesan. Cek kembali input Anda.', 'error');
          }
        });
      });
    }

    $(document).ready(function () {
      $('#open-contact-modal').on('click', function () {
        if (!IS_AUTH) {
          Swal.fire({
            title: 'Login diperlukan',
            text: 'Silakan login terlebih dahulu untuk mengirim masukan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Login',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = '{{ route('login') }}';
            }
          });
          return;
        }
        info();
      });
    });
  </script>
</body>

</html>
