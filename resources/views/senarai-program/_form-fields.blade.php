<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Program <span class="text-red-500">*</span></label>
        <select name="jenis_program" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
            @foreach(['Kuliah', 'Ceramah', 'Kursus', 'Bengkel', 'Seminar', 'Kem', 'Lain-lain'] as $jenis)
                <option value="{{ $jenis }}" {{ old('jenis_program', $program->jenis_program ?? '') === $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
        <select name="kategori" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
            @foreach(['Dewasa', 'Remaja', 'Kanak-kanak', 'Wanita', 'Umum'] as $kat)
                <option value="{{ $kat }}" {{ old('kategori', $program->kategori ?? 'Umum') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
            @endforeach
        </select>
    </div>
</div>
<div>
    <label class="block text-xs font-medium text-gray-700 mb-1">Penerangan</label>
    <textarea name="penerangan" rows="3" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">{{ old('penerangan', $program->penerangan ?? '') }}</textarea>
</div>
<div>
    <label class="block text-xs font-medium text-gray-700 mb-1">Lokasi</label>
    <input type="text" name="lokasi" value="{{ old('lokasi', $program->lokasi ?? '') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
</div>
<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Kapasiti</label>
        <input type="number" name="kapasiti" value="{{ old('kapasiti', $program->kapasiti ?? '') }}" min="0" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 mb-1">Yuran (RM)</label>
        <input type="number" name="yuran" value="{{ old('yuran', $program->yuran ?? 0) }}" step="0.01" min="0" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
    </div>
</div>
<div>
    <label class="block text-xs font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
    <select name="status" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
        @foreach(['Aktif', 'Tidak Aktif', 'Selesai'] as $s)
            <option value="{{ $s }}" {{ old('status', $program->status ?? 'Aktif') === $s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
    </select>
</div>
