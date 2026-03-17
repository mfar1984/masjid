<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ahli Jawatankuasa - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        function formatIC(input) {
            // Remove all non-digits
            let value = input.value.replace(/\D/g, '');

            // Limit to 12 digits
            if (value.length > 12) {
                value = value.substring(0, 12);
            }

            // Format with dashes: DDMMYY-DD-DDDD
            if (value.length >= 6) {
                value = value.substring(0, 6) + '-' + value.substring(6);
            }
            if (value.length >= 9) {
                value = value.substring(0, 9) + '-' + value.substring(9);
            }

            input.value = value;
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 mb-1">Edit Ahli Jawatankuasa</h1>
                            <p class="text-xs text-gray-600">Kemaskini maklumat ahli jawatankuasa</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('ajk.update', $ajk) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Gambar Profil - Section Pertama -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-purple-600 text-sm mr-2">photo_camera</span>
                            Gambar Profil (Carta Organisasi)
                        </h3>
                        <div class="flex items-center gap-6">
                            <div id="gambar-preview" class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-full flex items-center justify-center bg-white flex-shrink-0 overflow-hidden">
                                @if($ajk->gambar_path)
                                    <img src="{{ Storage::url($ajk->gambar_path) }}" class="w-24 h-24 rounded-full object-cover">
                                @else
                                    <span class="material-icons text-gray-400" style="font-size: 32px;">person</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="form-label text-gray-700 mb-1">Muat Naik Gambar</label>
                                <input type="file" name="gambar" accept=".jpg,.jpeg,.png" id="gambar-input"
                                    class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('gambar') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG (Max: 2MB). Gambar ini akan dipaparkan dalam Carta Organisasi.</p>
                                @error('gambar')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                @if($ajk->gambar_path)
                                    <p class="mt-1 text-xs text-green-600">Gambar sedia ada akan diganti jika muat naik gambar baru.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div>
                            <label class="form-label text-gray-700 mb-1">Nama Penuh *</label>
                            <input type="text" name="nama" value="{{ old('nama', $ajk->nama) }}" required
                                class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @enderror"
                                placeholder="Contoh: Ahmad bin Ali">
                            @error('nama')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No. IC -->
                        <div>
                            <label class="form-label text-gray-700 mb-1">Nombor IC *</label>
                            <input type="text" name="no_ic" value="{{ old('no_ic', $ajk->no_ic) }}" required
                                class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('no_ic') border-red-500 @enderror"
                                placeholder="Contoh: 891230-13-1581" maxlength="14" oninput="formatIC(this)">
                            @error('no_ic')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telefon -->
                        <div>
                            <label class="form-label text-gray-700 mb-1">Nombor Telefon *</label>
                            <input type="text" name="telefon" value="{{ old('telefon', $ajk->telefon) }}" required
                                class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('telefon') border-red-500 @enderror"
                                placeholder="Contoh: 012-3456789">
                            @error('telefon')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="form-label text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $ajk->email) }}"
                                class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                placeholder="Contoh: ahmad@example.com">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jantina -->
                        <x-forms.input-field
                            label="Jantina"
                            name="jantina"
                            type="select"
                            required="true"
                            :error="$errors->first('jantina')"
                        >
                            <option value="">Pilih Jantina</option>
                            <option value="Lelaki" {{ old('jantina', $ajk->jantina) == 'Lelaki' ? 'selected' : '' }}>Lelaki</option>
                            <option value="Perempuan" {{ old('jantina', $ajk->jantina) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            <option value="Tidak Dinyatakan" {{ old('jantina', $ajk->jantina) == 'Tidak Dinyatakan' ? 'selected' : '' }}>Tidak Dinyatakan</option>
                        </x-forms.input-field>

                        <!-- Jawatan (Manual Input) -->
                        <div>
                            <label class="form-label text-gray-700 mb-1">Jawatan *</label>
                            <input type="text" name="jawatan" value="{{ old('jawatan', $ajk->jawatan) }}" required
                                class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jawatan') border-red-500 @enderror"
                                placeholder="Contoh: Penasihat, Pengerusi, Imam 1, Bilal 2">
                            <p class="mt-1 text-xs text-gray-500">Masukkan jawatan secara manual</p>
                            @error('jawatan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Urutan (Level dalam carta organisasi) -->
                        <div>
                            <label class="form-label text-gray-700 mb-1">Level Carta</label>
                            <input type="number" name="urutan" value="{{ old('urutan', $ajk->urutan) }}" min="1" max="9"
                                class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('urutan') border-red-500 @enderror"
                                placeholder="Contoh: 1-9">
                            <p class="mt-1 text-xs text-gray-500">Level hierarki: 1=Penasihat, 2=Pengerusi, 3=Naib/Setiausaha, 4=Bendahari/Pen.SU, 5=Pen.Bendahari, 6=AJK, 7=Imam, 8=Bilal, 9=Siak</p>
                            @error('urutan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tarikh Lantikan -->
                        <div>
                            <label class="form-label text-gray-700 mb-1">Tarikh Lantikan *</label>
                            <input type="date" name="tarikh_lantikan" value="{{ old('tarikh_lantikan', $ajk->tarikh_lantikan?->format('Y-m-d')) }}" required
                                class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tarikh_lantikan') border-red-500 @enderror">
                            @error('tarikh_lantikan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tarikh Tamat -->
                        <div>
                            <label class="form-label text-gray-700 mb-1">Tarikh Tamat</label>
                            <input type="date" name="tarikh_tamat" value="{{ old('tarikh_tamat', $ajk->tarikh_tamat?->format('Y-m-d')) }}"
                                class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tarikh_tamat') border-red-500 @enderror">
                            @error('tarikh_tamat')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tempoh Jawatan -->
                        <div>
                            <label class="form-label text-gray-700 mb-1">Tempoh Jawatan</label>
                            <input type="text" name="tempoh_jawatan" value="{{ old('tempoh_jawatan', $ajk->tempoh_jawatan) }}"
                                class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tempoh_jawatan') border-red-500 @enderror"
                                placeholder="Contoh: 2 Tahun">
                            @error('tempoh_jawatan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <x-forms.input-field
                            label="Status"
                            name="status"
                            type="select"
                            required="true"
                            :error="$errors->first('status')"
                        >
                            <option value="">Pilih Status</option>
                            <option value="Aktif" {{ old('status', $ajk->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tidak Aktif" {{ old('status', $ajk->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="Menunggu" {{ old('status', $ajk->status) == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        </x-forms.input-field>
                    </div>

                    <!-- Alamat (Full Width) -->
                    <div>
                        <label class="form-label text-gray-700 mb-1">Alamat</label>
                        <textarea name="alamat" rows="3"
                            class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('alamat') border-red-500 @enderror"
                            placeholder="Masukkan alamat penuh">{{ old('alamat', $ajk->alamat) }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Uploads -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Dokumen Sokongan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- IC Depan -->
                            <div>
                                <label class="form-label text-gray-700 mb-1">IC Depan</label>
                                <input type="file" name="ic_depan" accept=".jpg,.jpeg,.png,.pdf"
                                    class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ic_depan') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF (Max: 2MB)</p>
                                @error('ic_depan')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                @if($ajk->ic_depan_path)
                                    <div class="mt-2">
                                        <a href="{{ Storage::url($ajk->ic_depan_path) }}" target="_blank" class="text-xs text-blue-600 hover:underline">
                                            Lihat dokumen sedia ada
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- IC Belakang -->
                            <div>
                                <label class="form-label text-gray-700 mb-1">IC Belakang</label>
                                <input type="file" name="ic_belakang" accept=".jpg,.jpeg,.png,.pdf"
                                    class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ic_belakang') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF (Max: 2MB)</p>
                                @error('ic_belakang')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                @if($ajk->ic_belakang_path)
                                    <div class="mt-2">
                                        <a href="{{ Storage::url($ajk->ic_belakang_path) }}" target="_blank" class="text-xs text-blue-600 hover:underline">
                                            Lihat dokumen sedia ada
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Surat Lantikan -->
                            <div>
                                <label class="form-label text-gray-700 mb-1">Surat Lantikan</label>
                                <input type="file" name="surat_lantikan" accept=".jpg,.jpeg,.png,.pdf"
                                    class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('surat_lantikan') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF (Max: 2MB)</p>
                                @error('surat_lantikan')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                @if($ajk->surat_lantikan_path)
                                    <div class="mt-2">
                                        <a href="{{ Storage::url($ajk->surat_lantikan_path) }}" target="_blank" class="text-xs text-blue-600 hover:underline">
                                            Lihat dokumen sedia ada
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Super Admin: Masjid Selection -->
                    @if(auth()->user()->isSuperAdmin())
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Tetapan Masjid (Super Admin)</h3>
                        <div>
                            <label class="form-label text-gray-700 mb-1">Masjid *</label>
                            <select name="masjid_id" required
                                class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('masjid_id') border-red-500 @enderror">
                                <option value="">Pilih Masjid</option>
                                @foreach(\App\Models\Masjid::orderBy('nama')->get() as $masjid)
                                <option value="{{ $masjid->id }}" {{ old('masjid_id', $ajk->masjid_id) == $masjid->id ? 'selected' : '' }}>
                                    {{ $masjid->nama }}
                                </option>
                                @endforeach
                            </select>
                            @error('masjid_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('ajk.index') }}"
                            class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">close</span>
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        // Preview gambar sebelum upload
        document.getElementById('gambar-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('gambar-preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" class="w-24 h-24 rounded-full object-cover">';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
