{{-- ============================================================ --}}
{{-- Halaman Login — Desain Titanium Minimalis --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Galeri Foto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Poppins', sans-serif; }

        body {
            background-color: #F2F2F7;
        }
        .card {
            background: #FFFFFF;
            border: 1px solid #E5E5EA;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06), 0 12px 40px rgba(0,0,0,0.05);
        }
        .input-field {
            background: #F2F2F7;
            border: 1.5px solid transparent;
            border-radius: 12px;
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            color: #1C1C1E;
            transition: all 0.18s ease;
            outline: none;
        }
        .input-field::placeholder { color: #8E8E93; }
        .input-field:focus {
            background: #FFFFFF;
            border-color: #007AFF;
            box-shadow: 0 0 0 3px rgba(0,122,255,0.12);
        }
        .btn-primary {
            width: 100%;
            padding: 13px;
            background: #1C1C1E;
            color: #FFFFFF;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.18s ease;
        }
        .btn-primary:hover { background: #3A3A3C; }
        .btn-primary:active { transform: scale(0.98); }

        .label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #3A3A3C;
            margin-bottom: 6px;
        }
        .logo-icon {
            width: 56px;
            height: 56px;
            background: #1C1C1E;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="logo-icon">
                <svg width="28" height="28" fill="white" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h1 style="font-size:22px; font-weight:700; color:#1C1C1E; margin-bottom:4px;">Galeri Foto</h1>
            <p style="font-size:13px; color:#8E8E93;">Masuk untuk melanjutkan</p>
        </div>

        {{-- Card --}}
        <div class="card p-7">

            {{-- Error Login --}}
            @if($errors->has('login'))
            <div style="background:#FFF2F2; border:1px solid #FFCDD2; border-radius:10px; padding:10px 14px; margin-bottom:18px; display:flex; align-items:center; gap:8px;">
                <svg width="15" height="15" fill="#E53935" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p style="font-size:13px; color:#C62828; font-weight:500;">{{ $errors->first('login') }}</p>
            </div>
            @endif

            {{-- Flash Sukses --}}
            @if(session('sukses'))
            <div style="background:#F0FFF4; border:1px solid #C6F6D5; border-radius:10px; padding:10px 14px; margin-bottom:18px;">
                <p style="font-size:13px; color:#276749; font-weight:500;">{{ session('sukses') }}</p>
            </div>
            @endif

            {{-- Flash Peringatan --}}
            @if(session('peringatan'))
            <div style="background:#FFFDF0; border:1px solid #FAE580; border-radius:10px; padding:10px 14px; margin-bottom:18px;">
                <p style="font-size:13px; color:#92600A; font-weight:500;">{{ session('peringatan') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                {{-- Username --}}
                <div style="margin-bottom:16px;">
                    <label class="label" for="username">Username</label>
                    <input class="input-field" type="text" id="username" name="username"
                           value="{{ old('username') }}" placeholder="Masukkan username" autocomplete="username" required>
                    @error('username')
                        <p style="font-size:11px; color:#E53935; margin-top:4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div style="margin-bottom:24px;">
                    <label class="label" for="password">Password</label>
                    <div style="position:relative;">
                        <input class="input-field" type="password" id="password" name="password"
                               placeholder="Masukkan password" autocomplete="current-password"
                               style="padding-right:44px;" required>
                        <button type="button" onclick="togglePwd('password',this)"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#8E8E93;padding:0;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p style="font-size:11px; color:#E53935; margin-top:4px;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">Masuk</button>
            </form>

            <div style="margin-top:20px; text-align:center;">
                <p style="font-size:13px; color:#8E8E93;">
                    Belum punya akun?
                    <a href="{{ route('register') }}" style="color:#007AFF; font-weight:600; text-decoration:none;">Daftar</a>
                </p>
            </div>
        </div>

        <p style="text-align:center; font-size:11px; color:#C7C7CC; margin-top:24px;">
            Latihan UKK · SMKN 11 Malang · XII RPL 2
        </p>
    </div>

    <script>
        function togglePwd(id, btn) {
            const f = document.getElementById(id);
            f.type = f.type === 'password' ? 'text' : 'password';
            btn.style.color = f.type === 'text' ? '#007AFF' : '#8E8E93';
        }
    </script>
</body>
</html>
