@extends('layouts.app')

@section('title', 'Album Saya')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-sys-label">Album Saya</h2>
            <p class="text-[13px] text-sys-label4 mt-0.5">{{ $albums->count() }} album</p>
        </div>
        <a href="{{ route('album.create') }}"
           class="btn-primary btn px-4 py-2 text-[13px] flex items-center gap-1.5 rounded-xl">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Album
        </a>
    </div>

    {{-- Daftar Album --}}
    @if($albums->isEmpty())
    <div class="card py-20 text-center">
        <div class="w-14 h-14 rounded-2xl bg-sys-bg flex items-center justify-center mx-auto mb-4 border border-sys-sep">
            <svg class="w-7 h-7 text-sys-label4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
        </div>
        <h3 class="text-[15px] font-semibold text-sys-label mb-1">Belum ada album</h3>
        <p class="text-[13px] text-sys-label4 mb-5">Buat album untuk mulai mengorganisir foto Anda.</p>
        <a href="{{ route('album.create') }}" class="btn-primary btn px-5 py-2 text-[13px] inline-block rounded-xl">
            Buat Album Pertama
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($albums as $album)
        <div class="card p-5 hover:shadow-md transition-shadow duration-200 flex flex-col">

            {{-- Header Album --}}
            <div class="flex items-start gap-3 mb-3">
                {{-- Ikon folder --}}
                <div class="w-11 h-11 rounded-xl bg-sys-bg border border-sys-sep flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-sys-label3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="font-semibold text-[14px] text-sys-label truncate">{{ $album->NamaAlbum }}</h3>
                    <p class="text-[12px] text-sys-label4 mt-0.5">
                        {{ \Carbon\Carbon::parse($album->TanggalDibuat)->locale('id')->isoFormat('D MMM Y') }}
                    </p>
                </div>
            </div>

            {{-- Deskripsi --}}
            @if($album->Deskripsi)
                <p class="text-[13px] text-sys-label3 line-clamp-2 mb-3 flex-1 leading-relaxed">{{ $album->Deskripsi }}</p>
            @else
                <p class="text-[13px] text-sys-label4 italic mb-3 flex-1">Tidak ada deskripsi.</p>
            @endif

            {{-- Footer Album --}}
            <div class="flex items-center justify-between pt-3 border-t border-sys-sep mt-auto">
                <span class="text-[12px] text-sys-label4">
                    <span class="font-semibold text-sys-label">{{ $album->fotos_count }}</span> foto
                </span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard', ['album' => $album->AlbumID]) }}"
                       class="text-[12px] font-medium text-sys-accent hover:underline">
                        Lihat &rarr;
                    </a>
                    <span class="text-sys-sep">|</span>
                    <form method="POST" action="{{ route('album.destroy', $album->AlbumID) }}"
                          onsubmit="return confirm('Hapus album \"{{ $album->NamaAlbum }}\" beserta semua fotonya?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-[12px] font-medium text-sys-label4 hover:text-red-500 transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
