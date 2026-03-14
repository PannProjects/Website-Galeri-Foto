@extends('layouts.app')

@section('title', $foto->JudulFoto)

@section('content')
<div class="max-w-3xl mx-auto space-y-4">

    {{-- Kembali --}}
    <a href="{{ route('dashboard') }}"
       class="inline-flex items-center gap-1.5 text-[13px] text-sys-label3 hover:text-sys-label transition-colors font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>

    {{-- Foto Utama --}}
    <div class="card overflow-hidden">
        {{-- Gambar --}}
        <div class="bg-sys-bg flex items-center justify-center" style="min-height:280px;max-height:520px;overflow:hidden;">
            <img src="{{ Storage::url($foto->LokasiFile) }}"
                 alt="{{ $foto->JudulFoto }}"
                 style="max-height:520px;width:100%;object-fit:contain;">
        </div>

        {{-- Info Foto --}}
        <div class="p-5">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <h1 class="text-lg font-bold text-sys-label mb-1.5">{{ $foto->JudulFoto }}</h1>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[12px] text-sys-label4">
                        <span class="flex items-center gap-1.5">
                            <div class="w-5 h-5 rounded-full bg-sys-label flex items-center justify-center text-white text-[10px] font-semibold">
                                {{ strtoupper(substr($foto->user->NamaLengkap ?? 'U', 0, 1)) }}
                            </div>
                            {{ $foto->user->NamaLengkap ?? 'Pengguna' }}
                        </span>
                        <span>{{ $foto->album->NamaAlbum ?? '-' }}</span>
                        <span>{{ \Carbon\Carbon::parse($foto->TanggalUnggah)->locale('id')->isoFormat('D MMMM Y') }}</span>
                    </div>
                    @if($foto->DeskripsiFoto)
                        <p class="text-[13px] text-sys-label3 mt-3 leading-relaxed">{{ $foto->DeskripsiFoto }}</p>
                    @endif
                </div>

                {{-- Aksi --}}
                <div class="flex items-center gap-2 shrink-0">
                    {{-- Like --}}
                    <form method="POST" action="{{ route('like.toggle', $foto->FotoID) }}">
                        @csrf
                        <button type="submit"
                                class="btn flex items-center gap-2 px-4 py-2 rounded-xl text-[13px] font-semibold border transition-colors
                                       {{ $sudahLike
                                           ? 'bg-red-50 text-red-500 border-red-200'
                                           : 'bg-white text-sys-label3 border-sys-sep hover:border-red-200 hover:text-red-500' }}">
                            <span>{{ $sudahLike ? '♥' : '♡' }}</span>
                            <span>{{ $foto->likes->count() }}</span>
                        </button>
                    </form>

                    {{-- Hapus (owner) --}}
                    @if($foto->UserID === $userId)
                    <form method="POST" action="{{ route('foto.destroy', $foto->FotoID) }}"
                          onsubmit="return confirm('Hapus foto ini? Tindakan tidak bisa dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn flex items-center gap-1.5 px-4 py-2 rounded-xl text-[13px] font-medium text-red-500 border border-sys-sep hover:bg-red-50 hover:border-red-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Komentar --}}
    <div class="card p-5">
        <h2 class="text-[15px] font-semibold text-sys-label mb-4 flex items-center gap-2">
            Komentar
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-sys-bg text-[11px] font-semibold text-sys-label3 border border-sys-sep">
                {{ $foto->komentars->count() }}
            </span>
        </h2>

        {{-- Form Komentar --}}
        <form method="POST" action="{{ route('komentar.store', $foto->FotoID) }}" class="flex gap-3 mb-5">
            @csrf
            <div class="w-7 h-7 rounded-full bg-sys-label flex items-center justify-center text-white text-[11px] font-semibold shrink-0 mt-1">
                {{ strtoupper(substr(session('user_nama', 'U'), 0, 1)) }}
            </div>
            <div class="flex-1">
                <textarea name="isi_komentar" rows="2"
                          placeholder="Tulis komentar..."
                          class="w-full px-3 py-2.5 rounded-xl text-[13px] text-sys-label resize-none
                                 bg-sys-bg border border-sys-sep focus:outline-none focus:border-sys-accent focus:bg-white transition-all"
                          style="font-family:inherit;" required>{{ old('isi_komentar') }}</textarea>
                @error('isi_komentar')
                    <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <div class="flex justify-end mt-2">
                    <button type="submit"
                            class="btn-accent btn px-4 py-1.5 rounded-xl text-[13px]">
                        Kirim
                    </button>
                </div>
            </div>
        </form>

        {{-- Daftar Komentar --}}
        @if($foto->komentars->isEmpty())
        <div class="text-center py-6 border-t border-sys-sep">
            <p class="text-[13px] text-sys-label4">Belum ada komentar.</p>
        </div>
        @else
        <div class="space-y-4 border-t border-sys-sep pt-4">
            @foreach($foto->komentars as $komentar)
            <div class="flex gap-3 group">
                <div class="w-7 h-7 rounded-full bg-sys-fill flex items-center justify-center text-sys-label text-[11px] font-semibold shrink-0 border border-sys-sep">
                    {{ strtoupper(substr($komentar->user->NamaLengkap ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 bg-sys-bg rounded-xl px-3 py-2.5">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[12px] font-semibold text-sys-label">{{ $komentar->user->NamaLengkap ?? 'Pengguna' }}</span>
                        <div class="flex items-center gap-3">
                            <span class="text-[11px] text-sys-label4">
                                {{ \Carbon\Carbon::parse($komentar->TanggalKomentar)->locale('id')->isoFormat('D MMM Y') }}
                            </span>
                            @if($komentar->UserID === $userId)
                            <form method="POST" action="{{ route('komentar.destroy', $komentar->KomentarID) }}"
                                  class="opacity-0 group-hover:opacity-100 transition-opacity"
                                  onsubmit="return confirm('Hapus komentar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[11px] font-medium text-red-400 hover:text-red-600">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </div>
                    <p class="text-[13px] text-sys-label3 leading-relaxed">{{ $komentar->IsiKomentar }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection
