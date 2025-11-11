<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $cerita->nama }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        :root{--card-radius:20px}
        html,body{height:100%}
        body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial}
        /* full-screen blurred background using cerita photo */
        .bg-photo{position:fixed;inset:0;background-color:#111;background-size:cover;background-position:center;filter:blur(14px) brightness(.6);transform:scale(1.04);z-index:-2}
        .bg-overlay{position:fixed;inset:0;background:linear-gradient(90deg, rgba(0,0,0,0.35) 0%, rgba(0,0,0,0.35) 100%);z-index:-1}

        .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:3rem}
        .panel{width:100%;max-width:1180px;background:rgba(255,255,255,0.05);backdrop-filter:blur(6px);border-radius:var(--card-radius);overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,.45)}

        .content{display:flex;flex-wrap:wrap}
        .left{flex:1 1 520px;min-width:320px;padding:4rem;display:flex;align-items:center;justify-content:center}
        .right{flex:1 1 420px;min-width:300px;padding:3.2rem 3.6rem;color:#fff}

    /* end-screen (shown when video finishes) */
    .end-screen{display:none;align-items:flex-start;gap:1.4rem}
    .end-screen .games{display:flex;flex-direction:column;gap:1rem}
    .game-pill{display:flex;align-items:center;gap:1rem;background:#fff7ea;color:#111;padding:14px 20px;border-radius:999px;box-shadow:0 6px 18px rgba(0,0,0,.18);cursor:pointer;text-decoration:none}
    .game-pill .icon{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:transparent}
    .end-title{font-weight:800;font-size:36px;letter-spacing:1px;margin-bottom:0.6rem}
    .end-sub{font-size:20px;color:#fff;margin-top:2rem;font-weight:700}

        /* organic blob shape for media */
        .blob{width:100%;max-width:560px;aspect-ratio:1.4/1;background:#222;border-radius:48% 52% 52% 48% / 56% 64% 36% 44%;overflow:hidden;position:relative;box-shadow:0 12px 40px rgba(0,0,0,.45);}
        .blob img, .blob video{width:100%;height:100%;object-fit:cover;display:block}

        /* play button center */
        .play-btn{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:74px;height:74px;border-radius:50%;background:rgba(255,255,255,.95);display:flex;align-items:center;justify-content:center;cursor:pointer}
        .play-btn svg{width:34px;height:34px;color:#111}

        /* description column */
        h1.title{color:#fff;font-size:28px;margin-bottom:0.8rem}
        .desc{color:#eee;line-height:1.8;font-size:16px}

        /* responsiveness */
        @media (max-width:900px){
            .left{padding:2rem}
            .right{padding:2rem}
            .panel{border-radius:14px}
        }

    </style>
</head>
<body>
    @php
        // prefer cerita photo as background (generated video posters removed)
        $bg = '';
        if (!empty($cerita->photo)) {
            $bg = Storage::url($cerita->photo);
        }
    @endphp
    <div class="bg-photo" style="background-image: url('{{ $bg }}')"></div>
    <div class="bg-overlay"></div>

    <div class="wrap">
        <div class="panel">
            <div class="content">
                <div class="left">
                    <div class="blob">
                        @if(!empty($cerita->video) && empty($cerita->video_processing))
                            <video id="cerita-player" preload="metadata" poster="{{ $bg }}" controls playsinline>
                                <source src="{{ Storage::url($cerita->video) }}" type="video/mp4">

                                Your browser does not support the video tag.
                            </video>
                            <div class="play-btn" id="play-btn" title="Play">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 5v14l11-7L8 5z" fill="#000"/>
                                </svg>
                            </div>
                        @elseif(!empty($cerita->video) && !empty($cerita->video_processing))
                            <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#fff;padding:2rem;flex-direction:column">
                                <div class="spinner-border text-light" role="status" style="width:3rem;height:3rem;margin-bottom:1rem"></div>
                                <div>Video sedang diproses. Silakan tunggu beberapa saat.</div>
                            </div>
                        @elseif(!empty($cerita->photo))
                            <img src="{{ Storage::url($cerita->photo) }}" alt="{{ $cerita->nama }}">
                        @else
                            <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#fff;padding:2rem">No media available</div>
                        @endif
                    </div>
                </div>

                <div class="right">
                    <h1 class="title">{{ $cerita->nama }}</h1>
                    <div class="desc">
                        {!! nl2br(e($cerita->deskripsi ?? 'Deskripsi belum tersedia.')) !!}
                    </div>
                    <div style="margin-top:1.6rem">
                        <a href="{{ route('user.cerita.index') }}" class="btn btn-light btn-sm">Kembali</a>
                    </div>

                    <!-- End-screen (hidden until video ends) -->
                    <div id="end-screen" class="end-screen">
                        <div style="flex:1">
                            <div class="end-title">PERMAINAN</div>
                            <div style="color:rgba(255,255,255,.85);margin-bottom:1.2rem">Pilih permainan untuk menguji pemahamanmu setelah membaca.</div>
                                    <div class="games">
                                            <a href="{{ route('user.games.menu_ruang_teka.index', ['nama' => $cerita->nama]) }}" class="game-pill" id="game-ruang-teka" title="Ruang Teka">
                                                <div class="icon">💡</div>
                                                <div style="font-weight:700">Ruang Teka</div>
                                            </a>
                                            <a href="{{ route('user.games.menu_cari_kata.index', ['nama' => $cerita->nama]) }}" class="game-pill" id="game-cari-kata" title="Cari Kata">
                                                <div class="icon">🔍</div>
                                                <div style="font-weight:700">Cari Kata</div>
                                            </a>
                                    </div>
                        </div>
                        <div style="flex-basis:100%;margin-top:1.6rem;color:#fff;font-size:20px;font-weight:700">
                            Selesai membaca?<br>Yuk, uji serunya lewat game pilihanmu!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function(){
            var btn = document.getElementById('play-btn');
            var player = document.getElementById('cerita-player');
            if(btn && player){
                // when clicking the center button, toggle play/pause
                btn.addEventListener('click', function(){
                    if(player.paused) player.play(); else player.pause();
                });
                // hide play button once playing
                player.addEventListener('play', function(){ btn.style.display='none'; });
                player.addEventListener('pause', function(){ btn.style.display='flex'; });
                // when video ends, show end-screen with game options
                player.addEventListener('ended', function(){
                    try {
                        var end = document.getElementById('end-screen');
                        if(end){ end.style.display = 'flex'; end.style.opacity = 0; end.style.transition = 'opacity .35s ease';
                            // hide original description to avoid duplication
                            var desc = document.querySelector('.right .desc');
                            if(desc) desc.style.display = 'none';
                            var backbtn = document.querySelector('.right .btn');
                            if(backbtn) backbtn.style.display = 'none';
                            setTimeout(function(){ end.style.opacity = 1; }, 10);
                        }
                    } catch(e){ console.warn('show end screen error', e); }
                });
            }
        })();
    </script>
        <!-- SweetAlert2 for user notifications (polling will use it) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Intercept clicks on game links and require login first
        (function(){
            const IS_AUTH = @json(auth()->check());
            const LOGIN_URL = {!! json_encode(route('login')) !!};

            function ensureLoginBeforeNavigate(el){
                el.addEventListener('click', function(e){
                    if (IS_AUTH) return; // proceed normally when authenticated
                    e.preventDefault();
                    // show SweetAlert prompt
                    if (typeof Swal === 'undefined') {
                        // fallback: redirect to login
                        if (confirm('Silakan login terlebih dahulu untuk memulai permainan. Mau menuju halaman login?')) {
                            window.location.href = LOGIN_URL;
                        }
                        return;
                    }
                    Swal.fire({
                        title: 'Silakan login terlebih dahulu',
                        text: 'Silakan login terlebih dahulu untuk memulai permainan.',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Login',
                        cancelButtonText: 'Tetap di sini',
                        allowOutsideClick: false
                    }).then(result => {
                        if (result.isConfirmed) {
                            window.location.href = LOGIN_URL;
                        }
                    });
                });
            }

            // Attach to known IDs and any .game-pill anchors
            document.addEventListener('DOMContentLoaded', function(){
                const ids = ['game-ruang-teka','game-cari-kata'];
                ids.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) ensureLoginBeforeNavigate(el);
                });
                document.querySelectorAll('.game-pill').forEach(ensureLoginBeforeNavigate);
            });
        })();
    </script>
    <script>
        (function(){
            // Poll cerita status endpoint and show alerts when processing starts/finishes.
            const statusUrl = '{{ route('user.cerita.status', $cerita->nama) }}';
            let lastProcessing = {{ $cerita->video_processing ? 'true' : 'false' }};

            // If initially processing, show info alert once
            if (lastProcessing) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Video sedang diproses',
                        text: 'Transcode job dijalankan. Silakan tunggu...',
                        icon: 'info',
                        allowOutsideClick: false,
                        willOpen: () => { Swal.showLoading(); }
                    });
                }
            }

            const poll = async () => {
                try {
                    const res = await fetch(statusUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if (!res.ok) return;
                    const data = await res.json();
                    const processing = !!data.video_processing;

                    if (processing && !lastProcessing) {
                        // started
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Transcode dimulai',
                                text: 'Video processing telah dimulai.',
                                icon: 'info',
                                timer: 2500,
                                showConfirmButton: false
                            });
                        }
                    }

                    if (!processing && lastProcessing) {
                        // finished
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Selesai',
                                text: 'Transcode selesai. Halaman akan dimuat ulang untuk menampilkan video.',
                                icon: 'success',
                                confirmButtonText: 'Muat ulang'
                            }).then(() => { window.location.reload(); });
                        } else {
                            window.location.reload();
                        }
                    }

                    lastProcessing = processing;
                } catch (e) {
                    // ignore network errors silently
                    console.warn('status poll error', e);
                }
            };

            // Poll every 4 seconds while page is open
            const interval = setInterval(poll, 4000);
            // Stop polling when leaving page
            window.addEventListener('beforeunload', () => clearInterval(interval));
        })();
    </script>
</body>
</html>
