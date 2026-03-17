<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penerima Bantuan - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Edit Penerima Bantuan</h1>
                        <p class="text-xs text-gray-600">{{ $penerimaBantuan->no_pendaftaran }} - {{ $penerimaBantuan->nama_penuh }}</p>
                    </div>
                    <a href="{{ route('penerima-bantuan.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('penerima-bantuan.update', $penerimaBantuan) }}">
                    @csrf
                    @method('PUT')

                    <!-- Section 1: Maklumat Peribadi -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">1. Maklumat Peribadi</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_penuh" class="block text-xs font-medium text-gray-700 mb-2">Nama Penuh *</label>
                                <input type="text" id="nama_penuh" name="nama_penuh" value="{{ old('nama_penuh', $penerimaBantuan->nama_penuh) }}" required maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('nama_penuh')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="no_kp" class="block text-xs font-medium text-gray-700 mb-2">No. KP *</label>
                                <input type="text" id="no_kp" name="no_kp" value="{{ old('no_kp', $penerimaBantuan->no_kp) }}" required maxlength="12" pattern="[0-9]{12}" placeholder="000000000000" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('no_kp')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Jantina *</label>
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="jantina" value="Lelaki" {{ old('jantina', $penerimaBantuan->jantina) == 'Lelaki' ? 'checked' : '' }} required class="mr-2">
                                        <span class="text-xs">Lelaki</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="jantina" value="Perempuan" {{ old('jantina', $penerimaBantuan->jantina) == 'Perempuan' ? 'checked' : '' }} required class="mr-2">
                                        <span class="text-xs">Perempuan</span>
                                    </label>
                                </div>
                                @error('jantina')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="tarikh_lahir" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Lahir *</label>
                                <input type="date" id="tarikh_lahir" name="tarikh_lahir" value="{{ old('tarikh_lahir', $penerimaBantuan->tarikh_lahir) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_lahir')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="bangsa" class="block text-xs font-medium text-gray-700 mb-2">Bangsa</label>
                                <select id="bangsa" name="bangsa" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Bangsa --</option>
                                    @foreach($bangsa as $item)
                                        <option value="{{ $item->nama_kategori }}" {{ old('bangsa', $penerimaBantuan->bangsa) == $item->nama_kategori ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="agama" class="block text-xs font-medium text-gray-700 mb-2">Agama</label>
                                <select id="agama" name="agama" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Agama --</option>
                                    @foreach($agama as $item)
                                        <option value="{{ $item->nama_kategori }}" {{ old('agama', $penerimaBantuan->agama) == $item->nama_kategori ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="status_perkahwinan" class="block text-xs font-medium text-gray-700 mb-2">Status Perkahwinan *</label>
                                <select id="status_perkahwinan" name="status_perkahwinan" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Bujang" {{ old('status_perkahwinan', $penerimaBantuan->status_perkahwinan) == 'Bujang' ? 'selected' : '' }}>Bujang</option>
                                    <option value="Berkahwin" {{ old('status_perkahwinan', $penerimaBantuan->status_perkahwinan) == 'Berkahwin' ? 'selected' : '' }}>Berkahwin</option>
                                    <option value="Duda" {{ old('status_perkahwinan', $penerimaBantuan->status_perkahwinan) == 'Duda' ? 'selected' : '' }}>Duda</option>
                                    <option value="Janda" {{ old('status_perkahwinan', $penerimaBantuan->status_perkahwinan) == 'Janda' ? 'selected' : '' }}>Janda</option>
                                    <option value="Bercerai" {{ old('status_perkahwinan', $penerimaBantuan->status_perkahwinan) == 'Bercerai' ? 'selected' : '' }}>Bercerai</option>
                                </select>
                                @error('status_perkahwinan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="kewarganegaraan" class="block text-xs font-medium text-gray-700 mb-2">Kewarganegaraan</label>
                                <input type="text" id="kewarganegaraan" name="kewarganegaraan" value="{{ old('kewarganegaraan', $penerimaBantuan->kewarganegaraan) }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Maklumat Hubungan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">2. Maklumat Hubungan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="no_telefon" class="block text-xs font-medium text-gray-700 mb-2">No. Telefon *</label>
                                <input type="text" id="no_telefon" name="no_telefon" value="{{ old('no_telefon', $penerimaBantuan->no_telefon) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('no_telefon')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="no_telefon_kecemasan" class="block text-xs font-medium text-gray-700 mb-2">No. Telefon Kecemasan</label>
                                <input type="text" id="no_telefon_kecemasan" name="no_telefon_kecemasan" value="{{ old('no_telefon_kecemasan', $penerimaBantuan->no_telefon_kecemasan) }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div class="md:col-span-2">
                                <label for="emel" class="block text-xs font-medium text-gray-700 mb-2">Emel</label>
                                <input type="email" id="emel" name="emel" value="{{ old('emel', $penerimaBantuan->emel) }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Alamat -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">3. Alamat Semasa</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="alamat_1" class="block text-xs font-medium text-gray-700 mb-2">Alamat 1 *</label>
                                <input type="text" id="alamat_1" name="alamat_1" value="{{ old('alamat_1', $penerimaBantuan->alamat_1) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('alamat_1')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="alamat_2" class="block text-xs font-medium text-gray-700 mb-2">Alamat 2</label>
                                <input type="text" id="alamat_2" name="alamat_2" value="{{ old('alamat_2', $penerimaBantuan->alamat_2) }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="poskod" class="block text-xs font-medium text-gray-700 mb-2">Poskod *</label>
                                    <input type="text" id="poskod" name="poskod" value="{{ old('poskod', $penerimaBantuan->poskod) }}" required maxlength="5" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    @error('poskod')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                                </div>

                                <div>
                                    <label for="bandar" class="block text-xs font-medium text-gray-700 mb-2">Bandar *</label>
                                    <input type="text" id="bandar" name="bandar" value="{{ old('bandar', $penerimaBantuan->bandar) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    @error('bandar')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                                </div>

                                <div>
                                    <label for="negeri" class="block text-xs font-medium text-gray-700 mb-2">Negeri *</label>
                                    <input type="text" id="negeri" name="negeri" value="{{ old('negeri', $penerimaBantuan->negeri) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    @error('negeri')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Maklumat Keluarga -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">4. Maklumat Keluarga</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="bilangan_tanggungan" class="block text-xs font-medium text-gray-700 mb-2">Bilangan Tanggungan</label>
                                <input type="number" id="bilangan_tanggungan" name="bilangan_tanggungan" value="{{ old('bilangan_tanggungan', $penerimaBantuan->bilangan_tanggungan) }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="bilangan_anak" class="block text-xs font-medium text-gray-700 mb-2">Bilangan Anak</label>
                                <input type="number" id="bilangan_anak" name="bilangan_anak" value="{{ old('bilangan_anak', $penerimaBantuan->bilangan_anak) }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="bilangan_anak_sekolah" class="block text-xs font-medium text-gray-700 mb-2">Bilangan Anak Sekolah</label>
                                <input type="number" id="bilangan_anak_sekolah" name="bilangan_anak_sekolah" value="{{ old('bilangan_anak_sekolah', $penerimaBantuan->bilangan_anak_sekolah) }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Maklumat Pekerjaan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">5. Maklumat Pekerjaan & Kewangan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="status_pekerjaan" class="block text-xs font-medium text-gray-700 mb-2">Status Pekerjaan *</label>
                                <select id="status_pekerjaan" name="status_pekerjaan" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Bekerja" {{ old('status_pekerjaan', $penerimaBantuan->status_pekerjaan) == 'Bekerja' ? 'selected' : '' }}>Bekerja</option>
                                    <option value="Tidak Bekerja" {{ old('status_pekerjaan', $penerimaBantuan->status_pekerjaan) == 'Tidak Bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                                    <option value="Pesara" {{ old('status_pekerjaan', $penerimaBantuan->status_pekerjaan) == 'Pesara' ? 'selected' : '' }}>Pesara</option>
                                    <option value="OKU" {{ old('status_pekerjaan', $penerimaBantuan->status_pekerjaan) == 'OKU' ? 'selected' : '' }}>OKU</option>
                                    <option value="Pelajar" {{ old('status_pekerjaan', $penerimaBantuan->status_pekerjaan) == 'Pelajar' ? 'selected' : '' }}>Pelajar</option>
                                    <option value="Suri Rumah" {{ old('status_pekerjaan', $penerimaBantuan->status_pekerjaan) == 'Suri Rumah' ? 'selected' : '' }}>Suri Rumah</option>
                                </select>
                                @error('status_pekerjaan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="pekerjaan" class="block text-xs font-medium text-gray-700 mb-2">Pekerjaan</label>
                                <input type="text" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan', $penerimaBantuan->pekerjaan) }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="pendapatan_bulanan" class="block text-xs font-medium text-gray-700 mb-2">Pendapatan Bulanan (RM)</label>
                                <input type="number" id="pendapatan_bulanan" name="pendapatan_bulanan" value="{{ old('pendapatan_bulanan', $penerimaBantuan->pendapatan_bulanan) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="jenis_kediaman" class="block text-xs font-medium text-gray-700 mb-2">Jenis Kediaman *</label>
                                <select id="jenis_kediaman" name="jenis_kediaman" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenisKediaman as $item)
                                        <option value="{{ $item->nama_kategori }}" {{ old('jenis_kediaman', $penerimaBantuan->jenis_kediaman) == $item->nama_kategori ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_kediaman')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 6: Kategori Kebajikan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">6. Kategori Kebajikan</h2>
                        <p class="text-[10px] text-gray-500 mb-4">Pilih kategori yang berkenaan untuk penerima bantuan ini.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if(($settings['enable_oku'] ?? 'Ya') === 'Ya')
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Status OKU</label>
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="status_oku" value="Ya" {{ old('status_oku', $penerimaBantuan->status_oku) == 'Ya' ? 'checked' : '' }} class="mr-2">
                                        <span class="text-xs">Ya</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="status_oku" value="Tidak" {{ old('status_oku', $penerimaBantuan->status_oku ?? 'Tidak') == 'Tidak' ? 'checked' : '' }} class="mr-2">
                                        <span class="text-xs">Tidak</span>
                                    </label>
                                </div>
                            </div>
                            @endif

                            @if(($settings['enable_yatim'] ?? 'Ya') === 'Ya')
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Status Yatim</label>
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="status_yatim" value="Ya" {{ old('status_yatim', $penerimaBantuan->status_yatim) == 'Ya' ? 'checked' : '' }} class="mr-2">
                                        <span class="text-xs">Ya</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="status_yatim" value="Tidak" {{ old('status_yatim', $penerimaBantuan->status_yatim ?? 'Tidak') == 'Tidak' ? 'checked' : '' }} class="mr-2">
                                        <span class="text-xs">Tidak</span>
                                    </label>
                                </div>
                            </div>
                            @endif

                            @if(($settings['enable_ibu_tunggal'] ?? 'Ya') === 'Ya')
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Status Ibu Tunggal</label>
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="status_ibu_tunggal" value="Ya" {{ old('status_ibu_tunggal', $penerimaBantuan->status_ibu_tunggal) == 'Ya' ? 'checked' : '' }} class="mr-2">
                                        <span class="text-xs">Ya</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="status_ibu_tunggal" value="Tidak" {{ old('status_ibu_tunggal', $penerimaBantuan->status_ibu_tunggal ?? 'Tidak') == 'Tidak' ? 'checked' : '' }} class="mr-2">
                                        <span class="text-xs">Tidak</span>
                                    </label>
                                </div>
                            </div>
                            @endif

                            @if(($settings['enable_warga_emas'] ?? 'Ya') === 'Ya')
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Status Warga Emas</label>
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="status_warga_emas" value="Ya" {{ old('status_warga_emas', $penerimaBantuan->status_warga_emas) == 'Ya' ? 'checked' : '' }} class="mr-2">
                                        <span class="text-xs">Ya</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="status_warga_emas" value="Tidak" {{ old('status_warga_emas', $penerimaBantuan->status_warga_emas ?? 'Tidak') == 'Tidak' ? 'checked' : '' }} class="mr-2">
                                        <span class="text-xs">Tidak</span>
                                    </label>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Section 7: Status -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">7. Status & Catatan</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="status_penerima" class="block text-xs font-medium text-gray-700 mb-2">Status Penerima *</label>
                                <select id="status_penerima" name="status_penerima" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="Aktif" {{ old('status_penerima', $penerimaBantuan->status_penerima) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ old('status_penerima', $penerimaBantuan->status_penerima) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    <option value="Tamat" {{ old('status_penerima', $penerimaBantuan->status_penerima) == 'Tamat' ? 'selected' : '' }}>Tamat</option>
                                </select>
                                @error('status_penerima')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="catatan" class="block text-xs font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea id="catatan" name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('catatan', $penerimaBantuan->catatan) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('penerima-bantuan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            Kemaskini Penerima
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
