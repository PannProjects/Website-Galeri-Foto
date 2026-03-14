@extends('layouts.app')

@section('title', 'Unggah Foto')

@section('content')
<div class="max-w-xl mx-auto space-y-4">

    {{-- Kembali --}}
    <a href="{{ route('dashboard') }}"
       class="inline-flex items-center gap-1.5 text-[13px] text-sys-label3 hover:text-sys-label transition-colors font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>

    <div class="card p-6">
        <div class="mb-6">
            <h2 class="text-lg font-bold text-sys-label">Unggah Foto</h2>
            <p class="text-[13px] text-sys-label4 mt-0.5">JPG, PNG, WEBP · Maksimal 5 MB</p>
        </div>

        <form method="POST" action="{{ route('foto.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- Drop Zone --}}
            <div>
                <label for="file_foto"
                       class="group flex flex-col items-center justify-center h-44 rounded-2xl border-2 border-dashed cursor-pointer transition-all duration-200 relative overflow-hidden
                              {{ $errors->has('file_foto') ? 'border-red-400 bg-red-50' : 'border-sys-sep hover:border-sys-accent bg-sys-bg hover:bg-white' }}">

                    {{-- Placeholder --}}
                    <div id="upload-placeholder" class="flex flex-col items-center gap-2 pointer-events-none">
                        <div class="w-10 h-10 rounded-xl bg-white border border-sys-sep flex items-center justify-center">
                            <svg class="w-5 h-5 text-sys-label4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <p class="text-[13px] text-sys-label3 text-center">
                            <span class="text-sys-accent font-semibold">Pilih file</span> atau seret ke sini
                        </p>
                        <p class="text-[11px] text-sys-label4">JPG, PNG, WEBP hingga 5MB</p>
                    </div>

                    {{-- Preview --}}
                    <img id="preview-foto" src="#" alt="Preview"
                         class="hidden absolute inset-0 w-full h-full object-cover rounded-2xl">

                    <input type="file" id="file_foto" name="file_foto"
                           accept="image/jpeg,image/png,image/webp"
                           class="hidden" onchange="previewGambar(this)">
                </label>
                @error('file_foto')
                    <p class="text-[11px] text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Judul Foto --}}
            <div>
                <label for="judul_foto" class="block text-[13px] font-semibold text-sys-label2 mb-1.5">
                    Judul Foto <span class="text-red-500">*</span>
                </label>
                <input type="text" id="judul_foto" name="judul_foto"
                       value="{{ old('judul_foto') }}"
                       placeholder="Beri judul foto ini"
                       class="input-field w-full px-4 py-2.5 text-[14px] text-sys-label rounded-xl {{ $errors->has('judul_foto') ? 'border-red-400' : '' }}"
                       required>
                @error('judul_foto')
                    <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Album --}}
            <div>
                <label for="album_id" class="block text-[13px] font-semibold text-sys-label2 mb-1.5">
                    Album <span class="text-red-500">*</span>
                </label>
                <select id="album_id" name="album_id"
                        class="input-field w-full px-4 py-2.5 text-[14px] text-sys-label rounded-xl cursor-pointer {{ $errors->has('album_id') ? 'border-red-400' : '' }}"
                        required>
                    <option value="">-- Pilih Album --</option>
                    @foreach($albums as $album)
                        <option value="{{ $album->AlbumID }}" {{ old('album_id') == $album->AlbumID ? 'selected' : '' }}>
                            {{ $album->NamaAlbum }}
                        </option>
                    @endforeach
                </select>
                @error('album_id')
                    <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-sys-label4 mt-1">
                    Belum ada album?
                    <a href="{{ route('album.create') }}" class="text-sys-accent hover:underline font-medium">Buat album baru</a>
                </p>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-[13px] font-semibold text-sys-label2 mb-1.5">
                    Deskripsi <span class="text-sys-label4 font-normal">(opsional)</span>
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                          placeholder="Ceritakan tentang foto ini..."
                          class="input-field w-full px-4 py-2.5 text-[14px] text-sys-label rounded-xl resize-none"
                          style="font-family:inherit;">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-2 pt-1">
                <a href="{{ route('dashboard') }}"
                   class="btn-ghost btn flex-1 py-3 text-[13px] text-center rounded-xl">
                    Batal
                </a>
                <button type="submit"
                        class="btn-primary btn flex-1 py-3 text-[13px] flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Unggah Foto
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewGambar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('preview-foto').src = e.target.result;
                document.getElementById('preview-foto').classList.remove('hidden');
                document.getElementById('upload-placeholder').classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
