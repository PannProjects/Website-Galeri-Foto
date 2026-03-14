{{-- ============================================================ --}}
{{-- Halaman Registrasi — Desain Titanium Minimalis --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | Galeri Foto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background-color: #F2F2F7; }
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
            box-sizing: border-box;
        }
        .input-field::placeholder { color: #8E8E93; }
        .input-field:focus {
            background: #FFFFFF;
            border-color: #007AFF;
            box-shadow: 0 0 0 3px rgba(0,122,255,0.12);
        }
        .input-field.err { border-color: #FF3B30; }
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
        .err-text { font-size: 11px; color: #FF3B30; margin-top: 4px; }
        .field-group { margin-bottom: 14px; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div style="text-align:center; margin-bottom:28px;">
            <div style="width:56px;height:56px;background:#1C1C1E;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                <svg width="28" height="28" fill="white" viewBox="0 0 20 20">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"/>
                </svg>
            </div>
            <h1 style="font-size:22px;font-weight:700;color:#1C1C1E;margin-bottom:4px;">Buat Akun</h1>
            <p style="font-size:13px;color:#8E8E93;">Bergabung dan mulai kelola foto Anda</p>
        </div>

        {{-- Card --}}
        <div class="card p-7">

            @if($errors->any())
            <div style="background:#FFF2F2;border:1px solid #FFCDD2;border-radius:10px;padding:10px 14px;margin-bottom:16px;">
                <p style="font-size:12px;color:#C62828;font-weight:600;margin-bottom:4px;">Perbaiki kesalahan berikut:</p>
                @foreach($errors->all() as $error)
                    <p style="font-size:12px;color:#E53935;">· {{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                {{-- Nama Lengkap --}}
                <div class="field-group">
                    <label class="label" for="nama_lengkap">Nama Lengkap <span style="color:#FF3B30;">*</span></label>
                    <input class="input-field {{ $errors->has('nama_lengkap') ? 'err' : '' }}"
                           type="text" id="nama_lengkap" name="nama_lengkap"
                           value="{{ old('nama_lengkap') }}" placeholder="Nama lengkap Anda" required>
                    @error('nama_lengkap')<p class="err-text">{{ $message }}</p>@enderror
                </div>

                {{-- Username --}}
                <div class="field-group">
                    <label class="label" for="username">Username <span style="color:#FF3B30;">*</span></label>
                    <input class="input-field {{ $errors->has('username') ? 'err' : '' }}"
                           type="text" id="username" name="username"
                           value="{{ old('username') }}" placeholder="Minimal 3 karakter" required>
                    @error('username')<p class="err-text">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div class="field-group">
                    <label class="label" for="email">Email <span style="color:#FF3B30;">*</span></label>
                    <input class="input-field {{ $errors->has('email') ? 'err' : '' }}"
                           type="email" id="email" name="email"
                           value="{{ old('email') }}" placeholder="contoh@email.com" required>
                    @error('email')<p class="err-text">{{ $message }}</p>@enderror
                </div>

                {{-- Password --}}
                <div class="field-group">
                    <label class="label" for="password">Password <span style="color:#FF3B30;">*</span></label>
                    <div style="position:relative;">
                        <input class="input-field {{ $errors->has('password') ? 'err' : '' }}"
                               type="password" id="password" name="password"
                               placeholder="Minimal 6 karakter" style="padding-right:44px;" required>
                        <button type="button" onclick="togglePwd('password',this)"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#8E8E93;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')<p class="err-text">{{ $message }}</p>@enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="field-group">
                    <label class="label" for="password_confirmation">Konfirmasi Password <span style="color:#FF3B30;">*</span></label>
                    <input class="input-field"
                           type="password" id="password_confirmation" name="password_confirmation"
                           placeholder="Ulangi password" required>
                </div>

                {{-- Alamat --}}
                <div style="margin-bottom:24px;">
                    <label class="label" for="alamat">Alamat <span style="font-weight:400;color:#8E8E93;">(opsional)</span></label>
                    <textarea class="input-field" id="alamat" name="alamat" rows="2"
                              placeholder="Alamat tempat tinggal"
                              style="resize:none;">{{ old('alamat') }}</textarea>
                </div>

                <button type="submit" class="btn-primary">Buat Akun</button>
            </form>

            <div style="margin-top:20px;text-align:center;">
                <p style="font-size:13px;color:#8E8E93;">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" style="color:#007AFF;font-weight:600;text-decoration:none;">Masuk</a>
                </p>
            </div>
        </div>

        <p style="text-align:center;font-size:11px;color:#C7C7CC;margin-top:24px;">
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
