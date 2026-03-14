<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Galeri Foto — Simpan dan kelola momen berharga Anda">
    <title>@yield('title', 'Galeri Foto') | Galeri Foto</title>

    {{-- Google Font: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- TailwindCSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        // Palet Titanium / iOS / OneUI
                        sys: {
                            bg:         '#F2F2F7', // Latar belakang sistem iOS
                            bg2:        '#FFFFFF', // Latar belakang sekunder (card)
                            bg3:        '#F2F2F7', // Latar belakang tersier
                            label:      '#1C1C1E', // Label utama
                            label2:     '#3A3A3C', // Label sekunder
                            label3:     '#636366', // Label tersier
                            label4:     '#8E8E93', // Label kuarter (placeholder)
                            sep:        '#E5E5EA', // Separator / divider
                            fill:       '#E5E5EA', // Fill elemen UI
                            fill2:      '#EBEBF0', // Fill sekunder
                            accent:     '#007AFF', // Aksen biru iOS
                            accentDark: '#0062CC', // Aksen biru gelap (hover)
                        }
                    },
                }
            }
        }
    </script>

    <style>
        * { font-family: 'Poppins', sans-serif; }

        /* Scrollbar minimal */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #C7C7CC; border-radius: 9999px; }

        /* Transisi halus tombol */
        .btn { transition: all 0.18s ease; }
        .btn:active { transform: scale(0.97); }

        /* Card standar */
        .card {
            background: #FFFFFF;
            border: 1px solid #E5E5EA;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 12px rgba(0,0,0,0.04);
        }

        /* Navbar frosted glass (putih transparan tipis) */
        .navbar-glass {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid #E5E5EA;
        }

        /* Input style iOS/OneUI */
        .input-field {
            background: #F2F2F7;
            border: 1.5px solid transparent;
            border-radius: 12px;
            transition: all 0.18s ease;
        }
        .input-field:focus {
            background: #FFFFFF;
            border-color: #007AFF;
            box-shadow: 0 0 0 3px rgba(0,122,255,0.12);
            outline: none;
        }

        /* Tombol Primer — hitam titanium */
        .btn-primary {
            background: #1C1C1E;
            color: #FFFFFF;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.18s ease;
        }
        .btn-primary:hover { background: #3A3A3C; }
        .btn-primary:active { transform: scale(0.97); }

        /* Tombol Aksen — iOS Blue */
        .btn-accent {
            background: #007AFF;
            color: #FFFFFF;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.18s ease;
        }
        .btn-accent:hover { background: #0062CC; }
        .btn-accent:active { transform: scale(0.97); }

        /* Tombol Ghost */
        .btn-ghost {
            background: #F2F2F7;
            color: #1C1C1E;
            border: 1px solid #E5E5EA;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.18s ease;
        }
        .btn-ghost:hover { background: #E5E5EA; }

        /* Animasi fade-in notifikasi */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .notif { animation: slideDown 0.25s ease; }
    </style>
</head>
<body class="font-poppins bg-sys-bg text-sys-label min-h-screen">

    {{-- ===================================================== --}}
    {{-- NAVBAR --}}
    {{-- ===================================================== --}}
    @if(session('user_id'))
    <nav class="navbar-glass sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between gap-4">

            {{-- Logo --}}
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 shrink-0">
                <div class="w-7 h-7 rounded-lg bg-sys-label flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="font-semibold text-sys-label text-[15px] tracking-tight">Galeri Foto</span>
            </a>

            {{-- Nav Links Desktop --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('dashboard') }}"
                   class="px-3.5 py-1.5 rounded-lg text-[13px] font-medium transition-colors
                          {{ request()->routeIs('dashboard') ? 'bg-sys-label text-white' : 'text-sys-label3 hover:bg-sys-bg hover:text-sys-label' }}">
                    Galeri
                </a>
                <a href="{{ route('album.index') }}"
                   class="px-3.5 py-1.5 rounded-lg text-[13px] font-medium transition-colors
                          {{ request()->routeIs('album.*') ? 'bg-sys-label text-white' : 'text-sys-label3 hover:bg-sys-bg hover:text-sys-label' }}">
                    Album
                </a>
            </div>

            {{-- Kanan: Unggah + User + Logout --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('foto.create') }}"
                   class="hidden sm:inline-flex btn-accent px-4 py-1.5 text-[13px] items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Unggah
                </a>

                {{-- Avatar & Nama --}}
                <div class="hidden sm:flex items-center gap-2 pl-2 border-l border-sys-sep">
                    <div class="w-7 h-7 rounded-full bg-sys-label flex items-center justify-center text-white text-xs font-semibold">
                        {{ strtoupper(substr(session('user_nama', 'U'), 0, 1)) }}
                    </div>
                    <span class="text-[13px] font-medium text-sys-label2 max-w-28 truncate">{{ session('user_nama') }}</span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="btn px-3 py-1.5 rounded-lg text-[12px] font-medium text-sys-label3 hover:text-red-500 hover:bg-red-50 transition-colors">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        {{-- Nav Mobile --}}
        <div class="md:hidden flex border-t border-sys-sep px-4 py-2 gap-2">
            <a href="{{ route('dashboard') }}"
               class="flex-1 text-center py-1.5 rounded-lg text-xs font-medium
                      {{ request()->routeIs('dashboard') ? 'bg-sys-label text-white' : 'text-sys-label3 bg-sys-bg' }}">
                Galeri
            </a>
            <a href="{{ route('album.index') }}"
               class="flex-1 text-center py-1.5 rounded-lg text-xs font-medium
                      {{ request()->routeIs('album.*') ? 'bg-sys-label text-white' : 'text-sys-label3 bg-sys-bg' }}">
                Album
            </a>
            <a href="{{ route('foto.create') }}"
               class="flex-1 text-center py-1.5 rounded-lg text-xs font-medium bg-sys-accent text-white btn-accent">
                + Unggah
            </a>
        </div>
    </nav>
    @endif

    {{-- ===================================================== --}}
    {{-- KONTEN UTAMA --}}
    {{-- ===================================================== --}}
    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-6">

        {{-- Flash: Sukses --}}
        @if(session('sukses'))
        <div class="notif card mb-4 px-4 py-3 flex items-center gap-3 border-l-4 border-green-500">
            <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-[13px] text-sys-label2 flex-1">{{ session('sukses') }}</p>
            <button onclick="this.parentElement.remove()" class="text-sys-label4 hover:text-sys-label text-lg leading-none">&times;</button>
        </div>
        @endif

        {{-- Flash: Peringatan --}}
        @if(session('peringatan'))
        <div class="notif card mb-4 px-4 py-3 flex items-center gap-3 border-l-4 border-yellow-400">
            <svg class="w-4 h-4 text-yellow-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <p class="text-[13px] text-sys-label2 flex-1">{{ session('peringatan') }}</p>
            <button onclick="this.parentElement.remove()" class="text-sys-label4 hover:text-sys-label text-lg leading-none">&times;</button>
        </div>
        @endif

        {{-- Validasi Error --}}
        @if($errors->any())
        <div class="notif card mb-4 px-4 py-3 border-l-4 border-red-500">
            <p class="text-[13px] font-semibold text-sys-label mb-1.5">Perbaiki kesalahan berikut:</p>
            <ul class="space-y-0.5">
                @foreach($errors->all() as $error)
                    <li class="text-[12px] text-red-500">· {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-sys-sep mt-8 py-5 text-center">
        <p class="text-[11px] text-sys-label4">
            © {{ date('Y') }} Galeri Foto &nbsp;·&nbsp; Latihan UKK SMKN 11 Malang &nbsp;·&nbsp; Pandu Setya Wijaya
        </p>
    </footer>

</body>
</html>
