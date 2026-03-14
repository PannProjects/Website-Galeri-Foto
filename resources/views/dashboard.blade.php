@extends('layouts.app')

@section('title', 'Galeri Saya')

@section('content')
<div class="space-y-5">

    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-sys-label">Galeri Saya</h2>
            <p class="text-[13px] text-sys-label4 mt-0.5">
                {{ $fotos->count() }} foto
                @if($albumId) · album difilter @endif
            </p>
        </div>

        <div class="flex items-center gap-2">
            {{-- Filter Album --}}
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                <select name="album" onchange="this.form.submit()"
                        class="text-[13px] bg-white border border-sys-sep rounded-xl px-3 py-2 text-sys-label font-medium focus:outline-none focus:border-sys-accent cursor-pointer"
                        style="box-shadow:0 1px 3px rgba(0,0,0,0.06);">
                    <option value="">Semua Album</option>
                    @foreach($albums as $album)
                        <option value="{{ $album->AlbumID }}" {{ $albumId == $album->AlbumID ? 'selected' : '' }}>
                            {{ $album->NamaAlbum }}
                        </option>
                    @endforeach
                </select>
                @if($albumId)
                    <a href="{{ route('dashboard') }}"
                       class="text-[12px] font-medium text-sys-label4 hover:text-sys-label px-2 py-1.5 rounded-lg hover:bg-sys-sep transition-colors">
                        Hapus Filter
                    </a>
                @endif
            </form>

            <a href="{{ route('foto.create') }}"
               class="btn-accent px-4 py-2 text-[13px] flex items-center gap-1.5 rounded-xl">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Unggah
            </a>
        </div>
    </div>

    {{-- Foto Grid / Empty State --}}
    @if($fotos->isEmpty())
    <div class="card py-20 text-center">
        <div class="w-14 h-14 rounded-2xl bg-sys-bg flex items-center justify-center mx-auto mb-4 border border-sys-sep">
            <svg class="w-7 h-7 text-sys-label4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="text-[15px] font-semibold text-sys-label mb-1">
            @if($albumId) Album ini masih kosong @else Galeri masih kosong @endif
        </h3>
        <p class="text-[13px] text-sys-label4 mb-5">
            @if($albums->isEmpty())
                Buat album dahulu, lalu unggah foto Anda.
            @else
                Mulai dengan mengunggah foto pertama Anda.
            @endif
        </p>
        @if($albums->isEmpty())
            <a href="{{ route('album.create') }}" class="btn-ghost px-5 py-2 text-[13px] inline-block rounded-xl">
                Buat Album
            </a>
        @else
            <a href="{{ route('foto.create') }}" class="btn-accent px-5 py-2 text-[13px] inline-block rounded-xl">
                Unggah Foto
            </a>
        @endif
    </div>
    @else
    {{-- Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 sm:gap-3">
        @foreach($fotos as $foto)
        <a href="{{ route('foto.show', $foto->FotoID) }}"
           class="group relative aspect-square rounded-2xl overflow-hidden bg-sys-fill"
           style="box-shadow:0 1px 4px rgba(0,0,0,0.08);">

            <img src="{{ Storage::url($foto->LokasiFile) }}"
                 alt="{{ $foto->JudulFoto }}"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                 loading="lazy">

            {{-- Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent
                        opacity-0 group-hover:opacity-100 transition-opacity duration-250 flex flex-col justify-end p-3">
                <p class="text-white text-xs font-semibold truncate leading-tight">{{ $foto->JudulFoto }}</p>
                <div class="flex items-center gap-2.5 mt-1">
                    <span class="text-white/75 text-[11px]">♥ {{ $foto->likes->count() }}</span>
                    <span class="text-white/75 text-[11px]">💬 {{ $foto->komentars->count() }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</div>
@endsection
