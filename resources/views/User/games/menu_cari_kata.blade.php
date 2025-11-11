<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Menu Permainan - {{ $cerita->nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Bootstrap CSS for the 'Kembali ke Video' button style --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"> 
    <style>
        /* full-screen blurred background using cerita photo */
        .bg-photo{position:fixed;inset:0;background-color:#111;background-size:cover;background-position:center;filter:blur(14px) brightness(.6);transform:scale(1.04);z-index:-2}
        .bg-overlay{position:fixed;inset:0;background:linear-gradient(90deg, rgba(0,0,0,0.35) 0%, rgba(0,0,0,0.35) 100%);z-index:-1}

        body { 
            font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, sans-serif; 
            background: transparent; /* Remove original body background to show the blurred photo */
            color: #fff; /* Default text color for better contrast on dark background */
        }
        a { color: #81e6d9; text-decoration: none; } /* Adjusted link color */
        a:hover { color: #5fd3c7; } /* Adjusted link hover color */

        .games-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            padding: 0 1rem;
        }
        @media(min-width: 576px){ .games-grid { grid-template-columns: repeat(2,1fr); } }
        @media(min-width: 768px){ .games-grid { grid-template-columns: repeat(3,1fr); } }
        @media(min-width: 992px){ .games-grid { grid-template-columns: repeat(4,1fr); } }

        /* The original game-card styling is not used here as we are focusing on level-select-container */

        .level-select-container {
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            padding: 1.5rem 1rem;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            max-width: 1100px;
            margin: 1.25rem auto;
        }
        .level-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            width: 100%;
            margin: 0 auto;
        }
        @media(min-width: 576px){ .level-grid { grid-template-columns: repeat(2, 1fr); } }
        @media(min-width: 768px){ .level-grid { grid-template-columns: repeat(3, 1fr); } }
        @media(min-width: 992px){ .level-grid { grid-template-columns: repeat(4, 1fr); } }

        .level-tile {
            position: relative;
            display: block;
            text-decoration: none;
            color: inherit;
        }

        .card.level-card { border-radius:12px; overflow:hidden; display:flex; flex-direction:column; height:260px; }
        .card.level-card .card-img-top { height:140px; background-size:cover; background-position:center; }
        .card.level-card .card-body { flex:1; display:flex; flex-direction:column; justify-content:space-between; padding: .75rem; }
        .card.level-card .meta { display:flex; justify-content:space-between; align-items:flex-start; gap:8px }
        .score-box { min-width:72px; text-align:right }
        .score-box .star { color:#fbbf24; margin-right:6px }

        .level-number-btn.locked {
            background-color: #4ade80; /* Dibuat sama seperti yang terbuka untuk demonstrasi statis */
            box-shadow: 0 6px 0 #16a34a, 0 10px 15px rgba(0,0,0,0.1);
            cursor: pointer;
        }
        .level-number-btn.locked .level-number-text {
            color: #fff;
            text-shadow: 1px 1px 0 #16a34a; 
        }

        .level-number-btn:active {
            transform: translateY(3px);
            box-shadow: 0 3px 0 #16a34a, 0 6px 10px rgba(0,0,0,0.1);
        }

        .level-number-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.8rem;
            font-weight: 900;
            color: #fff;
            text-shadow: 1px 1px 0 #16a34a;
        }

        .score-display {
            font-size: 0.9rem;
            font-weight: 600;
            color: #d1d5db; /* Adjusted for dark background */
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 2px;
        }
        .score-star-icon {
            width: 1rem;
            height: 1rem;
            color: #fbbf24;
        }
        /* Mobile floating back button */
        .mobile-back-btn {
            position: fixed;
            left: 12px;
            top: 12px;
            width: 44px;
            height: 44px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.6);
            color: #fff;
            z-index: 60;
            box-shadow: 0 6px 18px rgba(0,0,0,0.35);
            backdrop-filter: blur(6px);
        }
        .mobile-back-btn:active{ transform: translateY(1px); }
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

    <div class="container mx-auto px-4 py-8 sm:py-12" style="padding:4rem;">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-2xl font-semibold mb-1 text-white">Permainan untuk: {{ $cerita->nama }}</h3>
                <p class="text-gray-300 text-sm mb-0">Pilih level untuk memulai permainan Ruang Teka-Teki.</p>
            </div>
            <div class="text-right">
                {{-- Desktop/tablet back link (hidden on small screens) --}}
                <a href="{{ route('user.cerita.show', ['nama' => $cerita->nama]) }}" class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-200 hover:bg-gray-700 hidden sm:inline-block">
                    Kembali ke Cerita
                </a>
            </div>
        </div>

        {{-- Floating back button for mobile screens --}}
        <a href="{{ route('user.cerita.show', ['nama' => $cerita->nama]) }}" class="mobile-back-btn sm:hidden" aria-label="Kembali ke Cerita">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>

        <div class="games-grid">
            <div class="level-select-container col-span-full" id="level-select-example">
                <div class="text-center mb-6">
                    <div class="inline-flex p-3 rounded-full bg-yellow-100 text-yellow-700 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="28" height="28"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                    </div>
                    <h4 class="text-xl font-bold text-white">Ruang Teka-Teki</h4>
                    <p class="text-sm text-gray-300">Uji pemahamanmu setelah membaca.</p>
                </div>

                <div class="level-grid">
                    @php
                        $no = 1;
                    @endphp
                    @if($cari_katas instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        @php
                            $no = (($cari_katas->currentPage()-1) * $cari_katas->perPage()) + 1;
                        @endphp
                    @endif
                    @foreach($cari_katas as $cari_kata)
                        @php
                            $poster = $cari_kata->poster ?? $cari_kata->photo ?? null;
                            $posterUrl = $poster ? Storage::url($poster) : null;
                            $title = $cari_kata->title ?? $cari_kata->nama ?? '';
                            $score = $cari_kata->score ?? null;
                        @endphp

                        <!-- route for playing template: include template id in query string so user view can load it -->
                        <a href="{{ route('user.cerita.menu_cari_kata.cari_kata', ['nama' => $cerita->nama]) }}?id={{ $cari_kata->id ?? '' }}" class="level-tile start-cari-kata" data-template-id="{{ $cari_kata->id ?? '' }}" title="{{ e($title) }}">
                            <div class="card bg-dark text-white border-0 shadow-sm level-card" style="">
                                @if($posterUrl)
                                    <div class="card-img-top" style="background-image: url('{{ $posterUrl }}');"></div>
                                @else
                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-secondary">
                                        <svg width="56" height="56" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="7" height="7" x="3" y="3" rx="1" fill="#fff" opacity="0.9"/><rect width="7" height="7" x="14" y="3" rx="1" fill="#fff" opacity="0.85"/><rect width="7" height="7" x="14" y="14" rx="1" fill="#fff" opacity="0.8"/><rect width="7" height="7" x="3" y="14" rx="1" fill="#fff" opacity="0.75"/></svg>
                                    </div>
                                @endif
                                <div class="card-body p-3">
                                    <div class="meta mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="badge bg-success rounded-pill" style="font-weight:700;padding:.45rem .6rem;">{{ $no }}</div>
                                            <h5 class="m-0 fs-6 text-white" style="font-weight:700;">{{ \Illuminate\Support\Str::limit($title, 40) }}</h5>
                                        </div>
                                        <div class="score-box text-end">
                                            @if(!empty($score))
                                                <small class="text-white d-block">Skor</small>
                                                <div class="fw-bold d-inline-flex align-items-center gap-2">
                                                    <span class="star">★</span>
                                                    <span style="font-size:1rem">{{ $score }}</span>
                                                </div>
                                                @if(!empty($cari_kata->last_played))
                                                    <small class="text-white" style="font-size:.68rem;display:block">Terakhir: {{ \Carbon\Carbon::parse($cari_kata->last_played)->diffForHumans() }}</small>
                                                @endif
                                            @else
                                                <small class="text-white">Belum main</small>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="card-text text-white small mb-0">{{ $cari_kata->description ?? ($cari_kata->instructions ?? '') }}</p>
                                </div>
                            </div>
                        </a>
                        @php $no++; @endphp
                    @endforeach
                </div>
                <div class="d-flex flex-row justify-content-center">
                    @if($cari_katas instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $cari_katas->links('vendor.pagination.custom') }}
                    @endif
                </div>

            </div>
        </div>
    </div>
</body>
</html>