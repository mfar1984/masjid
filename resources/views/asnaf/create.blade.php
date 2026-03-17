<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Asnaf - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        function formatIC(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 12) value = value.substring(0, 12);
            if (value.length >= 6) value = value.substring(0, 6) + '-' + value.substring(6);
            if (value.length >= 9) value = value.substring(0, 9) + '-' + value.substring(9);
            input.value = value;
        }
        function copyAddress(sourcePrefix, targetPrefix) {
            document.querySelector(`[name="${targetPrefix}_alamat"]`).value = document.querySelector(`[name="${sourcePrefix}_alamat"]`).value;
            document.querySelector(`[name="${targetPrefix}_poskod"]`).value = document.querySelector(`[name="${sourcePrefix}_poskod"]`).value;
            document.querySelector(`[name="${targetPrefix}_bandar"]`).value = document.querySelector(`[name="${sourcePrefix}_bandar"]`).value;
            document.querySelector(`[name="${targetPrefix}_negeri"]`).value = document.querySelector(`[name="${sourcePrefix}_negeri"]`).value;
        }
        function toggleHutangFields() {
            const adaHutang = document.querySelector('input[name="ada_hutang"]:checked')?.value;
            const hutangFields = document.getElementById('hutangFields');
            if (hutangFields) {
                hutangFields.style.display = adaHutang === '1' ? 'block' : 'none';
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />
    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Permohonan Asnaf</h1>
                    <p class="text-xs text-gray-600">Borang permohonan bantuan zakat</p>
                </div>
                <form method="POST" action="{{ route('asnaf.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Section 1: Maklumat Peribadi -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">1. Maklumat Peribadi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label text-gray-700 mb-1">Nama Penuh *</label>
                                <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @enderror">
                                @error('nama')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">No. IC *</label>
                                <input type="text" name="no_ic" value="{{ old('no_ic') }}" required maxlength="14" oninput="formatIC(this)" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('no_ic') border-red-500 @enderror">
                                @error('no_ic')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Jantina *</label>
                                <select name="jantina" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jantina') border-red-500 @enderror">
                                    <option value="">Pilih Jantina</option>
                                    <option value="Lelaki" {{ old('jantina') == 'Lelaki' ? 'selected' : '' }}>Lelaki</option>
                                    <option value="Perempuan" {{ old('jantina') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jantina')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Bangsa *</label>
                                <select name="bangsa" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('bangsa') border-red-500 @enderror">
                                    <option value="">-- Pilih Bangsa --</option>
                                    @foreach($bangsa as $item)
                                        <option value="{{ $item->nama_kategori }}" {{ old('bangsa') == $item->nama_kategori ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bangsa')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Agama *</label>
                                <select name="agama" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('agama') border-red-500 @enderror">
                                    <option value="">-- Pilih Agama --</option>
                                    @foreach($agama as $item)
                                        <option value="{{ $item->nama_kategori }}" {{ old('agama', 'Islam') == $item->nama_kategori ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('agama')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Status Perkahwinan *</label>
                                <select name="status_perkahwinan" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status_perkahwinan') border-red-500 @enderror">
                                    <option value="">-- Pilih Status Perkahwinan --</option>
                                    @foreach($statusPerkahwinan as $item)
                                        <option value="{{ $item->nama_kategori }}" {{ old('status_perkahwinan') == $item->nama_kategori ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status_perkahwinan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Telefon *</label>
                                <input type="text" name="telefon" value="{{ old('telefon') }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('telefon') border-red-500 @enderror">
                                @error('telefon')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Telefon Alternatif</label>
                                <input type="text" name="telefon_alternatif" value="{{ old('telefon_alternatif') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Alamat IC -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">2. Alamat Mengikut Kad Pengenalan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="form-label text-gray-700 mb-1">Alamat *</label>
                                <textarea name="alamat_ic" rows="2" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('alamat_ic') border-red-500 @enderror">{{ old('alamat_ic') }}</textarea>
                                @error('alamat_ic')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Poskod *</label>
                                <input type="text" name="poskod_ic" value="{{ old('poskod_ic') }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Bandar *</label>
                                <input type="text" name="bandar_ic" value="{{ old('bandar_ic') }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label text-gray-700 mb-1">Negeri *</label>
                                <select name="negeri_ic" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Negeri --</option>
                                    @foreach($negeri as $item)
                                        <option value="{{ $item->nama_kategori }}" {{ old('negeri_ic') == $item->nama_kategori ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Alamat Surat -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">3. Alamat Surat Menyurat</h3>
                        <div class="mb-3">
                            <label class="inline-flex items-center">
                                <input type="checkbox" onclick="copyAddress('alamat_ic', 'alamat_surat')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-xs text-gray-700">Sama dengan alamat IC</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="form-label text-gray-700 mb-1">Alamat *</label>
                                <textarea name="alamat_surat" rows="2" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('alamat_surat') }}</textarea>
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Poskod *</label>
                                <input type="text" name="poskod_surat" value="{{ old('poskod_surat') }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Bandar *</label>
                                <input type="text" name="bandar_surat" value="{{ old('bandar_surat') }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label text-gray-700 mb-1">Negeri *</label>
                                <select name="negeri_surat" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Negeri --</option>
                                    @foreach($negeri as $item)
                                        <option value="{{ $item->nama_kategori }}" {{ old('negeri_surat') == $item->nama_kategori ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Alamat Kediaman -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">4. Alamat Kediaman Semasa</h3>
                        <div class="mb-3">
                            <label class="inline-flex items-center">
                                <input type="checkbox" onclick="copyAddress('alamat_surat', 'alamat_kediaman')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-xs text-gray-700">Sama dengan alamat surat</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="form-label text-gray-700 mb-1">Alamat *</label>
                                <textarea name="alamat_kediaman" rows="2" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('alamat_kediaman') }}</textarea>
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Poskod *</label>
                                <input type="text" name="poskod_kediaman" value="{{ old('poskod_kediaman') }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Bandar *</label>
                                <input type="text" name="bandar_kediaman" value="{{ old('bandar_kediaman') }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Negeri *</label>
                                <select name="negeri_kediaman" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Negeri --</option>
                                    @foreach($negeri as $item)
                                        <option value="{{ $item->nama_kategori }}" {{ old('negeri_kediaman') == $item->nama_kategori ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Status Kediaman *</label>
                                <select name="status_kediaman" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Status</option>
                                    <option value="Milik Sendiri" {{ old('status_kediaman') == 'Milik Sendiri' ? 'selected' : '' }}>Milik Sendiri</option>
                                    <option value="Sewa" {{ old('status_kediaman') == 'Sewa' ? 'selected' : '' }}>Sewa</option>
                                    <option value="Menumpang" {{ old('status_kediaman') == 'Menumpang' ? 'selected' : '' }}>Menumpang</option>
                                    <option value="Lain-lain" {{ old('status_kediaman') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Maklumat Waris -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">5. Maklumat Waris</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label text-gray-700 mb-1">Nama Waris *</label>
                                <input type="text" name="nama_waris" value="{{ old('nama_waris') }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Hubungan *</label>
                                <input type="text" name="hubungan_waris" value="{{ old('hubungan_waris') }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Anak, Suami, Isteri">
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">No. IC Waris *</label>
                                <input type="text" name="no_ic_waris" value="{{ old('no_ic_waris') }}" required maxlength="14" oninput="formatIC(this)" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Telefon Waris *</label>
                                <input type="text" name="telefon_waris" value="{{ old('telefon_waris') }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label text-gray-700 mb-1">Alamat Waris</label>
                                <textarea name="alamat_waris" rows="2" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('alamat_waris') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 6: Kategori Asnaf -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">6. Kategori Asnaf & Sebab Permohonan</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="form-label text-gray-700 mb-1">Kategori Asnaf *</label>
                                <select name="kategori_asnaf" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Kategori Asnaf --</option>
                                    @foreach($kategoriAsnafList as $item)
                                        <option value="{{ $item->nama_kategori }}" {{ old('kategori_asnaf') == $item->nama_kategori ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Sebab Permohonan *</label>
                                <textarea name="sebab_permohonan" rows="4" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Nyatakan sebab permohonan dengan terperinci">{{ old('sebab_permohonan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 7: Pekerjaan & Pendapatan -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">7. Maklumat Pekerjaan & Pendapatan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label text-gray-700 mb-1">Status Pekerjaan *</label>
                                <select name="status_pekerjaan" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Status Pekerjaan --</option>
                                    @foreach($statusPekerjaan as $item)
                                        <option value="{{ $item->nama_kategori }}" {{ old('status_pekerjaan') == $item->nama_kategori ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Nama Majikan</label>
                                <input type="text" name="nama_majikan" value="{{ old('nama_majikan') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Jawatan</label>
                                <input type="text" name="jawatan" value="{{ old('jawatan') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Pendapatan Bulanan (RM) *</label>
                                <input type="number" step="0.01" name="pendapatan_bulanan" value="{{ old('pendapatan_bulanan', '0.00') }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Pendapatan Pasangan (RM)</label>
                                <input type="number" step="0.01" name="pendapatan_pasangan" value="{{ old('pendapatan_pasangan', '0.00') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Pendapatan Lain (RM)</label>
                                <input type="number" step="0.01" name="pendapatan_lain" value="{{ old('pendapatan_lain', '0.00') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label text-gray-700 mb-1">Sumber Pendapatan Lain</label>
                                <input type="text" name="sumber_pendapatan_lain" value="{{ old('sumber_pendapatan_lain') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Sewa rumah, dividen">
                            </div>
                        </div>
                    </div>

                    <!-- Section 8: Tanggungan -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">8. Tanggungan & Perbelanjaan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label text-gray-700 mb-1">Bilangan Tanggungan *</label>
                                <input type="number" name="bilangan_tanggungan" value="{{ old('bilangan_tanggungan', '0') }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Jumlah Perbelanjaan Bulanan (RM) *</label>
                                <input type="number" step="0.01" name="jumlah_perbelanjaan" value="{{ old('jumlah_perbelanjaan', '0.00') }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section 9: Hutang -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">9. Maklumat Hutang</h3>
                        <div class="mb-4">
                            <label class="form-label text-gray-700 mb-2">Adakah anda mempunyai hutang? *</label>
                            <div class="flex gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="ada_hutang" value="1" {{ old('ada_hutang') == '1' ? 'checked' : '' }} required onclick="toggleHutangFields()" class="text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-xs text-gray-700">Ya</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="ada_hutang" value="0" {{ old('ada_hutang') == '0' ? 'checked' : '' }} required onclick="toggleHutangFields()" class="text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-xs text-gray-700">Tidak</span>
                                </label>
                            </div>
                        </div>
                        <div id="hutangFields" style="display: {{ old('ada_hutang') == '1' ? 'block' : 'none' }};">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label text-gray-700 mb-1">Jumlah Hutang (RM)</label>
                                    <input type="number" step="0.01" name="jumlah_hutang" value="{{ old('jumlah_hutang', '0.00') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="form-label text-gray-700 mb-1">Bayaran Hutang Bulanan (RM)</label>
                                    <input type="number" step="0.01" name="bayaran_hutang_bulanan" value="{{ old('bayaran_hutang_bulanan', '0.00') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="form-label text-gray-700 mb-1">Sebab Berhutang</label>
                                    <textarea name="sebab_berhutang" rows="2" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('sebab_berhutang') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 10: Kesihatan -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">10. Maklumat Kesihatan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label text-gray-700 mb-1">Status Kesihatan *</label>
                                <select name="status_kesihatan" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Status Kesihatan --</option>
                                    @foreach($statusKesihatan as $item)
                                        <option value="{{ $item->nama_kategori }}" {{ old('status_kesihatan') == $item->nama_kategori ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Jenis Penyakit (jika ada)</label>
                                <input type="text" name="jenis_penyakit" value="{{ old('jenis_penyakit') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label text-gray-700 mb-1">Kos Perubatan Bulanan (RM)</label>
                                <input type="number" step="0.01" name="kos_perubatan_bulanan" value="{{ old('kos_perubatan_bulanan', '0.00') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section 11: Aset -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">11. Maklumat Aset</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label text-gray-700 mb-1">Pemilikan Rumah *</label>
                                <select name="pemilikan_rumah" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih</option>
                                    <option value="Ada" {{ old('pemilikan_rumah') == 'Ada' ? 'selected' : '' }}>Ada</option>
                                    <option value="Tiada" {{ old('pemilikan_rumah') == 'Tiada' ? 'selected' : '' }}>Tiada</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Pemilikan Kenderaan *</label>
                                <select name="pemilikan_kenderaan" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih</option>
                                    <option value="Ada" {{ old('pemilikan_kenderaan') == 'Ada' ? 'selected' : '' }}>Ada</option>
                                    <option value="Tiada" {{ old('pemilikan_kenderaan') == 'Tiada' ? 'selected' : '' }}>Tiada</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label text-gray-700 mb-1">Simpanan Bank (RM)</label>
                                <input type="number" step="0.01" name="simpanan_bank" value="{{ old('simpanan_bank', '0.00') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section 12: Dokumen -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">12. Lampiran Dokumen</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label text-gray-700 mb-1">IC Depan</label>
                                <input type="file" name="ic_depan" accept="image/*,.pdf" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF (Max: 2MB)</p>
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">IC Belakang</label>
                                <input type="file" name="ic_belakang" accept="image/*,.pdf" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF (Max: 2MB)</p>
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">IC Waris</label>
                                <input type="file" name="ic_waris" accept="image/*,.pdf" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF (Max: 2MB)</p>
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Slip Gaji</label>
                                <input type="file" name="slip_gaji" accept="image/*,.pdf" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF (Max: 2MB)</p>
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Penyata Bank</label>
                                <input type="file" name="penyata_bank" accept="image/*,.pdf" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF (Max: 2MB)</p>
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Bil Utiliti</label>
                                <input type="file" name="bil_utiliti" accept="image/*,.pdf" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF (Max: 2MB)</p>
                            </div>
                            <div>
                                <label class="form-label text-gray-700 mb-1">Surat Sokongan</label>
                                <input type="file" name="surat_sokongan" accept="image/*,.pdf" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF (Max: 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 pt-6">
                        <a href="{{ route('asnaf.index') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Hantar Permohonan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
