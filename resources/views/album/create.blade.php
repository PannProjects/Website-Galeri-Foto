@extends('layouts.app')

@section('title', 'Buat Album Baru')

@section('content')
<div class="max-w-lg mx-auto space-y-4">

    {{-- Kembali --}}
    <a href="{{ route('album.index') }}"
       class="inline-flex items-center gap-1.5 text-[13px] text-sys-label3 hover:text-sys-label transition-colors font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Album
    </a>

    <div class="card p-6">
        <div class="mb-6">
            <h2 class="text-lg font-bold text-sys-label">Buat Album Baru</h2>
            <p class="text-[13px] text-sys-label4 mt-0.5">Organisir foto Anda dalam album yang terstruktur</p>
        </div>

        <form method="POST" action="{{ route('album.store') }}" class="space-y-4">
            @csrf

            {{-- Nama Album --}}
            <div>
                <label for="nama_album" class="block text-[13px] font-semibold text-sys-label2 mb-1.5">
                    Nama Album <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama_album" name="nama_album"
                       value="{{ old('nama_album') }}"
                       placeholder="Contoh: Liburan 2025, Foto Keluarga..."
                       class="input-field w-full px-4 py-2.5 text-[14px] text-sys-label rounded-xl {{ $errors->has('nama_album') ? 'border-red-400' : '' }}"
                       required>
                @error('nama_album')
                    <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-[13px] font-semibold text-sys-label2 mb-1.5">
                    Deskripsi <span class="text-sys-label4 font-normal">(opsional)</span>
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                          placeholder="Ceritakan tentang album ini..."
                          class="input-field w-full px-4 py-2.5 text-[14px] text-sys-label rounded-xl resize-none"
                          style="font-family:inherit;">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-2 pt-1">
                <a href="{{ route('album.index') }}"
                   class="btn-ghost btn flex-1 py-3 text-[13px] text-center rounded-xl">
                    Batal
                </a>
                <button type="submit"
                        class="btn-primary btn flex-1 py-3 text-[13px]">
                    Simpan Album
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
